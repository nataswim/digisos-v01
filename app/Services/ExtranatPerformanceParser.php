<?php

namespace App\Services;

use App\Models\SwimRecord;
use Illuminate\Support\Carbon;

/**
 * Lit le copier-coller de la page « Performances » d'un nageur sur extraNat (serveur fédéral FFN).
 *
 * Format reconnu (une valeur par ligne ou par cellule, tabulations acceptées) :
 *   NOM Prénom (2009)  FRA [1234567]          → nageur (nom, prénom, année, IUF)
 *   50 Nage Libre Dames / Bassin : 25 mètres  → épreuve et bassin
 *   50 NL  00:28.97  (17 ans)  1020 pts       → temps (âge et points ignorés)
 *   CHOLET  (FRA)  FRANCE                     → lieu
 *   31/10/2026  [REG]  NOM DU CLUB            → date (niveau ignoré), club
 * Les relais (« 4x50 … ») sont ignorés. Plusieurs nageurs peuvent être collés à la suite.
 *
 * @phpstan-type Perf array{event: string, pool: int, time_cs: int, time: string, location: ?string, date: string, club: ?string}
 */
class ExtranatPerformanceParser
{
    /** Abréviation extraNat → nage du module Performances */
    private const STROKES = ['NL' => 'NL', 'DOS' => 'Dos', 'BRA.' => 'Brasse', 'BRA' => 'Brasse', 'BRASSE' => 'Brasse',
        'PAP.' => 'Pap', 'PAP' => 'Pap', 'PAPILLON' => 'Pap', '4 N.' => '4N', '4N' => '4N', '4 N' => '4N', '4 NAGES' => '4N'];

    /**
     * @return array{swimmers: array<int, array{last_name: string, first_name: string, birth_year: ?int, iuf: ?string, performances: array<int, array>}>, ignored: int}
     */
    public function parse(string $text): array
    {
        $tokens = collect(preg_split('/[\t\r\n]+/u', str_replace("\u{00A0}", ' ', $text)))
            ->map(fn ($t) => trim(preg_replace('/\s+/u', ' ', $t)))
            ->filter(fn ($t) => $t !== '')
            ->values()->all();

        $swimmers = [];
        $current = null;      // index du nageur en cours
        $pool = 25;
        $relay = false;
        $perf = null;         // performance en cours de lecture
        $ignored = 0;

        $flush = function () use (&$perf, &$swimmers, &$current, &$ignored) {
            if ($perf === null) {
                return;
            }
            if ($current !== null && $perf['time_cs'] !== null && $perf['date'] && in_array($perf['event'], \App\Models\SwimPerformance::EVENTS, true)
                && ! ($perf['pool'] === 50 && str_starts_with($perf['event'], '25 '))) {
                $swimmers[$current]['performances'][] = $perf;
            } else {
                $ignored++;
            }
            $perf = null;
        };

        foreach ($tokens as $t) {
            // Nageur : « NOM Prénom (2009) FRA [1234567] »
            if (preg_match('/^(.+?)\s*\((\d{4})\)\s*(?:[A-Z]{3})?\s*\[(\d{4,})\]/u', $t, $m)) {
                $flush();
                [$nom, $prenom] = $this->splitName($m[1]);
                $swimmers[] = ['last_name' => $nom, 'first_name' => $prenom, 'birth_year' => (int) $m[2], 'iuf' => $m[3], 'performances' => []];
                $current = array_key_last($swimmers);
                $relay = false;
                continue;
            }
            // Bassin
            if (preg_match('/^Bassin\s*:\s*(25|50)\s*m/iu', $t, $m)) {
                $pool = (int) $m[1];
                continue;
            }
            // Titre d'épreuve : « 50 Nage Libre Dames », « 4x50 Nage Libre Messieurs » (relais)
            if (preg_match('/^\d+\s*(x\s*\d+\s*)?(Nage Libre|Dos|Brasse|Papillon|4 Nages)\b.*\b(Dames|Messieurs|Mixtes?)$/iu', $t, $m)) {
                $flush();
                $relay = ! empty($m[1]);
                continue;
            }
            // Début d'une performance : « 50 NL », « 100 Bra. », « 200 4 N. » (et « 4x50 NL » pour un relais)
            if (preg_match('/^(\d+\s*x\s*)?(\d{2,4})\s+(NL|Dos|Bra\.?|Brasse|Pap\.?|Papillon|4 ?N\.?|4 Nages)$/iu', $t, $m)) {
                $flush();
                if ($relay || ! empty($m[1])) {
                    $ignored++;
                    $perf = null;
                    continue;
                }
                $nage = self::STROKES[mb_strtoupper($m[3])] ?? null;
                $perf = ['event' => $nage ? $m[2] . ' ' . $nage : $t, 'pool' => $pool, 'time_cs' => null, 'time' => null, 'location' => null, 'date' => null, 'club' => null, '_step' => 'time'];
                continue;
            }
            if ($perf === null) {
                continue;
            }
            // Temps
            if ($perf['_step'] === 'time' && preg_match('/^\d{1,2}:\d{2}[.,]\d{2}$|^\d{1,2}[.,]\d{2}$/', $t)) {
                $cs = SwimRecord::parseTime(preg_replace('/^00:/', '', $t));
                $perf['time_cs'] = $cs;
                $perf['time'] = SwimRecord::formatTime($cs);
                $perf['_step'] = 'place';
                continue;
            }
            // Âge, points, code pays, niveau : ignorés
            if (preg_match('/^\(\d+ ans\)$|^\d+ pts$|^\([A-Z]{3}\)$|^\[[A-Z]+\]$/u', $t)) {
                continue;
            }
            // Date : « 31/10/2026 », éventuellement suivie du niveau et du club sur la même ligne
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})(?:\s*\[[A-Z]+\])?\s*(.*)$/u', $t, $m)) {
                $perf['date'] = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
                $perf['_step'] = 'club';
                if ($m[4] !== '') {
                    $perf['club'] = $m[4];
                    $perf['_step'] = 'done';
                }
                continue;
            }
            if ($perf['_step'] === 'place') {
                if ($perf['location'] === null) {
                    $perf['location'] = $this->cityName($t);
                }
                // le pays (« FRANCE ») qui suit la ville est ignoré
                continue;
            }
            if ($perf['_step'] === 'club') {
                $perf['club'] = $t;
                $perf['_step'] = 'done';
            }
        }
        $flush();

        foreach ($swimmers as &$s) {
            $s['performances'] = array_map(fn ($p) => array_diff_key($p, ['_step' => 1]), $s['performances']);
        }

        return ['swimmers' => $swimmers, 'ignored' => $ignored];
    }

    /** « DUPONT-MARTIN Léa Marie » → [« DUPONT-MARTIN », « Léa Marie »] (le nom est en majuscules) */
    private function splitName(string $full): array
    {
        $mots = preg_split('/\s+/u', trim($full));
        $nom = [];
        while ($mots && mb_strtoupper($mots[0]) === $mots[0] && preg_match('/\p{L}/u', $mots[0])) {
            $nom[] = array_shift($mots);
        }
        if (! $mots) {          // tout en majuscules : dernier mot = prénom
            $mots = [array_pop($nom)];
        }

        return [implode(' ', $nom), implode(' ', $mots)];
    }

    /** « SAINT-SÉBASTIEN-SUR-LOIRE » → « Saint-Sébastien-sur-Loire » */
    private function cityName(string $city): string
    {
        $c = mb_convert_case(mb_strtolower($city), MB_CASE_TITLE);

        return preg_replace_callback('/-(Sur|Sous|En|Les|Le|La|De|Du|Des|Lès|Lez|Aux|Au)-/u', fn ($m) => '-' . mb_strtolower($m[1]) . '-', $c);
    }
}
