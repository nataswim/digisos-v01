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


<!-- Section titre -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg mb-4 mb-lg-0">
                <h1 class="display-5 fw-bold mb-0">{{ $video->title }}</h1>


            </div>

        </div>
    </div>
</section>








<!-- Breadcrumb -->
<section class="py-3 bg-light border-bottom">
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

<article class="py-4">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-12">

                <!-- Card 1: Métadonnées -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap align-items-center gap-3 text-muted">
                            @if($video->is_featured)
                            <span class="badge bg-warning text-dark px-3 py-2">
                                <i class="fas fa-star me-1"></i>En vedette
                            </span>
                            @endif

                            @if($video->visibility === 'authenticated')
                            <span class="badge bg-info px-3 py-2">
                                <i class="fas fa-lock me-1"></i>Membres
                            </span>
                            @endif

                            <span class="d-flex align-items-center">
                                <i class="fas fa-eye me-1"></i>
                                12{{ number_format($video->views_count) }} vue{{ $video->views_count > 1 ? 's' : '' }}
                            </span>

                            @if($video->duration)
                            <span class="d-flex align-items-center">
                                <i class="fas fa-clock me-1"></i>
                                {{ $video->getFormattedDuration() }}
                            </span>
                            @endif
                        </div>

                      
                    </div>
                </div>

                <!-- Card 2: Lecteur vidéo -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        @if($contentVisible)
                        @if($video->type === 'upload' && $video->file_path)
                        <!-- Vidéo uploadée avec autoplay et loop -->
                        <video controls autoplay loop muted playsinline class="w-100" style="max-height: 600px; background: #000;">
                            <source src="{{ asset('storage/' . $video->file_path) }}" type="{{ $video->mime_type }}">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                        @elseif($video->getEmbedUrl())
                        <!-- Vidéo externe (YouTube, Vimeo, Dailymotion) -->
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $video->getEmbedUrl() }}"
                                allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                class="border-0">
                            </iframe>
                        </div>
                        @elseif($video->external_url)
                        <!-- Lien URL direct -->
                        <div class="p-4 text-center">
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
                        <div class="p-4 text-center">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <p class="text-muted">Aucune source vidéo disponible.</p>
                        </div>
                        @endif
                        
                        @else
<!-- Message d'accès restreint -->
<div class="p-4">
    <div class="alert alert-warning border-0">
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
                       class="btn btn-warning d-inline-flex align-items-center justify-content-center gap-2"
                       style="box-shadow: 0 2px 1px 0 rgba(0, 0, 0, 0.2), 0 8px 5px 0 rgba(0, 0, 0, 0.19);">
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
                </div>

                <!-- Card 3: Description -->
                @if($video->description)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="content-display fs-6 lh-lg">
                            {!! $video->description !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Card 4: Informations techniques -->
                @if($video->duration || $video->width || $video->height)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            @if($video->duration)
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                    </span>
                                    <strong>{{ $video->getFormattedDuration() }}</strong>
                                </div>
                            </div>
                            @endif

                            @if($video->width && $video->height)
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="fas fa-expand me-1"></i>
                                    </span>
                                    <strong>{{ $video->width }}x{{ $video->height }}px</strong>
                                </div>
                            </div>
                            @endif

                            @if($video->created_at)
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                    </span>
                                    <strong>{{ $video->created_at->diffForHumans() }}</strong>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Card 5: Partage et actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('public.videos.show', $video)) }}"
                                target="_blank"
                                class="btn btn-outline-secondary btn-sm"
                                rel="noopener noreferrer">
                                <i class="fab fa-facebook-f me-1"></i>Facebook
                            </a>

                            <!-- Twitter -->
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('public.videos.show', $video)) }}&text={{ urlencode($video->title) }}"
                                target="_blank"
                                class="btn btn-outline-secondary btn-sm"
                                rel="noopener noreferrer">
                                <i class="fab fa-twitter me-1"></i>Twitter
                            </a>

                            <!-- WhatsApp -->
                            <a href="https://wa.me/?text={{ urlencode($video->title . ' - ' . route('public.videos.show', $video)) }}"
                                target="_blank"
                                class="btn btn-outline-secondary btn-sm"
                                rel="noopener noreferrer">
                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                            </a>

                            <!-- Copier le lien -->
                            <button type="button"
                                class="btn btn-outline-secondary btn-sm"
                                onclick="copyVideoLink()"
                                id="copyLinkBtn">
                                <i class="fas fa-link me-1"></i>Copier le lien
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Vidéos similaires -->
                @if($relatedVideos->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-3">
                        <div class="row g-3">
                            @foreach($relatedVideos as $relatedVideo)
                            <div class="col-md-6 col-lg-4">
                                <a href="{{ route('public.videos.show', $relatedVideo) }}"
                                    class="card border hover-card h-100 text-decoration-none">
                                    @if($relatedVideo->thumbnail)
                                    <img src="{{ $relatedVideo->thumbnail }}"
                                        class="card-img-top"
                                        alt="{{ $relatedVideo->title }}"
                                        style="height: 150px; object-fit: cover;">
                                    @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                        style="height: 150px;">
                                        <i class="fas fa-video fa-3x text-muted"></i>
                                    </div>
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title text-dark mb-2">
                                            {!! Str::limit($relatedVideo->title, 50) !!}
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if($relatedVideo->duration)
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $relatedVideo->getFormattedDuration() }}
                                            </small>
                                            @endif
                                            <small class="text-muted">
                                                <i class="fas fa-eye me-1"></i>
                                                11{{ number_format($relatedVideo->views_count) }}
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Navigation et catégorie -->
                <div class="row g-4 mb-4">
                    @if($video->categories->count() > 0)
                    @php $firstCategory = $video->categories->first(); @endphp
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <a href="{{ route('public.videos.category', $firstCategory) }}"
                                    class="d-flex align-items-center text-decoration-none">
                                    @if($firstCategory->icon)
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 70px; height: 70px;">
                                        <i class="{{ $firstCategory->icon }} text-primary fs-3"></i>
                                    </div>
                                    @else
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 70px; height: 70px;">
                                        <i class="fas fa-folder text-primary fs-3"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-1 text-dark">{{ $firstCategory->name }}</h6>
                                        <small class="text-muted">Voir toutes les vidéos</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Boutons de navigation -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    @if($video->categories->count() > 0)
                                    <a href="{{ route('public.videos.category', $video->categories->first()) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour à {!! Str::limit($video->categories->first()->name, 30) !!}
                                    </a>
                                    @endif
                                    <a href="{{ route('public.videos.index') }}"
                                        class="btn btn-outline-secondary">
                                        <i class="fas fa-th me-2"></i>Toutes les vidéos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</article>




@endsection

@push('styles')
<style>
    /* Styles pour le contenu HTML enrichi */
    .content-display h1,
    .content-display h2,
    .content-display h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .content-display h1 {
        font-size: 1.8rem;
        color: #2d3748;
    }

    .content-display h2 {
        font-size: 1.5rem;
        color: #2d3748;
    }

    .content-display h3 {
        font-size: 1.3rem;
        color: #2d3748;
    }

    .content-display p {
        margin-bottom: 1.5rem;
        line-height: 1.8;
        text-align: justify;
        color: #4a5568;
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
        border-left: 4px solid #3182ce;
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
        color: #3182ce;
        text-decoration: underline;
    }

    .content-display a:hover {
        color: #2c5282;
    }

    /* Styles existants */
    .card {
        transition: box-shadow 0.2s ease;
    }

    /* Responsive pour le player vidéo */
    @media (max-width: 991px) {
        video {
            max-height: 400px !important;
        }
    }

    @media (max-width: 768px) {
        .display-5 {
            font-size: 1.75rem !important;
        }

        .d-flex.gap-3 {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.75rem !important;
        }

        video {
            max-height: 300px !important;
        }

        .content-display {
            font-size: 0.95rem;
        }
    }

    /* Animation pour les vidéos similaires */
    .hover-card {
        transition: transform 0.2s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
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