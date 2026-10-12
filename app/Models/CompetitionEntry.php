<?php

namespace App\Models;

use App\Support\SwimTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Engagement d'un nageur convoqué sur une épreuve (module Compétitions, V2).
 */
class CompetitionEntry extends Model
{
    protected $fillable = ['competition_attendance_id', 'swim_event_id', 'entry_time_cs'];

    protected $casts = [
        'entry_time_cs' => 'integer',
    ];

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(CompetitionAttendance::class, 'competition_attendance_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(SwimEvent::class, 'swim_event_id');
    }

    /** « 1:02.34 » ou null (pas de temps d'engagement = « NT ») */
    public function getEntryTimeAttribute(): ?string
    {
        return SwimTime::format($this->entry_time_cs);
    }
}
