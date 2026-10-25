<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Séance d'entraînement : natation (blocs et séries), PPG ou séance à la maison (exercices).
 *
 * content (JSON) :
 *   natation       : ['blocks' => [[kind, title, repeat, note, lines => [[reps, distance, stroke, zone, departure, rest, equipment, note], …]], …]]
 *   ppg / maison   : ['items'  => [[exercise_id, sets, reps, duration, rest_sets, rest_after, load, note], …]]   (durées en secondes)
 *   ecole / divers : ['steps'  => [[title, description, image, equipment, organisation], …]]   (equipment et organisation : école de natation)
 *
 * Visibilité : séance publiée + nageur coché par le coach (table training_session_swimmer).
 * Les types « école de natation » et « divers » sont des fiches internes : jamais visibles des adhérents.
 */
class TrainingSession extends Model
{
    /** [libellé, icône, couleur] */
    public const TYPES = [
        'natation' => ['Natation', 'fa-person-swimming', '#0090D4'],
        'ppg' => ['Préparation physique (PPG)', 'fa-dumbbell', '#D49F45'],
        'maison' => ['Séance à la maison', 'fa-house', '#059669'],
        'ecole' => ['École de natation', 'fa-children', '#7c3aed'],
        'divers' => ['Divers', 'fa-shapes', '#64748b'],
    ];

    /** Types réservés aux coachs : ni nageurs ciblés, ni publication. */
    public const INTERNAL_TYPES = ['ecole', 'divers'];

    public const ZONES = [
        'Z1' => 'Z1 · Récupération',
        'Z2' => 'Z2 · Endurance fondamentale',
        'Z3' => 'Z3 · Endurance active',
        'Z4' => 'Z4 · Seuil',
        'Z5' => 'Z5 · Vitesse / maximal',
    ];

    public const STATUSES = ['draft' => 'Brouillon', 'published' => 'Publiée'];

    /** Durée d'une répétition (en secondes) pour estimer la durée d'un exercice compté en répétitions. */
    public const SECONDS_PER_REP = 3;

    protected $fillable = [
        'type', 'title', 'objective', 'session_date', 'starts_at', 'ends_at', 'planning_slot_id', 'location',
        'equipment', 'instructions', 'content', 'rounds', 'total_distance', 'total_duration', 'status', 'published_at',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'session_date' => 'date',
        'published_at' => 'datetime',
        'equipment' => 'array',
        'content' => 'array',
        'rounds' => 'integer',
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(PlanningGroup::class, 'training_session_group');
    }

    public function swimmers(): BelongsToMany
    {
        return $this->belongsToMany(PlanningSwimmer::class, 'training_session_swimmer');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(PlanningSlot::class, 'planning_slot_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /** Séances publiées visibles par les nageurs rattachés à un compte. */
    public function scopeVisibleToAccount(Builder $query, User $user): Builder
    {
        return $query->published()->whereNotIn('type', self::INTERNAL_TYPES)->whereHas('swimmers', fn ($q) => $q->where('planning_swimmers.user_id', $user->id));
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isSwim(): bool
    {
        return $this->type === 'natation';
    }

    /** École de natation, divers : fiche interne aux coachs. */
    public function isInternal(): bool
    {
        return in_array($this->type, self::INTERNAL_TYPES, true);
    }

    /** Séances à étapes libres (école de natation, divers). */
    public function hasSteps(): bool
    {
        return $this->isInternal();
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type][0] ?? $this->type;
    }

    public function getTypeIconAttribute(): string
    {
        return self::TYPES[$this->type][1] ?? 'fa-dumbbell';
    }

    public function getTypeColorAttribute(): string
    {
        return self::TYPES[$this->type][2] ?? '#0090D4';
    }

    public function getTimeRangeAttribute(): string
    {
        if (! $this->starts_at) {
            return '';
        }

        return PlanningSlot::formatTime($this->starts_at) . ($this->ends_at ? ' – ' . PlanningSlot::formatTime($this->ends_at) : '');
    }

    /** « 2 400 m » ou « ≈ 35 min » */
    public function getVolumeLabelAttribute(): ?string
    {
        if ($this->isSwim() && $this->total_distance) {
            return number_format($this->total_distance, 0, ',', ' ') . ' m';
        }

        return $this->total_duration ? ($this->isSwim() || $this->hasSteps() ? '' : '≈ ') . $this->total_duration . ' min' : null;
    }

    public function blocks(): array
    {
        return $this->content['blocks'] ?? [];
    }

    public function items(): array
    {
        return $this->content['items'] ?? [];
    }

    public function steps(): array
    {
        return $this->content['steps'] ?? [];
    }

    /** Exercices de la bibliothèque utilisés, indexés par id. */
    public function exercises(): Collection
    {
        $ids = collect($this->items())->pluck('exercise_id')->filter()->unique();

        return $ids->isEmpty() ? collect() : TrainingExercise::whereIn('id', $ids)->get()->keyBy('id');
    }

    // ------------------------------------------------------------------
    //  Calculs
    // ------------------------------------------------------------------

    public static function lineDistance(array $line): int
    {
        return max(1, (int) ($line['reps'] ?? 1)) * max(0, (int) ($line['distance'] ?? 0));
    }

    public static function blockDistance(array $block): int
    {
        $simple = array_sum(array_map([self::class, 'lineDistance'], $block['lines'] ?? []));

        return $simple * max(1, (int) ($block['repeat'] ?? 1));
    }

    /** Durée estimée d'un exercice en secondes (séries × effort + récupérations). */
    public static function itemSeconds(array $item): int
    {
        $sets = max(1, (int) ($item['sets'] ?? 1));
        $effort = ! empty($item['duration']) ? (int) $item['duration'] : max(1, (int) ($item['reps'] ?? 1)) * self::SECONDS_PER_REP;

        return $sets * $effort + ($sets - 1) * (int) ($item['rest_sets'] ?? 0) + (int) ($item['rest_after'] ?? 0);
    }

    /** Recalcule distance et durée à partir du contenu. */
    public function computeTotals(): void
    {
        if ($this->isSwim() || $this->hasSteps()) {
            $this->total_distance = null;
            $this->total_duration = $this->starts_at && $this->ends_at
                ? max(0, (int) round((strtotime($this->ends_at) - strtotime($this->starts_at)) / 60)) ?: null
                : null;
        }
        if ($this->hasSteps()) {
            return;
        }

        if ($this->isSwim()) {
            $this->total_distance = array_sum(array_map([self::class, 'blockDistance'], $this->blocks())) ?: null;
            $this->total_duration = $this->starts_at && $this->ends_at
                ? max(0, (int) round((strtotime($this->ends_at) - strtotime($this->starts_at)) / 60)) ?: null
                : null;

            return;
        }

        $this->total_distance = null;
        $secondes = array_sum(array_map([self::class, 'itemSeconds'], $this->items())) * max(1, (int) $this->rounds);
        $this->total_duration = $secondes ? (int) ceil($secondes / 60) : null;
    }

    // ------------------------------------------------------------------
    //  Mise en forme
    // ------------------------------------------------------------------

    /** 75 → « 1'15 », 30 → « 30 s » */
    public static function formatSeconds(?int $seconds): ?string
    {
        if (! $seconds) {
            return null;
        }
        if ($seconds < 60) {
            return $seconds . ' s';
        }
        $m = intdiv($seconds, 60);
        $s = $seconds % 60;

        return $s ? sprintf("%d'%02d", $m, $s) : $m . ' min';
    }

    /** « 4 × 100 m Crawl » */
    public static function lineLabel(array $line): string
    {
        $reps = (int) ($line['reps'] ?? 1);
        $texte = ($reps > 1 ? $reps . ' × ' : '') . number_format((int) ($line['distance'] ?? 0), 0, ',', ' ') . ' m';

        return trim($texte . ' ' . ($line['stroke'] ?? ''));
    }

    /** « 3 × 15 répétitions » ou « 3 × 30 s » */
    public static function effortLabel(array $item): string
    {
        $sets = max(1, (int) ($item['sets'] ?? 1));
        $effort = ! empty($item['duration'])
            ? self::formatSeconds((int) $item['duration'])
            : ((int) ($item['reps'] ?? 0)) . ' répétition' . (((int) ($item['reps'] ?? 0)) > 1 ? 's' : '');

        return ($sets > 1 ? $sets . ' séries × ' : '1 série × ') . $effort;
    }
}
