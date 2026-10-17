<?php

namespace App\Models;

use App\Support\RichText;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Produit de la boutique vitrine (module Boutique).
 * Pas de vente sur le site : le bouton renvoie vers order_url (site externe).
 */
class ShopProduct extends Model
{
    /** Badges facultatifs : libellé et couleurs [fond, texte]. */
    public const BADGES = [
        'nouveau'     => ['label' => 'Nouveau',     'bg' => '#198754', 'text' => '#ffffff'],
        'precommande' => ['label' => 'Précommande', 'bg' => '#2f80b8', 'text' => '#ffffff'],
        'epuise'      => ['label' => 'Épuisé',      'bg' => '#6c757d', 'text' => '#ffffff'],
    ];

    protected $fillable = [
        'shop_category_id', 'name', 'slug', 'summary', 'description',
        'price_min', 'price_max', 'price_note', 'image', 'gallery',
        'order_url', 'order_label', 'badge', 'is_featured', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'price_min' => 'decimal:2',
        'price_max' => 'decimal:2',
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (ShopProduct $product) {
            if (blank($product->slug) || $product->isDirty('name')) {
                $base = Str::slug($product->name) ?: 'produit';
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->whereKeyNot($product->id)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $product->slug = $slug;
            }

            // Description : HTML de l'éditeur nettoyé à l'enregistrement
            if ($product->isDirty('description')) {
                $product->description = RichText::isEmpty($product->description)
                    ? null
                    : RichText::clean($product->description);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ShopCategory::class, 'shop_category_id');
    }

    // ---------------------------------------------------------------- Requêtes

    /** Produits affichés, dans une catégorie affichée. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true));
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('is_featured')->orderBy('sort_order')->orderBy('name');
    }

    // ---------------------------------------------------------------- Affichage

    /** « 12 € », « 10 à 15 € », « 12,50 € » ; null si aucun prix. */
    public function getPriceLabelAttribute(): ?string
    {
        $min = $this->price_min !== null ? (float) $this->price_min : null;
        $max = $this->price_max !== null ? (float) $this->price_max : null;

        if ($min === null && $max === null) {
            return null;
        }

        if ($min !== null && $max !== null && $max > $min) {
            return self::euros($min, false) . ' à ' . self::euros($max);
        }

        return self::euros($min ?? $max);
    }

    /** Formatage français : 12 → « 12 € », 12.5 → « 12,50 € ». */
    public static function euros(float $amount, bool $withSymbol = true): string
    {
        $text = floor($amount) == $amount
            ? number_format($amount, 0, ',', "\u{202F}")
            : number_format($amount, 2, ',', "\u{202F}");

        return $withSymbol ? $text . "\u{00A0}€" : $text;
    }

    public function getBadgeInfoAttribute(): ?array
    {
        return self::BADGES[$this->badge] ?? null;
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->badge === 'epuise';
    }

    public function getOrderButtonLabelAttribute(): string
    {
        return $this->order_label ?: 'Commander';
    }

    /** Domaine du lien de commande, pour informer le visiteur (« sur helloasso.com »). */
    public function getOrderHostAttribute(): ?string
    {
        $host = $this->order_url ? parse_url($this->order_url, PHP_URL_HOST) : null;

        return $host ? preg_replace('/^www\./i', '', $host) : null;
    }

    /** Toutes les images : principale puis galerie, sans doublon ni vide. */
    public function getImagesAttribute(): array
    {
        return collect([$this->image])
            ->merge($this->gallery ?? [])
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function getDescriptionHtmlAttribute(): string
    {
        return RichText::clean($this->description);
    }
}
