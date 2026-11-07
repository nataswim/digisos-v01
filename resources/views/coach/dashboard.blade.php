@extends('layouts.coach')

@section('title', 'Tableau de bord')

@section('content')
<div class="container-lg">
    @include('messages._dashboard', ['index' => 'coach.messages.index', 'show' => 'coach.messages.show'])
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Bonjour {{ $user->first_name ?: $user->name }}</h1>
            <p class="text-muted mb-0">Espace réservé aux coachs et aux administrateurs du club.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('coach.presence.index') }}" class="btn btn-primary">
                <i class="fas fa-clipboard-check me-2" aria-hidden="true"></i>Faire l'appel
            </a>
            <a href="{{ route('coach.assiduity.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-chart-column me-2" aria-hidden="true"></i>Assiduité
            </a>
            <a href="{{ route('coach.documents.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-upload me-2" aria-hidden="true"></i>Ajouter un document
            </a>
        </div>
    </div>

    @php
        $pluriel = fn (int $n, string $un, string $plusieurs) => $n . ' ' . ($n > 1 ? $plusieurs : $un);
    @endphp
    @include('layouts.partials.espace-accueil-tuiles', ['titre' => 'Accès rapides', 'masquerTitre' => true, 'tuiles' => [
        ['icon' => 'fa-dumbbell', 'titre' => 'Séances', 'texte' => 'Natation, PPG et séances à la maison.', 'route' => 'coach.training.index', 'badge' => $pluriel($counts['training'], 'cette semaine', 'cette semaine')],
        ['icon' => 'fa-person-swimming', 'titre' => 'Nageurs', 'texte' => 'Fiches des nageurs du planning.', 'route' => 'coach.planning.swimmers.index', 'badge' => $pluriel($counts['swimmers'], 'nageur actif', 'nageurs actifs')],
        ['icon' => 'fa-trophy', 'titre' => 'Compétitions', 'texte' => 'Programmes, convocations et réponses des familles.', 'route' => 'coach.competitions.index', 'badge' => $pluriel($counts['competitions'], 'à venir', 'à venir')],
        ['icon' => 'fa-folder-open', 'titre' => 'Documents', 'texte' => 'Documents réservés aux coachs.', 'route' => 'coach.documents.index', 'badge' => $pluriel($counts['documents'], 'document', 'documents')],
    ]])

    <div class="row g-4">
        {{-- Prochaines compétitions --}}
        <section class="col-lg-4" aria-labelledby="titre-competitions">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h2 id="titre-competitions" class="h6 fw-bold mb-0"><i class="fas fa-trophy me-2 text-primary" aria-hidden="true"></i>Prochaines compétitions</h2>
                    <a href="{{ route('coach.competitions.index') }}" class="small">Tout voir</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($competitions as $competition)
                        <li class="list-group-item">
                            <div class="fw-semibold">{{ $competition->title }}</div>
                            <div class="small text-muted mb-2">
                                {{ $competition->starts_on?->translatedFormat('d M Y') }}@if ($competition->city) · {{ $competition->city }}@endif
                            </div>
                            <a href="{{ route('coach.competitions.entries.index', $competition) }}" class="btn btn-sm btn-outline-primary">
                                Convocations ({{ $competition->attendances_count }})<span class="visually-hidden"> : {{ $competition->title }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">Aucune compétition à venir.</li>
                    @endforelse
                </ul>
            </div>
        </section>

        {{-- Dernières notes --}}
        <section class="col-lg-4" aria-labelledby="titre-notes">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h2 id="titre-notes" class="h6 fw-bold mb-0"><i class="fas fa-clipboard-list me-2 text-primary" aria-hidden="true"></i>Dernières notes de suivi</h2>
                    <a href="{{ route('coach.notes.index') }}" class="small">Tout voir</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($notes as $note)
                        <li class="list-group-item">
                            <a href="{{ route('coach.notes.show', $note->planning_swimmer_id) }}" class="fw-semibold text-decoration-none">
                                {{ $note->swimmer?->full_name }}
                            </a>
                            <div class="small text-muted">
                                {{ $note->noted_on->format('d/m/Y') }} · {{ $note->category_label }}@if ($note->author) · {{ $note->author->name }}@endif
                            </div>
                            <div class="small">{{ Str::limit($note->content, 90) }}</div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">Aucune note pour l'instant.</li>
                    @endforelse
                </ul>
            </div>
        </section>

        {{-- Derniers documents --}}
        <section class="col-lg-4" aria-labelledby="titre-documents">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h2 id="titre-documents" class="h6 fw-bold mb-0"><i class="fas fa-folder-open me-2 text-primary" aria-hidden="true"></i>Derniers documents</h2>
                    <a href="{{ route('coach.documents.index') }}" class="small">Tout voir</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($documents as $document)
                        <li class="list-group-item d-flex align-items-center gap-2">
                            <i class="fas {{ $document->icon }} text-primary" aria-hidden="true"></i>
                            <a href="{{ route('coach.documents.download', $document) }}" class="text-decoration-none"
                               @unless ($document->hasFile()) target="_blank" rel="noopener" @endunless>{{ $document->title }}</a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">Aucun document pour l'instant.</li>
                    @endforelse
                </ul>
            </div>
        </section>
    </div>
</div>
@endsection
