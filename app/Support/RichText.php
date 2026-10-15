<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Nettoyage du HTML produit par l'éditeur de texte (Quill) avant affichage public.
 *
 * Garde la mise en forme de l'éditeur (titres, gras, listes, liens, couleurs, alignement,
 * tableaux, images, vidéos YouTube / Vimeo / Dailymotion) et retire tout ce qui peut
 * exécuter du code : balises <script>, <style>, <form>…, attributs « on… », liens « javascript: ».
 */
class RichText
{
    /** Balises conservées ; les autres sont retirées mais leur texte est gardé. */
    private const ALLOWED_TAGS = [
        'p', 'br', 'span', 'div', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'sub', 'sup', 'small', 'mark',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'pre', 'code', 'hr',
        'ul', 'ol', 'li', 'a', 'img', 'figure', 'figcaption',
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption', 'colgroup', 'col',
        'iframe',
    ];

    /** Balises supprimées avec tout leur contenu. */
    private const REMOVED_WITH_CONTENT = [
        'script', 'style', 'noscript', 'template', 'object', 'embed', 'applet', 'form',
        'input', 'button', 'select', 'textarea', 'link', 'meta', 'base', 'svg', 'math',
        'frame', 'frameset',
    ];

    private const ALLOWED_ATTRIBUTES = [
        'class', 'style', 'href', 'target', 'rel', 'title', 'alt', 'src', 'width', 'height',
        'colspan', 'rowspan', 'scope', 'data-row', 'frameborder', 'allowfullscreen', 'allow', 'loading',
    ];

    /** Seuls lecteurs vidéo autorisés dans un <iframe>. */
    private const IFRAME_HOSTS = [
        'www.youtube.com', 'youtube.com', 'www.youtube-nocookie.com', 'player.vimeo.com', 'www.dailymotion.com', 'geo.dailymotion.com',
    ];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '' || self::isEmpty($html)) {
            return '';
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="rich-text-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementById('rich-text-root');
        if (! $root) {
            return e(strip_tags($html));
        }

        self::cleanChildren($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return trim($out);
    }

    /** Vrai si le texte ne contient que des paragraphes vides (« <p><br></p> » laissé par l'éditeur). */
    public static function isEmpty(?string $html): bool
    {
        $text = trim(html_entity_decode(strip_tags((string) $html, '<img><iframe>'), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        return $text === '' || preg_match('/^[\s\x{00A0}]*$/u', $text) === 1;
    }

    /** Vrai si le texte contient des balises HTML (sinon c'est du texte brut). */
    public static function isHtml(?string $text): bool
    {
        return (bool) preg_match('/<\s*[a-z][a-z0-9]*[\s>\/]/i', (string) $text);
    }

    // -----------------------------------------------------------------

    private static function cleanChildren(DOMNode $node): void
    {
        // Copie : la liste change pendant qu'on la parcourt
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_COMMENT_NODE || $child->nodeType === XML_PI_NODE) {
                $node->removeChild($child);
                continue;
            }

            if (! $child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::REMOVED_WITH_CONTENT, true)
                || ($tag === 'iframe' && ! self::isAllowedIframe($child))) {
                $node->removeChild($child);
                continue;
            }

            self::cleanChildren($child);

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                // Balise inconnue : on garde son contenu
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            self::cleanAttributes($child, $tag);
        }
    }

    private static function cleanAttributes(DOMElement $element, string $tag): void
    {
        foreach (iterator_to_array($element->attributes) as $attribute) {
            $name = strtolower($attribute->name);
            $value = trim($attribute->value);

            $keep = in_array($name, self::ALLOWED_ATTRIBUTES, true);

            if ($keep && in_array($name, ['href', 'src'], true)) {
                $keep = self::isSafeUrl($value, $name === 'href');
            }

            if ($keep && $name === 'style') {
                $keep = ! preg_match('/expression|javascript:|behavior|url\s*\(|@import/i', $value);
            }

            if (! $keep) {
                $element->removeAttribute($attribute->name);
            }
        }

        // Liens vers un autre site : nouvel onglet sans accès à la page d'origine
        if ($tag === 'a' && $element->getAttribute('target') === '_blank') {
            $element->setAttribute('rel', 'noopener noreferrer');
        }
    }

    private static function isSafeUrl(string $url, bool $isLink): bool
    {
        $url = preg_replace('/[\x00-\x20]+/', '', html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        // Ancre, adresse du site (« /pages/… ») ou adresse « //domaine » (http ou https selon la page)
        if ($url === '' || str_starts_with($url, '#') || str_starts_with($url, '/')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $allowed = $isLink ? ['http', 'https', 'mailto', 'tel'] : ['http', 'https'];

        // Pas de schéma : lien relatif (« page.html »), sans « : » suspect
        if ($scheme === '') {
            return ! str_contains($url, ':');
        }

        return in_array($scheme, $allowed, true);
    }

    private static function isAllowedIframe(DOMElement $iframe): bool
    {
        $host = strtolower((string) parse_url($iframe->getAttribute('src'), PHP_URL_HOST));

        return in_array($host, self::IFRAME_HOSTS, true)
            && in_array(strtolower((string) parse_url($iframe->getAttribute('src'), PHP_URL_SCHEME)), ['https'], true);
    }
}
