@extends('layouts.public')

@section('title', 'Assiduité aux entraînements')

@section('content')

<x-member.header
    title="Assiduité aux entraînements"
    icon="fa-clipboard-check"
    lead="Présences de vos nageurs aux séances de la saison, telles qu'enregistrées par les entraîneurs.">
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
                    <p class="text-muted mb-3">
                        Pour consulter les présences, demandez à l'entraîneur ou au secrétariat de rattacher votre enfant
                        (ou vous-même) à ce compte.
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-primary">Nous contacter</a>
                </div>
            </div>
        @else
            <p class="text-muted mb-4">
                Saison en cours : du {{ $from->format('d/m/Y') }} au {{ $to->format('d/m/Y') }}.
                Le taux de présence compte les séances où l'appel a été fait.
            </p>

            @foreach ($swimmers as $swimmer)
                @php
                    $c = $counts->get($swimmer->id);
                    $lignes = ($history->get($swimmer->id) ?? collect())->take(15);
                @endphp
                <section class="card border-0 shadow-sm mb-4" aria-labelledby="nageur-{{ $swimmer->id }}">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                            <div>
                                <h2 id="nageur-{{ $swimmer->id }}" class="h4 fw-bold mb-1">{{ $swimmer->full_name }}</h2>
                                <p class="text-muted mb-0">{{ $swimmer->groups->pluck('name')->join(', ') ?: 'Aucun groupe' }}</p>
                            </div>
                            <div class="text-end">
                                <div class="display-6 fw-bold text-primary">{{ isset($c['rate']) ? $c['rate'] . ' %' : '—' }}</div>
                                <div class="small text-muted">de présence</div>
                            </div>
                        </div>

                        <ul class="list-inline mb-3">
                            <li class="list-inline-item"><span class="badge bg-success">{{ $c['present'] ?? 0 }}</span> présent{{ ($c['present'] ?? 0) > 1 ? 's' : '' }}</li>
                            <li class="list-inline-item"><span class="badge bg-danger">{{ $c['absent'] ?? 0 }}</span> absent{{ ($c['absent'] ?? 0) > 1 ? 's' : '' }}</li>
                            <li class="list-inline-item"><span class="badge bg-warning text-dark">{{ $c['excuse'] ?? 0 }}</span> excusé{{ ($c['excuse'] ?? 0) > 1 ? 's' : '' }}</li>
                        </ul>

                        @if ($lignes->isEmpty())
                            <p class="text-muted mb-0">Aucune séance émargée pour l'instant cette saison.</p>
                        @else
                            <h3 class="h6 fw-bold">Dernières séances</h3>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <caption class="visually-hidden">Dernières séances de {{ $swimmer->full_name }}</caption>
                                    <thead><tr><th scope="col">Date</th><th scope="col">Séance</th><th scope="col">Présence</th></tr></thead>
                                    <tbody>
                                        @foreach ($lignes as $record)
                                            @php [$lettre, $couleur] = \App\Models\PresenceRecord::BADGES[$record->status] ?? ['?', 'secondary']; @endphp
                                            <tr>
                                                <td class="text-nowrap">{{ $record->session->date->translatedFormat('D d/m') }}</td>
                                                <td>{{ $record->session->label }} <span class="text-muted small">{{ $record->session->time_range }}</span></td>
                                                <td><span class="badge bg-{{ $couleur }} {{ $couleur === 'warning' ? 'text-dark' : '' }}">{{ $record->status_label }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </section>
            @endforeach

            <p class="small text-muted">
                Une absence à signaler ou une erreur ? Prévenez l'entraîneur du groupe ou
                <a href="{{ route('contact') }}">contactez le club</a>.
            </p>
        @endif
    </div>
</section>
@endsection
