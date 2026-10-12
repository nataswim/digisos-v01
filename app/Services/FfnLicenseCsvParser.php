<?php

namespace App\Services;

use DateTime;

/**
 * Lecture du fichier « extraction licences » exporté depuis extraNat (FFN).
 *
 * - Encodage : Windows-1252 (export extraNat) ou UTF-8, détecté automatiquement.
 * - Séparateur : « ; » (détection de « , » et tabulation par sécurité).
 * - Colonnes reconnues par leur intitulé (accents, casse et espaces ignorés) :
 *   l'ordre des colonnes peut changer d'une saison à l'autre.
 * - Les colonnes d'activités (CPT -, NPT -, COACH -, DIV -) non vides sont regroupées dans « activities ».
 *
 * Classe sans dépendance au framework : testable seule.
 */
class FfnLicenseCsvParser
{
    /** Intitulé normalisé => champ */
    public const COLUMNS = [
        'SAISON' => 'season',
        'IUF' => 'iuf',
        'CIVILITE' => 'civility',
        'NOM' => 'last_name',
        'PRENOM' => 'first_name',
        'NAISSANCE' => 'birth_date',
        'NATIONALITE' => 'nationality',
        'ADRESSE' => 'address',
        'ADRESSE (SUITE)' => 'address2',
        'CODE POSTAL' => 'postal_code',
        'VILLE' => 'city',
        'PAYS' => 'country',
        'TELEPHONE (1)' => 'phone1',
        'TELEPHONE (2)' => 'phone2',
        'TELEPHONE (3)' => 'phone3',
        'MAIL' => 'email',
        'ID. CLUB' => 'club_code',
        'CLUB' => 'club_name',
        'REGION' => 'region',
        'DEPARTEMENT' => 'department',
        'DATE CERTIFICAT MEDICAL' => 'medical_certificate_on',
        'TYPE_LICENCE' => 'license_type',
        'HOMOLOGATION' => 'homologated_on',
        'STATUT_LICENCE' => 'license_status',
        'ACCEPTE DE RECEVOIR DES MAILS FFN' => 'ffn_mail_consent',
    ];

    /** Colonnes indispensables */
    public const REQUIRED = ['iuf', 'last_name', 'first_name', 'birth_date'];

    /** Préfixes des colonnes d'activités */
    public const ACTIVITY_PREFIXES = ['CPT', 'NPT', 'COACH', 'DIV'];

    private const DATES = ['birth_date', 'medical_certificate_on', 'homologated_on'];

    /**
     * @return array{rows: array<int, array>, errors: array<int, string>, missing: array<int, string>, encoding: string, seasons: array<int, int>}
     */
    public function parse(string $path): array
    {
        $contenu = (string) file_get_contents($path);
        $contenu = preg_replace('/^\xEF\xBB\xBF/', '', $contenu);   // BOM UTF-8

        $encodage = 'UTF-8';
        if (! mb_check_encoding($contenu, 'UTF-8')) {
            $contenu = mb_convert_encoding($contenu, 'UTF-8', 'Windows-1252');
            $encodage = 'Windows-1252';
        }

        $lignes = preg_split('/\r\n|\r|\n/', $contenu);
        $entete = array_shift($lignes) ?? '';
        $separateur = $this->delimiter($entete);
        $intitules = str_getcsv($entete, $separateur, '"', '');

        // Correspondance index de colonne => champ
        $champs = [];
        $activites = [];
        foreach ($intitules as $i => $intitule) {
            $cle = self::normalizeHeader($intitule);
            if (isset(self::COLUMNS[$cle])) {
                $champs[$i] = self::COLUMNS[$cle];
            } elseif (preg_match('/^(' . implode('|', self::ACTIVITY_PREFIXES) . ')\s*-\s*/', $cle)) {
                $activites[$i] = trim($intitule);
            }
        }

        $manquants = array_values(array_diff(self::REQUIRED, $champs));
        $resultat = ['rows' => [], 'errors' => [], 'missing' => $manquants, 'encoding' => $encodage, 'seasons' => []];
        if ($manquants) {
            return $resultat;
        }

        $vus = [];
        foreach ($lignes as $n => $ligne) {
            $numero = $n + 2;   // numéro de ligne dans le fichier (en-tête = 1)
            if (trim($ligne) === '') {
                continue;
            }
            $cellules = str_getcsv($ligne, $separateur, '"', '');

            $row = ['line' => $numero, 'activities' => []];
            foreach ($champs as $i => $champ) {
                $row[$champ] = $this->clean($cellules[$i] ?? null);
            }
            foreach ($activites as $i => $libelle) {
                if ($this->clean($cellules[$i] ?? null) !== null) {
                    $row['activities'][] = $libelle;
                }
            }

            // Contrôles
            $iuf = preg_replace('/\D/', '', (string) ($row['iuf'] ?? ''));
            if ($iuf === '') {
                $resultat['errors'][] = "Ligne {$numero} : IUF (n° de licence) manquant.";
                continue;
            }
            if (isset($vus[$iuf])) {
                $resultat['errors'][] = "Ligne {$numero} : IUF {$iuf} déjà présent ligne {$vus[$iuf]}, ligne ignorée.";
                continue;
            }
            if (($row['last_name'] ?? null) === null || ($row['first_name'] ?? null) === null) {
                $resultat['errors'][] = "Ligne {$numero} : nom ou prénom manquant.";
                continue;
            }
            $row['iuf'] = $iuf;

            foreach (self::DATES as $champ) {
                if (! array_key_exists($champ, $row) || $row[$champ] === null) {
                    continue;
                }
                $date = $this->date($row[$champ]);
                if ($date === null) {
                    if ($champ === 'birth_date') {
                        $resultat['errors'][] = "Ligne {$numero} : date de naissance « {$row[$champ]} » non valide.";
                        continue 2;
                    }
                    $resultat['errors'][] = "Ligne {$numero} : date « {$row[$champ]} » non valide (ignorée).";
                }
                $row[$champ] = $date;
            }
            if (($row['birth_date'] ?? null) === null) {
                $resultat['errors'][] = "Ligne {$numero} : date de naissance manquante.";
                continue;
            }

            $row['season'] = isset($row['season']) && ctype_digit((string) $row['season']) ? (int) $row['season'] : null;
            $row['gender'] = self::genderFromCivility($row['civility'] ?? null);
            $row['ffn_mail_consent'] = isset($row['ffn_mail_consent']) ? in_array(mb_strtoupper($row['ffn_mail_consent']), ['OUI', 'O', 'YES', '1'], true) : null;
            $row['email'] = isset($row['email']) && filter_var($row['email'], FILTER_VALIDATE_EMAIL) ? mb_strtolower($row['email']) : null;
            $row['last_name'] = mb_substr($row['last_name'], 0, 100);
            $row['first_name'] = mb_substr($row['first_name'], 0, 100);

            $vus[$iuf] = $numero;
            $resultat['rows'][] = $row;
            if ($row['season']) {
                $resultat['seasons'][$row['season']] = $row['season'];
            }
        }

        $resultat['seasons'] = array_values($resultat['seasons']);

        return $resultat;
    }

    /** « Date certificat médical » → « DATE CERTIFICAT MEDICAL » */
    public static function normalizeHeader(string $value): string
    {
        $value = self::ascii($value);

        return trim(preg_replace('/\s+/', ' ', mb_strtoupper($value)));
    }

    /** Clé de comparaison de noms : minuscules, sans accents, lettres seulement. */
    public static function nameKey(?string $value): string
    {
        return preg_replace('/[^a-z]/', '', strtolower(self::ascii((string) $value)));
    }

    public static function genderFromCivility(?string $civility): ?string
    {
        $c = self::nameKey($civility);

        return match (true) {
            in_array($c, ['madame', 'mme', 'mademoiselle', 'mlle', 'f'], true) => 'F',
            in_array($c, ['monsieur', 'm', 'mr'], true) => 'M',
            default => null,
        };
    }

    private static function ascii(string $value): string
    {
        $map = ['À' => 'A', 'Â' => 'A', 'Ä' => 'A', 'Ç' => 'C', 'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Î' => 'I', 'Ï' => 'I', 'Ô' => 'O', 'Ö' => 'O', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ÿ' => 'Y',
            'à' => 'a', 'â' => 'a', 'ä' => 'a', 'ç' => 'c', 'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'î' => 'i', 'ï' => 'i', 'ô' => 'o', 'ö' => 'o', 'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ÿ' => 'y', 'œ' => 'oe', 'Œ' => 'OE', 'æ' => 'ae', 'Æ' => 'AE'];

        return strtr($value, $map);
    }

    private function delimiter(string $entete): string
    {
        $compte = [';' => substr_count($entete, ';'), ',' => substr_count($entete, ','), "\t" => substr_count($entete, "\t")];
        arsort($compte);

        return (string) array_key_first($compte);
    }

    private function clean(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim(preg_replace('/\s+/u', ' ', $value));

        return $value === '' ? null : $value;
    }

    /** « 18/01/2015 » → « 2015-01-18 » ; null si non valide */
    private function date(string $value): ?string
    {
        foreach (['d/m/Y', 'Y-m-d', 'd-m-Y', 'd/m/y'] as $format) {
            $d = DateTime::createFromFormat('!' . $format, $value);
            if ($d && $d->format($format) === $value) {
                return $d->format('Y-m-d');
            }
        }

        return null;
    }
}
