{{-- Indicateurs d'une performance. Variables : $perf (SwimPerformance), $info (analyse : gap, pct, pb, first, vs_serie…) --}}
@php use App\Services\PerformanceTracker as PT; @endphp
@if ($perf->isValid() && $info)
    <span class="perf-badges">
        @if ($info['first'])
            <span class="perf-badge perf-badge--first" title="Premier temps sur cette épreuve et ce bassin">1er temps</span>
        @elseif ($info['pb'])
            <span class="perf-badge perf-badge--pb" title="Ancienne MPP : {{ \App\Models\SwimRecord::formatTime($info['prev_best']) }}">★ Record perso {{ PT::gapLabel($info['gap']) }} ({{ PT::pctLabel($info['pct']) }})</span>
        @else
            <span class="perf-badge {{ $info['gap'] === 0 ? 'perf-badge--equal' : 'perf-badge--slower' }}" title="MPP : {{ \App\Models\SwimRecord::formatTime($info['prev_best']) }}">
                MPP {{ $info['gap'] === 0 ? '= égalée' : PT::gapLabel($info['gap']) . ' (' . PT::pctLabel($info['pct']) . ')' }}
            </span>
        @endif
        @if ($info['vs_serie'] !== null)
            <span class="perf-badge {{ $info['vs_serie'] < 0 ? 'perf-badge--better' : 'perf-badge--neutral' }}">vs série {{ PT::gapLabel($info['vs_serie']) }} ({{ PT::pctLabel($info['vs_serie_pct']) }})</span>
        @endif
    </span>
@endif

@once
@push('styles')
<style>
    .perf-badges { display: inline-flex; flex-wrap: wrap; gap: .25rem; }
    .perf-badge { display: inline-block; padding: .15rem .5rem; border-radius: 6px; font-size: .78rem; font-weight: 600; white-space: nowrap; }
    .perf-badge--pb { background: #FBF6EC; color: #7a5518; border: 1px solid #D49F45; }
    .perf-badge--first { background: #E0F2FE; color: #094a84; }
    .perf-badge--slower { background: #F1F5F9; color: #45556c; }
    .perf-badge--equal { background: #ECFDF5; color: #065f46; }
    .perf-badge--better { background: #ECFDF5; color: #065f46; }
    .perf-badge--neutral { background: #F1F5F9; color: #45556c; }
    .perf-time { font-family: var(--bs-font-monospace); font-weight: 700; white-space: nowrap; }
</style>
@endpush
@endonce
