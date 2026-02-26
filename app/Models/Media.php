<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_name',
        'original_name',
        'mime_type',
        'path',
        'size',
        'metadata',
        'alt_text',
        'description',
        'media_category_id',
        'uploaded_by',
        'used_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'size' => 'integer',
        'used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'url',
        'formatted_size',
        'is_image'
    ];

    public function category()
    {
        return $this->belongsTo(MediaCategory::class, 'media_category_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute()
    {
        if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
            return $this->path;
        }
        
        return asset('storage/' . $this->path);
    }

    public function getFullUrlAttribute()
    {
        return $this->url;
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsImageAttribute()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getDimensionsAttribute()
    {
        if ($this->is_image && isset($this->metadata['width'], $this->metadata['height'])) {
            return $this->metadata['width'] . ' × ' . $this->metadata['height'];
        }
        
        return null;
    }

    public function markAsUsed()
    {
        $this->update(['used_at' => now()]);
    }

    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('media_category_id', $categoryId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($media) {
            if (auth()->check()) {
                $media->uploaded_by = auth()->id();
            }
        });

        static::deleting(function ($media) {
            if (Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }
        });
    }
}