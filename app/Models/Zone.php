<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'espace_id',
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
    // Sluggable — slug composé : nom de l'espace + nom de la zone
    // Exemple : espace "Piscine intérieure" + zone "Bassin 25m"
    //        → "piscine-interieure-bassin-25m"
    // -------------------------------------------------------------------------

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'   => ['espace.name', 'name'],
                'onUpdate' => false,
            ],
        ];
    }

    /**
     * Slug comme clé de route pour le route model binding
     * URL : /installations/zones/{zone} → résolu par slug
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    public function espace(): BelongsTo
    {
        return $this->belongsTo(Espace::class);
    }

    public function structure(): BelongsTo
    {
        return $this->espace->structure();
    }

    public function service(): BelongsTo
    {
        return $this->espace->structure->service();
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }
}