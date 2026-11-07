<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\CompetitionAttendance;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\MessageAnnouncement;
use App\Models\PlanningGroup;
use App\Models\PlanningSwimmer;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Messagerie : destinataires (personnes, groupes du planning, convoqués d'une compétition,
 * tous les adhérents, tous les coachs), envoi (une conversation privée par destinataire),
 * réponses, pièces jointes et e-mails de notification.
 */
class MessageService
{
    /** Règles de validation d'une pièce jointe (PDF ou image, 5 Mo). */
    public const ATTACHMENT_RULES = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'];

    /** Convoqués d'une compétition : choix proposés */
    public const COMPETITION_SCOPES = [
        'tous' => 'tous les convoqués',
        'sans-reponse' => 'seulement ceux qui n\'ont pas répondu',
        'confirmes' => 'seulement les présences confirmées',
    ];

    /**
     * Résout la sélection en comptes destinataires.
     *
     * @param  array{users?: array, groups?: array, competition_id?: int|null, competition_scope?: string|null, all_members?: bool, all_coaches?: bool}  $sel
     * @return array{users: Collection<int, User>, missing: array<int, string>, targets: array<int, string>}
     */
    public function resolve(array $sel, User $sender): array
    {
        $ids = collect();
        $missing = [];
        $targets = [];

        if ($personnes = array_filter(array_map('intval', (array) ($sel['users'] ?? [])))) {
            $ids = $ids->merge($personnes);
            $targets[] = count($personnes) . ' personne(s) choisie(s)';
        }

        if ($groupes = array_filter(array_map('intval', (array) ($sel['groups'] ?? [])))) {
            $noms = PlanningGroup::whereIn('id', $groupes)->pluck('name');
            $nageurs = PlanningSwimmer::where('is_active', true)->whereHas('groups', fn ($q) => $q->whereIn('planning_groups.id', $groupes))->get();
            $ids = $ids->merge($nageurs->pluck('user_id')->filter());
            foreach ($nageurs->whereNull('user_id') as $n) {
                $missing[] = $n->full_name;
            }
            $targets[] = 'Groupe(s) : ' . $noms->implode(', ');
        }

        if (! empty($sel['competition_id']) && ($competition = Competition::find($sel['competition_id']))) {
            $scope = $sel['competition_scope'] ?? 'tous';
            $presences = CompetitionAttendance::with('swimmer')->where('competition_id', $competition->id)
                ->when($scope === 'sans-reponse', fn ($q) => $q->where('status', 'convoque'))
                ->when($scope === 'confirmes', fn ($q) => $q->where('status', 'confirme'))
                ->get();
            $ids = $ids->merge($presences->pluck('swimmer.user_id')->filter());
            foreach ($presences as $p) {
                if ($p->swimmer && ! $p->swimmer->user_id) {
                    $missing[] = $p->swimmer->full_name;
                }
            }
            $targets[] = $competition->title . ' : ' . (self::COMPETITION_SCOPES[$scope] ?? $scope);
        }

        foreach (['all_members' => ['user', 'Tous les adhérents'], 'all_coaches' => ['coach', 'Tous les coachs']] as $cle => [$role, $libelle]) {
            if (! empty($sel[$cle])) {
                $ids = $ids->merge(User::whereHas('role', fn ($q) => $q->where('slug', $role))->pluck('id'));
                $targets[] = $libelle;
            }
        }

        $users = User::with('role')->whereIn('id', $ids->unique()->all())
            ->where('id', '!=', $sender->id)->where('status', 'active')
            ->orderBy('last_name')->orderBy('first_name')->orderBy('name')->get();

        return ['users' => $users, 'missing' => array_values(array_unique($missing)), 'targets' => $targets];
    }

    /** Libellé d'un compte : « Prénom NOM » (ou nom du compte), rôle. */
    public static function userLabel(?User $user): string
    {
        if (! $user) {
            return 'Compte supprimé';
        }
        $nom = $user->first_name ? trim($user->first_name . ' ' . mb_strtoupper((string) $user->last_name)) : $user->name;

        return $nom;
    }

    public static function roleLabel(?User $user): string
    {
        return match ($user?->role?->slug) {
            'admin' => 'Administrateur',
            'editor' => 'Éditeur',
            'coach' => 'Coach',
            'user' => 'Adhérent',
            'visitor' => 'Visiteur',
            default => '',
        };
    }

    /**
     * Envoi par l'équipe du club à un ou plusieurs destinataires : une conversation privée chacun.
     * Plusieurs destinataires = une annonce (suivi « lu par x / y »).
     *
     * @return Conversation|MessageAnnouncement
     */
    public function send(User $sender, Collection $recipients, string $subject, string $body, ?UploadedFile $file = null, array $targets = [])
    {
        $piece = $this->storeAttachment($file);

        [$annonce, $conversations] = DB::transaction(function () use ($sender, $recipients, $subject, $body, $piece, $targets) {
            $annonce = $recipients->count() > 1
                ? MessageAnnouncement::create(['sender_id' => $sender->id, 'subject' => $subject, 'body' => $body, 'targets' => $targets, 'recipients_count' => $recipients->count()] + $piece)
                : null;

            $conversations = $recipients->map(fn (User $u) => $this->createConversation($subject, $sender, $u, $sender, $body, $piece, $annonce?->id));

            return [$annonce, $conversations];
        });

        @set_time_limit(0);
        foreach ($conversations as $c) {
            $this->notify($c->user, $c);
        }

        return $annonce ?? $conversations->first();
    }

    /** Nouveau message d'un utilisateur à un membre de l'équipe (coach ou administrateur). */
    public function startFromMember(User $member, User $staff, string $subject, string $body, ?UploadedFile $file = null): Conversation
    {
        $c = $this->createConversation($subject, $staff, $member, $member, $body, $this->storeAttachment($file));
        $this->notify($staff, $c);

        return $c;
    }

    /** Réponse dans une conversation (participant, ou administrateur pour le club). */
    public function reply(Conversation $c, User $author, string $body, ?UploadedFile $file = null): ConversationMessage
    {
        $destinataire = $author->id === $c->user_id ? $c->staff : $c->user;
        $dejaNonLu = $destinataire ? $c->isUnreadFor($destinataire) : true;

        $message = $c->messages()->create(['author_id' => $author->id, 'body' => $body] + $this->storeAttachment($file));
        // Nouveau message : il redevient non lu pour l'autre participant.
        $c->forceFill(['last_message_at' => $message->created_at, 'last_message_by' => $author->id]
            + ($author->id === $c->user_id ? ['staff_read_at' => null] : ['user_read_at' => null]))->save();
        $c->markReadBy($author);

        // Un seul e-mail tant que le destinataire n'a pas lu les messages précédents.
        if ($destinataire && ! $dejaNonLu) {
            $this->notify($destinataire, $c);
        }

        return $message;
    }

    private function createConversation(string $subject, User $staff, User $user, User $author, string $body, array $piece, ?int $announcementId = null): Conversation
    {
        $c = Conversation::create([
            'subject' => $subject,
            'staff_id' => $staff->id,
            'user_id' => $user->id,
            'announcement_id' => $announcementId,
            'started_by' => $author->id,
        ]);
        $m = $c->messages()->create(['author_id' => $author->id, 'body' => $body] + $piece);
        $c->forceFill(['last_message_at' => $m->created_at, 'last_message_by' => $author->id])->save();
        $c->markReadBy($author);

        return $c;
    }

    /** @return array<string, mixed> champs attachment_* (vides si pas de fichier) */
    private function storeAttachment(?UploadedFile $file): array
    {
        if (! $file) {
            return [];
        }

        return [
            'attachment_path' => $file->store('messages', 'local'),
            'attachment_name' => mb_substr($file->getClientOriginalName(), 0, 200),
            'attachment_mime' => $file->getMimeType(),
            'attachment_size' => $file->getSize(),
        ];
    }

    private function notify(?User $user, Conversation $c): void
    {
        if (! $user || ! $user->email || $user->status !== 'active') {
            return;
        }
        try {
            $user->notify(new NewMessageNotification($c));
        } catch (\Throwable $e) {
            Log::warning('Messagerie : e-mail non envoyé', ['user' => $user->id, 'conversation' => $c->id, 'error' => $e->getMessage()]);
        }
    }
}
