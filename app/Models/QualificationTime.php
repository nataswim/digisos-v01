<?php

namespace App\Models;

use App\Support\SwimTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Temps de qualification : catégorie × épreuve × sexe × bassin, en centièmes. */
class QualificationTime extends Model
{
    protected $fillable = ['qualification_grid_id', 'qualification_category_id', 'event', 'gender', 'pool', 'time_cs'];

    protected $casts = ['pool' => 'integer', 'time_cs' => 'integer'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(QualificationCategory::class, 'qualification_category_id');
    }

    public function getTimeLabelAttribute(): string
    {
        return (string) SwimTime::format($this->time_cs);
    }
}
