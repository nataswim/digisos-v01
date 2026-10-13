<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Partenaire du club (module Partenaires).
 * Géré par les administrateurs et les éditeurs ; affiché sur /partenaires.
 */
class Partner extends Model
{
    /** Niveaux, du plus élevé au plus bas : libellé du bloc, libellé court, couleurs [fond, texte]. */
    public const LEVELS = [
        'principal' => ['label' => 'Partenaire principal', 'short' => 'Principal', 'bg' => '#2f80b8', 'text' => '#ffffff', 'icon' => 'fa-star'],
        'or'        => ['label' => 'Partenaires Or',       'short' => 'Or',        'bg' => '#d4a017', 'text' => '#1c2111', 'icon' => 'fa-medal'],
        'argent'    => ['label' => 'Partenaires Argent',   'short' => 'Argent',    'bg' => '#a8adb3', 'text' => '#1c2111', 'icon' => 'fa-medal'],
        'bronze'    => ['label' => 'Partenaires Bronze',   'short' => 'Bronze',    'bg' => '#b0703c', 'text' => '#ffffff', 'icon' => 'fa-medal'],
    ];

    /** Nature du partenariat (facultatif). */
    public const CATEGORIES = [
        'sponsor'        => 'Sponsor',
        'mecene'         => 'Mécène',
        'institutionnel' => 'Partenaire institutionnel',
        'fournisseur'    => 'Fournisseur',
        'media'          => 'Partenaire média',
    ];

    /** Dossier des logos sur le disque « public ». */
    public const LOGO_DIR = 'partners';

    protected $fillable = [
        'name', 'slug', 'level', 'category', 'summary', 'presentation', 'actions',
        'website', 'partner_since', 'logo_path', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'partner_since' => 'integer',
    ];

    protected static function booted(): void
    {
        // Identifiant d'URL calculé à partir du nom, unique
        static::saving(function (Partner $partner) {
            if (blank($partner->slug) || $partner->isDirty('name')) {
                $base = Str::slug($partner->name) ?: 'partenaire';
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->whereKeyNot($partner->id)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $partner->slug = $slug;
            }
        });

        // Le logo part avec le partenaire
        static::deleted(function (Partner $partner) {
            $partner->deleteLogoFile();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ---------------------------------------------------------------- Requêtes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Ordre d'affichage : niveau (principal → bronze), puis ordre choisi, puis nom. */
    public function scopeOrdered(Builder $query): Builder
    {
        $levels = array_keys(self::LEVELS);
        $case = 'CASE level ' . collect($levels)->map(fn ($l, $i) => "WHEN '{$l}' THEN {$i}")->implode(' ') . ' ELSE ' . count($levels) . ' END';

        return $query->orderByRaw($case)->orderBy('sort_order')->orderBy('name');
    }

    // ---------------------------------------------------------------- Attributs calculés

    public function getLevelInfoAttribute(): array
    {
        return self::LEVELS[$this->level] ?? self::LEVELS['bronze'];
    }

    public function getCategoryLabelAttribute(): ?string
    {
        return self::CATEGORIES[$this->category] ?? null;
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::disk('public')->url($this->logo_path) : null;
    }

    /** Adresse du site sans « https:// » ni « www. », pour l'affichage. */
    public function getWebsiteHostAttribute(): ?string
    {
        if (! $this->website) {
            return null;
        }

        $host = parse_url($this->website, PHP_URL_HOST) ?: $this->website;

        return preg_replace('/^www\./i', '', $host);
    }

    /** Initiales affichées quand le partenaire n'a pas de logo. */
    public function getInitialsAttribute(): string
    {
        return collect(preg_split('/[\s\-]+/u', trim($this->name)))
            ->filter()
            ->take(2)
            ->map(fn ($mot) => mb_strtoupper(mb_substr($mot, 0, 1)))
            ->implode('');
    }

    public function deleteLogoFile(): void
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            Storage::disk('public')->delete($this->logo_path);
        }
    }
}
