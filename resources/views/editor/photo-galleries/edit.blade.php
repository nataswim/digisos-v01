@extends('layouts.editor')

@section('title', 'Éditer la galerie')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
            <i class="fas fa-edit text-primary me-2"></i>
            Éditer : {{ $photoGallery->title }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('editor.photo-galleries.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
            <a href="{{ route('editor.photo-galleries.show', $photoGallery) }}" 
               class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>Voir
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-4">
    <div class="container-fluid">
        <form method="POST" 
              action="{{ route('editor.photo-galleries.update', $photoGallery) }}" 
              id="galleryForm">
            @csrf
            @method('PUT')
            
            @include('editor.photo-galleries.partials.form', [
                'gallery' => $photoGallery,
                'submitText' => 'Mettre à jour'
            ])
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/gallery-media-selector.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($photoGallery->photos->count() > 0)
        const existingPhotos = [
            @foreach($photoGallery->photos as $photo)
            {
                id: {{ $photo->id }},
                url: "{{ $photo->url }}",
                name: "{{ addslashes($photo->name) }}",
                caption: "{{ addslashes($photo->pivot->caption ?? '') }}",
                sort_order: {{ $photo->pivot->sort_order }}
            }{{ $loop->last ? '' : ',' }}
            @endforeach
        ];
        
        if (window.GalleryMediaSelector) {
            window.GalleryMediaSelector.loadExistingPhotos(existingPhotos);
        }
    @endif
});
</script>
@endpush