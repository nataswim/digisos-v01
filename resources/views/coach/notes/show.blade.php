@extends('layouts.coach')

@section('title', 'Suivi — ' . $swimmer->full_name)

@section('content')
<div class="container-lg">
    <a href="{{ route('coach.notes.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Suivi des nageurs
    </a>

    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
        @if ($swimmer->photo)
            <img src="{{ $swimmer->photo }}" alt="" width="64" height="64" class="rounded-circle object-fit-cover">
        @endif
        <div>
            <h1 class="h3 fw-bold mb-1">{{ $swimmer->full_name }}</h1>
            <p class="text-muted mb-0">
                @if ($swimmer->birth_year)Né(e) en {{ $swimmer->birth_year }} · @endif
                {{ $swimmer->groups->pluck('name')->join(', ') ?: 'Aucun groupe' }}
                @if ($swimmer->license_number) · Licence {{ $swimmer->license_number }}@endif
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-5">
            <form method="POST" action="{{ route('coach.notes.store', $swimmer) }}" class="card border-0 shadow-sm">
                @csrf
                <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Ajouter une note</h2></div>
                <div class="card-body">@include('coach.notes._form')</div>
                <div class="card-footer bg-white text-end">
                    <button class="btn btn-primary"><i class="fas fa-plus me-2" aria-hidden="true"></i>Ajouter</button>
                </div>
            </form>
        </div>

        <section class="col-lg-7" aria-labelledby="titre-historique">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <h2 id="titre-historique" class="h5 fw-bold mb-0">Historique ({{ $notes->count() }})</h2>
                <nav aria-label="Filtrer par type" class="d-flex flex-wrap gap-1">
                    <a href="{{ route('coach.notes.show', $swimmer) }}" class="btn btn-sm {{ $category ? 'btn-outline-secondary' : 'btn-secondary' }}">Toutes</a>
                    @foreach (\App\Models\SwimmerNote::CATEGORIES as $key => $label)
                        <a href="{{ route('coach.notes.show', [$swimmer, 'categorie' => $key]) }}"
                           class="btn btn-sm {{ $category === $key ? 'btn-secondary' : 'btn-outline-secondary' }}">{{ $label }}</a>
                    @endforeach
                </nav>
            </div>

            @forelse ($notes as $item)
                <article class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="badge bg-primary">{{ $item->category_label }}</span>
                            <time datetime="{{ $item->noted_on->format('Y-m-d') }}" class="fw-semibold">{{ $item->noted_on->format('d/m/Y') }}</time>
                            <small class="text-muted">{{ $item->author?->name ?? 'Compte supprimé' }}</small>
                            @if ($item->isEditableBy(auth()->user()))
                                <span class="ms-auto d-flex gap-1">
                                    <a href="{{ route('coach.notes.edit', $item) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier la note du {{ $item->noted_on->format('d/m/Y') }}</span>
                                    </a>
                                    <form method="POST" action="{{ route('coach.notes.destroy', $item) }}" onsubmit="return confirm('Supprimer cette note ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer la note du {{ $item->noted_on->format('d/m/Y') }}</span>
                                        </button>
                                    </form>
                                </span>
                            @endif
                        </div>
                        <p class="mb-0" style="white-space: pre-line">{{ $item->content }}</p>
                    </div>
                </article>
            @empty
                <p class="text-muted">Aucune note{{ $category ? ' de ce type' : '' }} pour l'instant.</p>
            @endforelse
        </section>
    </div>
</div>
@endsection
