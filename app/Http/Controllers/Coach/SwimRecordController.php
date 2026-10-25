<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\SwimRecord;
use App\Services\FfnRecordParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Espace coachs → Records : records du club (saisie) et records officiels
 * (copier-coller d'un tableau extraNat, avec aperçu avant enregistrement).
 */
class SwimRecordController extends Controller
{
    public function index(Request $request): View
    {
        [$level, $pool, $category] = $this->filters($request);

        $records = SwimRecord::where(compact('level', 'pool', 'category'))->get()
            ->sortBy(fn ($r) => [array_search($r->sex, array_keys(SwimRecord::SEXES)), SwimRecord::eventOrder($r->event)])
            ->groupBy('sex');

        return view('coach.records.index', [
            'level' => $level, 'pool' => $pool, 'category' => $category, 'records' => $records,
            'counts' => SwimRecord::select('level', DB::raw('count(*) as total'))->groupBy('level')->pluck('total', 'level'),
            'updatedAt' => SwimRecord::where(compact('level', 'pool', 'category'))->max('updated_at'),
        ]);
    }

    public function create(Request $request): View
    {
        [$level, $pool, $category] = $this->filters($request);

        return view('coach.records.form', ['record' => new SwimRecord(['level' => $level, 'pool' => $pool, 'category' => $category, 'sex' => $request->query('sexe', 'F')])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $existant = SwimRecord::where(collect($data)->only(['level', 'pool', 'category', 'sex', 'event'])->all())->first();
        if ($existant) {
            return back()->withInput()->withErrors(['event' => 'Un record existe déjà pour cette épreuve : modifiez-le (' . $existant->time_label . ').']);
        }
        $record = SwimRecord::create($data + ['updated_by' => $request->user()->id]);

        return redirect()->route('coach.records.index', ['niveau' => $record->level, 'bassin' => $record->pool, 'categorie' => $record->category])
            ->with('success', "Record {$record->event} enregistré ({$record->time_label}).");
    }

    public function edit(SwimRecord $record): View
    {
        return view('coach.records.form', compact('record'));
    }

    public function update(Request $request, SwimRecord $record): RedirectResponse
    {
        $data = $this->validated($request, $record);
        $record->update($data + ['updated_by' => $request->user()->id]);

        return redirect()->route('coach.records.index', ['niveau' => $record->level, 'bassin' => $record->pool, 'categorie' => $record->category])
            ->with('success', "Record {$record->event} mis à jour ({$record->time_label}).");
    }

    public function destroy(SwimRecord $record): RedirectResponse
    {
        $record->delete();

        return redirect()->route('coach.records.index', ['niveau' => $record->level, 'bassin' => $record->pool, 'categorie' => $record->category])
            ->with('success', "Record {$record->event} supprimé.");
    }

    /** Import d'un tableau officiel : aperçu, puis confirmation. */
    public function importForm(Request $request): View
    {
        [$level, $pool, $category] = $this->filters($request, official: true);

        return view('coach.records.import', ['level' => $level, 'pool' => $pool, 'category' => $category, 'text' => '', 'parsed' => null]);
    }

    public function import(Request $request, FfnRecordParser $parser): View|RedirectResponse
    {
        $data = $request->validate([
            'level' => ['required', Rule::in(array_keys(SwimRecord::FFN_LEVELS))],
            'pool' => ['required', Rule::in(array_keys(SwimRecord::POOLS))],
            'category' => ['required', Rule::in(array_keys(SwimRecord::CATEGORIES))],
            'text' => ['required', 'string', 'max:200000'],
            'replace' => ['nullable', 'boolean'],
            'confirm' => ['nullable', 'boolean'],
        ], ['text.required' => "Collez le tableau des records copié depuis extraNat."]);

        $parsed = $parser->parse($data['text']);

        if (! $request->boolean('confirm') || ! $parsed['records']) {
            return view('coach.records.import', [
                'level' => $data['level'], 'pool' => (int) $data['pool'], 'category' => $data['category'],
                'text' => $data['text'], 'parsed' => $parsed,
            ]);
        }

        $cle = ['level' => $data['level'], 'pool' => (int) $data['pool'], 'category' => $data['category']];
        $source = SwimRecord::ffnUrl($data['level'], (int) $data['pool'], $data['category']);

        DB::transaction(function () use ($parsed, $cle, $source, $request) {
            $gardes = [];
            foreach ($parsed['records'] as $r) {
                $record = SwimRecord::updateOrCreate($cle + ['sex' => $r['sex'], 'event' => $r['event']], [
                    'time_cs' => $r['time_cs'],
                    'swimmers' => implode("\n", $r['swimmers']) ?: ($r['club'] ?? ''),
                    'birth_year' => $r['birth_year'],
                    'club' => $r['club'],
                    'record_date' => $r['record_date'],
                    'location' => $r['location'],
                    'source_url' => $source,
                    'updated_by' => $request->user()->id,
                ]);
                $record->touch();
                $gardes[] = $record->id;
            }
            if ($request->boolean('replace')) {
                SwimRecord::where($cle)->whereNotIn('id', $gardes)->delete();
            }
        });

        return redirect()->route('coach.records.index', ['niveau' => $cle['level'], 'bassin' => $cle['pool'], 'categorie' => $cle['category']])
            ->with('success', count($parsed['records']) . ' record(s) importé(s) : ' . SwimRecord::levelLabel($cle['level']) . ', ' . SwimRecord::POOLS[$cle['pool']] . ', ' . SwimRecord::CATEGORIES[$cle['category']] . '.');
    }

    // ------------------------------------------------------------------

    private function filters(Request $request, bool $official = false): array
    {
        $levels = $official ? array_keys(SwimRecord::FFN_LEVELS) : array_keys(SwimRecord::LEVELS);
        $level = in_array($request->query('niveau'), $levels, true) ? $request->query('niveau') : ($official ? 'france' : 'club');
        $pool = in_array((int) $request->query('bassin'), [25, 50], true) ? (int) $request->query('bassin') : 25;
        $category = array_key_exists((string) $request->query('categorie'), SwimRecord::CATEGORIES) ? (string) $request->query('categorie') : 'all';

        return [$level, $pool, $category];
    }

    private function validated(Request $request, ?SwimRecord $record = null): array
    {
        $data = $request->validate([
            'level' => ['required', Rule::in(array_keys(SwimRecord::LEVELS))],
            'pool' => ['required', Rule::in(array_keys(SwimRecord::POOLS))],
            'category' => ['required', Rule::in(array_keys(SwimRecord::CATEGORIES))],
            'sex' => ['required', Rule::in(array_keys(SwimRecord::SEXES))],
            'event' => ['required', Rule::in(SwimRecord::EVENTS),
                Rule::unique('swim_records')->where(fn ($q) => $q->where($request->only(['level', 'pool', 'category', 'sex'])))->ignore($record?->id)],
            'time' => ['required', 'string', 'max:12', function ($attr, $value, $fail) {
                if (SwimRecord::parseTime($value) === null) {
                    $fail('Temps illisible : écrivez par exemple 28.45, 1:05.32 ou 17:02.10.');
                }
            }],
            'swimmers' => ['required', 'string', 'max:1000'],
            'birth_year' => ['nullable', 'integer', 'min:1900', 'max:' . now()->year],
            'club' => ['nullable', 'string', 'max:255'],
            'record_date' => ['nullable', 'date', 'before_or_equal:today'],
            'location' => ['nullable', 'string', 'max:255'],
            'competition' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url:http,https', 'max:500'],
        ], [
            'event.unique' => 'Un record existe déjà pour cette épreuve : modifiez-le.',
            'swimmers.required' => 'Indiquez le nageur (ou les relayeurs, un par ligne).',
        ]);

        if ((int) $data['pool'] === 50 && $data['event'] === '100 4N') {
            abort(back()->withInput()->withErrors(['event' => "Le 100 4 nages ne se nage qu'en bassin de 25 m."]));
        }
        if ($data['sex'] === 'X' && ! SwimRecord::isRelay($data['event'])) {
            abort(back()->withInput()->withErrors(['sex' => 'La catégorie « Mixtes » ne concerne que les relais.']));
        }

        $data['time_cs'] = SwimRecord::parseTime($data['time']);
        unset($data['time']);

        return $data;
    }
}
