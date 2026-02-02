<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * 🇬🇧 Fiche model representing a professional sheet in the system
 * 🇫🇷 Modèle Fiche représentant une fiche professionnelle dans le système
 * 
 * @file app/Models/Fiche.php
 */
class Fiche extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'long_description',
        'image',
        'visibility',
        'is_published',
        'is_featured',
        'views_count',
        'sort_order',
        'fiches_category_id',
        'fiches_sous_category_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'meta_og_image',
        'meta_og_url',
        'created_by',
        'created_by_name',
        'updated_by',
        'deleted_by',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 🇬🇧 Boot the model
     * 🇫🇷 Démarrer le modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($fiche) {
            if (auth()->check()) {
                $fiche->created_by = auth()->id();
                $fiche->created_by_name = auth()->user()->name;
            }

            // 🇬🇧 Auto-set parent category if sous-category selected / 🇫🇷 Définir automatiquement la catégorie parente si sous-catégorie sélectionnée
            if ($fiche->fiches_sous_category_id && !$fiche->fiches_category_id) {
                $sousCategory = FichesSousCategory::find($fiche->fiches_sous_category_id);
                if ($sousCategory) {
                    $fiche->fiches_category_id = $sousCategory->fiches_category_id;
                }
            }

            if (empty($fiche->slug)) {
                $fiche->slug = Str::slug($fiche->title);
            }

            if ($fiche->is_published && !$fiche->published_at) {
                $fiche->published_at = now();
            }
        });

        static::updating(function ($fiche) {
            if (auth()->check()) {
                $fiche->updated_by = auth()->id();
            }

            // 🇬🇧 Auto-update parent category if sous-category changed / 🇫🇷 Mettre à jour automatiquement la catégorie parente si sous-catégorie modifiée
            if ($fiche->isDirty('fiches_sous_category_id')) {
                if ($fiche->fiches_sous_category_id) {
                    $sousCategory = FichesSousCategory::find($fiche->fiches_sous_category_id);
                    if ($sousCategory) {
                        $fiche->fiches_category_id = $sousCategory->fiches_category_id;
                    }
                }
            }

            if ($fiche->isDirty('is_published') && $fiche->is_published && !$fiche->published_at) {
                $fiche->published_at = now();
            }
        });
    }

    /**
     * 🇬🇧 Scope for fiches by sub-category
     * 🇫🇷 Scope pour les fiches par sous-catégorie
     */
    public function scopeBySousCategory($query, $sousCategoryId)
    {
        return $query->where('fiches_sous_category_id', $sousCategoryId);
    }
    /**
     * 🇬🇧 Get the category of this fiche
     * 🇫🇷 Obtenir la catégorie de cette fiche
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FichesCategory::class, 'fiches_category_id');
    }

    /**
     * 🇬🇧 Get the sub-category of this fiche
     * 🇫🇷 Obtenir la sous-catégorie de cette fiche
     */
    public function sousCategory(): BelongsTo
    {
        return $this->belongsTo(FichesSousCategory::class, 'fiches_sous_category_id');
    }

    /**
     * 🇬🇧 Get the creator of this fiche
     * 🇫🇷 Obtenir le créateur de cette fiche
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🇬🇧 Get the updater of this fiche
     * 🇫🇷 Obtenir le modificateur de cette fiche
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🇬🇧 Scope for published fiches
     * 🇫🇷 Scope pour les fiches publiées
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * 🇬🇧 Scope for featured fiches
     * 🇫🇷 Scope pour les fiches mises en avant
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * 🇬🇧 Scope for ordered fiches
     * 🇫🇷 Scope pour les fiches ordonnées
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc');
    }

    /**
     * 🇬🇧 Scope for fiches by category
     * 🇫🇷 Scope pour les fiches par catégorie
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('fiches_category_id', $categoryId);
    }

    /**
     * 🇬🇧 Scope for visible fiches according to user
     * 🇫🇷 Scope pour les fiches visibles selon l'utilisateur
     * 
     * Affiche toutes les fiches published (public + authenticated) dans les listings
     * Le contrôle d'accès au contenu se fait via canViewContent()
     */
    public function scopeVisibleTo($query, $user = null)
    {
        return $query->where(function ($q) use ($user) {
            // 🇬🇧 Public and authenticated published fiches (visible in listings)
            // 🇫🇷 Fiches publiques et authentifiées publiées (visibles dans les listings)
            $q->where('is_published', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->whereIn('visibility', ['public', 'authenticated']);

            // 🇬🇧 If admin/editor, see all fiches (even unpublished)
            // 🇫🇷 Si admin/éditeur, voir toutes les fiches (même non publiées)
            if ($user && ($user->hasRole('admin') || $user->hasRole('editor'))) {
                $q->orWhere(function ($subQ) {
                    $subQ->whereIn('is_published', [false, true]);
                });
            }
        });
    }

    /**
     * 🇬🇧 Check if user can view content
     * 🇫🇷 Vérifier si l'utilisateur peut voir le contenu
     */
    public function canViewContent($user = null): bool
    {
        // 🇬🇧 Admins/editors always see content / 🇫🇷 Admins/éditeurs voient toujours le contenu
        if ($user && ($user->hasRole('admin') || $user->hasRole('editor'))) {
            return true;
        }

        // 🇬🇧 If not published, only admins can see / 🇫🇷 Si non publié, seuls les admins peuvent voir
        if (!$this->is_published) {
            return false;
        }

        // 🇬🇧 Check visibility / 🇫🇷 Vérifier la visibilité
        if ($this->visibility === 'public') {
            return true;
        }

        if ($this->visibility === 'authenticated') {
            return $user !== null && !$user->hasRole('visitor');
        }

        return false;
    }



    /**
     * 🇬🇧 Get access message for restricted content
     * 🇫🇷 Obtenir le message d'accès pour le contenu restreint
     */
    public function getAccessMessage($user = null): string
    {
        if ($this->visibility === 'public') {
            return 'Ce contenu est accessible à tous.';
        }

        if ($this->visibility === 'authenticated') {
            if (!$user) {
                return 'Connectez-vous pour accéder à l\'intégralité de cette fiche.';
            }

            if ($user->hasRole('visitor')) {
                return 'Votre compte ne permet pas l\'accès à ce contenu. Passez en Premium.';
            }

            return 'Ce contenu est réservé aux membres authentifiés.';
        }

        return 'Accès au contenu non autorisé.';
    }






    /**
     * 🇬🇧 Get the full URL of this fiche
     * 🇫🇷 Obtenir l'URL complète de cette fiche
     */
    public function getUrlAttribute(): string
    {
        if ($this->category && $this->sousCategory) {
            return route('public.fiches.show', [
                'category' => $this->category->slug,
                'sousCategory' => $this->sousCategory->slug,
                'fiche' => $this->slug
            ]);
        }
        return '#';
    }

    /**
     * 🇬🇧 Get excerpt from content
     * 🇫🇷 Obtenir un extrait du contenu
     */
    public function getExcerptAttribute(): string
    {
        if ($this->short_description) {
            return strip_tags($this->short_description);
        }

        return Str::limit(strip_tags($this->long_description), 160);
    }

    /**
     * 🇬🇧 Increment views count
     * 🇫🇷 Incrémenter le compteur de vues
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * 🇬🇧 Get the route key name for model binding
     * 🇫🇷 Obtenir le nom de la clé de route pour la liaison du modèle
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}