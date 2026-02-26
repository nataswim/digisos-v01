<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class BannerSlide extends Model
{
    use HasFactory;

    // =========================================================
    //  Configuration
    // =========================================================

    protected $fillable = [
        'banner_id',
        'image',
        'image_alt',
        'title',
        'subtitle',
        'body',
        'cta_label',
        'cta_url',
        'cta_target',
        'cta_style',
        'text_color',
        'text_position',
        'overlay_opacity',
        'sort_order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sort_order'      => 'integer',
        'overlay_opacity' => 'integer',
        'is_active'       => 'boolean',
    ];

    // =========================================================
    //  Accessors
    // =========================================================

    /**
     * 🇬🇧 Full public URL of the image
     * 🇫🇷 URL publique complète de l'image
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('assets/images/banner-placeholder.jpg');
        }

        // 🇬🇧 External URL — return as-is
        // 🇫🇷 URL externe — retourner telle quelle
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    /**
     * 🇬🇧 CSS opacity value (0.0 – 1.0) derived from overlay_opacity (0–100)
     * 🇫🇷 Valeur CSS d'opacité (0.0 – 1.0) calculée depuis overlay_opacity (0–100)
     */
    public function getOverlayCssOpacityAttribute(): float
    {
        return round($this->overlay_opacity / 100, 2);
    }

    /**
     * 🇬🇧 Bootstrap text-alignment class
     * 🇫🇷 Classe Bootstrap d'alignement du texte
     */
    public function getTextAlignClassAttribute(): string
    {
        return match ($this->text_position) {
            'left'  => 'text-start',
            'right' => 'text-end',
            default => 'text-center',
        };
    }

    /**
     * 🇬🇧 Check if the slide has a call-to-action
     * 🇫🇷 Vérifier si le slide a un bouton d'action
     */
    public function getHasCtaAttribute(): bool
    {
        return ! empty($this->cta_label) && ! empty($this->cta_url);
    }

    // =========================================================
    //  Relations
    // =========================================================

    /**
     * 🇬🇧 Parent banner
     * 🇫🇷 Bannière parente
     */
    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class);
    }

    // =========================================================
    //  Scopes
    // =========================================================

    /**
     * 🇬🇧 Filter active slides
     * 🇫🇷 Filtrer les slides actifs
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
