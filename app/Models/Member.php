<?php

namespace App\Models;

use App\Models\Concerns\LogsMemberChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Adhérent du club (la personne), conservé d'une saison à l'autre.
 * Données personnelles : accès réservé aux administrateurs et aux éditeurs.
 */
class Member extends Model
{
    use LogsMemberChanges;

    public const GENDERS = ['F' => 'F', 'M' => 'M'];

    protected $fillable = [
        'last_name', 'first_name', 'gender', 'birth_date', 'birth_place', 'address', 'postal_code', 'city',
        'phone1', 'phone2', 'email', 'image_rights', 'guardians', 'planning_swimmer_id', 'user_id', 'ffn_iuf',
    ];

    protected $casts = ['birth_date' => 'date', 'image_rights' => 'boolean', 'guardians' => 'array'];

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class)->orderByDesc('season');
    }

    public function swimmer(): BelongsTo
    {
        return $this->belongsTo(PlanningSwimmer::class, 'planning_swimmer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(MemberLog::class)->latest('created_at')->latest('id');
    }

    public function membershipFor(string $season): ?Membership
    {
        return $this->relationLoaded('memberships')
            ? $this->memberships->firstWhere('season', $season)
            : $this->memberships()->where('season', $season)->first();
    }

    /** « DURAND Alice » */
    public function getFullNameAttribute(): string
    {
        return trim(mb_strtoupper($this->last_name) . ' ' . $this->first_name);
    }

    public function getBirthYearAttribute(): ?int
    {
        return $this->birth_date?->year;
    }

    /** Âge au 1er septembre de la saison (ou aujourd'hui). */
    public function ageAt(?string $season = null): ?int
    {
        if (! $this->birth_date) {
            return null;
        }
        $ref = $season ? \Illuminate\Support\Carbon::create((int) substr($season, 0, 4), 9, 1) : today();

        return (int) floor(abs($this->birth_date->diffInYears($ref)));
    }

    public function isMinor(?string $season = null): bool
    {
        $age = $this->ageAt($season);

        return $age !== null && $age < 18;
    }

    /** Licence FFN correspondante (n° de licence, sinon nom + prénom + date de naissance), la plus récente. */
    public function findLicense(): ?FfnLicense
    {
        try {
            if ($this->ffn_iuf) {
                $licence = FfnLicense::where('iuf', $this->ffn_iuf)->orderByDesc('season')->first();
                if ($licence) {
                    return $licence;
                }
            }
            if (! $this->birth_date) {
                return null;
            }
            $cle = fn ($v) => Str::lower(Str::ascii(preg_replace('/[^\p{L}]/u', '', (string) $v)));

            return FfnLicense::whereDate('birth_date', $this->birth_date)->orderByDesc('season')->get()
                ->first(fn ($l) => $cle($l->last_name) === $cle($this->last_name) && $cle($l->first_name) === $cle($this->first_name));
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Clé « foyer » pour la remise famille : même e-mail, ou même adresse + code postal. */
    public function householdKeys(): array
    {
        $keys = [];
        if ($this->email) {
            $keys[] = 'm:' . Str::lower(trim($this->email));
        }
        if ($this->address && $this->postal_code) {
            $keys[] = 'a:' . Str::lower(Str::ascii(preg_replace('/\s+/', '', $this->address))) . '|' . preg_replace('/\s+/', '', $this->postal_code);
        }

        return $keys;
    }

    protected function memberIdForLog(): ?int
    {
        return $this->id;
    }

    protected function logSubject(): string
    {
        return 'Fiche adhérent';
    }
}
