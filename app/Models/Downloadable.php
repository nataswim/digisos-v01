<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Downloadable extends Model
{
    use HasFactory, SoftDeletes;

    // Ajouter dans $fillable
protected $fillable = [
    'title', 'slug', 'format', 'short_description', 'long_description', 
    'file_path', 'ebook_file_id', // <- AJOUTER
    'file_size', 'cover_image', 'download_category_id', 'user_permission', 'download_count',
    'order', 'status', 'is_featured', 'meta_title', 'meta_description', 'meta_keywords',
    'created_by', 'created_by_name', 'updated_by'
];

/**
 * Relation avec EbookFile
 */
public function ebookFile()
{
    return $this->belongsTo(EbookFile::class, 'ebook_file_id');
}

/**
 * Obtenir le chemin physique du fichier
 */
public function getPhysicalPathAttribute()
{
    // Priorité au ebook_file_id
    if ($this->ebook_file_id && $this->ebookFile) {
        return $this->ebookFile->physical_path;
    }
    
    // Sinon file_path classique
    if ($this->file_path && Storage::disk('local')->exists($this->file_path)) {
        return Storage::disk('local')->path($this->file_path);
    }
    
    return null;
}

/**
 * Vérifier si le fichier existe
 */
public function hasFile()
{
    if ($this->ebook_file_id && $this->ebookFile) {
        return $this->ebookFile->fileExists();
    }
    
    if ($this->file_path) {
        return Storage::disk('local')->exists($this->file_path);
    }
    
    return false;
}

    protected $casts = [
        'download_count' => 'integer',
        'order' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(DownloadCategory::class, 'download_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function downloadLogs()
    {
        return $this->hasMany(DownloadLog::class);
    }

    /**
     * Vérifier si l'utilisateur peut télécharger ce fichier
     * Selon vos exigences : seuls user, editor, admin peuvent télécharger
     */
    public function canBeDownloadedBy($user = null): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        // Si pas connecté = NON
        if (!$user) {
            return false;
        }

        // Si pas de rôle = NON
        if (!$user->role) {
            return false;
        }

        // Si visitor = NON
        if ($user->hasRole('visitor')) {
            return false;
        }

        // Seuls user, editor, admin peuvent télécharger
        return $user->hasRole('user') || $user->hasRole('editor') || $user->hasRole('admin');
    }

    /**
     * Message d'accès unifié pour tous les cas de restriction
     */
    public function getAccessMessage($user = null): string
    {
        if ($this->canBeDownloadedBy($user)) {
            return '';
        }

        // Message unifié pour tous les cas de restriction
        return 'Veuillez vous inscrire ou vous identifier pour télécharger ce document.';
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * SCOPE MODIFIÉ : Afficher TOUS les téléchargements
     * La restriction se fait au niveau du bouton de téléchargement
     */
    public function scopeForPermission($query, $user = null)
    {
        // TOUS les téléchargements sont visibles
        // La restriction se fait dans les vues avec canBeDownloadedBy()
        return $query;
    }

    /**
     * Accessors
     */
    public function getUrlAttribute()
    {
        if (!$this->category) {
            return '#';
        }
        return route('ebook.show', [$this->category->slug, $this->slug]);
    }

    public function getDownloadUrlAttribute()
    {
        if (!$this->category) {
            return '#';
        }
        return route('ebook.download', [$this->category->slug, $this->slug]);
    }

    public function getFormatDisplayAttribute()
    {
        $formats = [
            'pdf' => 'PDF',
            'epub' => 'EPUB',
            'mp4' => 'Vidéo MP4',
            'zip' => 'Archive ZIP',
            'doc' => 'Word DOC',
            'docx' => 'Word DOCX'
        ];

        return $formats[$this->format] ?? strtoupper($this->format);
    }

    /**
     * Incrémenter le compteur de téléchargements
     */
    public function incrementDownloadCount($user = null, $request = null)
    {
        $this->increment('download_count');
        
        // Log du téléchargement
        DownloadLog::create([
            'downloadable_id' => $this->id,
            'user_id' => $user?->id,
            'ip_address' => $request?->ip() ?? request()->ip(),
            'user_agent' => $request?->userAgent() ?? request()->userAgent(),
            'referer' => $request?->header('referer') ?? request()->header('referer')
        ]);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($downloadable) {
            if (auth()->check()) {
                $downloadable->created_by = auth()->id();
                $downloadable->created_by_name = auth()->user()->name;
            }
        });

        static::updating(function ($downloadable) {
            if (auth()->check()) {
                $downloadable->updated_by = auth()->id();
            }
        });
    }
}