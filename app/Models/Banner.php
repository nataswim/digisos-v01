<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Banner extends Model
{
    use HasFactory, SoftDeletes;

    // =========================================================
    //  Configuration
    // =========================================================

    protected $fillable = [
        'name',
        'slug',
        'height',
        'autoplay',
        'autoplay_delay',
        'show_indicators',
        'show_controls',
        'pause_on_hover',
        'transition',
        'is_active',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'height'          => 'integer',
        'autoplay'        => 'boolean',
        'autoplay_delay'  => 'integer',
        'show_indicators' => 'boolean',
        'show_controls'   => 'boolean',
        'pause_on_hover'  => 'boolean',
        'is_active'       => 'boolean',
    ];

    // =========================================================
    //  Relations
    // =========================================================

    /**
     * 🇬🇧 All slides belonging to this banner (ordered)
     * 🇫🇷 Tous les slides de cette bannière (ordonnés)
     */
    public function slides(): HasMany
    {
        return $this->hasMany(BannerSlide::class)
                    ->orderBy('sort_order');
    }

    /**
     * 🇬🇧 Active slides only
     * 🇫🇷 Slides actifs uniquement
     */
    public function activeSlides(): HasMany
    {
        return $this->hasMany(BannerSlide::class)
                    ->where('is_active', true)
                    ->orderBy('sort_order');
    }

    // =========================================================
    //  Scopes
    // =========================================================

    /**
     * 🇬🇧 Filter active banners
     * 🇫🇷 Filtrer les bannières actives
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // =========================================================
    //  Static helpers
    // =========================================================

    /**
     * 🇬🇧 Retrieve an active banner by slug with its active slides (cached 10 min)
     * 🇫🇷 Récupérer une bannière active par slug avec ses slides actifs (cache 10 min)
     */
    public static function findBySlug(string $slug): ?self
    {
        return cache()->remember(
            "banner:{$slug}",
            now()->addMinutes(10),
            fn () => static::active()
                           ->with('activeSlides')
                           ->where('slug', $slug)
                           ->first()
        );
    }

    /**
     * 🇬🇧 Flush the cache for this banner
     * 🇫🇷 Vider le cache de cette bannière
     */
    public function clearCache(): void
    {
        cache()->forget("banner:{$this->slug}");
    }

    // =========================================================
    //  Model events
    // =========================================================

    protected static function booted(): void
    {
        // 🇬🇧 Clear cache whenever the banner is saved or deleted
        // 🇫🇷 Vider le cache à chaque sauvegarde ou suppression
        static::saved(fn (self $model) => $model->clearCache());
        static::deleted(fn (self $model) => $model->clearCache());
    }
}
