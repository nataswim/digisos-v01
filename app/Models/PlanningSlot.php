<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Créneau hebdomadaire d'un groupe, pour la période scolaire ou les vacances scolaires.
 */
class PlanningSlot extends Model
{
    public const PERIODS = [
        'scolaire' => 'Période scolaire',
        'vacances' => 'Vacances scolaires',
    ];

    /** Jours ISO : 1 = lundi … 7 = dimanche */
    public const DAYS = [
        1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi',
        5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche',
    ];

    protected $fillable = [
        'planning_group_id', 'period', 'day_of_week', 'starts_at', 'ends_at',
        'location', 'lanes', 'notes', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'day_of_week' => 'integer',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(PlanningGroup::class, 'planning_group_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePeriod($query, string $period)
    {
        return $query->where('period', $period);
    }

    public function scopeChronological($query)
    {
        return $query->orderBy('day_of_week')->orderBy('starts_at')->orderBy('ends_at');
    }

    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? '';
    }

    /** « 17 h 30 – 19 h » */
    public function getTimeRangeAttribute(): string
    {
        return self::formatTime($this->starts_at) . ' – ' . self::formatTime($this->ends_at);
    }

    /** « 17:30:00 » → « 17 h 30 », « 19:00 » → « 19 h » */
    public static function formatTime(?string $time): string
    {
        if (! $time) {
            return '';
        }
        [$h, $m] = array_map('intval', explode(':', $time) + [0, 0]);

        return $h . ' h' . ($m ? ' ' . str_pad((string) $m, 2, '0', STR_PAD_LEFT) : '');
    }

    /** Valeur pour <input type="time"> (« 17:30 ») */
    public static function inputTime(?string $time): string
    {
        return $time ? substr($time, 0, 5) : '';
    }
}
