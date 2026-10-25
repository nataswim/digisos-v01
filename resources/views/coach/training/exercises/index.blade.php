@extends('layouts.coach')

@section('title', "Bibliothèque d'exercices")

@section('content')
<div class="container-lg">
    <a href="{{ route('coach.training.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Séances</a>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Bibliothèque d'exercices</h1>
            <p class="text-muted mb-0">Exercices de PPG et de séances à la maison, réutilisables dans toutes les séances.</p>
        </div>
        <a href="{{ route('coach.exercises.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2" aria-hidden="true"></i>Nouvel exercice</a>
    </div>

    <form method="GET" class="card border-0 shadow-sm mb-4" role="search">
        <div class="card-body d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label for="q" class="form-label small mb-1">Rechercher</label>
                <input type="search" id="q" name="q" value="{{ $search }}" class="form-control" placeholder="Nom ou description">
            </div>
            <div>
                <label for="categorie" class="form-label small mb-1">Catégorie</label>
                <select id="categorie" name="categorie" class="form-select">
                    <option value="">Toutes</option>
                    @foreach ($categories as $c)<option value="{{ $c }}" @selected($category === $c)>{{ $c }}</option>@endforeach
                </select>
            </div>
            <button class="btn btn-outline-primary">Filtrer</button>
        </div>
    </form>

    @if ($exercises->isEmpty())
        <div class="card border-0 shadow-sm"><div class="card-body text-center text-muted py-5">Aucun exercice{{ $search !== '' || $category ? ' pour ce filtre' : ' pour l\'instant' }}.</div></div>
    @else
        <div class="row g-3">
            @foreach ($exercises as $exercise)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="card border-0 shadow-sm h-100 {{ $exercise->is_active ? '' : 'opacity-50' }}">
                        @if ($exercise->image)
                            <img src="{{ $exercise->image }}" alt="" class="card-img-top" style="height: 160px; object-fit: cover" loading="lazy">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="height: 160px" aria-hidden="true"><i class="fas fa-dumbbell fa-2x"></i></div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h2 class="h6 fw-bold mb-1">{{ $exercise->name }}</h2>
                            <div class="mb-2">
                                @if ($exercise->category)<span class="badge bg-light text-dark border">{{ $exercise->category }}</span>@endif
                                @unless ($exercise->is_active)<span class="badge bg-secondary">Désactivé</span>@endunless
                            </div>
                            <p class="small text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit($exercise->description, 90) }}</p>
                            <div class="d-flex gap-1">
                                <a href="{{ route('coach.exercises.edit', $exercise) }}" class="btn btn-sm btn-outline-primary flex-grow-1">Modifier<span class="visually-hidden"> {{ $exercise->name }}</span></a>
                                <form method="POST" action="{{ route('coach.exercises.destroy', $exercise) }}" onsubmit="return confirm('Supprimer cet exercice ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer {{ $exercise->name }}</span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">{{ $exercises->links() }}</div>
    @endif
</div>
@endsection
