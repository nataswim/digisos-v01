<?php

namespace App\Models;

use App\Models\Concerns\LogsMemberChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Adhésion d'un adhérent pour une saison.
 * Montant = tarif − remise. Reste à payer = montant − Pass'Sport − règlements.
 */
class Membership extends Model
{
    use LogsMemberChanges;

    public const SITUATIONS = ['nouveau' => 'Nouveau', 'renouvellement' => 'Renouvellement', 'retour' => 'Retour après une pause'];

    /** [libellé, couleur Bootstrap] */
    public const STATUSES = [
        'preinscrit' => ['Pré-inscrit', 'secondary'],
        'en_cours' => ['Dossier en cours', 'warning'],
        'complet' => ['Complet', 'info'],
        'licencie' => ['Licencié', 'success'],
        'abandon' => ['Abandon', 'dark'],
    ];

    /** Pièces du dossier d'inscription. */
    public const FILE_ITEMS = [
        'fiche' => "Fiche d'inscription",
        'sanitaire' => 'Fiche sanitaire',
        'certificat' => 'Certificat médical / questionnaire de santé',
        'photo' => "Photo d'identité",
        'reglement' => 'Règlement',
        'caution' => 'Caution badge (5 €)',
    ];

    public const PAYMENT_METHODS = [
        'cheque' => 'Chèque',
        'virement' => 'Virement',
        'especes' => 'Espèces',
        'cb' => 'Carte bancaire',
        'coupons' => 'Coupons sport',
        'ancv' => 'Chèques vacances ANCV',
        'autre' => 'Autre',
    ];

    /** Remise famille proposée : 2 adhésions = 10 € (sur la 2e), 3 et plus = 10 % sur chacune. */
    public const FAMILY_TWO_CENTS = 1000;
    public const FAMILY_THREE_PERCENT = 10;

    protected $fillable = [
        'member_id', 'season', 'situation', 'member_category_id', 'price_cents', 'discount_cents', 'amount_cents', 'status',
        'file_complete', 'file_items', 'pass_sport', 'pass_sport_code', 'pass_sport_cents', 'payment_certificate',
        'payment_certificate_on', 'mini_coach', 'medical_certificate_year', 'observations',
    ];

    protected $casts = [
        'price_cents' => 'integer', 'discount_cents' => 'integer', 'amount_cents' => 'integer', 'pass_sport_cents' => 'integer',
        'file_complete' => 'boolean', 'file_items' => 'array', 'pass_sport' => 'boolean', 'payment_certificate' => 'boolean',
        'payment_certificate_on' => 'date', 'mini_coach' => 'boolean', 'medical_certificate_year' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Membership $m) {
            $m->amount_cents = max(0, (int) $m->price_cents - (int) $m->discount_cents);
            if (! $m->pass_sport) {
                $m->pass_sport_cents = 0;
            }
        });
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MemberCategory::class, 'member_category_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(MembershipPayment::class)->orderBy('cashed_on')->orderBy('id');
    }

    // ------------------------------------------------------------------

    /** Saison en cours : « 2026-2027 » à partir du 1er septembre 2026. */
    public static function currentSeason(): string
    {
        $d = today();
        $start = $d->month >= 9 ? $d->year : $d->year - 1;

        return $start . '-' . ($start + 1);
    }

    public static function nextSeason(string $season): string
    {
        $start = (int) substr($season, 0, 4) + 1;

        return $start . '-' . ($start + 1);
    }

    /** 19000 → « 190,00 € » */
    public static function money(?int $cents): string
    {
        return number_format(($cents ?? 0) / 100, 2, ',', ' ') . ' €';
    }

    /** « 190 », « 190,00 € », « 190.5 » → centimes ; null si illisible. */
    public static function parseMoney($value): ?int
    {
        $v = trim(str_replace(['€', ' ', "\u{00A0}", "\u{202F}"], '', (string) $value));
        if ($v === '') {
            return null;
        }
        $v = str_replace(',', '.', $v);
        if (! is_numeric($v)) {
            return null;
        }

        return (int) round(((float) $v) * 100);
    }

    public function getPaidCentsAttribute(): int
    {
        return (int) ($this->relationLoaded('payments') ? $this->payments->sum('amount_cents') : $this->payments()->sum('amount_cents'));
    }

    public function getCashedCentsAttribute(): int
    {
        return (int) ($this->relationLoaded('payments') ? $this->payments->where('is_cashed', true)->sum('amount_cents') : $this->payments()->where('is_cashed', true)->sum('amount_cents'));
    }

    /** Reste à payer (montant − Pass'Sport − règlements reçus). */
    public function getDueCentsAttribute(): int
    {
        return max(0, $this->amount_cents - ($this->pass_sport ? $this->pass_sport_cents : 0) - $this->paid_cents);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status][0] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUSES[$this->status][1] ?? 'secondary';
    }

    /**
     * Certificat médical : null = rien à signaler ; sinon message.
     * Adultes : certificat valable 3 saisons ; mineurs : questionnaire de santé chaque saison.
     */
    public function certificateAlert(): ?string
    {
        $debut = (int) substr($this->season, 0, 4);
        if ($this->member?->isMinor($this->season)) {
            return null;
        }
        if (! $this->medical_certificate_year) {
            return 'Année du certificat inconnue';
        }

        return $this->medical_certificate_year <= $debut - 3 ? 'Certificat à renouveler (' . $this->medical_certificate_year . ')' : null;
    }

    /** Pièces du dossier : « complet » si toutes cochées. */
    public function syncFileComplete(): void
    {
        $items = array_keys(array_filter((array) $this->file_items));
        if ($items && count(array_intersect($items, array_keys(self::FILE_ITEMS))) === count(self::FILE_ITEMS)) {
            $this->file_complete = true;
        }
    }

    protected function memberIdForLog(): ?int
    {
        return $this->member_id;
    }

    protected function logSubject(): string
    {
        return 'Adhésion ' . $this->season;
    }
}
