<?php

namespace App\Models;

use App\Support\RichText;
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
    /** Niveaux, dans l'ordre d'affichage : libellé du bloc, libellé court, couleurs [fond, texte], icône, description facultative. */
    public const LEVELS = [
        'principal' => ['label' => 'Partenaire principal', 'short' => 'Principal', 'bg' => '#2f80b8', 'text' => '#ffffff', 'icon' => 'fa-star'],
        'or'        => ['label' => 'Partenaires Or',       'short' => 'Or',        'bg' => '#d4a017', 'text' => '#1c2111', 'icon' => 'fa-medal'],
        'argent'    => ['label' => 'Partenaires Argent',   'short' => 'Argent',    'bg' => '#a8adb3', 'text' => '#1c2111', 'icon' => 'fa-medal'],
        'bronze'    => ['label' => 'Partenaires Bronze',   'short' => 'Bronze',    'bg' => '#b0703c', 'text' => '#ffffff', 'icon' => 'fa-medal'],

        // Familles de partenaires (clé de 20 caractères au plus) ; « description » est affichée sous le titre du bloc
        'collectivites' => ['label' => 'Collectivités et acteurs institutionnels', 'short' => 'Collectivités', 'bg' => '#1d4e89', 'text' => '#ffffff', 'icon' => 'fa-landmark',
            'description' => 'Communes, intercommunalités et instances sportives soutenant le développement des infrastructures et de la vie associative.'],
        'federaux'      => ['label' => 'Partenaires fédéraux et d\'activités', 'short' => 'Fédéraux & activités', 'bg' => '#0b6e99', 'text' => '#ffffff', 'icon' => 'fa-flag',
            'description' => 'Fédérations, ligues et comités sportifs accompagnant le club dans ses activités, ses compétitions et la formation de ses nageurs et encadrants.'],
        'technique'     => ['label' => 'Partenaires techniques ou de services', 'short' => 'Techniques / services', 'bg' => '#3d5a6c', 'text' => '#ffffff', 'icon' => 'fa-tools',
            'description' => 'Prestataires accompagnant le club par leur savoir-faire, leurs prestations ou la mise à disposition de matériel et d\'équipements.'],
        'commerces'     => ['label' => 'Commerçants et PME locales', 'short' => 'Commerces & PME', 'bg' => '#c0631b', 'text' => '#ffffff', 'icon' => 'fa-store',
            'description' => 'Acteurs économiques de proximité engagés au quotidien aux côtés du club et de ses licenciés.'],
        'sante'         => ['label' => 'Partenaires santé et bien-être', 'short' => 'Santé & bien-être', 'bg' => '#1f8a70', 'text' => '#ffffff', 'icon' => 'fa-heartbeat',
            'description' => 'Professionnels de santé, préparateurs et centres spécialisés veillant à la prévention, au suivi et à la récupération des sportifs.'],
        'formation'     => ['label' => 'Partenaires compétences et formations', 'short' => 'Compétences & formations', 'bg' => '#5b3f8c', 'text' => '#ffffff', 'icon' => 'fa-graduation-cap',
            'description' => 'Organismes, écoles et experts contribuant à l\'accompagnement éducatif, à la formation des encadrants et à l\'insertion professionnelle.'],
        'evenementiel'  => ['label' => 'Partenaires événementiels et réceptifs', 'short' => 'Événementiel', 'bg' => '#b3264a', 'text' => '#ffffff', 'icon' => 'fa-glass-cheers',
            'description' => 'Acteurs de la restauration, de la location et de l\'animation assurant la réussite des tournois, réceptions et moments conviviaux.'],
        'medias'        => ['label' => 'Médias et presse', 'short' => 'Médias & presse', 'bg' => '#2b2d42', 'text' => '#ffffff', 'icon' => 'fa-newspaper',
            'description' => 'Supports d\'information locaux et régionaux assurant le relais médiatique et la visibilité des événements du club.'],
        'solidaires'    => ['label' => 'Partenaires associatifs et solidaires', 'short' => 'Associatifs & solidaires', 'bg' => '#2a7f9e', 'text' => '#ffffff', 'icon' => 'fa-hands-helping',
            'description' => 'Associations, fondations et structures engagées menant des actions citoyennes, caritatives ou inclusives aux côtés du club.'],
        'supporters'    => ['label' => 'Club des supporters', 'short' => 'Supporters', 'bg' => '#efa525', 'text' => '#1c2111', 'icon' => 'fa-users',
            'description' => 'Particuliers, bénévoles, anciens joueurs et sympathisants soutenant financièrement et humainement les projets du club.'],
    ];

    /** Nature du partenariat (facultatif). */
    public const CATEGORIES = [
        'sponsor'        => 'Sponsor',
        'mecene'         => 'Mécène',
        'institutionnel' => 'Partenaire institutionnel',
        'fournisseur'    => 'Fournisseur',
        'media'          => 'Partenaire média',
    ];

    /** Dossier des logos envoyés depuis l'ordinateur, sur le disque « public ». */
    public const LOGO_DIR = 'partners';

    protected $fillable = [
        'name', 'slug', 'level', 'category', 'summary', 'presentation', 'actions',
        'website', 'partner_since', 'logo', 'sort_order', 'is_active',
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

        // Présentation et actions : HTML de l'éditeur nettoyé à l'enregistrement
        static::saving(function (Partner $partner) {
            foreach (['presentation', 'actions'] as $field) {
                if ($partner->isDirty($field) && RichText::isHtml($partner->{$field})) {
                    $partner->{$field} = RichText::clean($partner->{$field}) ?: null;
                }
            }
        });

        // Un logo envoyé depuis l'ordinateur part avec le partenaire (pas une image de la médiathèque)
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
        return $this->logo ?: null;
    }

    /** Présentation prête à afficher : HTML de l'éditeur, ou ancien texte brut mis en paragraphes. */
    public function getPresentationHtmlAttribute(): string
    {
        $text = (string) $this->presentation;

        if (RichText::isHtml($text)) {
            return RichText::clean($text);
        }

        return collect(preg_split("/\R\s*\R/u", trim($text)))
            ->filter(fn ($p) => trim($p) !== '')
            ->map(fn ($p) => '<p>' . nl2br(e(trim($p))) . '</p>')
            ->implode("\n");
    }

    /** Actions prêtes à afficher : HTML de l'éditeur, ou ancien texte brut (une action par ligne) mis en liste. */
    public function getActionsHtmlAttribute(): string
    {
        $text = (string) $this->actions;

        if (RichText::isHtml($text)) {
            return RichText::clean($text);
        }

        $lines = collect(preg_split("/\R/u", trim($text)))
            ->map(fn ($l) => trim(ltrim(trim($l), "-•*–")))
            ->filter();

        return $lines->isEmpty() ? '' : '<ul>' . $lines->map(fn ($l) => '<li>' . e($l) . '</li>')->implode('') . '</ul>';
    }

    /** Vrai si le logo est un fichier envoyé pour ce partenaire (et non une image de la médiathèque). */
    public function hasUploadedLogo(): bool
    {
        return (bool) $this->uploadedLogoPath();
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

    /** Supprime le fichier du logo s'il a été envoyé pour ce partenaire ; les images de la médiathèque ne sont jamais supprimées. */
    public function deleteLogoFile(?string $logo = null): void
    {
        $path = $this->uploadedLogoPath($logo ?? $this->getOriginal('logo') ?? $this->logo);

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /** « /storage/partners/x.png » (ou l'adresse complète) → « partners/x.png » ; null pour une autre image. */
    private function uploadedLogoPath(?string $logo = null): ?string
    {
        $logo = $logo ?? $this->logo;
        if (! $logo) {
            return null;
        }

        $path = parse_url($logo, PHP_URL_PATH) ?: '';
        $prefix = '/storage/' . self::LOGO_DIR . '/';

        return str_starts_with($path, $prefix) ? substr($path, strlen('/storage/')) : null;
    }
}
