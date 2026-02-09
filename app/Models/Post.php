<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'intro', 'content', 'type', 'category_id', 'category_name',
        'is_featured', 'image', 'meta_title', 'meta_keywords', 'meta_description',
        'meta_og_image', 'meta_og_url', 'hits', 'order', 'status', 'visibility',
        'moderated_by', 'moderated_at', 'created_by', 'created_by_name', 
        'created_by_alias', 'updated_by', 'deleted_by', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'moderated_at' => 'datetime',
        'is_featured' => 'boolean',
        'hits' => 'integer',
        'order' => 'integer',
    ];

    /**
     * 🔧 CORRECTION : Utiliser le slug pour le route model binding
     * Permet d'accéder aux posts via leur slug au lieu de l'ID
     * Ex: /posts/mon-article au lieu de /posts/1
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    /**
     * Les metadonnees (titre, intro, image) sont toujours visibles si le post est publie
     */
    public function isMetadataVisible(): bool
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at <= now();
    }

    /**
     * Verifier si le contenu complet est visible pour l'utilisateur actuel
     */
    public function isContentVisibleTo($user = null): bool
    {
        // Si le post n'est pas publie, seuls les admins/editeurs peuvent voir le contenu
        if (!$this->isMetadataVisible()) {
            return $user && ($user->hasRole('admin') || $user->hasRole('editor'));
        }
        
        // Si la visibilite est publique, tout le monde peut voir le contenu
        if ($this->visibility === 'public') {
            return true;
        }
        
        // Si la visibilite est "authenticated", verifier le rÃ´le de l'utilisateur
        if ($this->visibility === 'authenticated') {
            if (!$user) {
                return false; // Pas connecte = pas d'acces
            }
            
            // Les admins et editeurs peuvent tout voir
            if ($user->hasRole('admin') || $user->hasRole('editor')) {
                return true;
            }
            
            // Les visitors ne peuvent PAS voir le contenu premium
            if ($user->hasRole('visitor')) {
                return false; // ❌ VISITOR = PAS D'ACCÈS AU PREMIUM
            }
            
            // Les users et rôles superieurs peuvent voir
            return $user->hasRole('user') || ($user->role && $user->role->level >= 10);
        }
        
        return false;
    }

    /**
     * Determiner le message A afficher pour l'acces restreint
     */
    public function getAccessMessage($user = null): string
    {
        if (!$user) {
            return 'Connectez-vous pour acceder A ce contenu premium.';
        }
        
        if ($user->hasRole('visitor')) {
            return 'Votre compte doit être validé par un administrateur pour acceder aux contenus premium.';
        }
        
        return 'Acces non autorise A ce contenu.';
    }

    /**
     * Scope pour les posts avec metadonnees visibles
     */
    public function scopeWithMetadataVisible($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope pour les posts visibles selon le niveau d'utilisateur
     */
    public function scopeVisibleTo($query, $user = null)
    {
        return $query->where(function($q) use ($user) {
            // Posts publics et publies
            $q->where('status', 'published')
              ->whereNotNull('published_at')
              ->where('published_at', '<=', now())
              ->where('visibility', 'public');
            
            // Si utilisateur connecte et NON-visitor, ajouter les posts premium
            if ($user && !$user->hasRole('visitor')) {
                $q->orWhere(function($subQ) use ($user) {
                    $subQ->where('status', 'published')
                         ->whereNotNull('published_at')
                         ->where('published_at', '<=', now())
                         ->where('visibility', 'authenticated');
                });
            }
            
            // Si admin/editeur, voir tous les posts
            if ($user && ($user->hasRole('admin') || $user->hasRole('editor'))) {
                $q->orWhere(function($subQ) {
                    $subQ->whereIn('status', ['draft', 'published']);
                });
            }
        });
    }

    /**
     * Scope pour les posts publies uniquement
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope pour les posts mis en avant
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Accessor pour l'URL de l'article
     */
    public function getUrlAttribute()
    {
        return route('posts.public.show', $this->slug);
    }

    /**
     * Accessor pour le resume du contenu
     */
    public function getExcerptAttribute()
    {
        if ($this->intro) {
            return $this->intro;
        }
        
        return \Str::limit(strip_tags($this->content), 160);
    }

    /**
     * Accessor pour le temps de lecture estime
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // 200 mots par minute en moyenne
        
        return $readingTime;
    }

    /**
     * Mutator pour generer automatiquement le slug
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = \Str::slug($value);
        }
    }

    /**
     * Boot method pour les evenements du modele
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (auth()->check()) {
                $post->created_by = auth()->id();
                $post->created_by_name = auth()->user()->name;
            }
        });

        static::updating(function ($post) {
            if (auth()->check()) {
                $post->updated_by = auth()->id();
            }
        });
    }
}