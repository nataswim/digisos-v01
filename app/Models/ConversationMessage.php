<?php

namespace App\Models;

use App\Models\Concerns\HasMessageAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationMessage extends Model
{
    use HasMessageAttachment;

    protected $fillable = ['conversation_id', 'author_id', 'body', 'attachment_path', 'attachment_name', 'attachment_mime', 'attachment_size'];

    protected $casts = ['attachment_size' => 'integer'];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
