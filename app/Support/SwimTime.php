<?php

namespace App\Support;

/**
 * Temps de natation stockés en centièmes de seconde (entier).
 *
 *   SwimTime::parse('1:02.34') → 6234      SwimTime::format(6234) → « 1:02.34 »
 *   SwimTime::parse('32.5')    → 3250      SwimTime::format(3250) → « 32.50 »
 *   SwimTime::parse('1.02.34') / '1\'02"34' / '1:02,34' sont aussi acceptés.
 */
class SwimTime
{
    public static function parse(?string $value): ?int
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        // Séparateurs tolérés : « : » ou « ' » pour les minutes, « . » « , » « " » pour les centièmes
        $value = str_replace(['’', "'", ',', '"', ' '], [':', ':', '.', '.', ''], $value);

        if (preg_match('/^(\d{1,2})[:.](\d{2})[.](\d{1,2})$/', $value, $m)) {
            [$min, $sec, $cent] = [(int) $m[1], (int) $m[2], $m[3]];
        } elseif (preg_match('/^(\d{1,2}):(\d{2})$/', $value, $m)) {
            [$min, $sec, $cent] = [(int) $m[1], (int) $m[2], '0'];      // « 1:02 » = 1:02.00
        } elseif (preg_match('/^(\d{1,3})(?:[.](\d{1,2}))?$/', $value, $m)) {
            [$min, $sec, $cent] = [0, (int) $m[1], $m[2] ?? '0'];
        } else {
            return null;
        }

        if ($min > 0 && $sec > 59) {
            return null;
        }

        $cent = (int) str_pad($cent, 2, '0');   // « 1:02.3 » = 1:02.30

        return ($min * 60 + $sec) * 100 + $cent;
    }

    public static function format(?int $centiseconds): ?string
    {
        if ($centiseconds === null) {
            return null;
        }

        $min = intdiv($centiseconds, 6000);
        $sec = intdiv($centiseconds % 6000, 100);
        $cent = $centiseconds % 100;

        return $min > 0
            ? sprintf('%d:%02d.%02d', $min, $sec, $cent)
            : sprintf('%d.%02d', $sec, $cent);
    }

    public static function isValid(?string $value): bool
    {
        return trim((string) $value) === '' || self::parse($value) !== null;
    }
}
