@extends('layouts.admin')

@section('title', 'Modifier l\'élément — ' . $user->name)

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-edit me-2 text-primary"></i>Modifier l'élément de contenu
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

            {{-- ============================================================
                 FORMULAIRE DE MODIFICATION — seul <form> dans la carte
            ============================================================ --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form id="form-update"
                          method="POST"
                          action="{{ route('admin.user-profiles.items.update', [$user, $item]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">
                                Titre <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $item->title) }}"
                                   maxlength="200"
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
                                      required>{{ old('description', $item->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 5 000 caractères.</div>
                        </div>

                        <div class="mb-4" style="max-width:200px;">
                            <label for="sort_order" class="form-label fw-semibold">
                                Ordre d'affichage
                            </label>
                            <input type="number"
                                   name="sort_order"
                                   id="sort_order"
                                   class="form-control @error('sort_order') is-invalid @enderror"
                                   value="{{ old('sort_order', $item->sort_order) }}"
                                   min="0"
                                   max="9999">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Les éléments sont affichés du plus petit au plus grand.</div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between align-items-center pt-2">

                            {{-- Bouton supprimer — ouvre la modale, ne soumet PAS ce form --}}
                            <button type="button"
                                    class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDelete">
                                <i class="fas fa-trash me-1"></i>Supprimer
                            </button>

                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.user-profiles.show', $user) }}"
                                   class="btn btn-outline-secondary">
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer
                                </button>
                            </div>

                        </div>

                    </form>
                    {{-- FIN form-update --}}

                </div>
            </div>
            {{-- FIN carte --}}

        </div>
    </div>
</div>

{{-- ============================================================
     FORMULAIRE DELETE — strictement EN DEHORS de tout autre <form>
     Le bouton "form=" pointe sur cet id
============================================================ --}}
<form id="form-delete"
      method="POST"
      action="{{ route('admin.user-profiles.items.destroy', [$user, $item]) }}">
    @csrf
    @method('DELETE')
</form>

{{-- Modale de confirmation --}}
<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold" id="modalDeleteLabel">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-2">
                <p class="mb-1">Vous êtes sur le point de supprimer l'élément :</p>
                <p class="fw-semibold text-danger mb-0">« {{ $item->title }} »</p>
                <p class="text-muted small mt-2 mb-0">Cette action est irréversible.</p>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Annuler
                </button>
                {{-- form="form-delete" relie ce bouton au formulaire DELETE indépendant --}}
                <button type="submit"
                        form="form-delete"
                        class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>Supprimer définitivement
                </button>
            </div>

        </div>
    </div>
</div>

@endsection