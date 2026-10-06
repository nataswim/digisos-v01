@extends('layouts.public')

@section('title', 'Recherche' . ($query ? ' : ' . $query : ''))

@section('content')
<div class="container py-5">
    <!-- En-tête de recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">
                @if($query)
                    Résultats pour "{{ $query }}"
                @else
                    Recherche
                @endif
            </h1>
            
            @if($query)
                <p class="text-muted">
                    {{ $totalResults }} résultat{{ $totalResults > 1 ? 's' : '' }} trouvé{{ $totalResults > 1 ? 's' : '' }}
                </p>
            @endif
        </div>
    </div>

    <!-- Formulaire de recherche -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            @include('public.partials.search-form')
        </div>
    </div>

    @if($query && $totalResults > 0)
        <!-- Articles -->
        @if($results['posts']->isNotEmpty())
            <section class="mb-5">
                <h2 class="h4 mb-3">
                    <i class="fas fa-newspaper text-primary me-2"></i>
                    Articles ({{ $results['posts']->count() }})
                </h2>
                <div class="row g-4">
                    @foreach($results['posts'] as $post)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                @if($post->image)
                                    <img src="{{ $post->image }}" 
                                         class="card-img-top" 
                                         alt="{{ $post->name }}"
                                         style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ $post->url }}" class="text-decoration-none text-dark">
                                            {{ $post->name }}
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small">
                                        {{ Str::limit(strip_tags($post->intro), 120) }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="{{ $post->url }}" class="btn btn-sm btn-outline-primary">
                                        Lire la suite
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Fiches -->
        @if($results['fiches']->isNotEmpty())
            <section class="mb-5">
                <h2 class="h4 mb-3">
                    <i class="fas fa-file-alt text-success me-2"></i>
                    Fiches ({{ $results['fiches']->count() }})
                </h2>
                <div class="row g-4">
                    @foreach($results['fiches'] as $fiche)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                @if($fiche->image)
                                    <img src="{{ $fiche->image }}" 
                                         class="card-img-top" 
                                         alt="{{ $fiche->title }}"
                                         style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ $fiche->url }}" class="text-decoration-none text-dark">
                                            {{ $fiche->title }}
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small">
                                        {{ Str::limit(strip_tags($fiche->short_description), 120) }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="{{ $fiche->url }}" class="btn btn-sm btn-outline-success">
                                        Voir la fiche
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Vidéos -->
        @if($results['videos']->isNotEmpty())
            <section class="mb-5">
                <h2 class="h4 mb-3">
                    <i class="fas fa-video text-warning me-2"></i>
                    Vidéos ({{ $results['videos']->count() }})
                </h2>
                <div class="row g-4">
                    @foreach($results['videos'] as $video)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                @if($video->thumbnail)
                                    <img src="{{ $video->thumbnail }}" 
                                         class="card-img-top" 
                                         alt="{{ $video->title }}"
                                         style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ route('public.videos.show', $video->slug) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ $video->title }}
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small">
                                        {{ Str::limit(strip_tags($video->description), 120) }}
                                    </p>
                                    @if($video->duration)
                                        <div class="mt-2">
                                            <span class="badge bg-dark">
                                                <i class="fas fa-clock"></i> {{ $video->formatted_duration }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="{{ route('public.videos.show', $video->slug) }}" 
                                       class="btn btn-sm btn-outline-warning">
                                        Voir la vidéo
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    @elseif($query && $totalResults === 0)
        <!-- Aucun résultat -->
        <div class="alert alert-info text-center">
            <i class="fas fa-search fa-3x mb-3"></i>
            <h4>Aucun résultat trouvé</h4>
            <p class="mb-0">Essayez avec d'autres mots-clés.</p>
        </div>
    @endif
</div>
@endsection