<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Épreuve au programme d'une réunion : « 100 Dos Dames — Finale A ».
 */
class CompetitionEvent extends Model
{
    /** Clé => [libellé, libellé court] — l'ordre sert au tri automatique */
    public const GENDERS = [
        'F' => ['Dames', 'D'],
        'M' => ['Messieurs', 'M'],
        'X' => ['Mixte', 'X'],
    ];

    /** Clé => [libellé, libellé court] — l'ordre sert au tri automatique (finale C, puis B, puis A) */
    public const ROUNDS = [
        'series'   => ['Séries', 'S'],
        'finale-c' => ['Finale C', 'FC'],
        'finale-b' => ['Finale B', 'FB'],
        'finale-a' => ['Finale A', 'FA'],
    ];

    protected $fillable = ['competition_session_id', 'swim_event_id', 'gender', 'round', 'sort_order'];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(CompetitionSession::class, 'competition_session_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(SwimEvent::class, 'swim_event_id');
    }

    public function getGenderLabelAttribute(): string
    {
        return self::GENDERS[$this->gender][0] ?? $this->gender;
    }

    public function getRoundLabelAttribute(): string
    {
        return self::ROUNDS[$this->round][0] ?? $this->round;
    }

    /** « 100 Dos Dames » */
    public function getTitleAttribute(): string
    {
        return trim(($this->event->label ?? '') . ' ' . $this->gender_label);
    }

    /** « 100 Dos Dames — Finale A » */
    public function getFullLabelAttribute(): string
    {
        return $this->title . ' — ' . $this->round_label;
    }

    /** Clé de tri automatique : épreuve, puis Dames / Messieurs / Mixte, puis séries, finales C, B, A. */
    public static function autoKey(int $eventOrder, string $gender, string $round): string
    {
        return sprintf(
            '%05d-%d-%d',
            $eventOrder,
            array_search($gender, array_keys(self::GENDERS), true),
            array_search($round, array_keys(self::ROUNDS), true)
        );
    }
}
