<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Présence d'un nageur à une séance.
 */
class PresenceRecord extends Model
{
    public const STATUSES = [
        'present' => 'Présent',
        'absent' => 'Absent',
        'excuse' => 'Excusé',
    ];

    /** Lettre et couleur Bootstrap pour les tableaux. */
    public const BADGES = [
        'present' => ['P', 'success'],
        'absent' => ['A', 'danger'],
        'excuse' => ['E', 'warning'],
    ];

    protected $fillable = ['presence_session_id', 'planning_swimmer_id', 'status', 'arrived_at', 'comment', 'recorded_by'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(PresenceSession::class, 'presence_session_id');
    }

    public function swimmer(): BelongsTo
    {
        return $this->belongsTo(PlanningSwimmer::class, 'planning_swimmer_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /** « 17 h 34 » */
    public function getArrivalLabelAttribute(): ?string
    {
        return $this->arrived_at ? PlanningSlot::formatTime($this->arrived_at) : null;
    }
}
