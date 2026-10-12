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
    ];

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

    public function getStrokeLabelAttribute(): string
    {
        return self::STROKES[$this->stroke] ?? $this->stroke;
    }
}
