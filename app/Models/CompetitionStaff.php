<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Membre de l'encadrement d'une compétition (entraîneur, officiel, accompagnateur, chauffeur).
 */
class CompetitionStaff extends Model
{
    protected $table = 'competition_staff';

    public const ROLES = [
        'entraineur'     => 'Entraîneur',
        'officiel'       => 'Officiel',
        'accompagnateur' => 'Accompagnateur',
        'chauffeur'      => 'Chauffeur',
        'responsable'    => 'Responsable de déplacement',
    ];

    protected $fillable = ['competition_id', 'name', 'role', 'phone', 'sort_order'];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    /** Lien tel: (chiffres et + uniquement) */
    public function getPhoneHrefAttribute(): ?string
    {
        if (! $this->phone) {
            return null;
        }
        $digits = preg_replace('/[^\d+]/', '', $this->phone);

        return str_starts_with($digits, '0') ? '+33' . substr($digits, 1) : $digits;
    }
}
