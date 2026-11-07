<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Conversation privée entre un membre de l'équipe du club (staff : admin, éditeur, coach)
 * et UN utilisateur. Seuls ces deux participants la voient ; l'administrateur voit et supprime tout.
 */
class Conversation extends Model
{
    /** Rôles de l'équipe du club (accès à la messagerie de l'équipe). */
    public const STAFF_ROLES = ['admin', 'editor', 'coach'];

    /** Rôles qu'un adhérent peut choisir comme destinataire d'un nouveau message. */
    public const MEMBER_CAN_WRITE_TO = ['coach', 'admin'];

    protected $fillable = ['subject', 'staff_id', 'user_id', 'announcement_id', 'started_by', 'last_message_at', 'last_message_by', 'staff_read_at', 'user_read_at'];

    protected $casts = ['last_message_at' => 'datetime', 'staff_read_at' => 'datetime', 'user_read_at' => 'datetime'];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(MessageAnnouncement::class, 'announcement_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class)->orderBy('id');
    }

    public static function isStaff(?User $user): bool
    {
        return $user && $user->status === 'active' && in_array($user->role?->slug, self::STAFF_ROLES, true);
    }

    /** Conversations où la personne est l'un des deux participants. */
    public function scopeInvolving(Builder $query, User $user): Builder
    {
        return $query->where(fn ($q) => $q->where('staff_id', $user->id)->orWhere('user_id', $user->id));
    }

    /** Conversations non lues par la personne (dernier message écrit par l'autre participant). */
    public function scopeUnreadFor(Builder $query, User $user): Builder
    {
        $id = $user->id;

        return $query->whereNotNull('last_message_at')
            ->where(fn ($q) => $q->whereNull('last_message_by')->orWhere('last_message_by', '!=', $id))
            ->where(fn ($q) => $q
                ->where(fn ($q) => $q->where('staff_id', $id)->where(fn ($q) => $q->whereNull('staff_read_at')->orWhereColumn('staff_read_at', '<', 'last_message_at')))
                ->orWhere(fn ($q) => $q->where('user_id', $id)->where(fn ($q) => $q->whereNull('user_read_at')->orWhereColumn('user_read_at', '<', 'last_message_at'))));
    }

    public static function unreadCountFor(?User $user): int
    {
        return $user ? static::unreadFor($user)->count() : 0;
    }

    public function isParticipant(?User $user): bool
    {
        return $user && in_array($user->id, [$this->staff_id, $this->user_id], true);
    }

    /** Lecture : les deux participants, et l'administrateur. */
    public function canBeViewedBy(?User $user): bool
    {
        return $this->isParticipant($user) || ($user?->hasRole('admin') && $user->status === 'active');
    }

    /** L'autre participant, vu par $user (pour l'administrateur qui lit une conversation d'un autre : l'utilisateur). */
    public function otherParty(User $user): ?User
    {
        return $user->id === $this->user_id ? $this->staff : $this->user;
    }

    public function isUnreadFor(User $user): bool
    {
        if (! $this->last_message_at || $this->last_message_by === $user->id) {
            return false;
        }
        $lu = match ($user->id) {
            $this->staff_id => $this->staff_read_at,
            $this->user_id => $this->user_read_at,
            default => now(),
        };

        return ! $lu || $lu->lt($this->last_message_at);
    }

    public function markReadBy(User $user): void
    {
        if ($user->id === $this->staff_id) {
            $this->forceFill(['staff_read_at' => now()])->saveQuietly();
        }
        if ($user->id === $this->user_id) {
            $this->forceFill(['user_read_at' => now()])->saveQuietly();
        }
    }

    /** Suppression avec les pièces jointes qui ne servent plus à aucun autre message ou annonce. */
    public function deleteWithFiles(): void
    {
        $fichiers = $this->messages()->whereNotNull('attachment_path')->pluck('attachment_path')->unique();
        $this->delete();
        foreach ($fichiers as $f) {
            $utilise = ConversationMessage::where('attachment_path', $f)->exists() || MessageAnnouncement::where('attachment_path', $f)->exists();
            if (! $utilise) {
                Storage::disk('local')->delete($f);
            }
        }
    }
}
