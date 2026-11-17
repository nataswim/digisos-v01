<?php

namespace App\Services;

/**
 * Lit une grille de qualification collée depuis un PDF ou un tableur.
 *
 * Formats reconnus, ligne par ligne :
 *   - en-têtes de catégories : « U12 (2014)  U13 (2013) … U17 et + (2009 et -) » (facultatif)
 *   - épreuve : « 50 nage libre », « 100 dos », « 200 4 nages », « 50 NL », « 100 Pap. »…
 *   - sexe + temps : « Dames 00:34.63 00:35.33 00:32.65 00:33.35 » (ou « Messieurs », « Filles », « Garçons »…)
 *   L'épreuve peut être sur la même ligne que les temps, avant ou après la ligne Dames (copie de PDF).
 *
 * Temps acceptés : 00:28.96 · 00:28,96 · 00.28.96 · 2.28.71 · 1;25.96 · 35.82 · 03:.03.25
 * Collage depuis un tableur (tabulations) : les cellules vides gardent leur place.
 * Collage depuis un PDF : les cases vides disparaissent ; s'il manque des temps, ils sont
 * placés sur les dernières colonnes (les jeunes catégories n'ont pas toutes les épreuves).
 * Un « - » ou « — » peut marquer explicitement une case vide.
 */
class QualificationGridParser
{
    private const STROKES = [
        'nl' => 'NL', 'nage libre' => 'NL', 'nage-libre' => 'NL', 'crawl' => 'NL', 'free' => 'NL', 'libre' => 'NL',
        'dos' => 'Dos',
        'brasse' => 'Brasse', 'bra' => 'Brasse', 'bra.' => 'Brasse',
        'papillon' => 'Pap', 'pap' => 'Pap', 'pap.' => 'Pap',
        '4 nages' => '4N', '4nages' => '4N', '4n' => '4N', '4 n' => '4N', '4 n.' => '4N', 'quatre nages' => '4N',
    ];

    private const GENDERS = [
        'dames' => 'F', 'dame' => 'F', 'filles' => 'F', 'fille' => 'F', 'femmes' => 'F', 'f' => 'F',
        'messieurs' => 'M', 'monsieur' => 'M', 'garçons' => 'M', 'garcons' => 'M', 'garçon' => 'M', 'hommes' => 'M', 'h' => 'M', 'm' => 'M',
    ];

    /**
     * @param  int  $knownCategories  nombre de catégories de la grille (utilisé si le texte n'a pas d'en-têtes)
     * @param  int  $poolsPerCategory 2 si chaque catégorie a deux colonnes (25 m et 50 m), 1 sinon
     * @return array{rows: array<int, array{event: string, gender: string, cells: array<int, ?int>, raw: array<int, ?string>}>,
     *               categories: array<int, array{label: string, from: ?int, to: ?int}>, warnings: array<int, string>}
     *   cells : une case par colonne de la grille entière (catégorie × bassin), dans l'ordre.
     */
    public function parse(string $text, int $knownCategories = 0, int $poolsPerCategory = 2): array
    {
        $text = str_replace(["\u{00A0}", "\r"], [' ', ''], $text);
        $lines = explode("\n", $text);

        $categories = [];
        $sections = [['offset' => 0, 'columns' => $knownCategories * $poolsPerCategory]];
        $sectionHasRows = false;
        $items = [];          // ['event', section, '50 NL'] ou ['row', section, gender, cells, raw, tabs, ligne]
        $warnings = [];

        foreach ($lines as $num => $line) {
            if (trim($line) === '') {
                continue;
            }

            // En-têtes de catégories : « U12 (2014)  U13 (2013) » → nouveau bloc de colonnes
            if (preg_match_all('/(?:^|\s)(U\s?\d{1,2}(?:\s*et\s*\+)?|[A-Z][\w+\-]{1,15})\s*\((\d{4})(\s*et\s*[-+]|\s*et\s*(?:avant|après|apres|moins|plus)|\s*[-–]\s*\d{4})?\)/u', $line, $mm, PREG_SET_ORDER)) {
                $nouvelles = array_map(fn ($m) => $this->category(trim($m[1]), (int) $m[2], trim($m[3] ?? '')), $mm);
                $offset = count($categories) * $poolsPerCategory;
                $categories = array_merge($categories, $nouvelles);
                $bloc = ['offset' => $offset, 'columns' => count($nouvelles) * $poolsPerCategory];
                if (! $sectionHasRows && count($sections) === 1 && $sections[0]['offset'] === 0 && $offset === 0) {
                    $sections[0] = $bloc;      // les en-têtes remplacent la grille connue
                } else {
                    $sections[] = $bloc;
                }
                $sectionHasRows = false;
                continue;
            }
            $section = array_key_last($sections);

            $tabs = str_contains($line, "\t");
            $gender = null;
            $cells = [];
            $raw = [];
            $rest = $line;

            if ($tabs) {
                $parts = array_map('trim', explode("\t", $line));
                $idx = null;
                foreach ($parts as $i => $p) {
                    if (isset(self::GENDERS[mb_strtolower($p)])) {
                        $gender = self::GENDERS[mb_strtolower($p)];
                        $idx = $i;
                        break;
                    }
                }
                if ($gender !== null) {
                    $avant = implode(' ', array_slice($parts, 0, $idx));
                    foreach (array_slice($parts, $idx + 1) as $p) {
                        [$cs, $brut] = $this->cell($p);
                        if ($cs === false) {
                            $warnings[] = 'Ligne '.($num + 1).' : « '.$p.' » n\'est pas un temps reconnu (case laissée vide).';
                            $cs = null;
                        }
                        $cells[] = $cs;
                        $raw[] = $brut;
                    }
                    while ($cells && end($cells) === null && end($raw) === null) {
                        array_pop($cells);
                        array_pop($raw);
                    }
                    $rest = $avant;
                }
            } elseif (preg_match('/(?:^|\s)(Dames|Dame|Filles|Fille|Femmes|Messieurs|Monsieur|Garçons|Garcons|Garçon|Hommes)(?=\s|$)/iu', $line, $m, PREG_OFFSET_CAPTURE)) {
                $gender = self::GENDERS[mb_strtolower($m[1][0])];
                $avant = substr($line, 0, $m[1][1]);
                $apres = substr($line, $m[1][1] + strlen($m[1][0]));
                foreach (preg_split('/\s+/u', trim($apres)) as $tok) {
                    if ($tok === '') {
                        continue;
                    }
                    [$cs, $brut] = $this->cell($tok);
                    if ($cs === false) {
                        $avant .= ' '.$tok;     // mot après les temps (ex. nom de l'épreuve)
                        continue;
                    }
                    $cells[] = $cs;
                    $raw[] = $brut;
                }
                $rest = $avant;
            }

            $event = $this->event($rest);
            if ($event) {
                $items[] = ['event', $section, $event];
            }
            if ($gender !== null) {
                $items[] = ['row', $section, $gender, $cells, $raw, $tabs, $num + 1];
                $sectionHasRows = true;
            }
        }

        // Rattache chaque ligne Dames / Messieurs à son épreuve, bloc par bloc
        $parBloc = [];
        foreach ($items as $it) {
            $parBloc[$it[1]][] = $it;
        }
        $total = max(count($categories), $knownCategories) * $poolsPerCategory;
        $rows = [];

        foreach ($parBloc as $s => $liste) {
            $courante = null;
            $enAttente = [];
            $deja = [];
            $poser = function (string $event, array $r) use (&$rows, &$deja, &$warnings, $sections, $s, $total) {
                $deja[$event][$r[2]] = true;
                [$cells, $raw] = $this->align($event, $r, $sections[$s]['columns'], $warnings);
                $cle = $event.'|'.$r[2];
                $rows[$cle] ??= ['event' => $event, 'gender' => $r[2], 'cells' => array_fill(0, $total, null), 'raw' => array_fill(0, $total, null)];
                foreach ($cells as $i => $cs) {
                    $col = $sections[$s]['offset'] + $i;
                    if ($col < $total || $total === 0) {
                        $rows[$cle]['cells'][$col] = $cs;
                        $rows[$cle]['raw'][$col] = $raw[$i];
                    }
                }
            };

            foreach ($liste as $it) {
                if ($it[0] === 'event') {
                    foreach ($enAttente as $r) {
                        $poser($it[2], $r);
                    }
                    $enAttente = [];
                    $courante = $it[2];
                    continue;
                }
                if ($courante !== null && empty($deja[$courante][$it[2]])) {
                    $poser($courante, $it);
                } else {
                    $enAttente[] = $it;
                }
            }
            foreach ($enAttente as $r) {
                $warnings[] = 'Ligne '.$r[6].' : temps sans épreuve reconnue (ignorés).';
            }
        }

        // Ordre du module Performances, Dames avant Messieurs
        $rows = array_values($rows);
        usort($rows, fn ($a, $b) => [array_search($a['event'], \App\Models\SwimPerformance::EVENTS, true), $a['gender']]
            <=> [array_search($b['event'], \App\Models\SwimPerformance::EVENTS, true), $b['gender']]);

        return ['rows' => $rows, 'categories' => $categories, 'warnings' => $warnings];
    }

    /** Lit une case : [centièmes, texte] ; [null, null] si vide ; [false, texte] si illisible. */
    public function cell(string $value): array
    {
        $v = trim($value);
        if ($v === '' || in_array($v, ['-', '—', '–', 'x', 'X', '/'], true)) {
            return [null, null];
        }

        return [$this->time($v) ?? false, $v];
    }

    /** Temps écrit de façon irrégulière → centièmes, ou null. */
    public function time(string $value): ?int
    {
        $v = trim($value);
        if (! preg_match('/^\d{1,2}(?:[:.;,\'"’]+\d{1,2}){1,2}$/u', $v)) {
            return null;
        }
        $g = array_values(array_filter(preg_split('/[^0-9]+/', $v), fn ($x) => $x !== ''));

        if (count($g) === 3) {
            [$min, $sec, $cent] = [(int) $g[0], (int) $g[1], $g[2]];
        } elseif (count($g) === 2) {
            [$min, $sec, $cent] = [0, (int) $g[0], $g[1]];
        } else {
            return null;
        }
        if ($sec > 59 || strlen($cent) > 2) {
            return null;
        }

        return ($min * 60 + $sec) * 100 + (int) str_pad($cent, 2, '0');
    }

    /** « 50 nage libre », « 200 4 Nages », « 100 Pap. » → « 50 NL », « 200 4N », « 100 Pap ». */
    public function event(string $text): ?string
    {
        $t = mb_strtolower(preg_replace('/\s+/u', ' ', trim($text)));
        if ($t === '' || preg_match('/\d+\s*[x×]\s*\d+/u', $t)) {
            return null;   // vide ou relais
        }
        $nages = implode('|', array_map(fn ($k) => preg_quote($k, '/'), array_keys(self::STROKES)));
        if (! preg_match('/(?:^|\s)(25|50|100|200|400|800|1500)\s*(?:m\s+|m\.\s*|mètres\s+)?('.$nages.')(?=\s|$|\.)/u', $t, $m)) {
            return null;
        }
        $nage = self::STROKES[$m[2]] ?? null;
        $label = $m[1].' '.$nage;

        return in_array($label, \App\Models\SwimPerformance::EVENTS, true) ? $label : null;
    }

    /** Ajuste une ligne au nombre de colonnes de son bloc : [cells, raw]. */
    private function align(string $event, array $it, int $columns, array &$warnings): array
    {
        [, , $gender, $cells, $raw, $tabs, $ligne] = $it;
        $nom = $event.' '.($gender === 'F' ? 'Dames' : 'Messieurs');

        if ($columns > 0 && count($cells) > $columns) {
            $warnings[] = "{$nom} (ligne {$ligne}) : ".count($cells)." temps pour {$columns} colonnes, les derniers sont ignorés.";
            $cells = array_slice($cells, 0, $columns);
            $raw = array_slice($raw, 0, $columns);
        } elseif ($columns > 0 && ! $tabs && count($cells) < $columns && count($cells) > 0) {
            // Copie de PDF : cases vides perdues → temps placés sur les dernières colonnes
            $manque = $columns - count($cells);
            $warnings[] = "{$nom} (ligne {$ligne}) : ".count($cells)." temps pour {$columns} colonnes, placés sur les dernières colonnes. Vérifiez l'aperçu.";
            $cells = array_merge(array_fill(0, $manque, null), $cells);
            $raw = array_merge(array_fill(0, $manque, null), $raw);
        }

        return [$cells, $raw];
    }

    /** « U17 et + », 2009, « et - » → U17 et +, de null à 2009. */
    private function category(string $label, int $year, string $suite): array
    {
        $suite = mb_strtolower($suite);
        $label = preg_replace('/\s+/u', ' ', $label);

        if (preg_match('/[-–]\s*(\d{4})/', $suite, $m)) {
            return ['label' => $label, 'from' => min($year, (int) $m[1]), 'to' => max($year, (int) $m[1])];
        }
        if (preg_match('/et\s*(-|avant|moins)/u', $suite)) {
            return ['label' => $label, 'from' => null, 'to' => $year];
        }
        if (preg_match('/et\s*(\+|après|apres|plus)/u', $suite)) {
            return ['label' => $label, 'from' => $year, 'to' => null];
        }

        return ['label' => $label, 'from' => $year, 'to' => $year];
    }
}
