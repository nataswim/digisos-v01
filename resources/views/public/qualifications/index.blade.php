@extends('layouts.public')

@section('title', 'Grilles de qualification')
@section('meta_description', 'Les grilles de qualification suivies par les nageurs du Cercle des Nageurs du Bocage Bressuirais : temps à réaliser par catégorie d\'âge, en bassin de 25 m et de 50 m.')

@section('content')
<x-public.hero
    title="Grilles de qualification"
    eyebrow="Compétitions"
    icon="fa-stopwatch"
    lead="Les temps à réaliser pour se qualifier, par catégorie d'âge, Dames et Messieurs, en bassin de 25 m et de 50 m.">
    <a href="{{ route('competitions.index') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-trophy me-2" aria-hidden="true"></i>Calendrier des compétitions
    </a>
</x-public.hero>

<section class="py-5 bg-light">
    <div class="container-lg">
        @if ($grids->isEmpty())
            <p class="text-center text-muted mb-0">Aucune grille publiée pour le moment.</p>
        @else
            <div class="row g-4">
                @foreach ($grids as $grid)
                    <div class="col-md-6 col-lg-4">
                        <article class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <h2 class="h5 fw-bold mb-2">{{ $grid->name }}</h2>
                                @if ($grid->description)
                                    <p class="text-muted small mb-3">{{ \Illuminate\Support\Str::limit($grid->description, 160) }}</p>
                                @endif
                                <p class="small mb-3">
                                    <i class="fas fa-users me-1 text-primary" aria-hidden="true"></i>{{ $grid->categories_count }} catégorie(s) d'âge
                                    @if ($grid->period_label)<br><i class="far fa-calendar me-1 text-primary" aria-hidden="true"></i>Temps réalisés {{ $grid->period_label }}@endif
                                </p>
                                <a href="{{ route('qualifications.show', $grid->slug) }}" class="btn btn-primary mt-auto align-self-start">Voir la grille</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
