@extends('layouts.admin')

@section('title', 'Créer une galerie')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-0">
            <i class="fas fa-plus text-primary me-2"></i>
            Créer une galerie
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.photo-galleries.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-4">
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.photo-galleries.store') }}" id="galleryForm">
            @csrf
            
            @include('admin.photo-galleries.partials.form', [
                'gallery' => null,
                'submitText' => 'Créer la galerie'
            ])
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/gallery-media-selector.js') }}"></script>
@endpush