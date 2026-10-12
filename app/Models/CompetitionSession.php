<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Réunion d'une compétition (une journée peut en compter plusieurs : matin, après-midi…).
 */
class CompetitionSession extends Model
{
    protected $fillable = [
        'competition_id', 'day', 'label', 'doors_open_at', 'warmup_at', 'starts_at', 'events', 'sort_order',
    ];

    protected $casts = [
        'day' => 'date',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    /** Programme structuré de la réunion (grille des épreuves), dans l'ordre. */
    public function programEvents(): HasMany
    {
        return $this->hasMany(CompetitionEvent::class)->orderBy('sort_order')->orderBy('id');
    }

    /** Épreuves en texte libre, une par ligne, lignes vides retirées. */
    public function getEventListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->events))));
    }

    /** « 17:30:00 » → « 17 h 30 » */
    public static function formatTime(?string $time): ?string
    {
        if (! $time) {
            return null;
        }
        [$h, $m] = array_map('intval', explode(':', $time) + [0, 0]);

        return $h . ' h' . ($m ? ' ' . str_pad((string) $m, 2, '0', STR_PAD_LEFT) : '');
    }

    /** Valeur pour <input type="time"> */
    public static function inputTime(?string $time): string
    {
        return $time ? substr($time, 0, 5) : '';
    }
}
