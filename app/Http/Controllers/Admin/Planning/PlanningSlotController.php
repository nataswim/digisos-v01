<?php

namespace App\Http\Controllers\Admin\Planning;

use App\Models\PlanningGroup;
use App\Models\PlanningSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanningSlotController extends PlanningController
{
    /** Tableau de bord du module : les créneaux des deux périodes. */
    public function index(Request $request)
    {
        $period = array_key_exists($request->query('periode'), PlanningSlot::PERIODS)
            ? $request->query('periode')
            : 'scolaire';

        $slots = PlanningSlot::with('group')
            ->period($period)
            ->chronological()
            ->get()
            ->groupBy('day_of_week');

        $counts = PlanningSlot::selectRaw('period, count(*) as total')->groupBy('period')->pluck('total', 'period');

        return $this->planningView('slots.index', compact('slots', 'period', 'counts'));
    }

    public function create(Request $request)
    {
        $slot = new PlanningSlot([
            'period' => array_key_exists($request->query('periode'), PlanningSlot::PERIODS) ? $request->query('periode') : 'scolaire',
            'day_of_week' => $request->integer('jour') ?: 3,
            'location' => 'Centre aquatique Cœur d\'O',
            'is_active' => true,
        ]);

        return $this->planningView('slots.form', [
            'slot' => $slot,
            'groups' => PlanningGroup::ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $slot = PlanningSlot::create($this->validated($request));

        return redirect()->route($this->planningRoute('slots.index'), ['periode' => $slot->period])
            ->with('success', 'Créneau ajouté.');
    }

    public function edit(PlanningSlot $slot)
    {
        return $this->planningView('slots.form', [
            'slot' => $slot,
            'groups' => PlanningGroup::ordered()->get(),
        ]);
    }

    public function update(Request $request, PlanningSlot $slot): RedirectResponse
    {
        $slot->update($this->validated($request));

        return redirect()->route($this->planningRoute('slots.index'), ['periode' => $slot->period])
            ->with('success', 'Créneau mis à jour.');
    }

    public function destroy(PlanningSlot $slot): RedirectResponse
    {
        $period = $slot->period;
        $slot->delete();

        return redirect()->route($this->planningRoute('slots.index'), ['periode' => $period])
            ->with('success', 'Créneau supprimé.');
    }

    /** Copie un créneau vers l'autre période (ex. scolaire → vacances), puis ouvre la copie. */
    public function duplicate(PlanningSlot $slot): RedirectResponse
    {
        $copy = $slot->replicate();
        $copy->period = $slot->period === 'scolaire' ? 'vacances' : 'scolaire';
        $copy->save();

        return redirect()->route($this->planningRoute('slots.edit'), $copy)
            ->with('success', 'Créneau copié vers « ' . PlanningSlot::PERIODS[$copy->period] . ' » : vérifiez-le puis enregistrez.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'planning_group_id' => ['required', 'integer', 'exists:planning_groups,id'],
            'period' => ['required', Rule::in(array_keys(PlanningSlot::PERIODS))],
            'day_of_week' => ['required', 'integer', 'between:1,7'],
            'starts_at' => ['required', 'date_format:H:i'],
            'ends_at' => ['required', 'date_format:H:i', 'after:starts_at'],
            'location' => ['required', 'string', 'max:100'],
            'lanes' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'ends_at.after' => 'L\'heure de fin doit être après l\'heure de début.',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
