<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Structure extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'service_id',
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
    // Sluggable — slug composé : nom du service + nom de la structure
    // Exemple : service "Complexe Aquatique" + structure "Centre aquatique"
    //        → "complexe-aquatique-centre-aquatique"
    // -------------------------------------------------------------------------

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'   => ['service.name', 'name'],
                'onUpdate' => false,
            ],
        ];
    }

    /**
     * Slug comme clé de route pour le route model binding
     * URL : /installations/structures/{structure} → résolu par slug
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function espaces(): HasMany
    {
        return $this->hasMany(Espace::class);
    }

    public function zones(): HasManyThrough
    {
        return $this->hasManyThrough(Zone::class, Espace::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }
}