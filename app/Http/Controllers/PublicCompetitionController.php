<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionDocument;
use App\Models\PlanningGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Pages publiques du module Compétitions : calendrier, fiche, agenda iCal, documents.
 *
 * - Tout le monde voit l'en-tête, la présentation, le programme et les documents publics.
 * - La logistique, l'encadrement (téléphones) et les documents « adhérents » sont réservés
 *   aux adhérents, éditeurs et administrateurs connectés.
 * - Admins et éditeurs peuvent prévisualiser une compétition non publiée.
 * - V2 : liste des engagés (si « entries_visible » : prénom + initiale pour le public,
 *   nom complet pour les adhérents), covoiturage et rappel des convocations de la famille (adhérents).
 */
class PublicCompetitionController extends Controller
{
    public function index(Request $request)
    {
        $vue = $request->query('vue') === 'passees' ? 'passees' : 'a-venir';
        $season = $request->query('saison') ?: Competition::currentSeason();
        $level = array_key_exists($request->query('niveau'), Competition::LEVELS) ? $request->query('niveau') : null;
        $groupSlug = $request->query('groupe');

        $competitions = Competition::published()
            ->with('groups')
            ->when($vue === 'a-venir', fn ($q) => $q->upcoming())
            ->when($vue === 'passees', fn ($q) => $q->past()->where('season', $season))
            ->when($level, fn ($q) => $q->where('level', $level))
            ->when($groupSlug, fn ($q) => $q->whereHas('groups', fn ($g) => $g->where('slug', $groupSlug)))
            ->get();

        return view('public.competitions.index', [
            'competitions' => $competitions,
            'months' => $competitions->groupBy(fn ($c) => $c->starts_on->format('Y-m')),
            'vue' => $vue,
            'season' => $season,
            'seasons' => Competition::published()->distinct()->orderByDesc('season')->pluck('season'),
            'level' => $level,
            'groupSlug' => $groupSlug,
            'groups' => PlanningGroup::active()->ordered()->whereHas('competitions')->get(),
        ]);
    }

    public function show(Request $request, Competition $competition)
    {
        $this->ensureVisible($request, $competition);

        $competition->load(['groups', 'sessions.programEvents.event', 'staff', 'documents']);
        $isMember = $this->isMember($request);

        // V2 : convoqués attendus (convoqués sans réponse ou confirmés)
        $attendus = collect();
        if ($competition->entries_visible || $isMember) {
            $attendus = $competition->attendances()
                ->expected()
                ->with(['swimmer', 'entries.event'])
                ->get()
                ->sortBy(fn ($a) => mb_strtolower($a->swimmer->first_name . ' ' . $a->swimmer->last_name))
                ->values();
        }

        $userId = $request->user()?->id;
        $mesConvocations = $isMember
            ? $competition->attendances()->with('swimmer')->whereHas('swimmer', fn ($q) => $q->where('user_id', $userId))->get()
            : collect();

        return view('public.competitions.show', [
            'competition' => $competition,
            'isMember' => $isMember,
            'engaged' => $competition->entries_visible ? $attendus : collect(),
            'carpool' => $isMember && $attendus->isNotEmpty() ? [
                'needed' => $attendus->where('needs_seat', true)->count(),
                'offered' => $attendus->sum('seats_offered'),
                'offers' => $attendus->filter(fn ($a) => $a->seats_offered > 0)->map(fn ($a) => [$a->swimmer->full_name, $a->seats_offered])->values(),
                'seekers' => $attendus->where('needs_seat', true)->map(fn ($a) => $a->swimmer->full_name)->values(),
            ] : null,
            'myAttendances' => $mesConvocations,
            'documents' => $competition->documents->filter(fn ($d) => $isMember || $d->isPublic()),
            'hiddenDocuments' => $isMember ? 0 : $competition->documents->reject->isPublic()->count(),
            'sessionsByDay' => $competition->sessions->groupBy(fn ($s) => $s->day->format('Y-m-d')),
        ]);
    }

    /** Téléchargement d'un document (contrôle de la visibilité). */
    public function document(Request $request, Competition $competition, CompetitionDocument $document)
    {
        $this->ensureVisible($request, $competition);
        abort_unless($document->competition_id === $competition->id, 404);

        if (! $document->isPublic() && ! $this->isMember($request)) {
            return redirect()->guest(route('login'));
        }
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        $nom = \Illuminate\Support\Str::slug($document->title) . '.pdf';

        return Storage::disk('local')->response($document->file_path, $nom, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /** Agenda iCal de tout le calendrier (abonnement depuis un téléphone). */
    public function calendar()
    {
        $competitions = Competition::published()
            ->whereDate('ends_on', '>=', today()->subYear())
            ->orderBy('starts_on')
            ->get();

        return $this->ics($competitions, 'cnbb-competitions.ics');
    }

    /** Agenda iCal d'une seule compétition (« Ajouter à mon agenda »). */
    public function calendarOne(Request $request, Competition $competition)
    {
        $this->ensureVisible($request, $competition);

        return $this->ics(collect([$competition]), $competition->slug . '.ics');
    }

    // ------------------------------------------------------------------

    private function isMember(Request $request): bool
    {
        $user = $request->user();

        return $user && ! $user->hasRole('visitor');
    }

    private function ensureVisible(Request $request, Competition $competition): void
    {
        $user = $request->user();
        $canPreview = $user && ($user->hasRole('admin') || $user->hasRole('editor'));

        abort_unless($competition->is_published || $canPreview, 404);
    }

    private function ics($competitions, string $filename)
    {
        $esc = fn (?string $v) => str_replace(["\\", ";", ",", "\r\n", "\n"], ["\\\\", "\;", "\\,", "\\n", "\\n"], (string) $v);
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'cnbb';

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//CNBB//Competitions//FR',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:' . $esc('CNBB — Compétitions'),
            'X-WR-TIMEZONE:Europe/Paris',
        ];

        foreach ($competitions as $c) {
            $statut = in_array($c->state, ['annulee', 'reportee'], true) ? ' (' . $c->state_label . ')' : '';
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:competition-' . $c->id . '@' . $host;
            $lines[] = 'DTSTAMP:' . $c->updated_at->utc()->format('Ymd\THis\Z');
            $lines[] = 'DTSTART;VALUE=DATE:' . $c->starts_on->format('Ymd');
            $lines[] = 'DTEND;VALUE=DATE:' . $c->ends_on->copy()->addDay()->format('Ymd');   // date de fin exclusive
            $lines[] = 'SUMMARY:' . $esc($c->title . $statut);
            $lines[] = 'LOCATION:' . $esc(trim($c->venue . ', ' . ($c->address ? $c->address . ', ' : '') . $c->city, ', '));
            $lines[] = 'DESCRIPTION:' . $esc(trim($c->level_label . ($c->pool_label ? ' — ' . $c->pool_label : '') . "\n" . route('competitions.show', $c)));
            $lines[] = 'URL:' . route('competitions.show', $c);
            if ($c->state === 'annulee') {
                $lines[] = 'STATUS:CANCELLED';
            }
            $lines[] = 'END:VEVENT';
        }
        $lines[] = 'END:VCALENDAR';

        // Lignes de 75 octets maximum (RFC 5545)
        $folded = array_map(function ($line) {
            $out = '';
            while (strlen($line) > 75) {
                $cut = 75;
                while ($cut > 0 && (ord($line[$cut]) & 0xC0) === 0x80) {
                    $cut--;   // ne pas couper un caractère UTF-8
                }
                $out .= substr($line, 0, $cut) . "\r\n ";
                $line = substr($line, $cut);
            }

            return $out . $line;
        }, $lines);

        return response(implode("\r\n", $folded) . "\r\n", 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
