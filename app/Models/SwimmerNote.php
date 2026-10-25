<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Note de suivi d'un nageur, rédigée par un coach (espace coachs).
 * Visible uniquement des coachs et des administrateurs. Aucune donnée médicale.
 */
class SwimmerNote extends Model
{
    public const CATEGORIES = [
        'entrainement' => 'Entraînement',
        'technique' => 'Technique',
        'competition' => 'Compétition',
        'objectifs' => 'Objectifs',
        'autre' => 'Autre',
    ];

    protected $fillable = ['planning_swimmer_id', 'user_id', 'noted_on', 'category', 'content'];

    protected $casts = ['noted_on' => 'date'];

    public function swimmer(): BelongsTo
    {
        return $this->belongsTo(PlanningSwimmer::class, 'planning_swimmer_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? 'Autre';
    }

    /** L'auteur et les administrateurs peuvent modifier ou supprimer la note. */
    public function isEditableBy(?User $user): bool
    {
        return $user && ($user->hasRole('admin') || $this->user_id === $user->id);
    }
}
