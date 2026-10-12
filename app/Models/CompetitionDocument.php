<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Document d'une compétition (convocation, programme officiel…), stocké sur le disque privé « local ».
 */
class CompetitionDocument extends Model
{
    public const VISIBILITIES = [
        'public'  => 'Tout le monde',
        'members' => 'Adhérents connectés',
    ];

    protected $fillable = ['competition_id', 'title', 'file_path', 'original_name', 'file_size', 'visibility'];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function isPublic(): bool
    {
        return $this->visibility === 'public';
    }

    /** « 245 Ko », « 1,2 Mo » */
    public function getSizeLabelAttribute(): string
    {
        if ($this->file_size >= 1048576) {
            return number_format($this->file_size / 1048576, 1, ',', ' ') . ' Mo';
        }

        return max(1, (int) round($this->file_size / 1024)) . ' Ko';
    }
}
