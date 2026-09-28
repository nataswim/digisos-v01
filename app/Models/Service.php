<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
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
    // Sluggable — basé sur le nom uniquement (niveau racine)
    // Exemple : "Complexe Aquatique Municipal" → "complexe-aquatique-municipal"
    // -------------------------------------------------------------------------

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'   => 'name',
                'onUpdate' => false, // ne pas régénérer si le nom change
            ],
        ];
    }

    /**
     * Laravel utilisera le slug pour le route model binding
     * URL : /installations/{service} → résolu par slug
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }

    public function espaces(): HasManyThrough
    {
        return $this->hasManyThrough(Espace::class, Structure::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }
}