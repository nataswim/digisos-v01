<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Convocation d'un nageur à une compétition, réponse de la famille et covoiturage (module Compétitions, V2).
 *
 * Cycle : convoqué → confirmé ou absent (réponse de la famille avant la date limite)
 *         → forfait (retrait après confirmation, saisi par l'encadrement).
 */
class CompetitionAttendance extends Model
{
    /** Statut => [libellé, classe de badge Bootstrap, icône Font Awesome] */
    public const STATUSES = [
        'convoque' => ['En attente de réponse', 'bg-warning text-dark', 'fa-hourglass-half'],
        'confirme' => ['Présence confirmée', 'bg-success', 'fa-check'],
        'absent'   => ['Absent (excusé)', 'bg-secondary', 'fa-times'],
        'forfait'  => ['Forfait', 'bg-danger', 'fa-ban'],
    ];

    /** Réponses possibles pour une famille. */
    public const FAMILY_ANSWERS = ['confirme', 'absent'];

    protected $fillable = [
        'competition_id', 'planning_swimmer_id', 'status', 'needs_seat', 'seats_offered',
        'family_comment', 'staff_note', 'notified_at', 'responded_at', 'responded_by',
    ];

    protected $casts = [
        'needs_seat' => 'boolean',
        'seats_offered' => 'integer',
        'notified_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function swimmer(): BelongsTo
    {
        return $this->belongsTo(PlanningSwimmer::class, 'planning_swimmer_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(CompetitionEntry::class);
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /** Nageurs attendus sur place (convoqués sans réponse ou confirmés). */
    public function scopeExpected($query)
    {
        return $query->whereIn('status', ['convoque', 'confirme']);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status][0] ?? $this->status;
    }

    public function getStatusBadgeAttribute(): string
    {
        return self::STATUSES[$this->status][1] ?? 'bg-light text-dark';
    }

    public function getStatusIconAttribute(): string
    {
        return self::STATUSES[$this->status][2] ?? 'fa-circle';
    }

    /** Engagements triés dans l'ordre du référentiel (charger entries.event au préalable). */
    public function getSortedEntriesAttribute()
    {
        return $this->entries->sortBy(fn ($e) => sprintf('%05d-%05d', $e->event->sort_order ?? 0, $e->event->distance ?? 0))->values();
    }

    public function isExpected(): bool
    {
        return in_array($this->status, ['convoque', 'confirme'], true);
    }

    /** La famille peut-elle encore répondre ? (pas après un forfait, ni après la date limite) */
    public function canBeAnsweredByFamily(): bool
    {
        return $this->status !== 'forfait' && $this->competition->isOpenForResponses();
    }
}
