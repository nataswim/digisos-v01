@extends('layouts.admin')

@section('title', 'Créer une fiche utilisateur')

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-id-card me-2 text-primary"></i>Créer une fiche utilisateur
            </h4>
            <p class="text-muted mb-0">Associez une fiche enrichie à un utilisateur.</p>
        </div>
        <a href="{{ route('admin.user-profiles.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('admin.user-profiles.store') }}">
                        @csrf

                        {{-- Sélection de l'utilisateur --}}
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold">
                                Utilisateur <span class="text-danger">*</span>
                            </label>
                            <select name="user_id"
                                    id="user_id"
                                    class="form-select @error('user_id') is-invalid @enderror"
                                    required>
                                <option value="">— Sélectionner un utilisateur —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}"
                                        {{ (old('user_id', $selectedUser?->id) == $u->id) ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($users->isEmpty())
                                <div class="form-text text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Tous les utilisateurs possèdent déjà une fiche.
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        {{-- Informations professionnelles --}}
                        <h6 class="fw-semibold mb-3 text-muted text-uppercase" style="font-size:.75rem;letter-spacing:.05em;">
                            Informations professionnelles
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="job_title" class="form-label fw-semibold">Poste / Fonction</label>
                                <input type="text"
                                       name="job_title"
                                       id="job_title"
                                       class="form-control @error('job_title') is-invalid @enderror"
                                       value="{{ old('job_title') }}"
                                       maxlength="150"
                                       placeholder="Ex : Responsable sportif">
                                @error('job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="website" class="form-label fw-semibold">Site web</label>
                                <input type="url"
                                       name="website"
                                       id="website"
                                       class="form-control @error('website') is-invalid @enderror"
                                       value="{{ old('website') }}"
                                       maxlength="255"
                                       placeholder="https://exemple.fr">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">Adresse</label>
                                <input type="text"
                                       name="address"
                                       id="address"
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address') }}"
                                       maxlength="255"
                                       placeholder="Ex : 12 rue de la Paix, 75001 Paris">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Options admin --}}
                        <h6 class="fw-semibold mb-3 text-muted text-uppercase" style="font-size:.75rem;letter-spacing:.05em;">
                            Options administrateur
                        </h6>

                        <div class="mb-3">
                            <label for="admin_notes" class="form-label fw-semibold">Notes internes</label>
                            <textarea name="admin_notes"
                                      id="admin_notes"
                                      class="form-control @error('admin_notes') is-invalid @enderror"
                                      rows="3"
                                      maxlength="5000"
                                      placeholder="Notes visibles uniquement par les administrateurs...">{{ old('admin_notes') }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_visible"
                                   id="is_visible"
                                   value="1"
                                   {{ old('is_visible', '1') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_visible">
                                Fiche visible par l'utilisateur
                            </label>
                            <div class="form-text">Si désactivé, l'utilisateur ne verra pas sa fiche dans son espace.</div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.user-profiles.index') }}"
                               class="btn btn-outline-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer la fiche
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
