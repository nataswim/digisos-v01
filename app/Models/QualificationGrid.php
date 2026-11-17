<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Grille de qualification (module Compétitions) : catégories d'âge en colonnes,
 * épreuves × Dames / Messieurs en lignes, un temps par bassin (25 m et 50 m).
 */
class QualificationGrid extends Model
{
    public const GENDERS = ['F' => 'Dames', 'M' => 'Messieurs'];

    public const POOLS = [25 => '25 m', 50 => '50 m'];

    protected $fillable = ['name', 'slug', 'description', 'period_start', 'period_end', 'is_published', 'sort_order'];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (QualificationGrid $grid) {
            if (blank($grid->slug) || $grid->isDirty('name')) {
                $grid->slug = static::uniqueSlug($grid->name, $grid->id);
            }
        });
        static::deleting(function (QualificationGrid $grid) {
            QualificationTime::where('qualification_grid_id', $grid->id)->delete();
            QualificationCategory::where('qualification_grid_id', $grid->id)->delete();
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'grille';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function categories(): HasMany
    {
        return $this->hasMany(QualificationCategory::class)->orderBy('sort_order')->orderBy('id');
    }

    public function times(): HasMany
    {
        return $this->hasMany(QualificationTime::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    /** Épreuves présentes dans la grille, dans l'ordre du module Performances. */
    public function events(): array
    {
        $presentes = $this->relationLoaded('times') ? $this->times->pluck('event')->unique()->all()
            : $this->times()->distinct()->pluck('event')->all();

        return array_values(array_filter(SwimPerformance::EVENTS, fn ($e) => in_array($e, $presentes, true)));
    }

    /** Temps indexés [category_id][event][gender][pool] => centièmes. */
    public function timeMap(): array
    {
        $map = [];
        foreach ($this->times as $t) {
            $map[$t->qualification_category_id][$t->event][$t->gender][$t->pool] = $t->time_cs;
        }

        return $map;
    }

    /** « 50 NL » → « 50 nage libre », « 200 4N » → « 200 4 nages ». */
    public static function eventLabel(string $event): string
    {
        [$distance, $nage] = array_pad(explode(' ', $event, 2), 2, '');

        return $distance.' '.(['NL' => 'nage libre', 'Dos' => 'dos', 'Brasse' => 'brasse', 'Pap' => 'papillon', '4N' => '4 nages'][$nage] ?? $nage);
    }

    /** Nage d'une épreuve : « 50 NL » → « NL ». */
    public static function strokeOf(string $event): string
    {
        return explode(' ', $event, 2)[1] ?? '';
    }

    /** « du 01/09/2026 au 15/03/2027 », ou null. */
    public function getPeriodLabelAttribute(): ?string
    {
        return match (true) {
            $this->period_start && $this->period_end => 'du '.$this->period_start->format('d/m/Y').' au '.$this->period_end->format('d/m/Y'),
            (bool) $this->period_start => 'à partir du '.$this->period_start->format('d/m/Y'),
            (bool) $this->period_end => 'jusqu\'au '.$this->period_end->format('d/m/Y'),
            default => null,
        };
    }
}
