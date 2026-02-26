<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PhotoGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image_id',
        'visibility',
        'is_published',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * 🔧 Utiliser le slug pour le route model binding
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relation : Image de couverture
     */
    public function coverImage()
    {
        return $this->belongsTo(Media::class, 'cover_image_id');
    }

    /**
     * Relation : Photos de la galerie (many-to-many)
     */
    public function photos()
    {
        return $this->belongsToMany(Media::class, 'gallery_media', 'photo_gallery_id', 'media_id')
                    ->withPivot(['sort_order', 'caption'])
                    ->withTimestamps()
                    ->orderBy('gallery_media.sort_order');
    }

    /**
     * Accessor : URL de la galerie
     */
    public function getUrlAttribute()
    {
        return route('galleries.show', $this->slug);
    }

    /**
     * Accessor : Nombre de photos
     */
    public function getPhotosCountAttribute()
    {
        return $this->photos()->count();
    }

    /**
     * Vérifier si visible pour l'utilisateur actuel
     */
    public function isVisibleTo($user = null): bool
    {
        // Si non publié, personne ne peut voir
        if (!$this->is_published) {
            return false;
        }

        // Si visibilité publique, tout le monde peut voir
        if ($this->visibility === 'public') {
            return true;
        }

        // Si visibilité authentifiée, vérifier que l'utilisateur est connecté
        if ($this->visibility === 'authenticated') {
            return $user !== null;
        }

        return false;
    }

    /**
     * Message d'accès si restriction
     */
    public function getAccessMessage($user = null): string
    {
        if (!$user && $this->visibility === 'authenticated') {
            return 'Connectez-vous pour voir cette galerie.';
        }

        return 'Accès non autorisé à cette galerie.';
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVisibleTo($query, $user = null)
    {
        return $query->where(function($q) use ($user) {
            // Galeries publiques et publiées
            $q->where('is_published', true)
              ->whereNotNull('published_at')
              ->where('published_at', '<=', now())
              ->where('visibility', 'public');
            
            // Si utilisateur connecté, ajouter les galeries authentifiées
            if ($user) {
                $q->orWhere(function($subQ) {
                    $subQ->where('is_published', true)
                         ->whereNotNull('published_at')
                         ->where('published_at', '<=', now())
                         ->where('visibility', 'authenticated');
                });
            }
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('published_at', 'desc');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement le slug
        static::creating(function ($gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->title);
            }

            // Définir la date de publication si publiée
            if ($gallery->is_published && empty($gallery->published_at)) {
                $gallery->published_at = now();
            }
        });

        static::updating(function ($gallery) {
            // Définir la date de publication si passage en publié
            if ($gallery->is_published && empty($gallery->published_at)) {
                $gallery->published_at = now();
            }
        });
    }
}
