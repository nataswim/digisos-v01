<?php

namespace App\Notifications;

use App\Models\Competition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * E-mail de convocation à une compétition, envoyé au compte lié au(x) nageur(s) (module Compétitions, V2).
 */
class CompetitionConvocationNotification extends Notification
{
    use Queueable;

    /**
     * @param  array<int, string>  $firstNames  prénoms des nageurs convoqués rattachés à ce compte
     */
    public function __construct(
        public Competition $competition,
        public array $firstNames,
        public bool $reminder = false,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $c = $this->competition;
        $noms = $this->joinNames($this->firstNames);
        $pluriel = count($this->firstNames) > 1;

        $mail = (new MailMessage)
            ->subject(($this->reminder ? 'Rappel : ' : '') . 'Convocation — ' . $c->title)
            ->greeting('Bonjour,')
            ->line(($pluriel ? "{$noms} sont convoqués" : "{$noms} est convoqué(e)") . " à la compétition « {$c->title} ».")
            ->line($c->date_range . ' — ' . $c->venue . ', ' . $c->city . '.');

        if ($c->meeting_point || $c->departure_at) {
            $mail->line('Rendez-vous : ' . trim(($c->meeting_point ?? '') . ($c->departure_at ? ', départ le ' . $c->departure_at->locale('fr')->isoFormat('dddd D MMMM [à] H[h]mm') : ''), ', ') . '.');
        }

        if ($c->response_deadline) {
            $mail->line('Merci de confirmer la présence ou l\'absence avant le ' . $c->response_deadline->locale('fr')->isoFormat('dddd D MMMM') . ', et d\'indiquer si vous cherchez ou proposez des places en voiture.');
        }

        return $mail
            ->action('Répondre à la convocation', route('user.competitions.index'))
            ->line('Toutes les informations (programme, logistique, documents) sont sur la fiche de la compétition : ' . route('competitions.show', $c))
            ->salutation('Sportivement, le Cercle des Nageurs du Bocage Bressuirais');
    }

    private function joinNames(array $names): string
    {
        $names = array_values($names);
        if (count($names) <= 1) {
            return $names[0] ?? '';
        }

        return implode(', ', array_slice($names, 0, -1)) . ' et ' . end($names);
    }
}
