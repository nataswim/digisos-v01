<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Groupe d'entraînement du module Planning.
 */
class PlanningGroup extends Model
{
    /** Sections du club, dans l'ordre du parcours, avec leur couleur [fond, texte]. */
    public const SECTIONS = [
        'École de natation' => ['#17a2b8', '#ffffff'],
        'Jeunesse'          => ['#198754', '#ffffff'],
        'Relève'            => ['#efa525', '#1c2111'],
        'Ligue'             => ['#2f80b8', '#ffffff'],
        'Maîtres'           => ['#1c2111', '#ffffff'],
    ];

    protected $fillable = [
        'name', 'slug', 'section', 'color', 'description', 'page_url', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (PlanningGroup $group) {
            if (blank($group->slug) || $group->isDirty('name')) {
                $group->slug = Str::slug($group->name);
            }
        });
    }

    public function swimmers(): BelongsToMany
    {
        return $this->belongsToMany(PlanningSwimmer::class, 'planning_group_swimmer')
            ->withTimestamps()
            ->orderBy('last_name')
            ->orderBy('first_name');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(PlanningSlot::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /** Couleur de fond : celle du groupe, sinon celle de sa section. */
    public function getBackgroundColorAttribute(): string
    {
        return $this->color ?: (self::SECTIONS[$this->section][0] ?? '#2f80b8');
    }

    /** Couleur du texte lisible sur le fond. */
    public function getTextColorAttribute(): string
    {
        if (! $this->color) {
            return self::SECTIONS[$this->section][1] ?? '#ffffff';
        }

        // Luminance simple : texte sombre sur fond clair
        [$r, $g, $b] = sscanf($this->color, '#%02x%02x%02x');

        return (0.299 * $r + 0.587 * $g + 0.114 * $b) > 160 ? '#1c2111' : '#ffffff';
    }
}
