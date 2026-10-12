<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Licence FFN d'une saison, importée depuis l'extraction extraNat (réservé aux administrateurs).
 */
class FfnLicense extends Model
{
    /** Préfixe de colonne d'activité => libellé */
    public const ACTIVITY_GROUPS = [
        'CPT' => 'Compétition',
        'NPT' => 'Natation pour tous',
        'COACH' => 'Encadrement',
        'DIV' => 'Divers',
    ];

    /** Champs repris du fichier (servent aussi à détecter les modifications) */
    public const IMPORTED_FIELDS = [
        'civility', 'gender', 'last_name', 'first_name', 'birth_date', 'nationality', 'address', 'address2',
        'postal_code', 'city', 'country', 'phone1', 'phone2', 'phone3', 'email', 'club_code', 'club_name',
        'region', 'department', 'medical_certificate_on', 'license_type', 'homologated_on', 'license_status',
        'ffn_mail_consent', 'activities',
    ];

    protected $fillable = [
        'season', 'iuf', 'civility', 'gender', 'last_name', 'first_name', 'birth_date', 'nationality', 'address',
        'address2', 'postal_code', 'city', 'country', 'phone1', 'phone2', 'phone3', 'email', 'club_code',
        'club_name', 'region', 'department', 'medical_certificate_on', 'license_type', 'homologated_on',
        'license_status', 'ffn_mail_consent', 'activities', 'planning_swimmer_id', 'ffn_license_import_id',
    ];

    protected $casts = [
        'season' => 'integer',
        'birth_date' => 'date',
        'medical_certificate_on' => 'date',
        'homologated_on' => 'date',
        'ffn_mail_consent' => 'boolean',
        'activities' => 'array',
    ];

    public function swimmer(): BelongsTo
    {
        return $this->belongsTo(PlanningSwimmer::class, 'planning_swimmer_id');
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(FfnLicenseImport::class, 'ffn_license_import_id');
    }

    public function scopeSeason($query, int $season)
    {
        return $query->where('season', $season);
    }

    /** « Prénom NOM » */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . mb_strtoupper($this->last_name));
    }

    /** Âge actuel en années */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? (int) $this->birth_date->diffInYears(today()) : null;
    }

    /** Âge atteint au cours de l'année civile (référence courante des catégories d'âge) */
    public function getAgeInYearAttribute(): ?int
    {
        return $this->birth_date ? (int) today()->year - (int) $this->birth_date->year : null;
    }

    /** « Compétition — Natation », « Encadrement — Natation »… */
    public function getActivityLabelsAttribute(): array
    {
        return array_map(function ($colonne) {
            [$prefixe, $discipline] = array_map('trim', explode('-', $colonne, 2) + [1 => '']);
            $groupe = self::ACTIVITY_GROUPS[mb_strtoupper($prefixe)] ?? $prefixe;

            return trim($groupe . ' — ' . mb_convert_case(mb_strtolower($discipline), MB_CASE_TITLE), ' —');
        }, $this->activities ?? []);
    }

    /** Téléphones renseignés */
    public function getPhonesAttribute(): array
    {
        return array_values(array_filter([$this->phone1, $this->phone2, $this->phone3]));
    }

    /** Adresse sur une ligne */
    public function getFullAddressAttribute(): ?string
    {
        $adresse = trim(implode(', ', array_filter([
            $this->address, $this->address2, trim(($this->postal_code ?? '') . ' ' . ($this->city ?? '')),
        ])));

        return $adresse !== '' ? $adresse : null;
    }

    public static function phoneHref(string $phone): string
    {
        $chiffres = preg_replace('/[^0-9+]/', '', $phone);

        return str_starts_with($chiffres, '0') ? '+33' . substr($chiffres, 1) : $chiffres;
    }
}
