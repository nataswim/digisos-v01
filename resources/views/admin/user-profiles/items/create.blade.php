@extends('layouts.admin')

@section('title', 'Ajouter un élément — ' . $user->name)

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-plus-circle me-2 text-success"></i>Ajouter un élément de contenu
            </h4>
            <p class="text-muted mb-0">
                Fiche de <strong>{{ $user->name }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.user-profiles.show', $user) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la fiche
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('admin.user-profiles.items.store', $user) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">
                                Titre <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   maxlength="200"
                                   placeholder="Ex : Certifications, Expériences, Compétences..."
                                   required
                                   autofocus>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">
                                Description <span class="text-danger">*</span>
                            </label>
                            <textarea name="description"
                                      id="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="5"
                                      maxlength="5000"
                                      placeholder="Décrivez cet élément..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 5 000 caractères.</div>
                        </div>

                        <div class="mb-4" style="max-width:200px;">
                            <label for="sort_order" class="form-label fw-semibold">Ordre d'affichage</label>
                            <input type="number"
                                   name="sort_order"
                                   id="sort_order"
                                   class="form-control @error('sort_order') is-invalid @enderror"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   max="9999">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Les éléments sont affichés du plus petit au plus grand.</div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.user-profiles.show', $user) }}"
                               class="btn btn-outline-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Ajouter l'élément
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
