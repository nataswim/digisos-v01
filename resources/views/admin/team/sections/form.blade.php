@extends('layouts.admin')

@section('title', $section->exists ? 'Modifier une section' : 'Ajouter une section')

@section('content')
<div class="team-admin">
    @include('admin.team._nav', ['active' => 'sections'])

    <form method="POST" action="{{ $section->exists ? route('admin.team.sections.update', $section) : route('admin.team.sections.store') }}">
        @csrf
        @if ($section->exists) @method('PUT') @endif

        <div class="card border-0 shadow-sm" style="max-width: 760px;">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-4">{{ $section->exists ? 'Modifier « ' . $section->name . ' »' : 'Nouvelle section' }}</h2>

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nom</label>
                    <input type="text" id="name" name="name" maxlength="120" required
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $section->name) }}" placeholder="ex. Comité directeur">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Texte d'introduction <span class="text-muted fw-normal">(facultatif)</span></label>
                    <textarea id="description" name="description" rows="3" maxlength="500"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="ex. Élu par l'assemblée générale, le comité directeur gère le club.">{{ old('description', $section->description) }}</textarea>
                    <div class="form-text">Affiché sous le titre de la section.</div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-5">
                        <label for="sort_order" class="form-label fw-semibold">Ordre d'affichage</label>
                        <input type="number" id="sort_order" name="sort_order" min="0" max="65000"
                               class="form-control @error('sort_order') is-invalid @enderror"
                               value="{{ old('sort_order', $section->sort_order) }}">
                    </div>
                    <div class="col-sm-7 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $section->is_active))>
                            <label class="form-check-label" for="is_active">Afficher cette section</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
            </button>
            <a href="{{ route('admin.team.sections.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
