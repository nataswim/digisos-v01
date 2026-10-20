<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * Membre de l'équipe du club (dirigeant, entraîneur, bénévole, officiel…).
 * Rattaché à une ou plusieurs sections, avec une fonction pour chacune (table team_member_section).
 */
class TeamMember extends Model
{
    /** Réseaux proposés : champ => [libellé, icône Font Awesome]. */
    public const LINKS = [
        'facebook_url'  => ['Facebook',  'fab fa-facebook-f'],
        'instagram_url' => ['Instagram', 'fab fa-instagram'],
        'linkedin_url'  => ['LinkedIn',  'fab fa-linkedin-in'],
        'website_url'   => ['Site web',  'fas fa-globe'],
    ];

    protected $fillable = [
        'first_name', 'last_name', 'photo',
        'facebook_url', 'instagram_url', 'linkedin_url', 'website_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(TeamSection::class, 'team_member_section')
            ->withPivot(['position', 'sort_order'])
            ->withTimestamps()
            ->orderBy('team_sections.sort_order');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('last_name')->orderBy('first_name');
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /** Initiales, affichées à la place de la photo quand il n'y en a pas. */
    public function getInitialsAttribute(): string
    {
        return Str::upper(Str::substr($this->first_name, 0, 1) . Str::substr($this->last_name, 0, 1));
    }

    /** Liens renseignés : [['url' => …, 'label' => …, 'icon' => …], …] */
    public function getLinksAttribute(): array
    {
        $links = [];
        foreach (self::LINKS as $field => [$label, $icon]) {
            if (filled($this->{$field})) {
                $links[] = ['url' => $this->{$field}, 'label' => $label, 'icon' => $icon];
            }
        }

        return $links;
    }
}
