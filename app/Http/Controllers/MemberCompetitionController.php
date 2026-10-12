<?php

namespace App\Http\Controllers;

use App\Models\CompetitionAttendance;
use App\Models\PlanningSwimmer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Espace adhérent « Mes compétitions » (module Compétitions, V2).
 *
 * Les familles voient les convocations des nageurs rattachés à leur compte
 * (Planning › Nageurs › « Compte du site ») et y répondent avant la date limite :
 * présence ou absence, besoin d'une place en voiture, places offertes, commentaire.
 */
class MemberCompetitionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $swimmerIds = PlanningSwimmer::where('user_id', $user->id)->pluck('id');

        $attendances = CompetitionAttendance::with(['competition', 'swimmer', 'entries.event'])
            ->whereIn('planning_swimmer_id', $swimmerIds)
            ->whereHas('competition', fn ($q) => $q->where('is_published', true))
            ->get();

        $aVenir = $attendances
            ->filter(fn ($a) => ! $a->competition->ends_on->lt(today()))
            ->sortBy(fn ($a) => $a->competition->starts_on->format('Y-m-d') . '-' . $a->swimmer->first_name);

        $passees = $attendances
            ->filter(fn ($a) => $a->competition->ends_on->lt(today()))
            ->sortByDesc(fn ($a) => $a->competition->starts_on->format('Y-m-d'))
            ->take(20);

        return view('user.competitions.index', [
            'upcoming' => $aVenir->groupBy('competition_id'),
            'past' => $passees,
            'hasSwimmers' => $swimmerIds->isNotEmpty(),
        ]);
    }

    public function respond(Request $request, CompetitionAttendance $attendance): RedirectResponse
    {
        $attendance->load(['competition', 'swimmer']);
        abort_unless((int) $attendance->swimmer->user_id === (int) $request->user()->id, 403);

        if (! $attendance->canBeAnsweredByFamily()) {
            return redirect()->route('user.competitions.index')
                ->with('error', 'Les réponses en ligne sont closes pour cette compétition : contactez l\'entraîneur.');
        }

        $data = $request->validate([
            'status' => ['required', Rule::in(CompetitionAttendance::FAMILY_ANSWERS)],
            'seats_offered' => ['nullable', 'integer', 'between:0,8'],
            'family_comment' => ['nullable', 'string', 'max:500'],
        ], [
            'status.required' => 'Indiquez si ' . $attendance->swimmer->first_name . ' sera présent(e) ou absent(e).',
        ]);

        $present = $data['status'] === 'confirme';

        $attendance->update([
            'status' => $data['status'],
            'needs_seat' => $present && $request->boolean('needs_seat'),
            'seats_offered' => $present ? (int) ($data['seats_offered'] ?? 0) : 0,
            'family_comment' => $data['family_comment'] ?? null,
            'responded_at' => now(),
            'responded_by' => $request->user()->id,
        ]);

        return redirect()->to(route('user.competitions.index') . '#convocation-' . $attendance->id)
            ->with('answered', $attendance->id)
            ->with('success', $present
                ? "Merci ! La présence de {$attendance->swimmer->first_name} est confirmée."
                : "C'est noté : {$attendance->swimmer->first_name} sera absent(e).");
    }
}
