<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Exercice de la bibliothèque (PPG, séances à la maison) : image, description, conseils de posture.
 */
class TrainingExercise extends Model
{
    protected $fillable = ['name', 'category', 'image', 'video_url', 'description', 'tips', 'is_active', 'created_by'];

    protected $casts = ['is_active' => 'boolean'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Nombre de séances qui utilisent l'exercice. */
    public function usageCount(): int
    {
        return TrainingSession::whereIn('type', ['ppg', 'maison'])->get(['content'])
            ->filter(fn ($s) => collect($s->content['items'] ?? [])->contains('exercise_id', $this->id))
            ->count();
    }
}
