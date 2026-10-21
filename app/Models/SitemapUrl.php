<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class SitemapUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'type',
        'source',
        'source_id',
        'priority',
        'changefreq',
        'is_approved',
        'is_active',
        'last_modified'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
        'last_modified' => 'datetime',
        'priority' => 'decimal:1'
    ];

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForSitemap($query)
    {
        return $query->approved()->active();
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getLastModifiedFormattedAttribute()
    {
        if (!$this->last_modified) {
            return $this->updated_at->toISOString();
        }
        return $this->last_modified->toISOString();
    }

    public function getSourceLabelAttribute()
    {
        return \App\Services\SitemapService::SOURCES[$this->source] ?? ($this->type === 'manual' ? 'Ajoutée à la main' : ucfirst($this->source ?? '—'));
    }

    public function getTypeBadgeAttribute()
    {
        return match($this->type) {
            'static' => 'primary',
            'dynamic' => 'info',
            'manual' => 'success',
            default => 'secondary'
        };
    }

    public function getChangefreqBadgeAttribute()
    {
        return match($this->changefreq) {
            'always', 'hourly', 'daily' => 'danger',
            'weekly' => 'warning',
            'monthly' => 'primary',
            'yearly', 'never' => 'secondary',
            default => 'light'
        };
    }
}