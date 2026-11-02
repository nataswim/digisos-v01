<?php

namespace App\Models;

use App\Models\Concerns\LogsMemberChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Règlement d'une adhésion (chèque, virement, coupons sport…). */
class MembershipPayment extends Model
{
    use LogsMemberChanges;

    protected $fillable = ['membership_id', 'method', 'amount_cents', 'cashed_on', 'is_cashed', 'reference'];

    protected $casts = ['amount_cents' => 'integer', 'cashed_on' => 'date', 'is_cashed' => 'boolean'];

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    public function getMethodLabelAttribute(): string
    {
        return Membership::PAYMENT_METHODS[$this->method] ?? $this->method;
    }

    protected function memberIdForLog(): ?int
    {
        return $this->membership?->member_id;
    }

    protected function logSubject(): string
    {
        return 'Règlement ' . ($this->membership?->season ?? '') . ' : ' . $this->method_label . ' ' . Membership::money($this->amount_cents);
    }
}
