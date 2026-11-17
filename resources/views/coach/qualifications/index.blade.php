@extends('layouts.coach')

@section('title', 'Nageurs qualifiés')

@section('content')
<div class="container-lg">
    <h1 class="h3 fw-bold mb-1"><i class="fas fa-medal text-primary me-2" aria-hidden="true"></i>Nageurs qualifiés</h1>
    <p class="text-muted">Choisissez une grille : la liste compare les meilleures performances enregistrées des nageurs aux temps de la grille (temps égal ou plus rapide = qualifié).</p>

    @if ($grids->isEmpty())
        <div class="alert alert-info">Aucune grille de qualification pour l'instant.
            @can('access-admin') <a href="{{ route('admin.qualifications.create') }}" class="alert-link">Créer une grille</a>.@endcan
        </div>
    @else
        <div class="row g-3">
            @foreach ($grids as $grid)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('coach.qualifications.show', $grid) }}" class="card border-0 shadow-sm h-100 text-decoration-none text-reset">
                        <div class="card-body">
                            <h2 class="h6 fw-bold mb-1">{{ $grid->name }}</h2>
                            <p class="small text-muted mb-2">{{ $grid->categories_count }} catégorie(s) · {{ $grid->times_count }} temps</p>
                            @if ($grid->period_label)<p class="small mb-0"><i class="far fa-calendar me-1" aria-hidden="true"></i>{{ ucfirst($grid->period_label) }}</p>@endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
