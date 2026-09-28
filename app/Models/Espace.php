<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Espace extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'structure_id',
        'name',
        'slug',
        'description',
        'capacity',
        'photo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'capacity'  => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Sluggable — slug composé : nom de la structure + nom de l'espace
    // Exemple : structure "Centre aquatique" + espace "Piscine intérieure"
    //        → "centre-aquatique-piscine-interieure"
    // -------------------------------------------------------------------------

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'   => ['structure.name', 'name'],
                'onUpdate' => false,
            ],
        ];
    }

    /**
     * Slug comme clé de route pour le route model binding
     * URL : /installations/espaces/{espace} → résolu par slug
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function service(): BelongsTo
    {
        return $this->structure->service();
    }

    public function zones(): HasMany
    {
        return $this->hasMany(Zone::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }
}