<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Catégorie d'adhésion et tarif par défaut (modifiable dans Adhérents → Catégories et tarifs). */
class MemberCategory extends Model
{
    protected $fillable = ['code', 'label', 'price_cents', 'sort_order', 'is_active'];

    protected $casts = ['price_cents' => 'integer', 'is_active' => 'boolean'];

    public function memberships(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('label');
    }

    public function getPriceLabelAttribute(): string
    {
        return Membership::money($this->price_cents);
    }
}
