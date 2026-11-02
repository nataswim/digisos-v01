<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Ligne du journal des modifications du module Adhérents. */
class MemberLog extends Model
{
    public const UPDATED_AT = null;

    public const ACTIONS = ['created' => 'Création', 'updated' => 'Modification', 'deleted' => 'Suppression', 'imported' => 'Import'];

    protected $fillable = ['member_id', 'user_id', 'action', 'subject', 'changes', 'created_at'];

    protected $casts = ['changes' => 'array', 'created_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
