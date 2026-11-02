<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Temps réalisé par un nageur du club sur une épreuve.
 * Les calculs (meilleure performance personnelle, écarts, %) sont dans App\Services\PerformanceTracker.
 */
class SwimPerformance extends Model
{
    public const CONTEXTS = [
        'competition' => 'Compétition du calendrier',
        'meeting' => 'Meeting extérieur',
        'training' => "Contre-la-montre à l'entraînement",
    ];

    /** Tours, dans l'ordre d'une compétition. */
    public const ROUNDS = [
        'serie' => 'Série',
        'demi' => 'Demi-finale',
        'finale_c' => 'Finale C',
        'finale_b' => 'Finale B',
        'finale_a' => 'Finale A',
        'finale' => 'Finale',
        'clm' => 'Contre-la-montre',
    ];

    public const STATUSES = [
        'valid' => 'Temps validé',
        'dsq' => 'Disqualifié',
        'dnf' => 'Abandon',
        'dns' => 'Forfait',
    ];

    /** Épreuves individuelles : 25 m de chaque nage, puis la liste FFN. */
    public const EVENTS = [
        '25 NL', '50 NL', '100 NL', '200 NL', '400 NL', '800 NL', '1500 NL',
        '25 Dos', '50 Dos', '100 Dos', '200 Dos',
        '25 Brasse', '50 Brasse', '100 Brasse', '200 Brasse',
        '25 Pap', '50 Pap', '100 Pap', '200 Pap',
        '100 4N', '200 4N', '400 4N',
    ];

    /** Code de nage du référentiel des compétitions (SwimEvent) → libellé court. */
    public const STROKE_CODES = ['nl' => 'NL', 'dos' => 'Dos', 'brasse' => 'Brasse', 'pap' => 'Pap', '4n' => '4N'];

    protected $fillable = [
        'planning_swimmer_id', 'competition_id', 'context', 'meet_name', 'location', 'performed_on', 'pool',
        'event', 'round', 'time_cs', 'status', 'note', 'created_by',
    ];

    protected $casts = ['performed_on' => 'date', 'pool' => 'integer', 'time_cs' => 'integer'];

    public function swimmer(): BelongsTo
    {
        return $this->belongsTo(PlanningSwimmer::class, 'planning_swimmer_id');
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function isValid(): bool
    {
        return $this->status === 'valid' && $this->time_cs !== null;
    }

    public function getTimeLabelAttribute(): string
    {
        return $this->isValid() ? SwimRecord::formatTime($this->time_cs) : (self::STATUSES[$this->status] ?? '');
    }

    public function getRoundLabelAttribute(): string
    {
        return self::ROUNDS[$this->round] ?? $this->round;
    }

    /** Nom de la compétition ou du meeting. */
    public function getMeetLabelAttribute(): string
    {
        return $this->competition?->title ?? ($this->meet_name ?: self::CONTEXTS[$this->context] ?? '');
    }

    /** Clé d'une réunion (pour rapprocher série et finale). */
    public function getMeetKeyAttribute(): string
    {
        return $this->competition_id ? 'c' . $this->competition_id : 'm' . $this->performed_on?->toDateString() . '|' . mb_strtolower((string) $this->meet_name);
    }

    public static function roundOrder(string $round): int
    {
        $i = array_search($round, array_keys(self::ROUNDS), true);

        return $i === false ? 99 : $i;
    }

    public static function eventOrder(string $event): int
    {
        $i = array_search($event, self::EVENTS, true);

        return $i === false ? 999 : $i;
    }

    /** SwimEvent (référentiel des compétitions) → « 50 NL » ; null pour un relais. */
    public static function eventFromSwimEvent(?SwimEvent $event): ?string
    {
        if (! $event || $event->is_relay) {
            return null;
        }
        $label = $event->distance . ' ' . (self::STROKE_CODES[$event->stroke] ?? '');

        return in_array($label, self::EVENTS, true) ? $label : null;
    }
}
