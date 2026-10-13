<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Nettoyage du HTML produit par l'éditeur Quill avant enregistrement et affichage.
 *
 * Garde la mise en forme (titres, listes, liens, images, tableaux, vidéos YouTube / Vimeo, alignements Quill)
 * et retire tout ce qui peut exécuter du code : <script>, attributs on*, liens javascript:, iframes d'autres sites…
 */
class HtmlSanitizer
{
    /** Balises gardées (les autres sont « déballées » : leur texte est conservé) */
    private const ALLOWED_TAGS = [
        'p', 'br', 'hr', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'sub', 'sup', 'span', 'div',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'li', 'blockquote', 'pre', 'code',
        'a', 'img', 'iframe', 'figure', 'figcaption',
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption',
    ];

    /** Balises supprimées avec leur contenu */
    private const REMOVED_TAGS = ['script', 'style', 'object', 'embed', 'form', 'input', 'button', 'select', 'textarea', 'link', 'meta', 'base', 'svg', 'math'];

    /** Attributs autorisés par balise ('*' = toutes) */
    private const ALLOWED_ATTRIBUTES = [
        '*' => ['class', 'style', 'title'],
        'a' => ['href', 'target', 'rel'],
        'img' => ['src', 'alt', 'width', 'height'],
        'iframe' => ['src', 'width', 'height', 'frameborder', 'allowfullscreen', 'allow'],
        'td' => ['colspan', 'rowspan'],
        'th' => ['colspan', 'rowspan', 'scope'],
        'ol' => ['start'],
    ];

    /** Vidéos intégrables */
    private const IFRAME_HOSTS = '#^https://(www\.)?(youtube\.com/embed/|youtube-nocookie\.com/embed/|player\.vimeo\.com/video/)#i';

    public static function clean(?string $html): ?string
    {
        $html = trim((string) $html);
        if ($html === '' || self::isEmpty($html)) {
            return null;
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="__racine">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $racine = $dom->getElementById('__racine') ?? $dom->documentElement;
        self::walk($racine);

        $resultat = '';
        foreach (iterator_to_array($racine->childNodes) as $enfant) {
            $resultat .= $dom->saveHTML($enfant);
        }

        return self::isEmpty($resultat) ? null : trim($resultat);
    }

    /** Vrai si le contenu ne contient ni texte ni média (ex. « <p><br></p> » laissé par l'éditeur). */
    public static function isEmpty(?string $html): bool
    {
        return trim(html_entity_decode(strip_tags((string) $html, '<img><iframe><table>'), ENT_QUOTES | ENT_HTML5, 'UTF-8'), " \t\n\r\0\x0B\u{A0}") === '';
    }

    private static function walk(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $enfant) {
            if ($enfant->nodeType === XML_COMMENT_NODE || $enfant->nodeType === XML_PI_NODE) {
                $node->removeChild($enfant);
                continue;
            }
            if (! $enfant instanceof DOMElement) {
                continue;
            }

            $balise = strtolower($enfant->nodeName);

            if (in_array($balise, self::REMOVED_TAGS, true)) {
                $node->removeChild($enfant);
                continue;
            }

            self::walk($enfant);

            if (! in_array($balise, self::ALLOWED_TAGS, true)) {
                // Balise inconnue : on garde son contenu
                while ($enfant->firstChild) {
                    $node->insertBefore($enfant->firstChild, $enfant);
                }
                $node->removeChild($enfant);
                continue;
            }

            self::cleanAttributes($enfant, $balise);

            if ($balise === 'iframe' && ! preg_match(self::IFRAME_HOSTS, (string) $enfant->getAttribute('src'))) {
                $node->removeChild($enfant);
                continue;
            }
            if ($balise === 'img' && ! $enfant->getAttribute('src')) {
                $node->removeChild($enfant);
                continue;
            }
            if ($balise === 'a' && $enfant->getAttribute('target') === '_blank') {
                $enfant->setAttribute('rel', 'noopener noreferrer');
            }
        }
    }

    private static function cleanAttributes(DOMElement $element, string $balise): void
    {
        $autorises = array_merge(self::ALLOWED_ATTRIBUTES['*'], self::ALLOWED_ATTRIBUTES[$balise] ?? []);

        foreach (iterator_to_array($element->attributes) as $attribut) {
            $nom = strtolower($attribut->nodeName);
            $valeur = trim($attribut->nodeValue);

            if (! in_array($nom, $autorises, true)) {
                $element->removeAttribute($attribut->nodeName);
                continue;
            }
            if (in_array($nom, ['href', 'src'], true) && ! self::safeUrl($valeur, $nom === 'href')) {
                $element->removeAttribute($attribut->nodeName);
                continue;
            }
            if ($nom === 'style' && preg_match('/expression|javascript|url\s*\(|@import|behavior/i', $valeur)) {
                $element->removeAttribute($attribut->nodeName);
                continue;
            }
            if ($nom === 'target' && ! in_array($valeur, ['_blank', '_self'], true)) {
                $element->removeAttribute($attribut->nodeName);
            }
        }
    }

    /** http(s), chemins du site, ancres ; mailto: et tel: pour les liens seulement. */
    private static function safeUrl(string $url, bool $isLink): bool
    {
        $url = preg_replace('/[\x00-\x20]+/', '', $url);

        if (preg_match('#^(https?:)?//#i', $url) || str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return ! str_starts_with($url, '//') || preg_match('#^//[a-z0-9.-]+#i', $url);
        }
        if ($isLink && preg_match('/^(mailto|tel):/i', $url)) {
            return true;
        }

        // Chemin relatif sans schéma (ex. « storage/media/photo.jpg »)
        return ! preg_match('/^[a-z][a-z0-9+.-]*:/i', $url);
    }
}
