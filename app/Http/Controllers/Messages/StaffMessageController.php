<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Conversation;
use App\Models\MessageAnnouncement;
use App\Models\PlanningGroup;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Messagerie de l'équipe du club (espaces admin, éditeur et coachs : *.messages.*).
 * Chaque conversation relie un membre de l'équipe et UN utilisateur ; un envoi à plusieurs
 * personnes crée une annonce et une conversation privée par destinataire.
 * Coach / éditeur : leurs conversations. Administrateur : toutes, et lui seul supprime.
 */
class StaffMessageController extends Controller
{
    public function __construct(private MessageService $messages)
    {
    }

    public function index(Request $request): View
    {
        $me = $request->user();
        $vue = $request->query('vue', 'boite');
        if ($vue === 'toutes' && ! $this->isAdmin()) {
            $vue = 'boite';
        }

        if ($vue === 'annonces') {
            $annonces = MessageAnnouncement::with('sender')
                ->withCount(['conversations as read_count' => fn ($q) => $q->whereNotNull('user_read_at')])
                ->when(! $this->isAdmin(), fn ($q) => $q->where('sender_id', $me->id))
                ->latest()->paginate(25)->withQueryString();

            return $this->view('index', compact('vue', 'annonces') + ['conversations' => null]);
        }

        $q = trim((string) $request->query('q'));
        $conversations = Conversation::with(['staff', 'user', 'announcement'])
            ->when($vue === 'boite', fn ($query) => $query->involving($me))
            ->when($request->boolean('non-lus'), fn ($query) => $query->unreadFor($me))
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('subject', 'like', "%{$q}%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%")->orWhere('first_name', 'like', "%{$q}%")->orWhere('last_name', 'like', "%{$q}%"))
                ->orWhereHas('staff', fn ($u) => $u->where('name', 'like', "%{$q}%")->orWhere('first_name', 'like', "%{$q}%")->orWhere('last_name', 'like', "%{$q}%"))))
            ->orderByDesc('last_message_at')->paginate(30)->withQueryString();

        return $this->view('index', compact('vue', 'conversations', 'q') + ['annonces' => null]);
    }

    public function create(Request $request): View
    {
        $preselection = $request->integer('a') ? User::where('status', 'active')->find($request->integer('a')) : null;

        return $this->view('create', [
            'preselection' => $preselection,
            'groups' => PlanningGroup::where('is_active', true)->withCount(['swimmers' => fn ($q) => $q->where('is_active', true)])->orderBy('sort_order')->orderBy('name')->get(),
            'competitions' => Competition::whereDate('ends_on', '>=', today()->subDays(15))->withCount('attendances')->orderBy('starts_on')->get(),
            'scopes' => MessageService::COMPETITION_SCOPES,
        ]);
    }

    /** Recherche de personnes (nom, prénom, e-mail) pour le choix des destinataires. */
    public function search(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q'));
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $users = User::with(['role', 'planningSwimmers'])->where('status', 'active')->where('id', '!=', $request->user()->id)
            ->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('first_name', 'like', "%{$q}%")
                ->orWhere('last_name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")
                ->orWhereHas('planningSwimmers', fn ($s) => $s->where('first_name', 'like', "%{$q}%")->orWhere('last_name', 'like', "%{$q}%")))
            ->orderBy('last_name')->limit(15)->get();

        return response()->json($users->map(fn (User $u) => [
            'id' => $u->id,
            'label' => MessageService::userLabel($u),
            'role' => MessageService::roleLabel($u),
            'swimmers' => $u->planningSwimmers->pluck('first_name')->implode(', '),
        ]));
    }

    /** Aperçu : nombre de destinataires et nageurs sans compte. */
    public function preview(Request $request): JsonResponse
    {
        $r = $this->messages->resolve($this->selection($request), $request->user());

        return response()->json([
            'count' => $r['users']->count(),
            'names' => $r['users']->take(60)->map(fn ($u) => MessageService::userLabel($u))->values(),
            'missing' => $r['missing'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:10000'],
            'users' => ['nullable', 'array'], 'users.*' => ['integer'],
            'groups' => ['nullable', 'array'], 'groups.*' => ['integer'],
            'competition_id' => ['nullable', 'integer', 'exists:competitions,id'],
            'competition_scope' => ['nullable', Rule::in(array_keys(MessageService::COMPETITION_SCOPES))],
            'attachment' => MessageService::ATTACHMENT_RULES,
        ], [
            'attachment.mimes' => 'La pièce jointe doit être un PDF ou une image (JPG, PNG, WebP).',
            'attachment.max' => 'La pièce jointe ne doit pas dépasser 5 Mo.',
        ]);

        $r = $this->messages->resolve($this->selection($request), $request->user());
        if ($r['users']->isEmpty()) {
            return back()->withInput()->withErrors(['users' => 'Aucun destinataire : choisissez au moins une personne, un groupe ou une compétition (les nageurs sans compte sur le site ne peuvent pas recevoir de message).']);
        }

        $envoi = $this->messages->send($request->user(), $r['users'], trim($data['subject']), trim($data['body']), $request->file('attachment'), $r['targets']);

        if ($envoi instanceof MessageAnnouncement) {
            return redirect()->route($this->area() . '.messages.announcement', $envoi)
                ->with('success', "Message envoyé à {$envoi->recipients_count} personnes. Chacune reçoit une conversation privée.");
        }

        return redirect()->route($this->area() . '.messages.show', $envoi)->with('success', 'Message envoyé.');
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $me = $request->user();
        abort_unless($conversation->canBeViewedBy($me), 403);
        $conversation->markReadBy($me);
        $conversation->load(['messages.author.role', 'staff.role', 'user.role', 'user.planningSwimmers', 'announcement']);

        return $this->view('show', ['conversation' => $conversation, 'participant' => $conversation->isParticipant($me)]);
    }

    public function reply(Request $request, Conversation $conversation): RedirectResponse
    {
        abort_unless($conversation->canBeViewedBy($request->user()), 403);
        $data = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
            'attachment' => MessageService::ATTACHMENT_RULES,
        ], ['attachment.mimes' => 'La pièce jointe doit être un PDF ou une image.', 'attachment.max' => 'La pièce jointe ne doit pas dépasser 5 Mo.']);

        $this->messages->reply($conversation, $request->user(), trim($data['body']), $request->file('attachment'));

        return redirect()->to(route($this->area() . '.messages.show', $conversation) . '#dernier')->with('success', 'Réponse envoyée.');
    }

    public function announcement(Request $request, MessageAnnouncement $announcement): View
    {
        abort_unless($this->isAdmin() || $announcement->sender_id === $request->user()->id, 403);
        $conversations = $announcement->conversations()->with('user')
            ->withCount(['messages as user_replies' => fn ($q) => $q->whereColumn('conversation_messages.author_id', 'conversations.user_id')])->get()
            ->sortBy(fn ($c) => [$c->user_read_at ? 1 : 0, MessageService::userLabel($c->user)]);

        return $this->view('announcement', [
            'announcement' => $announcement->load('sender'),
            'conversations' => $conversations,
            'readCount' => $conversations->whereNotNull('user_read_at')->count(),
            'replyCount' => $conversations->where('user_replies', '>', 0)->count(),
        ]);
    }

    public function destroy(Conversation $conversation): RedirectResponse
    {
        abort_unless($this->isAdmin(), 403);
        $conversation->deleteWithFiles();

        return redirect()->route($this->area() . '.messages.index', ['vue' => 'toutes'])->with('success', 'Conversation supprimée.');
    }

    public function destroyAnnouncement(MessageAnnouncement $announcement): RedirectResponse
    {
        abort_unless($this->isAdmin(), 403);
        foreach ($announcement->conversations as $c) {
            $c->deleteWithFiles();
        }
        $fichier = $announcement->attachment_path;
        $announcement->delete();
        if ($fichier && ! \App\Models\ConversationMessage::where('attachment_path', $fichier)->exists()) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($fichier);
        }

        return redirect()->route($this->area() . '.messages.index', ['vue' => 'annonces'])->with('success', 'Annonce et conversations supprimées.');
    }

    private function selection(Request $request): array
    {
        return [
            'users' => (array) $request->input('users', []),
            'groups' => (array) $request->input('groups', []),
            'competition_id' => $request->input('competition_id'),
            'competition_scope' => $request->input('competition_scope'),
            'all_members' => $request->boolean('all_members'),
            'all_coaches' => $request->boolean('all_coaches'),
        ];
    }

    private function isAdmin(): bool
    {
        return (bool) request()->user()?->hasRole('admin');
    }

    private function area(): string
    {
        return match (true) {
            request()->routeIs('editor.*') => 'editor',
            request()->routeIs('coach.*') => 'coach',
            default => 'admin',
        };
    }

    private function view(string $name, array $data): View
    {
        return view('messages.staff.' . $name, $data + [
            'area' => $this->area(),
            'layout' => 'layouts.' . $this->area(),
            'isAdmin' => $this->isAdmin(),
        ]);
    }
}
