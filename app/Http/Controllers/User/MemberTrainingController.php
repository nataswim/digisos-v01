<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PlanningSwimmer;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * Espace adhérent → Mes entraînements : séances publiées pour les nageurs rattachés au compte.
 */
class MemberTrainingController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user->hasRole('visitor')) {
            return redirect()->route('visitor.dashboard');
        }

        try {
            $monday = Carbon::createFromFormat('Y-m-d', (string) $request->query('semaine'))->startOfWeek(Carbon::MONDAY);
        } catch (\Throwable $e) {
            $monday = today()->startOfWeek(Carbon::MONDAY);
        }

        $swimmers = PlanningSwimmer::where('user_id', $user->id)->orderBy('first_name')->get();

        $sessions = TrainingSession::visibleToAccount($user)
            ->with(['swimmers' => fn ($q) => $q->where('planning_swimmers.user_id', $user->id)])
            ->whereBetween('session_date', [$monday->toDateString(), $monday->copy()->addDays(6)->toDateString() . ' 23:59:59'])
            ->orderBy('session_date')->orderBy('starts_at')
            ->get();

        $next = TrainingSession::visibleToAccount($user)
            ->whereDate('session_date', '>=', today())
            ->orderBy('session_date')->orderBy('starts_at')
            ->first();

        return view('user.training.index', [
            'monday' => $monday,
            'days' => collect(range(0, 6))->map(fn ($i) => $monday->copy()->addDays($i)),
            'sessions' => $sessions->groupBy(fn ($s) => $s->session_date->toDateString()),
            'swimmers' => $swimmers,
            'next' => $next,
        ]);
    }

    public function show(Request $request, TrainingSession $training): View
    {
        abort_unless(
            $training->isPublished()
            && $training->swimmers()->where('planning_swimmers.user_id', $request->user()->id)->exists(),
            404
        );

        return view('user.training.show', [
            'session' => $training->load(['swimmers' => fn ($q) => $q->where('planning_swimmers.user_id', $request->user()->id)]),
            'exercises' => $training->exercises(),
        ]);
    }
}
