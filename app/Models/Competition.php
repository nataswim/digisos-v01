<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

/**
 * Compétition du calendrier du club (module Compétitions : V1 calendrier, V2 convocations et engagements).
 */
class Competition extends Model
{
    public const LEVELS = [
        'intraclub'     => 'Intraclub',
        'departemental' => 'Départemental',
        'regional'      => 'Régional',
        'interregional' => 'Interrégional',
        'national'      => 'National',
        'international' => 'International',
        'meeting'       => 'Meeting',
    ];

    public const KINDS = [
        'individuel' => 'Individuel',
        'interclubs' => 'Interclubs',
        'eau-libre'  => 'Eau libre',
    ];

    public const TRANSPORTS = [
        'bus'         => 'Bus',
        'minibus'     => 'Minibus du club',
        'covoiturage' => 'Covoiturage',
        'train'       => 'Train',
        'individuel'  => 'Par ses propres moyens',
    ];

    /** Statuts saisis à la main ; les autres sont calculés depuis les dates. */
    /** Délai de réponse des familles par défaut (règlement : confirmation 4 jours avant). */
    public const DEFAULT_RESPONSE_DAYS = 4;

    public const STATE_OVERRIDES = [
        'annulee'  => 'Annulée',
        'reportee' => 'Reportée',
    ];

    /** Statut => [libellé, classe de badge Bootstrap] */
    public const STATES = [
        'a-venir'  => ['À venir', 'bg-primary'],
        'en-cours' => ['En cours', 'bg-success'],
        'terminee' => ['Terminée', 'bg-secondary'],
        'annulee'  => ['Annulée', 'bg-danger'],
        'reportee' => ['Reportée', 'bg-warning text-dark'],
    ];

    protected $fillable = [
        'title', 'slug', 'season', 'level', 'kind', 'starts_on', 'ends_on', 'venue', 'city', 'address',
        'pool_length', 'lanes', 'organizer', 'description', 'image', 'extranat_url', 'live_url', 'state_override',
        'meeting_point', 'departure_at', 'return_at', 'transport', 'accommodation', 'meals', 'logistics_notes',
        'response_deadline', 'entries_visible', 'is_published', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'starts_on' => 'date',
        'ends_on' => 'date',
        'departure_at' => 'datetime',
        'return_at' => 'datetime',
        'response_deadline' => 'date',
        'entries_visible' => 'boolean',
        'is_published' => 'boolean',
        'pool_length' => 'integer',
        'lanes' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Competition $competition) {
            $competition->season = self::seasonFor($competition->starts_on);

            // Date limite de réponse : 4 jours avant par défaut, recalculée si les dates changent
            $datesChanged = $competition->exists && $competition->isDirty('starts_on') && ! $competition->isDirty('response_deadline');
            if ($competition->starts_on && (! $competition->response_deadline || $datesChanged)) {
                $competition->response_deadline = Carbon::parse($competition->starts_on)->subDays(self::DEFAULT_RESPONSE_DAYS);
            }

            if (blank($competition->slug) || $competition->isDirty('title')) {
                $competition->slug = $competition->uniqueSlug();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ------------------------------------------------------------------ relations

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(PlanningGroup::class, 'competition_planning_group')->orderBy('sort_order');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(CompetitionStaff::class)->orderBy('sort_order');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CompetitionSession::class)->orderBy('day')->orderBy('sort_order')->orderBy('starts_at');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CompetitionDocument::class)->orderBy('title');
    }

    /** Toutes les épreuves au programme (toutes réunions confondues). */
    public function programEvents(): HasManyThrough
    {
        return $this->hasManyThrough(CompetitionEvent::class, CompetitionSession::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(CompetitionAttendance::class);
    }

    // ------------------------------------------------------------------ scopes

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('ends_on', '>=', today())->orderBy('starts_on');
    }

    public function scopePast($query)
    {
        return $query->whereDate('ends_on', '<', today())->orderByDesc('starts_on');
    }

    // ------------------------------------------------------------------ calculs

    /** Saison sportive de septembre à août : « 2026-2027 ». */
    public static function seasonFor($date): string
    {
        $date = Carbon::parse($date ?? today());
        $start = $date->month >= 9 ? $date->year : $date->year - 1;

        return $start . '-' . ($start + 1);
    }

    public static function currentSeason(): string
    {
        return self::seasonFor(today());
    }

    /** Clé du statut : annulee / reportee (saisis) ou a-venir / en-cours / terminee (calculés). */
    public function getStateAttribute(): string
    {
        if ($this->state_override && isset(self::STATES[$this->state_override])) {
            return $this->state_override;
        }
        if ($this->starts_on && today()->lt($this->starts_on)) {
            return 'a-venir';
        }
        if ($this->ends_on && today()->gt($this->ends_on)) {
            return 'terminee';
        }

        return 'en-cours';
    }

    public function getStateLabelAttribute(): string
    {
        return self::STATES[$this->state][0];
    }

    public function getStateBadgeAttribute(): string
    {
        return self::STATES[$this->state][1];
    }

    public function getLevelLabelAttribute(): string
    {
        return self::LEVELS[$this->level] ?? $this->level;
    }

    public function getKindLabelAttribute(): string
    {
        return self::KINDS[$this->kind] ?? $this->kind;
    }

    public function getTransportLabelAttribute(): ?string
    {
        return $this->transport ? (self::TRANSPORTS[$this->transport] ?? $this->transport) : null;
    }

    /** « Du 14 au 16 novembre 2026 », « Le 14 novembre 2026 », « Du 30 novembre au 2 décembre 2026 » */
    public function getDateRangeAttribute(): string
    {
        $start = $this->starts_on->locale('fr');
        $end = $this->ends_on->locale('fr');

        if ($start->isSameDay($end)) {
            return 'Le ' . $start->isoFormat('dddd D MMMM YYYY');
        }
        if ($start->isSameMonth($end)) {
            return 'Du ' . $start->isoFormat('D') . ' au ' . $end->isoFormat('D MMMM YYYY');
        }
        if ($start->isSameYear($end)) {
            return 'Du ' . $start->isoFormat('D MMMM') . ' au ' . $end->isoFormat('D MMMM YYYY');
        }

        return 'Du ' . $start->isoFormat('D MMMM YYYY') . ' au ' . $end->isoFormat('D MMMM YYYY');
    }

    /** Nombre de jours (1 pour une compétition d'une journée). */
    public function getDurationDaysAttribute(): int
    {
        return (int) abs($this->starts_on->diffInDays($this->ends_on)) + 1;
    }

    /** « Bassin 50 m — 8 couloirs » */
    public function getPoolLabelAttribute(): ?string
    {
        $parts = array_filter([
            $this->pool_length ? "Bassin {$this->pool_length} m" : null,
            $this->lanes ? "{$this->lanes} couloirs" : null,
        ]);

        return $parts ? implode(' — ', $parts) : null;
    }

    /**
     * Présentation prête à afficher : HTML enrichi (éditeur) nettoyé,
     * ou texte simple d'avant l'éditeur converti (retours à la ligne).
     */
    public function getDescriptionHtmlAttribute(): ?string
    {
        if (blank($this->description)) {
            return null;
        }
        if ($this->description === strip_tags($this->description)) {
            return nl2br(e($this->description));
        }

        return \App\Support\HtmlSanitizer::clean($this->description);
    }

    /** Résumé en texte brut (cartes, balise meta description) */
    public function getExcerptAttribute(): ?string
    {
        $texte = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags(str_replace('<', ' <', (string) $this->description)), ENT_QUOTES | ENT_HTML5, 'UTF-8')));

        return $texte !== '' ? Str::limit($texte, 160) : null;
    }

    /** URL absolue de l'image de couverture (partage sur les réseaux sociaux) */
    public function getImageUrlAttribute(): ?string
    {
        if (blank($this->image)) {
            return null;
        }

        return preg_match('#^https?://#i', $this->image) ? $this->image : url($this->image);
    }

    public function hasLogistics(): bool
    {
        return filled($this->meeting_point) || $this->departure_at || $this->return_at || filled($this->transport)
            || filled($this->accommodation) || filled($this->meals) || filled($this->logistics_notes);
    }

    /** Les familles peuvent-elles encore répondre aux convocations ? */
    public function isOpenForResponses(): bool
    {
        if (in_array($this->state, ['annulee', 'reportee', 'en-cours', 'terminee'], true)) {
            return false;
        }

        return ! $this->response_deadline || today()->lte($this->response_deadline);
    }

    private function uniqueSlug(): string
    {
        $base = Str::slug($this->title . ' ' . optional($this->starts_on)->format('Y')) ?: 'competition';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($this->exists, fn ($q) => $q->whereKeyNot($this->id))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
