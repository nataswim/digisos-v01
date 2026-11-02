<?php

namespace App\Services;

use App\Models\Membership;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Lit le tableau des adhérents du club (copié depuis Excel / LibreOffice, ou fichier CSV) :
 * colonnes Observations, saisie pass'sport, Dossier complet, Attest. Paie., mini-coach, NOM, Prénom, Sexe,
 * Date naissance, Année de naissance, lieu de naissance, Adresse, Code postal, Ville, Téléphone1, Téléphone 2,
 * Mail, Droit à l'image, Année Certificat Médical, Situation, Catégorie, Tarif, Statut, Remise, Montant.
 * Les colonnes sont reconnues par leur intitulé (ordre libre, accents et majuscules indifférents).
 */
class MemberSheetParser
{
    /** intitulé normalisé (début) → champ */
    private const HEADERS = [
        'observation' => 'observations',
        'saisiepasssport' => 'pass_sport', 'passsport' => 'pass_sport',
        'dossiercomplet' => 'file_complete', 'dossier' => 'file_complete',
        'attestpaie' => 'payment_certificate', 'attestation' => 'payment_certificate',
        'minicoach' => 'mini_coach',
        'nom' => 'last_name',
        'prenom' => 'first_name',
        'sexe' => 'gender',
        'datenaissance' => 'birth_date', 'datedenaissance' => 'birth_date', 'naissance' => 'birth_date',
        'anneedenaissance' => 'birth_year', 'anneenaissance' => 'birth_year',
        'lieudenaissance' => 'birth_place', 'lieunaissance' => 'birth_place',
        'adresse' => 'address',
        'codepostal' => 'postal_code', 'cp' => 'postal_code',
        'ville' => 'city',
        'telephone1' => 'phone1', 'tel1' => 'phone1', 'telephone' => 'phone1',
        'telephone2' => 'phone2', 'tel2' => 'phone2',
        'mail' => 'email', 'email' => 'email', 'courriel' => 'email',
        'droitalimage' => 'image_rights', 'droitimage' => 'image_rights',
        'anneecertificatmedical' => 'medical_certificate_year', 'certificat' => 'medical_certificate_year',
        'situation' => 'situation',
        'categorie' => 'category',
        'tarif' => 'price',
        'statut' => 'status',
        'remise' => 'discount',
        'montant' => 'amount',
    ];

    public static function normalize(string $value): string
    {
        return preg_replace('/[^a-z0-9]/', '', Str::lower(Str::ascii($value)));
    }

    /**
     * @return array{rows: array<int, array>, columns: array<int, string>, missing: array<int, string>, warnings: array<int, string>}
     */
    public function parse(string $text): array
    {
        $text = ltrim($text, "\u{FEFF}");
        if (! mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
        }
        $lines = array_values(array_filter(preg_split('/\r\n|\r|\n/', $text), fn ($l) => trim($l, " \t;,") !== ''));
        $result = ['rows' => [], 'columns' => [], 'missing' => [], 'warnings' => []];
        if (! $lines) {
            return $result;
        }

        // Ligne d'en-tête : la première qui contient « nom » et « prénom »
        $headerIndex = null;
        foreach (array_slice($lines, 0, 10, true) as $i => $line) {
            $n = self::normalize($line);
            if (str_contains($n, 'nom') && str_contains($n, 'prenom')) {
                $headerIndex = $i;
                break;
            }
        }
        if ($headerIndex === null) {
            $result['missing'] = ['NOM', 'Prénom'];

            return $result;
        }

        $delimiter = $this->delimiter($lines[$headerIndex]);
        $headers = str_getcsv($lines[$headerIndex], $delimiter);
        $map = [];
        foreach ($headers as $col => $label) {
            $field = $this->field(self::normalize((string) $label));
            if ($field && ! in_array($field, $map, true)) {
                $map[$col] = $field;
            }
        }
        $result['columns'] = array_values($map);
        foreach (['last_name' => 'NOM', 'first_name' => 'Prénom'] as $f => $label) {
            if (! in_array($f, $map, true)) {
                $result['missing'][] = $label;
            }
        }
        if ($result['missing']) {
            return $result;
        }

        foreach (array_slice($lines, $headerIndex + 1) as $n => $line) {
            $cells = str_getcsv($line, $delimiter);
            $raw = [];
            foreach ($map as $col => $field) {
                $raw[$field] = trim((string) ($cells[$col] ?? ''), " \t\u{00A0}");
            }
            if (($raw['last_name'] ?? '') === '' && ($raw['first_name'] ?? '') === '') {
                continue;
            }
            $result['rows'][] = $this->row($raw, $n + $headerIndex + 2, $result['warnings']);
        }

        return $result;
    }

    private function field(string $normalized): ?string
    {
        if ($normalized === '') {
            return null;
        }
        // Correspondance exacte d'abord, puis par début d'intitulé (« telephone2 » avant « telephone »)
        if (isset(self::HEADERS[$normalized])) {
            return self::HEADERS[$normalized];
        }
        $keys = array_keys(self::HEADERS);
        usort($keys, fn ($a, $b) => strlen($b) <=> strlen($a));
        foreach ($keys as $key) {
            if (strlen($key) >= 4 && str_starts_with($normalized, $key)) {
                return self::HEADERS[$key];
            }
        }

        return null;
    }

    private function delimiter(string $line): string
    {
        $counts = ["\t" => substr_count($line, "\t"), ';' => substr_count($line, ';'), ',' => substr_count($line, ',')];
        arsort($counts);

        return (string) array_key_first($counts);
    }

    private function row(array $raw, int $lineNumber, array &$warnings): array
    {
        $who = trim(($raw['last_name'] ?? '') . ' ' . ($raw['first_name'] ?? ''));

        $birth = null;
        if (! empty($raw['birth_date'])) {
            foreach (['d/m/Y', 'd/m/y', 'Y-m-d', 'd-m-Y', 'd.m.Y'] as $format) {
                try {
                    $d = Carbon::createFromFormat('!' . $format, $raw['birth_date']);
                    if ($d && $d->format($format) === $raw['birth_date'] || $d) {
                        $birth = $d->toDateString();
                        break;
                    }
                } catch (\Throwable $e) {
                }
            }
            if (! $birth) {
                $warnings[] = "Ligne {$lineNumber} ({$who}) : date de naissance illisible « {$raw['birth_date']} ».";
            }
        }

        $passRaw = $raw['pass_sport'] ?? '';
        $passBool = self::bool($passRaw);
        $passCode = ($passBool === null && $passRaw !== '') ? mb_substr($passRaw, 0, 40) : null;

        $gender = Str::upper(Str::substr($raw['gender'] ?? '', 0, 1));

        return [
            'line' => $lineNumber,
            'observations' => ($raw['observations'] ?? '') ?: null,
            'pass_sport' => $passBool === true || $passCode !== null,
            'pass_sport_code' => $passCode,
            'file_complete' => self::bool($raw['file_complete'] ?? '') === true,
            'payment_certificate' => self::bool($raw['payment_certificate'] ?? '') === true,
            'mini_coach' => self::bool($raw['mini_coach'] ?? '') === true,
            'last_name' => mb_substr(preg_replace('/\s+/u', ' ', $raw['last_name'] ?? ''), 0, 100),
            'first_name' => mb_substr(preg_replace('/\s+/u', ' ', $raw['first_name'] ?? ''), 0, 100),
            'gender' => in_array($gender, ['F', 'M'], true) ? $gender : ($gender === 'G' || $gender === 'H' ? 'M' : null),
            'birth_date' => $birth,
            'birth_place' => ($raw['birth_place'] ?? '') ?: null,
            'address' => ($raw['address'] ?? '') ?: null,
            'postal_code' => ($raw['postal_code'] ?? '') ?: null,
            'city' => ($raw['city'] ?? '') ?: null,
            'phone1' => ($raw['phone1'] ?? '') ?: null,
            'phone2' => ($raw['phone2'] ?? '') ?: null,
            'email' => filter_var($raw['email'] ?? '', FILTER_VALIDATE_EMAIL) ? Str::lower($raw['email']) : null,
            'image_rights' => self::bool($raw['image_rights'] ?? ''),
            'medical_certificate_year' => preg_match('/(19|20)\d{2}/', $raw['medical_certificate_year'] ?? '', $m) ? (int) $m[0] : null,
            'situation' => self::situation($raw['situation'] ?? ''),
            'category' => ($raw['category'] ?? '') !== '' ? mb_substr(Str::upper(trim($raw['category'])), 0, 40) : null,
            'price_cents' => Membership::parseMoney($raw['price'] ?? ''),
            'status' => self::status($raw['status'] ?? ''),
            'discount_cents' => Membership::parseMoney($raw['discount'] ?? '') ?? 0,
            'amount_cents' => Membership::parseMoney($raw['amount'] ?? ''),
        ];
    }

    /** O / Oui / X / 1 → true ; N / Non / 0 → false ; vide → null */
    public static function bool(string $value): ?bool
    {
        $v = self::normalize($value);
        if ($v === '') {
            return null;
        }
        if (in_array($v, ['o', 'oui', 'x', 'y', 'yes', '1', 'ok', 'vrai'], true)) {
            return true;
        }
        if (in_array($v, ['n', 'non', 'no', '0', 'faux'], true)) {
            return false;
        }

        return null;
    }

    public static function situation(string $value): ?string
    {
        $v = self::normalize($value);

        return match (true) {
            $v === '' => null,
            str_contains($v, 'nouv') => 'nouveau',
            str_contains($v, 'retour') || str_contains($v, 'pause') || str_contains($v, 'reprise') => 'retour',
            default => 'renouvellement',   // « Déjà présent », « Renouvellement »…
        };
    }

    public static function status(string $value): ?string
    {
        $v = self::normalize($value);

        return match (true) {
            $v === '' => null,
            str_contains($v, 'preinscr') => 'preinscrit',
            str_contains($v, 'cours') => 'en_cours',
            str_contains($v, 'licenc') => 'licencie',
            str_contains($v, 'abandon') => 'abandon',
            str_contains($v, 'complet') => 'complet',
            default => null,
        };
    }
}
