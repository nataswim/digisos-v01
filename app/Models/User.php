<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;      // <-- AJOUT
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'username', 'first_name', 'last_name',
        'role_id', 'avatar', 'bio', 'phone', 'date_of_birth', 'status',
        'last_login_at', 'last_login_ip', 'login_count', 'preferences',
        'locale', 'timezone',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'date_of_birth'     => 'date',
        'last_login_at'     => 'datetime',
        'preferences'       => 'array',
    ];

    protected $attributes = [
        'status'      => 'active',
        'locale'      => 'fr',
        'timezone'    => 'Europe/Paris',
        'login_count' => 0,
    ];

    // -------------------------------------------------------------------------
    // Relations existantes — inchangées
    // -------------------------------------------------------------------------

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'created_by');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'created_by');
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class, 'created_by');
    }

    
    // -------------------------------------------------------------------------
    // AJOUT — Relation fiche utilisateur enrichie (One-to-One)
    // -------------------------------------------------------------------------

    /**
     * Fiche de profil enrichi de l'utilisateur.
     * Créée automatiquement par l'admin depuis UserProfileController.
     */
    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    // -------------------------------------------------------------------------
    // Méthodes métier existantes — inchangées
    // -------------------------------------------------------------------------

    public function hasRole(string $roleSlug): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->slug === $roleSlug;
    }
}