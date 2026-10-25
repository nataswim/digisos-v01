<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Record de natation : un temps par niveau, bassin, catégorie, sexe et épreuve.
 */
class SwimRecord extends Model
{
    /** Niveaux, du plus proche au plus large. */
    public const LEVELS = [
        'club' => 'Records du club',
        'departement' => 'Records départementaux',
        'region' => 'Records régionaux',
        'france' => 'Records de France',
        'europe' => "Records d'Europe",
        'monde' => 'Records du monde',
    ];

    /** Niveaux officiels importables depuis extraNat (idniv FFN). */
    public const FFN_LEVELS = ['monde' => 1, 'europe' => 2, 'france' => 3, 'region' => 4, 'departement' => 5];

    public const POOLS = [25 => 'Bassin de 25 m', 50 => 'Bassin de 50 m'];

    public const CATEGORIES = [
        'all' => 'Toutes catégories', '12' => '12 ans', '13' => '13 ans', '14' => '14 ans',
        '15' => '15 ans', '16' => '16 ans', '17' => '17 ans', '18' => '18 ans',
    ];

    public const SEXES = ['F' => 'Dames', 'M' => 'Messieurs', 'X' => 'Mixtes'];

    /** Épreuves dans l'ordre FFN. 100 4N : bassin de 25 m uniquement. */
    public const EVENTS = [
        '50 NL', '100 NL', '200 NL', '400 NL', '800 NL', '1500 NL',
        '50 Dos', '100 Dos', '200 Dos',
        '50 Brasse', '100 Brasse', '200 Brasse',
        '50 Pap', '100 Pap', '200 Pap',
        '100 4N', '200 4N', '400 4N',
        '4x50 NL', '4x100 NL', '4x200 NL', '4x50 4N', '4x100 4N',
    ];

    protected $fillable = [
        'level', 'pool', 'category', 'sex', 'event', 'time_cs', 'swimmers', 'birth_year', 'club',
        'record_date', 'location', 'competition', 'source_url', 'updated_by',
    ];

    protected $casts = ['record_date' => 'date', 'pool' => 'integer', 'time_cs' => 'integer', 'birth_year' => 'integer'];

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /** Libellé du niveau, avec le nom du département ou de la région. */
    public static function levelLabel(string $level): string
    {
        return match ($level) {
            'departement' => 'Records ' . config('club.records.departement', 'départementaux'),
            'region' => 'Records ' . config('club.records.region', 'régionaux'),
            default => self::LEVELS[$level] ?? $level,
        };
    }

    /** Lien vers la page officielle extraNat correspondante. */
    public static function ffnUrl(string $level, int $pool, string $category = 'all'): ?string
    {
        if (! isset(self::FFN_LEVELS[$level])) {
            return null;
        }
        $params = ['idact' => '', 'idniv' => self::FFN_LEVELS[$level], 'idsex' => '', 'idbas' => $pool, 'day' => '', 'month' => '', 'year' => '',
            'idcat' => $category === 'all' ? '' : $category, 'idzon' => ''];
        if ($level === 'region') {
            $params['idzon'] = config('club.records.zone_region');
        }
        if ($level === 'departement') {
            $params['idzon'] = config('club.records.zone_departement');
        }

        return 'https://ffn.extranat.fr/webffn/nat_records.php?' . http_build_query($params);
    }

    public static function isRelay(string $event): bool
    {
        return str_starts_with($event, '4x');
    }

    /** Rang d'une épreuve pour le tri. */
    public static function eventOrder(string $event): int
    {
        $i = array_search($event, self::EVENTS, true);

        return $i === false ? 999 : $i;
    }

    // ------------------------------------------------------------------
    //  Temps
    // ------------------------------------------------------------------

    /** « 1:05.32 », « 00:23.41 », « 23.41 », « 15:12.08 », « 1'05"32 » → centièmes ; null si illisible. */
    public static function parseTime(?string $value): ?int
    {
        $v = trim(str_replace([',', "''", '"', "'", '’', '″', '′'], ['.', '.', '.', ':', ':', '.', ':'], (string) $value));
        if (! preg_match('/^(?:(\d{1,2}):)?(\d{1,2})\.(\d{1,2})$/', $v, $m)) {
            return null;
        }
        $cs = strlen($m[3]) === 1 ? (int) $m[3] * 10 : (int) $m[3];

        return ((int) ($m[1] ?? 0) * 60 + (int) $m[2]) * 100 + $cs;
    }

    /** 2341 → « 23.41 » ; 12167 → « 2:01.67 » */
    public static function formatTime(?int $cs): string
    {
        if ($cs === null) {
            return '';
        }
        $min = intdiv($cs, 6000);
        $sec = intdiv($cs % 6000, 100);
        $c = $cs % 100;

        return $min ? sprintf('%d:%02d.%02d', $min, $sec, $c) : sprintf('%d.%02d', $sec, $c);
    }

    /** Écart en centièmes → « +2.35 » / « −0.12 » */
    public static function formatGap(int $cs): string
    {
        $signe = $cs > 0 ? '+' : ($cs < 0 ? '−' : '');

        return $signe . self::formatTime(abs($cs));
    }

    public function getTimeLabelAttribute(): string
    {
        return self::formatTime($this->time_cs);
    }

    /** @return array<int, string> */
    public function getSwimmerListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->swimmers))));
    }
}
