@extends('layouts.public')

{{-- SEO Meta --}}
@section('title', $video->title)
@section('meta_description', $video->description ?? 'Regardez cette vidéo sur ' . config('app.name'))

{{-- Open Graph / Facebook --}}
@section('og_type', 'video.other')
@section('og_title', $video->title)
@section('og_description', $video->description ?? 'Regardez cette vidéo')
@section('og_url', route('public.videos.show', $video))
@if($video->thumbnail)
@section('og_image', $video->thumbnail)
@section('og_image_alt', $video->title)
@endif

{{-- Twitter Card --}}
@section('twitter_title', $video->title)
@section('twitter_description', $video->description ?? 'Regardez cette vidéo')
@if($video->thumbnail)
@section('twitter_image', $video->thumbnail)
@endif

@section('content')

<!-- Hero Section titre -->
<section class="nataswim-titre1 position-relative  text-white">
    <div class="py-5 container ">
        <div class="row align-items-center">
            <div class="col-lg mb-4 mb-lg-0 fade-in-up">
                <h1 class=" text-white mb-0">{{ $video->title }}</h1>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<section class="py-3 bg-aqua-light border-bottom">
    <div class="container-lg">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('public.videos.index') }}">
                        <i class="fas fa-home me-1"></i>Vidéos
                    </a>
                </li>
                @if($video->categories->count() > 0)
                <li class="breadcrumb-item">
                    <a href="{{ route('public.videos.category', $video->categories->first()) }}">
                        {{ $video->categories->first()->name }}
                    </a>
                </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">
                    {!! Str::limit($video->title, 50) !!}
                </li>
            </ol>
        </nav>
    </div>
</section>

<article class="py-4 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-12">

                <!-- Card 1: Métadonnées -->
                <div class="card-aqua mb-4 fade-in-up">
                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted">
                        @if($video->is_featured)
                        <span class="badge badge-warning px-3 py-2">
                            <i class="fas fa-star me-1"></i>En vedette
                        </span>
                        @endif

                        @if($video->visibility === 'authenticated')
                        <span class="badge badge-info px-3 py-2">
                            <i class="fas fa-lock me-1"></i>Membres
                        </span>
                        @endif

                        <span class="d-flex align-items-center">
                            <i class="fas fa-eye me-1"></i>
                            {{ number_format($video->views_count) }} vue{{ $video->views_count > 1 ? 's' : '' }}
                        </span>

                        @if($video->duration)
                        <span class="d-flex align-items-center">
                            <i class="fas fa-clock me-1"></i>
                            {{ $video->getFormattedDuration() }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Card 2: Lecteur vidéo -->
                <div class="card-aqua mb-4 p-0 fade-in-up" style="animation-delay: 0.1s;">
                    @if($contentVisible)
                    @if($video->type === 'upload' && $video->file_path)
                    <!-- Vidéo uploadée avec autoplay et loop -->
                    <video controls autoplay loop muted playsinline class="w-100 rounded-lg" style="max-height: 600px; background: #000;">
                        <source src="{{ asset('storage/' . $video->file_path) }}" type="{{ $video->mime_type }}">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                    @elseif($video->getEmbedUrl())
                    <!-- Vidéo externe (YouTube, Vimeo, Dailymotion) -->
                    <div class="ratio ratio-16x9 rounded-lg overflow-hidden">
                        <iframe src="{{ $video->getEmbedUrl() }}"
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            class="border-0">
                        </iframe>
                    </div>
                    @elseif($video->external_url)
                    <!-- Lien URL direct -->
                    <div class="p-5 text-center">
                        <i class="fas fa-link fa-3x text-muted mb-3"></i>
                        <p class="mb-3">Cette vidéo est hébergée sur un service externe.</p>
                        <a href="{{ $video->external_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-primary">
                            <i class="fas fa-external-link-alt me-2"></i>Ouvrir la vidéo
                        </a>
                    </div>
                    @else
                    <!-- Aucune source disponible -->
                    <div class="p-5 text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <p class="text-muted">Aucune source vidéo disponible.</p>
                    </div>
                    @endif
                    
                    @else
                    <!-- Message d'accès restreint -->
                    <div class="p-4">
                        <div class="alert alert-warning border-0 mb-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="alert-heading mb-2">
                                        <i class="fas fa-lock me-2"></i>Premium
                                    </h5>
                                    <p class="mb-3">
                                        Contenu premium réservé aux membres inscrits
                                    </p>
                                    @if(!auth()->check())
                                    {{-- Utilisateur non connecté --}}
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('register') }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-user-plus me-2"></i>Inscription 
                                        </a>
                                        <a href="{{ route('login') }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-sign-in-alt me-2"></i>Connection
                                        </a>
                                    </div>
                                    @else
                                    {{-- Utilisateur connecté --}}
                                    @if(auth()->user()->hasRole('visitor'))
                                    {{-- Utilisateur visitor : afficher le bouton Premium --}}
                                    <div class="d-flex flex-column gap-2">
                                        <p class="mb-2 text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Votre compte ne permet pas l'accès à ce contenu premium.
                                        </p>
                                        <a href="{{ route('payments.index') }}" 
                                           class="btn btn-warning d-inline-flex align-items-center justify-content-center gap-2 shadow-lg">
                                            <i class="fas fa-crown"></i>
                                            <span>Devenir Premium</span>
                                        </a>
                                    </div>
                                    @else
                                    {{-- Autre rôle sans accès --}}
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Votre compte ne permet pas l'accès à ce contenu premium.
                                    </p>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Card 3: Description -->
                @if($video->description)
                <div class="card-aqua mb-4 fade-in-up" style="animation-delay: 0.2s;">
                    <div class="content-display">
                        {!! $video->description !!}
                    </div>
                </div>
                @endif

                <!-- Card 4: Informations techniques -->
                @if($video->duration || $video->width || $video->height)
                <div class="card-aqua mb-4 fade-in-up" style="animation-delay: 0.3s;">
                    <div class="row g-3">
                        @if($video->duration)
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded bg-primary-lighter">
                                <span class="text-muted">
                                    <i class="fas fa-clock me-1"></i>Durée
                                </span>
                                <strong>{{ $video->getFormattedDuration() }}</strong>
                            </div>
                        </div>
                        @endif

                        @if($video->width && $video->height)
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded bg-info-lighter">
                                <span class="text-muted">
                                    <i class="fas fa-expand me-1"></i>Résolution
                                </span>
                                <strong>{{ $video->width }}×{{ $video->height }}</strong>
                            </div>
                        </div>
                        @endif

                        @if($video->file_size)
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded bg-success-lighter">
                                <span class="text-muted">
                                    <i class="fas fa-hdd me-1"></i>Taille
                                </span>
                                <strong>{{ number_format($video->file_size / 1024 / 1024, 2) }} MB</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Card 5: Catégories -->
                @if($video->categories->count() > 0)
                <div class="card-aqua mb-4 fade-in-up" style="animation-delay: 0.4s;">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($video->categories as $category)
                        <a href="{{ route('public.videos.category', $category) }}"
                            class="badge badge-m2pc badge-planning text-decoration-none">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Card 6: Actions de partage -->
                <div class="card-aqua mb-4 fade-in-up" style="animation-delay: 0.5s;">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" 
                                onclick="copyVideoLink()"
                                id="copyLinkBtn"
                                class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-link me-1"></i>Copier le lien
                        </button>
                        
                        @include('layouts.partials.social-share', [
                            'url' => route('public.videos.show', $video),
                            'title' => $video->title
                        ])
                    </div>
                </div>

            </div>
        </div>
    </div>
</article>

<!-- Vidéos similaires -->
@if(isset($relatedVideos) && $relatedVideos->count() > 0)
<section class="py-5 bg-secondary">
    <div class="container-lg">
        <div class="text-center mb-5">
        </div>

        <div class="row g-4">
            @foreach($relatedVideos as $relatedVideo)
            <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                <div class="card-aqua h-100 hover-lift">
                    @if($relatedVideo->thumbnail)
                    <div class="position-relative">
                        <img src="{{ $relatedVideo->thumbnail }}"
                            class="card-img-top"
                            style="height: 200px; object-fit: cover;"
                            alt="{{ $relatedVideo->title }}">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <div class="bg-danger bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-play text-white fs-5"></i>
                            </div>
                        </div>
                        @if($relatedVideo->duration)
                        <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark">
                            {{ $relatedVideo->getFormattedDuration() }}
                        </span>
                        @endif
                    </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ Str::limit($relatedVideo->title, 60) }}</h5>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($relatedVideo->views_count) }}
                            </small>
                            <a href="{{ route('public.videos.show', $relatedVideo) }}"
                                class="btn btn-sm btn-primary">
                                Regarder
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
/* Styles pour le contenu riche (description) */
.content-display {
    font-size: 1rem;
    line-height: 1.8;
    color: #4a5568;
}

.content-display h1,
.content-display h2,
.content-display h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
    line-height: 1.3;
    color: #2d3748;
}

.content-display h1 { font-size: 1.8rem; }
.content-display h2 { font-size: 1.5rem; }
.content-display h3 { font-size: 1.3rem; }

.content-display p {
    margin-bottom: 1.5rem;
    line-height: 1.8;
    text-align: justify;
}

.content-display ul,
.content-display ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
    line-height: 1.7;
}

.content-display li {
    margin-bottom: 0.5rem;
}

.content-display blockquote {
    border-left: 4px solid var(--color-primary);
    padding: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    background: #f7fafc;
    border-radius: 0.375rem;
    color: #2d3748;
}

.content-display img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 2rem 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: block;
    margin-left: auto;
    margin-right: auto;
}

.content-display pre {
    background: #1a202c;
    color: #e2e8f0;
    padding: 1.5rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 2rem 0;
    font-size: 0.875rem;
    line-height: 1.6;
}

.content-display code {
    background-color: #edf2f7;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    color: #d63384;
    font-family: 'Courier New', monospace;
}

.content-display table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.content-display th,
.content-display td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.content-display th {
    background-color: #f7fafc;
    font-weight: 600;
}

.content-display strong {
    font-weight: 600;
    color: #2d3748;
}

.content-display em {
    font-style: italic;
}

.content-display a {
    color: var(--color-primary);
    text-decoration: underline;
}

.content-display a:hover {
    color: var(--color-primary-dark);
}

/* Responsive pour le player vidéo */
@media (max-width: 991px) {
    video {
        max-height: 400px !important;
    }
}

@media (max-width: 768px) {
    video {
        max-height: 300px !important;
    }
    
    .content-display {
        font-size: 0.95rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
    /**
     * Copier le lien de la vidéo
     */
    function copyVideoLink() {
        const url = window.location.href;
        const btn = document.getElementById('copyLinkBtn');

        // Utiliser l'API Clipboard si disponible
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function() {
                // Succès
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Copié !';
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-success');

                setTimeout(function() {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-secondary');
                }, 2000);
            }).catch(function(err) {
                console.error('Erreur lors de la copie:', err);
                fallbackCopyToClipboard(url, btn);
            });
        } else {
            // Fallback pour les anciens navigateurs
            fallbackCopyToClipboard(url, btn);
        }
    }

    /**
     * Méthode fallback pour copier le lien
     */
    function fallbackCopyToClipboard(text, btn) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.top = '0';
        textArea.style.left = '0';
        textArea.style.opacity = '0';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Copié !';
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-success');

                setTimeout(function() {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-secondary');
                }, 2000);
            } else {
                alert('Impossible de copier le lien. Veuillez copier manuellement : ' + text);
            }
        } catch (err) {
            alert('Impossible de copier le lien. Veuillez copier manuellement : ' + text);
        }

        document.body.removeChild(textArea);
    }
</script>
@endpush