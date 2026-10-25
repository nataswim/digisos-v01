{{--
    Fiche d'une séance (aperçu coach et espace adhérent).
    Variables : $session (TrainingSession), $exercises (exercices de la bibliothèque, par id)
--}}
@php
    use App\Models\TrainingSession as TS;
    $zoneCouleurs = ['Z1' => '#64748b', 'Z2' => '#059669', 'Z3' => '#ca8a04', 'Z4' => '#ea580c', 'Z5' => '#dc2626'];
@endphp

<article class="training-sheet" style="--type-color: {{ $session->type_color }}">
    {{-- En-tête --}}
    <header class="training-head card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <span class="training-type"><i class="fas {{ $session->type_icon }} me-1" aria-hidden="true"></i>{{ $session->type_label }}</span>
            <h2 class="h3 fw-bold mt-2 mb-1">{{ $session->title }}</h2>
            @if ($session->objective)
                <p class="lead mb-2">{{ $session->objective }}</p>
            @endif
            <ul class="list-inline text-muted mb-0 training-meta">
                <li class="list-inline-item">
                    <i class="far fa-calendar me-1" aria-hidden="true"></i>
                    {{ $session->type === 'maison' ? 'À faire avant le ' : '' }}{{ $session->session_date->translatedFormat('l j F Y') }}
                </li>
                @if ($session->time_range)
                    <li class="list-inline-item"><i class="far fa-clock me-1" aria-hidden="true"></i>{{ $session->time_range }}</li>
                @endif
                @if ($session->location)
                    <li class="list-inline-item"><i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i>{{ $session->location }}</li>
                @endif
                @if ($session->volume_label)
                    <li class="list-inline-item fw-semibold text-dark"><i class="fas fa-ruler-horizontal me-1" aria-hidden="true"></i>{{ $session->volume_label }}</li>
                @endif
            </ul>

            @if ($session->equipment)
                <p class="mb-0 mt-3">
                    <span class="small fw-semibold me-1">Matériel :</span>
                    @foreach ($session->equipment as $item)
                        <span class="badge rounded-pill training-chip">{{ $item }}</span>
                    @endforeach
                </p>
            @endif

            @if ($session->instructions)
                @if ($session->isInternal())<p class="small fw-semibold mt-3 mb-1">Consignes et observations</p>@endif
                <div class="training-instructions {{ $session->isInternal() ? '' : 'mt-3' }}">{{ $session->instructions }}</div>
            @endif
        </div>
    </header>

    @if ($session->isSwim())
        {{-- Programme natation --}}
        @forelse ($session->blocks() as $block)
            @php $distance = TS::blockDistance($block); @endphp
            <section class="card border-0 shadow-sm mb-3 training-block">
                <div class="card-header bg-white d-flex flex-wrap align-items-baseline justify-content-between gap-2">
                    <h3 class="h5 fw-bold mb-0">
                        {{ $block['kind'] ?: 'Bloc' }}@if (! empty($block['title'])) <span class="fw-normal">· {{ $block['title'] }}</span>@endif
                    </h3>
                    <span class="fw-semibold">
                        @if (($block['repeat'] ?? 1) > 1)<span class="badge bg-dark me-1">{{ $block['repeat'] }} fois</span>@endif
                        {{ number_format($distance, 0, ',', ' ') }} m
                    </span>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($block['lines'] ?? [] as $line)
                        <li class="list-group-item training-line">
                            <div class="tr-inline">
                                <span class="fw-bold training-line-main">{{ TS::lineLabel($line) }}</span>
                                @if (! empty($line['zone']))
                                    <span class="badge training-zone" style="background: {{ $zoneCouleurs[$line['zone']] ?? '#64748b' }}" title="{{ TS::ZONES[$line['zone']] ?? '' }}">{{ $line['zone'] }}</span>
                                @endif
                                @if (! empty($line['departure']))<span class="small"><i class="fas fa-stopwatch me-1" aria-hidden="true"></i>départ {{ $line['departure'] }}</span>@endif
                                @if (! empty($line['rest']))<span class="small"><i class="fas fa-pause me-1" aria-hidden="true"></i>récup {{ $line['rest'] }}</span>@endif
                                @if (! empty($line['equipment']))<span class="badge rounded-pill training-chip">{{ $line['equipment'] }}</span>@endif
                            </div>
                            @if (! empty($line['note']))
                                <div class="small text-muted mt-1">{{ $line['note'] }}</div>
                            @endif
                        </li>
                    @endforeach
                </ul>
                @if (! empty($block['note']))
                    <div class="card-footer bg-white small text-muted">{{ $block['note'] }}</div>
                @endif
            </section>
        @empty
            <p class="text-muted">Le programme de la séance n'est pas encore détaillé.</p>
        @endforelse

        @if ($session->total_distance)
            <p class="h5 fw-bold text-end mt-3">Total : {{ number_format($session->total_distance, 0, ',', ' ') }} m</p>
        @endif

        <p class="small text-muted training-zones">
            Zones : @foreach (TS::ZONES as $code => $label)<span class="me-2"><span class="badge training-zone" style="background: {{ $zoneCouleurs[$code] }}">{{ $code }}</span> {{ \Illuminate\Support\Str::after($label, '· ') }}</span>@endforeach
        </p>
    @elseif ($session->hasSteps())
        {{-- École de natation / divers : étapes --}}
        @forelse ($session->steps() as $step)
            <section class="card border-0 shadow-sm mb-3 training-exercise">
                <div class="row g-0">
                    @if (! empty($step['image']))
                        <div class="col-sm-4 col-lg-3">
                            <img src="{{ $step['image'] }}" alt="Illustration : {{ $step['title'] ?? 'étape ' . $loop->iteration }}" class="training-exercise-img" loading="lazy">
                        </div>
                    @endif
                    <div class="{{ ! empty($step['image']) ? 'col-sm-8 col-lg-9' : 'col-12' }}">
                        <div class="card-body">
                            <h3 class="h5 fw-bold mb-2"><span class="training-step">{{ $loop->iteration }}</span>{{ $step['title'] ?? 'Étape ' . $loop->iteration }}</h3>
                            @if (! empty($step['description']))
                                <p class="mb-2" style="white-space: pre-line">{{ $step['description'] }}</p>
                            @endif
                            @if (! empty($step['equipment']))
                                <p class="mb-2">
                                    <span class="small fw-semibold me-1">Matériel :</span>
                                    @foreach ($step['equipment'] as $item)<span class="badge rounded-pill training-chip">{{ $item }}</span> @endforeach
                                </p>
                            @endif
                            @if (! empty($step['organisation']))
                                <p class="mb-0 training-tips"><i class="fas fa-people-arrows me-1" aria-hidden="true"></i><strong>Organisation :</strong> {{ $step['organisation'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @empty
            <p class="text-muted">Aucune étape pour l'instant.</p>
        @endforelse
    @else
        {{-- Exercices PPG / maison --}}
        @if ($session->rounds > 1)
            <div class="alert alert-info tr-inline">
                <i class="fas fa-redo" aria-hidden="true"></i>
                <span><strong>Circuit : {{ $session->rounds }} tours.</strong> Enchaînez les exercices dans l'ordre, puis recommencez.</span>
            </div>
        @endif

        @forelse ($session->items() as $item)
            @php $exercise = $exercises->get($item['exercise_id']); @endphp
            <section class="card border-0 shadow-sm mb-3 training-exercise">
                <div class="row g-0">
                    <div class="col-sm-4 col-lg-3">
                        @if ($exercise?->image)
                            <img src="{{ $exercise->image }}" alt="Illustration : {{ $exercise->name }}" class="training-exercise-img" loading="lazy">
                        @else
                            <div class="training-exercise-img training-exercise-img--empty" aria-hidden="true"><i class="fas fa-dumbbell fa-2x"></i></div>
                        @endif
                    </div>
                    <div class="col-sm-8 col-lg-9">
                        <div class="card-body">
                            <h3 class="h5 fw-bold mb-1">
                                <span class="training-step">{{ $loop->iteration }}</span>{{ $exercise?->name ?? 'Exercice supprimé' }}
                            </h3>
                            @if ($exercise?->category)<span class="badge bg-light text-dark border mb-2">{{ $exercise->category }}</span>@endif

                            <ul class="list-unstyled training-effort mb-2">
                                <li><i class="fas fa-repeat me-2" aria-hidden="true"></i><strong>{{ TS::effortLabel($item) }}</strong></li>
                                @if (! empty($item['rest_sets']))<li><i class="fas fa-pause me-2" aria-hidden="true"></i>Repos entre les séries : <strong>{{ TS::formatSeconds($item['rest_sets']) }}</strong></li>@endif
                                @if (! empty($item['rest_after']))<li><i class="fas fa-hourglass-half me-2" aria-hidden="true"></i>Repos avant l'exercice suivant : <strong>{{ TS::formatSeconds($item['rest_after']) }}</strong></li>@endif
                                @if (! empty($item['load']))<li><i class="fas fa-weight-hanging me-2" aria-hidden="true"></i>Charge : <strong>{{ $item['load'] }}</strong></li>@endif
                            </ul>

                            @if (! empty($item['note']))
                                <p class="mb-2"><i class="fas fa-bullhorn me-2 text-primary" aria-hidden="true"></i>{{ $item['note'] }}</p>
                            @endif
                            @if ($exercise?->description)
                                <p class="small mb-2">{{ $exercise->description }}</p>
                            @endif
                            @if ($exercise?->tips)
                                <p class="small mb-2 training-tips"><i class="fas fa-lightbulb me-1" aria-hidden="true"></i><strong>Posture :</strong> {{ $exercise->tips }}</p>
                            @endif
                            @if ($exercise?->video_url)
                                <a href="{{ $exercise->video_url }}" target="_blank" rel="noopener" class="small d-print-none">
                                    <i class="fas fa-play-circle me-1" aria-hidden="true"></i>Voir la vidéo<span class="visually-hidden"> : {{ $exercise->name }} (nouvel onglet)</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @empty
            <p class="text-muted">Aucun exercice pour l'instant.</p>
        @endforelse

        @if ($session->total_duration)
            <p class="h5 fw-bold text-end mt-3">Durée estimée : ≈ {{ $session->total_duration }} min</p>
        @endif
    @endif
</article>

@once
@push('styles')
<style>
    .tr-inline { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; }
    .training-type { display: inline-block; padding: .25rem .75rem; border-radius: 999px; background: var(--type-color); color: #fff; font-weight: 600; font-size: .85rem; }
    .training-head { border-top: 5px solid var(--type-color) !important; }
    .training-meta .list-inline-item { margin-right: 1rem; }
    .training-instructions { white-space: pre-line; padding: .75rem 1rem; border-radius: 10px; background: #F0F9FF; border-left: 4px solid var(--type-color); }
    .training-chip { background: #F1F5F9; color: #314158; font-weight: 600; }
    .training-block .card-header { border-left: 5px solid var(--type-color); }
    .training-line-main { font-size: 1.05rem; }
    .training-zone { color: #fff; min-width: 2.2em; }
    .training-exercise-img { width: 100%; height: 100%; min-height: 180px; max-height: 260px; object-fit: cover; border-radius: .5rem 0 0 .5rem; }
    .training-exercise-img--empty { display: flex; align-items: center; justify-content: center; background: #F1F5F9; color: #90a1b9; }
    .training-step { display: inline-flex; align-items: center; justify-content: center; width: 1.8em; height: 1.8em; margin-right: .5rem; border-radius: 50%; background: var(--type-color); color: #fff; font-size: .85rem; }
    .training-effort li { margin-bottom: .25rem; }
    .training-tips { background: #FBF6EC; border-radius: 8px; padding: .5rem .75rem; }
    @media (max-width: 575.98px) {
        .training-exercise-img { border-radius: .5rem .5rem 0 0; max-height: 220px; }
    }
    @media print {
        nav, footer, .navbar, .shop-ticker, .d-print-none, .cookie-consent, .social-share, .skip-link { display: none !important; }
        body { background: #fff !important; }
        .training-sheet .card { box-shadow: none !important; border: 1px solid #ddd !important; break-inside: avoid; }
        .training-exercise-img { max-height: 160px; }
        a[href]::after { content: none !important; }
    }
</style>
@endpush
@endonce
