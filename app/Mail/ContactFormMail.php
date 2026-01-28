<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Données du formulaire de contact
     */
    public array $contactData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[DIGITALSOS] ' . $this->getSubjectLabel(),
            replyTo: [$this->contactData['email']]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: ['contactData' => $this->contactData]
        );
    }

    /**
     * Obtenir le libellé du sujet
     */
    private function getSubjectLabel(): string
    {
        $subjects = [
            'information' => 'Demande d\'information',
            'support' => 'Support technique',
            'partnership' => 'Partenariat',
            'billing' => 'Facturation',
            'other' => 'Autre demande'
        ];

        return $subjects[$this->contactData['subject']] ?? 'Nouveau message';
    }
}