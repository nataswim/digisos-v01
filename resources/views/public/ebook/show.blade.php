@extends('layouts.public')

{{-- SEO Meta --}}
@section('title', $downloadable->title . ' - ' . $category->name)
@section('meta_description', $downloadable->short_description ?? 'Téléchargez ' . $downloadable->title . ' - ' . $downloadable->format_display)

@section('content')

{{-- En-tête de section --}}
<section class="nataswim-titre1 position-relative  text-white">
    <div class="container-lg">
        <div class="text-center">
            <h1 class="text-white mb-0">{{ $downloadable->title }}</h1>
        </div>
    </div>
</section>

{{-- Breadcrumb --}}
<section class="py-3 bg-aqua-light border-bottom">
    <div class="container-lg">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-primary-lighter px-3 py-2 rounded">
                <li class="breadcrumb-item">
                    <a href="{{ route('ebook.index') }}" class="text-primary text-decoration-none">
                        <i class="fas fa-home me-1"></i>Espace Téléchargement
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('ebook.category', $category->slug) }}" class="text-primary text-decoration-none">
                        {{ $category->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active text-primary-dark" aria-current="page">
                    {!! Str::limit($downloadable->title, 50) !!}
                </li>
            </ol>
        </nav>
    </div>
</section>

{{-- Image de couverture --}}
@if($downloadable->cover_image)
<section class="py-4 bg-light">
    <div class="container-lg">
        <div class="text-center">
            <img src="{{ $downloadable->cover_image }}"
                alt="{{ $downloadable->title }}"
                class="img-fluid rounded shadow-aqua hover-scale"
                style="max-height: 600px; object-fit: contain; cursor: pointer;"
                onclick="openImageModal('{{ $downloadable->cover_image }}', '{{ addslashes($downloadable->title) }}')"
                title="Cliquez pour agrandir">
        </div>
    </div>
</section>
@endif

<article class="section">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">

                {{-- Card 1: Métadonnées --}}
                <div class="card-aqua mb-4">
                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted">
                        <span class="d-flex align-items-center">
                            <i class="fas fa-download me-1 text-primary"></i>
                            {{ number_format($downloadable->download_count) }} téléchargement{{ $downloadable->download_count > 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>

                {{-- Card 2: Description courte --}}
                @if($downloadable->short_description)
                <div class="card-aqua mb-4 bg-info-lighter border border-info">
                    <div class="lead text-info-dark">
                        {{ $downloadable->short_description }}
                    </div>
                </div>
                @endif

                {{-- Card 3: Description complète --}}
                @if($downloadable->long_description)
                <div class="card-aqua mb-4">
                    <h5 class="title-section mb-4">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Description détaillée
                    </h5>
                    <div class="content-display">
                        {!! $downloadable->long_description !!}
                    </div>
                </div>
                @endif

                {{-- Card 4: Action de téléchargement --}}
                <div class="card-aqua mb-4">
                    <h5 class="title-section mb-4">
                        <i class="fas fa-download me-2 text-success"></i>
                        Téléchargement
                    </h5>

                    @if($downloadable->canBeDownloadedBy(auth()->user()))
                        <div class="d-grid gap-2 d-md-flex mb-4">
                            <a href="{{ route('ebook.download', [$category->slug, $downloadable->slug]) }}"
                                class="btn btn-success btn-lg hover-lift flex-grow-1">
                                <i class="fas fa-download me-2"></i>Télécharger maintenant
                            </a>
                            <button class="btn btn-outline-primary btn-lg hover-lift" onclick="shareContent()">
                                <i class="fas fa-share-alt me-2"></i>Partager
                            </button>
                        </div>

                        <div class="alert alert-success-lighter border border-success">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            Vous avez accès à ce téléchargement. Cliquez sur le bouton ci-dessus pour commencer.
                        </div>
                    @else
                        {{-- Accès restreint --}}
                        <div class="alert alert-danger-lighter border border-danger">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <i class="fas fa-lock text-warning fs-2"></i>
                                </div>
                                <div class="col">
                                    <h5 class="fw-bold mb-2 text-danger">
                                        <i class="fas fa-crown me-1"></i>
                                        Accès restreint
                                    </h5>
                                    <p class="mb-3">
                                        {{ $downloadable->getAccessMessage(auth()->user()) }}
                                    </p>
                                    @if(!auth()->check())
                                        {{-- Utilisateur non connecté --}}
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('register') }}" class="btn btn-warning hover-lift">
                                                <i class="fas fa-user-plus me-2"></i>Inscription
                                            </a>
                                            <a href="{{ route('login') }}" class="btn btn-outline-warning hover-lift">
                                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                            </a>
                                        </div>
                                    @elseif(auth()->user()->hasRole('visitor'))
                                        {{-- Utilisateur visitor : Bouton Premium --}}
                                        <a href="{{ route('payments.index') }}" 
                                           class="btn btn-warning hover-lift shadow-lg">
                                            <i class="fas fa-crown me-2"></i>
                                            Devenir Premium
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Card 5: Informations du fichier --}}
                <div class="card-aqua mb-4">
                    <h5 class="title-section mb-4">
                        <i class="fas fa-info me-2 text-primary"></i>
                        Informations du fichier
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">
                                    <i class="fas fa-folder me-1"></i>Catégorie:
                                </span>
                                <strong>{{ $category->name }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">
                                    <i class="fas fa-file me-1"></i>Format:
                                </span>
                                <strong>{{ $downloadable->format_display }}</strong>
                            </div>
                        </div>
                        @if($downloadable->file_size)
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">
                                    <i class="fas fa-hdd me-1"></i>Taille:
                                </span>
                                <strong>{{ $downloadable->file_size }}</strong>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">
                                    <i class="fas fa-download me-1"></i>Téléchargements:
                                </span>
                                <strong>{{ number_format($downloadable->download_count) }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>Vérifié le:
                                </span>
                                <strong>{{ $downloadable->created_at->format('d F Y') }}</strong>
                            </div>
                        </div>
                        @if($downloadable->creator)
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">
                                    <i class="fas fa-user me-1"></i>Conseillé par:
                                </span>
                                <strong>{{ $downloadable->creator->name }}</strong>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="text-muted">
                                    <i class="fas fa-edit me-1"></i>Mise à jour:
                                </span>
                                <strong>{{ $downloadable->updated_at->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section Navigation --}}
                <div class="row g-4 mb-4">
                    {{-- Catégorie --}}
                    <div class="col-md-6">
                        <div class="card-aqua h-100">
                            <h5 class="title-section mb-3">
                                <i class="fas fa-folder me-2"></i>Catégorie
                            </h5>
                            <a href="{{ route('ebook.category', $category->slug) }}"
                                class="d-flex align-items-center text-decoration-none hover-lift">
                                @if($category->image)
                                <img src="{{ $category->image }}"
                                    class="rounded me-3 shadow-sm"
                                    style="width: 70px; height: 70px; object-fit: cover;"
                                    alt="{{ $category->name }}">
                                @else
                                <div class="bg-primary-lighter rounded d-flex align-items-center justify-content-center me-3"
                                    style="width: 70px; height: 70px;">
                                    <i class="fas fa-folder text-primary fs-3"></i>
                                </div>
                                @endif
                                <div>
                                    <h6 class="mb-1 text-dark">{{ $category->name }}</h6>
                                    <small class="text-muted">Voir tous les téléchargements</small>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Boutons de navigation --}}
                    <div class="col-md-6">
                        <div class="card-aqua h-100">
                            <h5 class="title-section mb-3">
                                Plus
                            </h5>
                            <div class="d-grid gap-2">
                                <a href="{{ route('ebook.category', $category->slug) }}"
                                    class="btn btn-primary hover-lift">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à {!! Str::limit($category->name, 30) !!}
                                </a>
                                <a href="{{ route('ebook.index') }}"
                                    class="btn btn-outline-secondary hover-lift">
                                    <i class="fas fa-th me-2"></i>Toutes les catégories
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ressources similaires --}}
                @if($relatedDownloadables->count() > 0)
                <div class="card-aqua">
                    <h5 class="title-section mb-4">
                        <i class="fas fa-star me-2"></i>Ressources similaires
                    </h5>
                    <div class="row g-3">
                        @foreach($relatedDownloadables as $related)
                            <div class="col-md-6 col-lg-4 fade-in-up">
                                <a href="{{ route('ebook.show', [$related->category->slug, $related->slug]) }}"
                                    class="card-aqua h-100 text-decoration-none hover-lift">
                                    <div class="position-relative mb-3">
                                        @if($related->cover_image)
                                            <img src="{{ $related->cover_image }}"
                                                class="w-100 rounded"
                                                style="height: 150px; object-fit: cover;"
                                                alt="{{ $related->title }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                style="height: 150px;">
                                                <i class="fas fa-file-{{ $related->format === 'pdf' ? 'pdf' : 'alt' }} fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <span class="badge badge-primary position-absolute top-0 start-0 m-2">
                                            {{ strtoupper($related->format) }}
                                        </span>
                                    </div>
                                    <h6 class="fw-bold mb-2 text-dark">{{ $related->title }}</h6>
                                    <p class="text-muted small mb-2">
                                        {!! Str::limit($related->short_description, 80) !!}
                                    </p>
                                    <div class="d-flex align-items-center gap-2 text-muted small">
                                        <i class="fas fa-download"></i>
                                        {{ $related->download_count }}
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</article>

{{-- Modal pour l'image --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center">
                <button type="button" class="btn btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-lg" 
                        style="z-index: 1060;" 
                        onclick="closeImageModal()">
                </button>
                <img id="modalImage" src="" alt="" class="img-fluid rounded shadow-aqua-lg">
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function shareContent() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $downloadable->title }}',
            text: '{{ $downloadable->short_description ?? "Découvrez cette ressource" }}',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('Lien copié dans le presse-papiers !');
        });
    }
}

function openImageModal(imageUrl, imageTitle) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    modalImage.src = imageUrl;
    modalImage.alt = imageTitle;

    modal.style.display = 'block';
    modal.classList.add('show');
    document.body.classList.add('modal-open');

    if (!document.querySelector('.modal-backdrop')) {
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.style.zIndex = '1040';
        document.body.appendChild(backdrop);
    }
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.body.classList.remove('modal-open');

    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.remove();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imageModal');
    const closeBtn = modal.querySelector('.btn-close');

    if (closeBtn) {
        closeBtn.addEventListener('click', closeImageModal);
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal || e.target.classList.contains('modal-content')) {
            closeImageModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
});
</script>
@endpush