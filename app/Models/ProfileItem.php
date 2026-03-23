<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileItem extends Model
{
    protected $fillable = [
        'user_profile_id',
        'title',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    /**
     * La fiche utilisateur à laquelle appartient cet élément.
     */
    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Retourne les items dans l'ordre d'affichage défini par l'admin.
     */
    public function scopeOrdered(\Illuminate\Database\Eloquent\Builder $query): void
    {
        $query->orderBy('sort_order');
    }
}
