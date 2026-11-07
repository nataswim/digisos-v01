<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Liens communs : ouvrir une conversation dans le bon espace (lien des e-mails), pièce jointe protégée. */
class MessageLinkController extends Controller
{
    public function open(Request $request, Conversation $conversation): RedirectResponse
    {
        $me = $request->user();
        abort_unless($conversation->canBeViewedBy($me), 404);

        // Côté « utilisateur » de la conversation → espace adhérent ; côté équipe → son espace.
        if ($me->id === $conversation->user_id && ! Conversation::isStaff($me)) {
            return redirect()->route('user.messages.show', $conversation);
        }
        $espace = match ($me->role?->slug) {
            'admin' => 'admin',
            'editor' => 'editor',
            'coach' => 'coach',
            default => 'user',
        };

        return redirect()->route($espace . '.messages.show', $conversation);
    }

    public function attachment(Request $request, ConversationMessage $message): StreamedResponse
    {
        abort_unless($message->hasAttachment() && $message->conversation?->canBeViewedBy($request->user()), 404);
        abort_unless(Storage::disk('local')->exists($message->attachment_path), 404);

        return Storage::disk('local')->response($message->attachment_path, $message->attachment_name, [
            'Content-Type' => $message->attachment_mime,
            'X-Content-Type-Options' => 'nosniff',
        ], $message->attachment_is_image ? 'inline' : 'attachment');
    }
}
