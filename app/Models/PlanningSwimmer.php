<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Nageur du module Planning (saisi par l'administration, compte du site facultatif).
 * Les noms ne sont affichés sur le site qu'aux adhérents connectés (voir PublicPlanningController).
 */
class PlanningSwimmer extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'birth_year', 'user_id', 'notes', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'birth_year' => 'integer',
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(PlanningGroup::class, 'planning_group_swimmer')
            ->withTimestamps()
            ->orderBy('sort_order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** « Prénom NOM » */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . mb_strtoupper($this->last_name));
    }

    /** « Prénom N. » : forme courte affichée sur le site */
    public function getShortNameAttribute(): string
    {
        return trim($this->first_name . ' ' . mb_strtoupper(mb_substr($this->last_name, 0, 1)) . '.');
    }
}
