<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Lien vers un site internet (page « Liens utiles »).
 */
class Link extends Model
{
    protected $fillable = [
        'link_category_id', 'title', 'url', 'facebook_url', 'instagram_url', 'image', 'description', 'keywords', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        // Mots-clés rangés : « natation,  FFN , ffn » → « natation, FFN »
        static::saving(function (Link $link) {
            $link->keywords = static::normalizeKeywords($link->keywords);
        });
    }

    public static function normalizeKeywords(?string $keywords): ?string
    {
        $list = collect(preg_split('/[,;\n]+/', (string) $keywords))
            ->map(fn ($k) => trim(preg_replace('/\s+/', ' ', $k)))
            ->filter()
            ->unique(fn ($k) => Str::lower($k))
            ->values();

        return $list->isEmpty() ? null : $list->implode(', ');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(LinkCategory::class, 'link_category_id');
    }

    /** Liens affichés : actifs, dans une catégorie active. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true));
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /** Réseaux sociaux renseignés : [['url' => …, 'label' => …, 'icon' => …, 'class' => …], …] */
    public function getSocialLinksAttribute(): array
    {
        $reseaux = [];
        foreach ([
            'facebook_url' => ['Facebook', 'fab fa-facebook-f', 'facebook'],
            'instagram_url' => ['Instagram', 'fab fa-instagram', 'instagram'],
        ] as $champ => [$label, $icon, $class]) {
            if (filled($this->{$champ})) {
                $reseaux[] = ['url' => $this->{$champ}, 'label' => $label, 'icon' => $icon, 'class' => $class];
            }
        }

        return $reseaux;
    }

    /** @return array<string> */
    public function getKeywordListAttribute(): array
    {
        return $this->keywords ? array_map('trim', explode(',', $this->keywords)) : [];
    }

    /** Nom de domaine affiché sous le titre (« ffnatation.fr »). */
    public function getHostAttribute(): ?string
    {
        $host = parse_url($this->url, PHP_URL_HOST);

        return $host ? preg_replace('/^www\./i', '', $host) : null;
    }

    /** Icône du site lié, utilisée quand aucune image n'a été choisie. */
    public function getFaviconUrlAttribute(): ?string
    {
        $scheme = parse_url($this->url, PHP_URL_SCHEME);
        $host = parse_url($this->url, PHP_URL_HOST);

        return $scheme && $host ? "{$scheme}://{$host}/favicon.ico" : null;
    }
}
