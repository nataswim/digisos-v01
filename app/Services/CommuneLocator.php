<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Position (latitude, longitude) d'une commune française, pour la carte du tableau de bord des adhérents.
 * Source : API officielle geo.api.gouv.fr (gratuite, sans clé). Seuls le code postal et le nom de la
 * commune sont envoyés, jamais une adresse. Résultat gardé en cache (1 an ; échec : 1 jour).
 * Si l'API ne répond pas, les communes restantes de la page ne sont pas interrogées (pas d'attente).
 */
class CommuneLocator
{
    private bool $unavailable = false;

    /** @return array{lat: float, lng: float, name: string}|null */
    public function locate(?string $postalCode, ?string $city): ?array
    {
        $postalCode = preg_replace('/\D/', '', (string) $postalCode);
        $city = trim((string) $city);
        if ($city === '' && $postalCode === '') {
            return null;
        }

        $key = 'commune-geo:' . $postalCode . ':' . Str::slug($city);
        $cached = Cache::get($key);
        if ($cached !== null) {
            return $cached ?: null;   // [] = déjà cherché, introuvable
        }
        if ($this->unavailable) {
            return null;
        }

        try {
            $params = ['fields' => 'nom,centre,codesPostaux', 'format' => 'json'];
            $params += strlen($postalCode) === 5 ? ['codePostal' => $postalCode] : ['nom' => $city, 'boost' => 'population', 'limit' => 5];
            $response = Http::timeout(4)->acceptJson()->get('https://geo.api.gouv.fr/communes', $params);
            if (! $response->successful()) {
                throw new \RuntimeException('HTTP ' . $response->status());
            }
            $found = $this->pick((array) $response->json(), $city);
        } catch (\Throwable $e) {
            $this->unavailable = true;
            report($e);

            return null;
        }

        Cache::put($key, $found ?? [], $found ? now()->addYear() : now()->addDay());

        return $found;
    }

    /** Commune dont le nom correspond (sans accents, tirets ni « Saint » abrégé), sinon la première. */
    private function pick(array $communes, string $city): ?array
    {
        $norm = fn ($s) => str_replace(['saint-', 'sainte-'], ['st-', 'ste-'], Str::slug((string) $s));
        $choisie = collect($communes)->first(fn ($c) => $city !== '' && $norm($c['nom'] ?? '') === $norm($city))
            ?? ($communes[0] ?? null);
        $coords = $choisie['centre']['coordinates'] ?? null;   // GeoJSON : [longitude, latitude]

        return $coords ? ['lat' => (float) $coords[1], 'lng' => (float) $coords[0], 'name' => (string) $choisie['nom']] : null;
    }
}
