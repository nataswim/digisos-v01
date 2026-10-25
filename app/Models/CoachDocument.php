<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Document réservé aux coachs : fichier stocké hors du dossier public (disque « local »,
 * dossier coach-documents) et téléchargé via l'espace coachs, ou simple lien externe.
 */
class CoachDocument extends Model
{
    public const DISK = 'local';
    public const DIRECTORY = 'coach-documents';

    public const CATEGORIES = [
        'entrainement' => 'Séances et programmes',
        'technique' => 'Technique',
        'reglementation' => 'Règlements et officiels',
        'formation' => 'Formation',
        'organisation' => 'Organisation du club',
        'autre' => 'Autre',
    ];

    /** Extensions acceptées à l'envoi. */
    public const EXTENSIONS = ['pdf', 'doc', 'docx', 'odt', 'xls', 'xlsx', 'ods', 'csv', 'ppt', 'pptx', 'odp', 'txt', 'jpg', 'jpeg', 'png', 'webp'];

    public const MAX_KB = 20480;

    protected $fillable = ['title', 'category', 'description', 'file_path', 'original_name', 'mime_type', 'size', 'url', 'uploaded_by'];

    protected static function booted(): void
    {
        // Le fichier est supprimé avec sa fiche
        static::deleting(function (CoachDocument $document) {
            $document->deleteFile();
        });
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? 'Autre';
    }

    public function hasFile(): bool
    {
        return filled($this->file_path);
    }

    public function getExtensionAttribute(): ?string
    {
        return $this->original_name ? strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION)) : null;
    }

    /** Icône Font Awesome selon le type de fichier. */
    public function getIconAttribute(): string
    {
        if (! $this->hasFile()) {
            return 'fa-link';
        }

        return match ($this->extension) {
            'pdf' => 'fa-file-pdf',
            'doc', 'docx', 'odt', 'txt' => 'fa-file-word',
            'xls', 'xlsx', 'ods', 'csv' => 'fa-file-excel',
            'ppt', 'pptx', 'odp' => 'fa-file-powerpoint',
            'jpg', 'jpeg', 'png', 'webp' => 'fa-file-image',
            default => 'fa-file',
        };
    }

    /** Taille lisible : « 1,2 Mo ». */
    public function getReadableSizeAttribute(): ?string
    {
        if (! $this->size) {
            return null;
        }
        if ($this->size < 1024 * 1024) {
            return number_format($this->size / 1024, 0, ',', ' ') . ' Ko';
        }

        return number_format($this->size / 1024 / 1024, 1, ',', ' ') . ' Mo';
    }

    public function deleteFile(): void
    {
        if ($this->hasFile()) {
            Storage::disk(self::DISK)->delete($this->file_path);
        }
    }
}
