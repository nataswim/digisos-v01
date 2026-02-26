@extends('layouts.public')

@section('title', $photoGallery->meta_title ?: $photoGallery->title)

@section('meta_description', $photoGallery->meta_description ?: Str::limit($photoGallery->description, 160))

@section('meta_keywords', $photoGallery->meta_keywords)

@section('content')

<!-- Section Hero -->
<section>
    <div class="nataswim-titre1 position-relative ">
        <div class="container-lg">
            <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3 text-white">{{ $photoGallery->title }}</h1>
        
        @if($photoGallery->description)
            <p class="lead mb-4 text-white">{{ $photoGallery->description }}</p>
        @endif

        <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
            <span class="badge bg-warning p-2 text-white">
                <i class="fas fa-images me-1"></i>
                {{ $photoGallery->photos->count() }} 
            </span>
            <span class="text-white">
                <i class="far fa-calendar me-1"></i>
                {{ $photoGallery->published_at->format('d F Y') }}
            </span>
            @if($photoGallery->visibility === 'authenticated')
                <span class="badge bg-warning p-2">
                    <i class="fas fa-lock me-1"></i>
                    Réservé aux membres
                </span>
            @endif
        </div>
        </div>
    </div>
</section>



<div class="container py-5">
    {{-- Grille de photos --}}
    @if($photoGallery->photos->count() > 0)
        <div class="row g-4" id="galleryGrid">
            @foreach($photoGallery->photos as $index => $photo)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-item position-relative">
                        <a href="{{ $photo->url }}" 
                           class="d-block gallery-link"
                           data-lightbox="gallery-{{ $photoGallery->id }}"
                           data-title="{{ $photo->pivot->caption ?: $photo->name }}"
                           data-index="{{ $index }}">
                            <img src="{{ $photo->url }}" 
                                 alt="{{ $photo->pivot->caption ?: $photo->name }}"
                                 class="img-fluid rounded shadow-sm"
                                 style="width: 100%; height: 250px; object-fit: cover;">
                            
                            <div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded">
                                <i class="fas fa-search-plus fa-2x text-white"></i>
                            </div>
                            
                            @if($photo->pivot->caption)
                                <div class="gallery-caption position-absolute bottom-0 start-0 end-0 p-3 text-white">
                                    {{ $photo->pivot->caption }}
                                </div>
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-images fa-4x text-muted opacity-50 mb-4"></i>
            <h5 class="text-muted">Aucune photo dans cette galerie</h5>
        </div>
    @endif

    {{-- Galeries similaires --}}
    @if($relatedGalleries->count() > 0)
        <div class="mt-5 pt-5 border-top">
            <h2 class="h3 fw-bold mb-4">
                <i class="fas fa-images text-primary me-2"></i>
                Autres galeries
            </h2>
            
            <div class="row g-4">
                @foreach($relatedGalleries as $related)
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('galleries.show', $related) }}" 
                           class="card border-0 shadow-sm h-100 text-decoration-none hover-lift">
                            @if($related->coverImage)
                                <img src="{{ $related->coverImage->url }}" 
                                     alt="{{ $related->title }}"
                                     class="card-img-top"
                                     style="height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="height: 180px;">
                                    <i class="fas fa-images fa-3x text-muted opacity-50"></i>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-dark bg-opacity-75">
                                    {{ $related->photos_count }}
                                </span>
                            </div>
                            
                            <div class="card-body p-3">
                                <h6 class="card-title fw-bold mb-1">{{ $related->title }}</h6>
                                <small class="text-muted">
                                    {{ $related->published_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- Lightbox Modal --}}
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="lightboxTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 position-relative">
                <img id="lightboxImage" 
                     src="" 
                     alt=""
                     class="img-fluid w-100"
                     style="max-height: 80vh; object-fit: contain;">
                
                {{-- Navigation --}}
                <button class="btn btn-light position-absolute top-50 start-0 translate-middle-y ms-3"
                        id="lightboxPrev"
                        style="z-index: 1;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-light position-absolute top-50 end-0 translate-middle-y me-3"
                        id="lightboxNext"
                        style="z-index: 1;">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                {{-- Compteur --}}
                <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                    <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2" id="lightboxCounter"></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.gallery-item {
    overflow: hidden;
    border-radius: 0.5rem;
}

.gallery-link {
    display: block;
    position: relative;
    overflow: hidden;
}

.gallery-overlay {
    background: rgba(0, 0, 0, 0);
    transition: background 0.3s ease;
    opacity: 0;
}

.gallery-link:hover .gallery-overlay {
    background: rgba(0, 0, 0, 0.6);
    opacity: 1;
}

.gallery-caption {
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    font-size: 0.875rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-link:hover .gallery-caption {
    opacity: 1;
}

.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
}

#lightboxModal .modal-dialog {
    max-width: 90vw;
}

#lightboxImage {
    background: #000;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const galleryLinks = document.querySelectorAll('.gallery-link');
    const lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxCounter = document.getElementById('lightboxCounter');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');
    
    let currentIndex = 0;
    const photos = @json($photoGallery->photos->map(function($photo) {
        return [
            'url' => $photo->url,
            'title' => $photo->pivot->caption ?: $photo->name,
        ];
    })->values());

    // Ouvrir le lightbox
    galleryLinks.forEach((link, index) => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            currentIndex = index;
            showPhoto(currentIndex);
            lightboxModal.show();
        });
    });

    // Navigation
    lightboxPrev.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + photos.length) % photos.length;
        showPhoto(currentIndex);
    });

    lightboxNext.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % photos.length;
        showPhoto(currentIndex);
    });

    // Navigation clavier
    document.addEventListener('keydown', function(e) {
        const modalElement = document.getElementById('lightboxModal');
        if (modalElement.classList.contains('show')) {
            if (e.key === 'ArrowLeft') {
                lightboxPrev.click();
            } else if (e.key === 'ArrowRight') {
                lightboxNext.click();
            } else if (e.key === 'Escape') {
                lightboxModal.hide();
            }
        }
    });

    function showPhoto(index) {
        const photo = photos[index];
        lightboxImage.src = photo.url;
        lightboxImage.alt = photo.title;
        lightboxTitle.textContent = photo.title;
        lightboxCounter.textContent = `${index + 1} / ${photos.length}`;
        
        // Désactiver les boutons aux extrémités si nécessaire
        lightboxPrev.disabled = false;
        lightboxNext.disabled = false;
    }
});
</script>
@endpush
