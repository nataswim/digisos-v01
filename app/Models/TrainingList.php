<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Listes modifiables du module Séances (Espace coachs → Séances → Listes).
 */
class TrainingList extends Model
{
    public const LISTS = [
        'blocks' => ['Types de blocs (natation)', 'Échauffement, corps de séance, retour au calme…'],
        'strokes' => ['Nages et exercices en bassin', 'Crawl, dos, jambes, éducatifs…'],
        'equipment' => ['Matériel', 'Plaquettes, pull-buoy, élastique, tapis…'],
        'categories' => ["Catégories d'exercices (PPG / maison)", 'Gainage, haut du corps, mobilité…'],
        'school_equipment' => ['Matériel école de natation', 'Frites, cerceaux, tapis flottant…'],
    ];

    protected $fillable = ['list', 'label', 'sort_order'];

    /** @return array<int, string> libellés d'une liste, dans l'ordre */
    public static function labels(string $list): array
    {
        try {
            return static::where('list', $list)->orderBy('sort_order')->orderBy('id')->pluck('label')->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** Remplace le contenu d'une liste (un libellé par ligne, doublons et lignes vides retirés). */
    public static function replace(string $list, array $labels): void
    {
        $labels = collect($labels)->map(fn ($l) => mb_substr(trim((string) $l), 0, 100))->filter()->unique()->values();

        static::where('list', $list)->delete();
        foreach ($labels as $i => $label) {
            static::create(['list' => $list, 'label' => $label, 'sort_order' => $i]);
        }
    }
}
