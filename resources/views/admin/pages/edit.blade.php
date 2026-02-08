@extends('layouts.admin')

@section('title', 'Modifier une page')
@section('page-title', 'Modifier la page')
@section('page-description', 'Modification de la page : ' . $page->title)

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
        @method('PUT')
        @include('admin.pages.partials.form', [
            'submitLabel' => 'Mettre à jour la page',
            'page' => $page,
            'categories' => $categories
        ])
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Quill Editor pour long_description
    if (typeof initQuillEditor === 'function') {
        initQuillEditor('#quill-editor-long-description', 'long_description');
    }

    // Auto-génération du slug
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.dataset.manuallyEdited) {
                const slug = this.value
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                slugInput.value = slug;
            }
        });

        slugInput.addEventListener('input', function() {
            this.dataset.manuallyEdited = 'true';
        });
    }

    // Aperçu d'image
    const imageInput = document.getElementById('pageImage');
    const imagePreview = document.getElementById('pageImagePreview');
    const imagePreviewContainer = document.getElementById('pageImagePreviewContainer');

    if (imageInput) {
        imageInput.addEventListener('input', function() {
            if (this.value) {
                imagePreview.src = this.value;
                imagePreviewContainer.classList.remove('d-none');
            } else {
                imagePreviewContainer.classList.add('d-none');
            }
        });
    }
});
</script>
@endpush
