<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Référentiel des épreuves de natation course (module Compétitions, V2).
 */
class SwimEvent extends Model
{
    public const STROKES = [
        'nl'     => 'Nage libre',
        'dos'    => 'Dos',
        'brasse' => 'Brasse',
        'pap'    => 'Papillon',
        '4n'     => '4 nages',
        'divers' => 'Divers (2 nages)',
    ];

    /** Libellé court de la nage, utilisé pour composer « 50 NL », « 4×200 NL »… */
    public const STROKE_SHORT = ['nl' => 'NL', 'dos' => 'Dos', 'brasse' => 'Brasse', 'pap' => 'Pap', '4n' => '4 N', 'divers' => 'Divers'];

    protected $fillable = ['code', 'stroke', 'distance', 'is_relay', 'label', 'sort_order', 'is_active'];

    protected $casts = [
        'distance' => 'integer',
        'is_relay' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('distance');
    }

    /** Code et libellé proposés pour une épreuve : « 25-dos » / « 25 Dos », « 10x50-nl » / « 10×50 NL ». */
    public static function suggest(string $stroke, int $distance, ?int $legs = null): array
    {
        $court = self::STROKE_SHORT[$stroke] ?? $stroke;

        return $legs
            ? ["{$legs}x{$distance}-{$stroke}", "{$legs}×{$distance} {$court}"]
            : ["{$distance}-{$stroke}", "{$distance} {$court}"];
    }

    /** Épreuve déjà utilisée dans un programme ou une convocation (suppression impossible). */
    public function isUsed(): bool
    {
        return \Illuminate\Support\Facades\DB::table('competition_events')->where('swim_event_id', $this->id)->exists()
            || \Illuminate\Support\Facades\DB::table('competition_entries')->where('swim_event_id', $this->id)->exists();
    }

    public function getStrokeLabelAttribute(): string
    {
        return self::STROKES[$this->stroke] ?? $this->stroke;
    }
}
