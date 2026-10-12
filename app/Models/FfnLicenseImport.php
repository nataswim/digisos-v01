<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Historique d'un import du fichier de licences extraNat.
 */
class FfnLicenseImport extends Model
{
    protected $fillable = [
        'user_id', 'original_name', 'season', 'rows_total', 'created_count', 'updated_count', 'unchanged_count',
        'error_count', 'swimmers_linked', 'swimmers_updated', 'swimmers_created', 'accounts_linked', 'errors',
    ];

    protected $casts = [
        'errors' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(FfnLicense::class);
    }
}
