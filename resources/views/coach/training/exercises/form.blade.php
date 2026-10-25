@extends('layouts.coach')

@section('title', $exercise->exists ? "Modifier l'exercice" : 'Nouvel exercice')

@section('content')
@php $image = old('image', $exercise->image); @endphp
<div class="container-lg" style="max-width: 860px">
    <a href="{{ route('coach.exercises.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Bibliothèque</a>
    <h1 class="h3 fw-bold mb-4">{{ $exercise->exists ? "Modifier l'exercice" : 'Nouvel exercice' }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $exercise->exists ? route('coach.exercises.update', $exercise) : route('coach.exercises.store') }}" class="card border-0 shadow-sm">
        @csrf
        @if ($exercise->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            <div class="col-md-8">
                <label for="name" class="form-label fw-semibold">Nom <span class="text-danger" aria-hidden="true">*</span></label>
                <input type="text" id="name" name="name" required maxlength="255" class="form-control" value="{{ old('name', $exercise->name) }}" placeholder="Planche ventrale">
            </div>
            <div class="col-md-4">
                <label for="category" class="form-label fw-semibold">Catégorie</label>
                <input type="text" id="category" name="category" list="liste-categories" maxlength="100" class="form-control" value="{{ old('category', $exercise->category) }}">
                <datalist id="liste-categories">@foreach ($categories as $c)<option value="{{ $c }}">@endforeach</datalist>
            </div>

            <div class="col-12">
                <span class="form-label fw-semibold d-block mb-2">Image ou illustration</span>
                <div class="d-flex flex-wrap gap-3 align-items-start">
                    <img src="{{ $image }}" alt="" id="image-preview" class="rounded" style="width: 160px; height: 120px; object-fit: cover" @if (! $image) hidden @endif>
                    <div class="flex-grow-1" style="min-width: 240px">
                        <div class="input-group">
                            <input type="text" id="image" name="image" maxlength="500" class="form-control" value="{{ $image }}" placeholder="Choisir une image…" aria-label="Image de l'exercice">
                            <button type="button" class="btn btn-primary" onclick="openMediaSelector('image', 'image-preview')">
                                <i class="fas fa-images me-1" aria-hidden="true"></i>Médiathèque
                            </button>
                        </div>
                        <div class="form-text">Une photo ou un schéma de la position aide beaucoup les nageurs.</div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label for="description" class="form-label fw-semibold">Description</label>
                <textarea id="description" name="description" rows="3" maxlength="3000" class="form-control" placeholder="Comment réaliser l'exercice">{{ old('description', $exercise->description) }}</textarea>
            </div>
            <div class="col-12">
                <label for="tips" class="form-label fw-semibold">Conseils de posture</label>
                <textarea id="tips" name="tips" rows="2" maxlength="3000" class="form-control" placeholder="Dos droit, gainage du ventre, respiration…">{{ old('tips', $exercise->tips) }}</textarea>
            </div>
            <div class="col-md-8">
                <label for="video_url" class="form-label fw-semibold">Lien vidéo (facultatif)</label>
                <input type="url" id="video_url" name="video_url" maxlength="500" class="form-control" value="{{ old('video_url', $exercise->video_url) }}" placeholder="https://">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check form-switch mb-2">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $exercise->is_active))>
                    <label class="form-check-label" for="is_active">Proposé dans les séances</label>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white d-flex gap-2 justify-content-end">
            <a href="{{ route('coach.exercises.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button class="btn btn-primary"><i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var champ = document.getElementById('image'), apercu = document.getElementById('image-preview');
    function maj() { var u = champ.value.trim(); apercu.hidden = !u; if (u) apercu.src = u; }
    champ.addEventListener('input', maj);
    champ.addEventListener('change', maj);
});
</script>
@endpush
