<?php

namespace App\Models;

use App\Models\Concerns\HasMessageAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Envoi groupé : un même message envoyé à plusieurs personnes, une conversation privée chacune. */
class MessageAnnouncement extends Model
{
    use HasMessageAttachment;

    protected $fillable = ['sender_id', 'subject', 'body', 'attachment_path', 'attachment_name', 'attachment_mime', 'attachment_size', 'targets', 'recipients_count'];

    protected $casts = ['targets' => 'array', 'recipients_count' => 'integer', 'attachment_size' => 'integer'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'announcement_id');
    }
}
