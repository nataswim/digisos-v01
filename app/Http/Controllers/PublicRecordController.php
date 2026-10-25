<?php

namespace App\Http\Controllers;

use App\Models\SwimRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Page publique « Records » : records du club et records officiels, bassin 25 / 50 m, par catégorie.
 * Sur les records du club : écart avec les records départemental, régional et de France.
 */
class PublicRecordController extends Controller
{
    private const COMPARE = ['departement' => 'Dép.', 'region' => 'Rég.', 'france' => 'France'];

    public function index(Request $request): View
    {
        $level = array_key_exists($request->query('niveau'), SwimRecord::LEVELS) ? $request->query('niveau') : 'club';
        $pool = in_array((int) $request->query('bassin'), [25, 50], true) ? (int) $request->query('bassin') : 25;
        $category = array_key_exists((string) $request->query('categorie'), SwimRecord::CATEGORIES) ? (string) $request->query('categorie') : 'all';

        $records = SwimRecord::where(compact('level', 'pool', 'category'))->get()
            ->sortBy(fn ($r) => SwimRecord::eventOrder($r->event));

        // Références pour comparer les records du club
        $references = collect();
        if ($level === 'club') {
            $references = SwimRecord::whereIn('level', array_keys(self::COMPARE))->where(compact('pool', 'category'))->get()
                ->groupBy(fn ($r) => $r->sex . '|' . $r->event)
                ->map(fn ($g) => $g->keyBy('level'));
        }

        return view('public.records.index', [
            'level' => $level,
            'pool' => $pool,
            'category' => $category,
            'records' => $records->groupBy('sex'),
            'references' => $references,
            'compare' => self::COMPARE,
            'updatedAt' => $records->max('updated_at'),
            'ffnUrl' => SwimRecord::ffnUrl($level, $pool, $category),
            'counts' => SwimRecord::where(compact('pool', 'category'))->selectRaw('level, count(*) as total')->groupBy('level')->pluck('total', 'level'),
        ]);
    }
}
