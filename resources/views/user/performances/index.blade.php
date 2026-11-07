@extends('layouts.member')

@section('title', 'Mes performances')

@section('content')
<x-member.header
    title="Mes performances"
    icon="fa-stopwatch"
    lead="Meilleures performances, historique des temps et progression, tels qu'enregistrés par les entraîneurs.">
    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-light"><i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Mon espace</a>
</x-member.header>

<section class="py-5 bg-light">
    <div class="container-lg">
        @if ($swimmers->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-user-friends fa-2x text-primary mb-3" aria-hidden="true"></i>
                    <h2 class="h5 fw-bold">Aucun nageur rattaché à votre compte</h2>
                    <p class="text-muted mb-3">Demandez à l'entraîneur ou au secrétariat de rattacher votre enfant (ou vous-même) à ce compte.</p>
                    <a href="{{ route('contact') }}" class="btn btn-primary">Nous contacter</a>
                </div>
            </div>
        @else
            @if ($swimmers->count() > 1)
                <nav class="perf-chips mb-4" aria-label="Choisir le nageur">
                    @foreach ($swimmers as $s)
                        <a href="{{ route('user.performances.index', ['nageur' => $s->id]) }}" class="perf-chip {{ $s->id === $swimmer->id ? 'is-active' : '' }}"
                           @if ($s->id === $swimmer->id) aria-current="page" @endif>{{ $s->full_name }}</a>
                    @endforeach
                </nav>
            @endif
            <h2 class="h3 fw-bold mb-4">{{ $swimmer->full_name }}</h2>
            @include('performances._swimmer', ['chartRoute' => ['user.performances.index', ['nageur' => $swimmer->id]], 'coach' => false])
            <p class="small text-muted mt-3">MPP : meilleure performance personnelle. Les écarts comparent chaque temps à la MPP établie avant la course (négatif = plus rapide).</p>
        @endif
    </div>
</section>
@endsection
