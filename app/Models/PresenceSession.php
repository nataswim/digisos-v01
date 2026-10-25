<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Séance d'entraînement émargée : un créneau du planning à une date donnée,
 * ou une séance supplémentaire (stage, rattrapage…) sans créneau.
 */
class PresenceSession extends Model
{
    protected $fillable = [
        'planning_slot_id', 'planning_group_id', 'date', 'starts_at', 'ends_at', 'title', 'location', 'notes', 'created_by',
    ];

    protected $casts = ['date' => 'date'];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(PlanningSlot::class, 'planning_slot_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(PlanningGroup::class, 'planning_group_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(PresenceRecord::class);
    }

    /** Ouvre (ou retrouve) la séance d'un créneau à une date. */
    public static function forSlot(PlanningSlot $slot, Carbon $date, ?int $userId = null): self
    {
        return self::firstOrCreate(
            ['planning_slot_id' => $slot->id, 'date' => $date->toDateString()],
            [
                'planning_group_id' => $slot->planning_group_id,
                'starts_at' => $slot->starts_at,
                'ends_at' => $slot->ends_at,
                'location' => $slot->location,
                'created_by' => $userId,
            ]
        );
    }

    /** « Avenirs » ou « Stage de Toussaint » */
    public function getLabelAttribute(): string
    {
        return $this->title ?: ($this->group?->name ?? 'Séance');
    }

    /** « 17 h 30 – 19 h » */
    public function getTimeRangeAttribute(): string
    {
        if (! $this->starts_at) {
            return '';
        }

        return PlanningSlot::formatTime($this->starts_at) . ($this->ends_at ? ' – ' . PlanningSlot::formatTime($this->ends_at) : '');
    }

    public function isExtra(): bool
    {
        return $this->planning_slot_id === null;
    }

    /**
     * Nageurs à émarger : nageurs actifs du groupe, plus ceux déjà émargés hors groupe.
     *
     * @return Collection<int, PlanningSwimmer>
     */
    public function expectedSwimmers(): Collection
    {
        $groupe = $this->group
            ? $this->group->swimmers()->where('is_active', true)->get()
            : collect();
        $emarges = PlanningSwimmer::whereIn('id', $this->records()->pluck('planning_swimmer_id'))->get();

        return $groupe->concat($emarges)->unique('id')
            ->sortBy(fn ($s) => mb_strtolower($s->last_name . ' ' . $s->first_name))
            ->values();
    }
}
