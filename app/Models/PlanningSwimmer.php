<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Nageur du module Planning (saisi par l'administration, compte du site facultatif).
 * Les noms ne sont affichés sur le site qu'aux adhérents connectés (voir PublicPlanningController).
 */
class PlanningSwimmer extends Model
{
    public const GENDERS = [
        'F' => 'Fille / femme',
        'M' => 'Garçon / homme',
    ];

    protected $fillable = [
        'first_name', 'last_name', 'photo', 'birth_year', 'gender', 'license_number', 'user_id', 'notes', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'birth_year' => 'integer',
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(PlanningGroup::class, 'planning_group_swimmer')
            ->withTimestamps()
            ->orderBy('sort_order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Convocations aux compétitions (module Compétitions, V2). */
    public function attendances(): HasMany
    {
        return $this->hasMany(CompetitionAttendance::class);
    }

    /** Notes de suivi (espace coachs), la plus récente en premier. */
    public function coachNotes(): HasMany
    {
        return $this->hasMany(SwimmerNote::class)->orderByDesc('noted_on')->orderByDesc('id');
    }

    /** Émargements aux séances d'entraînement. */
    public function presenceRecords(): HasMany
    {
        return $this->hasMany(PresenceRecord::class);
    }

    /** Licences FFN importées (une par saison), la plus récente en premier. */
    public function ffnLicenses(): HasMany
    {
        return $this->hasMany(FfnLicense::class)->orderByDesc('season');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Nageurs rattachés à un compte du site (famille), actifs, avec groupes et licences. */
    public function scopeOfUser($query, ?int $userId)
    {
        return $query->where('user_id', $userId)->where('is_active', true)
            ->with(['groups', 'ffnLicenses'])->orderBy('first_name');
    }

    /** Adresse de la photo (URL complète, chemin /… ou chemin relatif dans public), ou null. */
    public function getPhotoUrlAttribute(): ?string
    {
        $photo = trim((string) $this->photo);
        if ($photo === '') {
            return null;
        }

        return preg_match('#^(https?:)?//|^/#', $photo) ? $photo : asset($photo);
    }

    /** Numéro de licence : celui de la fiche, sinon l'IUF de la dernière licence FFN importée. */
    public function getLicenseLabelAttribute(): ?string
    {
        return $this->license_number ?: $this->ffnLicenses->first()?->iuf;
    }

    /** « Prénom NOM » */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . mb_strtoupper($this->last_name));
    }

    /** Initiales, affichées dans l'administration quand il n'y a pas de photo */
    public function getInitialsAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->first_name, 0, 1) . mb_substr($this->last_name, 0, 1));
    }

    /** « Prénom N. » : forme courte affichée sur le site */
    public function getShortNameAttribute(): string
    {
        return trim($this->first_name . ' ' . mb_strtoupper(mb_substr($this->last_name, 0, 1)) . '.');
    }
}
