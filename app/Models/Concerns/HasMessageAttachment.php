<?php

namespace App\Models\Concerns;

/** Pièce jointe d'un message ou d'une annonce (fichier privé, disque « local »). */
trait HasMessageAttachment
{
    public function hasAttachment(): bool
    {
        return filled($this->attachment_path);
    }

    public function getAttachmentSizeLabelAttribute(): string
    {
        $o = (int) $this->attachment_size;

        return $o >= 1048576 ? number_format($o / 1048576, 1, ',', '') . ' Mo' : max(1, (int) round($o / 1024)) . ' Ko';
    }

    public function getAttachmentIsImageAttribute(): bool
    {
        return str_starts_with((string) $this->attachment_mime, 'image/');
    }
}
