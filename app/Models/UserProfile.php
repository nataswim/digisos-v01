<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'job_title',
        'address',
        'website',
        'admin_notes',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    /**
     * L'utilisateur propriétaire de cette fiche.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Les éléments de contenu rattachés à cette fiche,
     * triés par ordre d'affichage défini par l'admin.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProfileItem::class)
                    ->orderBy('sort_order');
    }

    // -------------------------------------------------------------------------
    // Accesseurs
    // -------------------------------------------------------------------------

    /**
     * Indique si la fiche possède au moins un élément de contenu.
     */
    public function hasItems(): bool
    {
        return $this->items()->exists();
    }
}
