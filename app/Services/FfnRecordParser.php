<?php

namespace App\Services;

use App\Models\SwimRecord;
use Illuminate\Support\Carbon;

/**
 * Lit un tableau de records copié depuis une page « Records » d'extraNat (ffn.extranat.fr)
 * et collé tel quel dans le formulaire d'import.
 *
 * Formats reconnus (le découpage ne dépend pas des retours à la ligne) :
 *   « Dames » / « Messieurs » / « Mixtes »                        → sexe des lignes suivantes
 *   « 50 NL  00:23.41  NOM Prénom (1995/30 ans) FRA  CLUB  07/12/2025  VILLE (PAYS) »
 *   « 4x100 NL  03:31.23  CLUB  12/12/2024  VILLE (PAYS) » puis une ligne par relayeur
 * Les autres lignes (en-têtes, légende…) sont ignorées.
 */
class FfnRecordParser
{
    private const EVENT = '(?:(4)\s*[xX×]\s*)?(\d{2,4})\s*m?\s*(NL|Nage\s*libre|Libre|Dos|Brasse|Pap(?:illon)?|4\s*N(?:ages)?)';
    private const TIME = '((?:\d{1,2}:)?\d{1,2}[.,]\d{2})';
    private const SWIMMER = "([\\p{Lu}][\\p{L}'’ .-]+?\\s*\\(\\d{4}(?:\\s*\\/\\s*\\d+\\s*ans)?\\))";
    private const DATE = '(\d{2}\/\d{2}\/\d{4})';

    /**
     * Le texte collé dépend du navigateur : une ligne par record (cases séparées par des tabulations),
     * ou une case par ligne. On travaille donc sur le texte entier, découpé en « morceaux » qui commencent
     * chacun par « épreuve + temps » ou par un titre de section (Dames, Messieurs, Mixtes).
     *
     * @return array{records: array<int, array>, ignored: array<int, string>}
     */
    public function parse(string $text): array
    {
        $flat = trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $text));
        $eventTime = self::EVENT . '\s+' . self::TIME . '(?=\s|$)';
        $section = '(?<![\p{L}])(Dames|Femmes|Messieurs|Hommes|Mixtes?)(?![\p{L}])';

        // Découpage : avant chaque « épreuve + temps » et avant chaque titre de section
        $parts = preg_split('/(?=(?<![\p{L}\d])' . $eventTime . ')|(?=' . $section . ')/iu', $flat, -1, PREG_SPLIT_NO_EMPTY);

        // « 4 x 50 NL » : le découpage a pu séparer « 4 x » de « 50 NL » ; on recolle
        $morceaux = [];
        $prefixe = '';
        foreach ($parts as $part) {
            $part = $prefixe . $part;
            $prefixe = '';
            if (preg_match('/(?:^|\s)(4\s*[xX×]\s*)$/u', $part, $r)) {
                $prefixe = $r[1];
                $part = substr($part, 0, -strlen($r[1]));
            }
            $morceaux[] = $part;
        }

        $records = [];
        $ignored = [];
        $sex = 'F';

        foreach ($morceaux as $part) {
            $part = trim($part);

            if (preg_match('/^' . $section . '/iu', $part, $m) && ! preg_match('/^' . $eventTime . '/iu', $part)) {
                $sex = match (mb_strtolower(mb_substr($m[1], 0, 3))) {
                    'dam', 'fem' => 'F',
                    'mes', 'hom' => 'M',
                    default => 'X',
                };
                continue;
            }

            if (! preg_match('/^' . $eventTime . '\s*(.*)$/iu', $part, $m)) {
                continue;   // texte avant le tableau (titres, filtres…)
            }
            [$relay, $distance, $stroke, $time, $rest] = [$m[1], $m[2], $m[3], $m[4], $m[5]];

            if (! preg_match('/' . self::DATE . '/u', $rest, $d, PREG_OFFSET_CAPTURE)) {
                $ignored[] = mb_substr($part, 0, 160);
                continue;
            }
            $date = $d[1][0];
            // Nationalité (« FRA ») placée entre deux nageurs : retirée
            $sansNat = fn ($t) => preg_replace('/\)\s+[A-Z]{3}(?=\s+[\p{Lu}][\p{L}\'’ .-]+?\s*\(\d{4}|\s*$)/u', ') ', $t);
            $before = $sansNat(substr($rest, 0, $d[0][1]));
            $after = trim($sansNat(substr($rest, $d[0][1] + strlen($date))));

            // Nageurs : avant la date (cas général) et après le lieu (relayeurs listés ensuite)
            preg_match_all('/' . self::SWIMMER . '/u', $before, $avant, PREG_OFFSET_CAPTURE);
            preg_match_all('/' . self::SWIMMER . '/u', $after, $apres, PREG_OFFSET_CAPTURE);
            $swimmers = array_merge(array_column($avant[1], 0), array_column($apres[1], 0));

            // Club : ce qui suit le dernier nageur (et sa nationalité) avant la date
            $club = $before;
            if ($avant[0]) {
                $last = end($avant[0]);
                $club = substr($before, $last[1] + strlen($last[0]));
            }
            $club = trim(preg_replace('/^\s*[A-Z]{3}(?=\s|$)/u', '', $club));

            // Lieu : après la date, jusqu'au premier relayeur éventuel
            $location = $apres[0] ? substr($after, 0, $apres[0][0][1]) : $after;
            $location = trim(preg_replace('/\s+(Légende|Legende)\b.*$/iu', '', $location));

            $records[] = $this->record($sex, $relay, $distance, $stroke, $time, $swimmers, $club, $date, $location);
        }

        // Une seule ligne par sexe et épreuve (la première rencontrée)
        $uniques = [];
        foreach ($records as $r) {
            $key = $r['sex'] . '|' . $r['event'];
            if ($r['event'] && $r['time_cs'] && ! isset($uniques[$key])) {
                $r['swimmers'] = array_values(array_unique($r['swimmers']));
                $uniques[$key] = $r;
            }
        }

        $records = array_values($uniques);
        usort($records, fn ($a, $b) => [array_search($a['sex'], ['F', 'M', 'X']), SwimRecord::eventOrder($a['event'])]
            <=> [array_search($b['sex'], ['F', 'M', 'X']), SwimRecord::eventOrder($b['event'])]);

        return ['records' => $records, 'ignored' => $ignored];
    }

    public static function normalizeEvent(?string $relay, string $distance, string $stroke): ?string
    {
        $s = mb_strtolower(preg_replace('/\s+/', '', $stroke));
        $nage = match (true) {
            str_starts_with($s, 'nl'), str_starts_with($s, 'nage'), str_starts_with($s, 'libre') => 'NL',
            str_starts_with($s, 'dos') => 'Dos',
            str_starts_with($s, 'brasse') => 'Brasse',
            str_starts_with($s, 'pap') => 'Pap',
            str_starts_with($s, '4n') => '4N',
            default => null,
        };
        if (! $nage) {
            return null;
        }
        $event = ($relay ? '4x' : '') . (int) $distance . ' ' . $nage;

        return in_array($event, SwimRecord::EVENTS, true) ? $event : null;
    }

    private function record(string $sex, ?string $relay, string $distance, string $stroke, string $time, array $swimmers, string $club, string $date, string $location): array
    {
        $event = self::normalizeEvent($relay ?: null, $distance, $stroke);
        $swimmers = array_map(fn ($s) => $this->cleanSwimmer($s), $swimmers);
        $birth = null;
        if (! $relay && isset($swimmers[0]) && preg_match('/\((\d{4})/', $swimmers[0], $b)) {
            $birth = (int) $b[1];
        }

        try {
            $recordDate = Carbon::createFromFormat('d/m/Y', $date)->toDateString();
        } catch (\Throwable $e) {
            $recordDate = null;
        }

        return [
            'sex' => $sex,
            'event' => $event,
            'time_cs' => SwimRecord::parseTime($time),
            'swimmers' => $swimmers,
            'birth_year' => $birth,
            'club' => trim($club, " -–") ?: null,
            'record_date' => $recordDate,
            'location' => trim($location) ?: null,
        ];
    }

    /** « GASTALDELLO Béryl (1995/30 ans) » → « GASTALDELLO Béryl (1995) » : l'âge change chaque année. */
    private function cleanSwimmer(string $swimmer): string
    {
        return trim(preg_replace('/\(\s*(\d{4})\s*\/\s*\d+\s*ans\s*\)/u', '($1)', $swimmer));
    }
}
