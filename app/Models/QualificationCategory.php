<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Catégorie d'âge d'une grille de qualification : libellé + années de naissance.
 * birth_year_from vide = « et avant », birth_year_to vide = « et après ».
 */
class QualificationCategory extends Model
{
    protected $fillable = ['qualification_grid_id', 'label', 'birth_year_from', 'birth_year_to', 'sort_order'];

    protected $casts = [
        'birth_year_from' => 'integer',
        'birth_year_to' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        // Les temps de la catégorie disparaissent avec elle (même si la base n'applique pas les clés étrangères)
        static::deleting(fn (QualificationCategory $cat) => QualificationTime::where('qualification_category_id', $cat->id)->delete());
    }

    public function grid(): BelongsTo
    {
        return $this->belongsTo(QualificationGrid::class, 'qualification_grid_id');
    }

    public function includesYear(?int $year): bool
    {
        if (! $year || ($this->birth_year_from === null && $this->birth_year_to === null)) {
            return false;
        }

        return ($this->birth_year_from === null || $year >= $this->birth_year_from)
            && ($this->birth_year_to === null || $year <= $this->birth_year_to);
    }

    /** « 2014 », « 2010-2011 », « 2009 et avant », « 2015 et après ». */
    public function getYearsLabelAttribute(): string
    {
        [$de, $a] = [$this->birth_year_from, $this->birth_year_to];

        return match (true) {
            $de && $a && $de === $a => (string) $de,
            $de && $a => $de.'-'.$a,
            (bool) $a => $a.' et avant',
            (bool) $de => $de.' et après',
            default => '',
        };
    }

    public function getFullLabelAttribute(): string
    {
        return trim($this->label.($this->years_label ? ' ('.$this->years_label.')' : ''));
    }
}
