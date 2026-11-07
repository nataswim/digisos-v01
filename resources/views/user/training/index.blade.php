@extends('layouts.member')

@section('title', 'Mes entraînements')

@section('content')
<x-member.header
    title="Mes entraînements"
    icon="fa-person-swimming"
    lead="Les séances préparées par les entraîneurs : programme en bassin, préparation physique et séances à faire à la maison.">
    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-light">
        <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Mon espace
    </a>
</x-member.header>

<section class="py-5 bg-light">
    <div class="container-lg">
        @if ($swimmers->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-user-friends fa-2x text-primary mb-3" aria-hidden="true"></i>
                    <h2 class="h5 fw-bold">Aucun nageur rattaché à votre compte</h2>
                    <p class="text-muted mb-3">Pour recevoir les séances, demandez à l'entraîneur ou au secrétariat de rattacher votre enfant (ou vous-même) à ce compte.</p>
                    <a href="{{ route('contact') }}" class="btn btn-primary">Nous contacter</a>
                </div>
            </div>
        @else
            @if ($next)
                <a href="{{ route('user.training.show', $next) }}" class="card border-0 shadow-sm mb-4 text-decoration-none tr-next" style="border-left: 6px solid {{ $next->type_color }} !important">
                    <span class="card-body d-flex flex-wrap gap-3 justify-content-between">
                        <span>
                            <span class="small text-uppercase fw-bold text-muted d-block">Prochaine séance</span>
                            <span class="h5 fw-bold text-dark d-block mb-0">{{ $next->title }}</span>
                            <span class="text-muted">{{ $next->type_label }} · {{ $next->type === 'maison' ? 'avant le ' : '' }}{{ $next->session_date->translatedFormat('l j F') }}@if ($next->time_range) · {{ $next->time_range }}@endif</span>
                        </span>
                        <span class="btn btn-primary align-self-center">Voir la séance <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i></span>
                    </span>
                </a>
            @endif

            <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                <h2 class="h4 fw-bold mb-0">Semaine du {{ $monday->translatedFormat('j F') }}</h2>
                <nav class="btn-group" aria-label="Changer de semaine">
                    <a href="{{ route('user.training.index', ['semaine' => $monday->copy()->subWeek()->toDateString()]) }}" class="btn btn-outline-primary"><i class="fas fa-chevron-left" aria-hidden="true"></i><span class="visually-hidden">Semaine précédente</span></a>
                    <a href="{{ route('user.training.index') }}" class="btn btn-outline-primary">Cette semaine</a>
                    <a href="{{ route('user.training.index', ['semaine' => $monday->copy()->addWeek()->toDateString()]) }}" class="btn btn-outline-primary"><i class="fas fa-chevron-right" aria-hidden="true"></i><span class="visually-hidden">Semaine suivante</span></a>
                </nav>
            </div>

            <ol class="list-unstyled tr-week">
                @foreach ($days as $day)
                    @php $jour = $sessions->get($day->toDateString(), collect()); @endphp
                    <li class="tr-day {{ $day->isToday() ? 'tr-day--today' : '' }}">
                        <div class="tr-day-name">
                            <span class="fw-bold text-capitalize">{{ $day->translatedFormat('l') }}</span>
                            <span class="text-muted small">{{ $day->format('d/m') }}</span>
                            @if ($day->isToday())<span class="badge bg-primary">Aujourd'hui</span>@endif
                        </div>
                        <div class="tr-day-sessions">
                            @forelse ($jour as $session)
                                <a href="{{ route('user.training.show', $session) }}" class="tr-session text-decoration-none" style="--type-color: {{ $session->type_color }}">
                                    <span class="tr-session-type"><i class="fas {{ $session->type_icon }}" aria-hidden="true"></i> {{ $session->type_label }}</span>
                                    <span class="fw-bold d-block text-dark">{{ $session->title }}</span>
                                    <span class="small text-muted">
                                        @if ($session->time_range){{ $session->time_range }}@endif
                                        @if ($session->volume_label) · {{ $session->volume_label }}@endif
                                        @if ($swimmers->count() > 1) · {{ $session->swimmers->pluck('first_name')->join(', ') }}@endif
                                    </span>
                                </a>
                            @empty
                                <span class="text-muted small">—</span>
                            @endforelse
                        </div>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .tr-week { display: grid; gap: .5rem; }
    .tr-day { display: grid; grid-template-columns: 140px 1fr; gap: 1rem; padding: .75rem 1rem; background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
    .tr-day--today { outline: 2px solid #0090D4; }
    .tr-day-name { display: flex; flex-direction: column; gap: .15rem; }
    .tr-day-sessions { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: .5rem; align-content: center; }
    .tr-session { display: block; padding: .6rem .8rem; border-radius: 10px; border-left: 5px solid var(--type-color); background: #F8FAFC; transition: background .15s; }
    .tr-session:hover, .tr-session:focus { background: #F0F9FF; }
    .tr-session-type { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: var(--type-color); }
    @media (max-width: 575.98px) { .tr-day { grid-template-columns: 1fr; gap: .5rem; } .tr-day-name { flex-direction: row; align-items: baseline; gap: .5rem; } }
</style>
@endpush
