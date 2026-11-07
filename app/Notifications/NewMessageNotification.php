<?php

namespace App\Notifications;

use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * « Vous avez un nouveau message du CNBB » : prévient le destinataire, par discrétion
 * sans le contenu ni le sujet du message, avec un lien vers le site (connexion requise).
 */
class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(public Conversation $conversation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Vous avez un nouveau message du CNBB')
            ->greeting('Bonjour,')
            ->line('Vous avez reçu un nouveau message dans votre espace sur le site du Cercle des Nageurs du Bocage Bressuirais.')
            ->action('Lire le message', route('messages.open', $this->conversation))
            ->line('Pour des raisons de confidentialité, le message n\'est consultable que sur le site, après connexion.')
            ->salutation('Sportivement, le Cercle des Nageurs du Bocage Bressuirais');
    }
}
