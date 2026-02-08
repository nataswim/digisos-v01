<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * 🇬🇧 Page model representing a static page in the system
 * 🇫🇷 Modèle Page représentant une page statique dans le système
 * 
 * @file app/Models/Page.php
 */
class Page extends Model
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
        'sort_order',
        'pages_category_id',
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
        'sort_order' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 🇬🇧 Boot the model
     * 🇫🇷 Démarrer le modèle
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($page) {
            if (auth()->check()) {
                $page->created_by = auth()->id();
                $page->created_by_name = auth()->user()->name;
            }

            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }

            if ($page->is_published && !$page->published_at) {
                $page->published_at = now();
            }
        });

        static::updating(function ($page) {
            if (auth()->check()) {
                $page->updated_by = auth()->id();
            }

            if ($page->isDirty('is_published') && $page->is_published && !$page->published_at) {
                $page->published_at = now();
            }
        });
    }

    /**
     * 🇬🇧 Get the category of this page
     * 🇫🇷 Obtenir la catégorie de cette page
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PagesCategory::class, 'pages_category_id');
    }

    /**
     * 🇬🇧 Get the creator of this page
     * 🇫🇷 Obtenir le créateur de cette page
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🇬🇧 Get the updater of this page
     * 🇫🇷 Obtenir le modificateur de cette page
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🇬🇧 Scope for published pages
     * 🇫🇷 Scope pour les pages publiées
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * 🇬🇧 Scope for ordered pages
     * 🇫🇷 Scope pour les pages ordonnées
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc');
    }

    /**
     * 🇬🇧 Scope for pages by category
     * 🇫🇷 Scope pour les pages par catégorie
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('pages_category_id', $categoryId);
    }

    /**
     * 🇬🇧 Scope for visible pages according to user
     * 🇫🇷 Scope pour les pages visibles selon l'utilisateur
     */
    public function scopeVisibleTo($query, $user = null)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('is_published', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->whereIn('visibility', ['public', 'authenticated']);

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
        if ($user && ($user->hasRole('admin') || $user->hasRole('editor'))) {
            return true;
        }

        if (!$this->is_published) {
            return false;
        }

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
                return 'Connectez-vous pour accéder à l\'intégralité de cette page.';
            }

            if ($user->hasRole('visitor')) {
                return 'Votre compte ne permet pas l\'accès à ce contenu. Passez en Premium.';
            }

            return 'Ce contenu est réservé aux membres authentifiés.';
        }

        return 'Accès au contenu non autorisé.';
    }

    /**
     * 🇬🇧 Get the full URL of this page
     * 🇫🇷 Obtenir l'URL complète de cette page
     */
    public function getUrlAttribute(): string
    {
        if ($this->category) {
            return route('public.pages.show', [
                'category' => $this->category->slug,
                'page' => $this->slug
            ]);
        }
        return route('public.pages.show', ['page' => $this->slug]);
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
     * 🇬🇧 Get the route key name for model binding
     * 🇫🇷 Obtenir le nom de la clé de route pour la liaison du modèle
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
