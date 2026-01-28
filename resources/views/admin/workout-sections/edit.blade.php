@extends('layouts.admin')

@section('title', 'Modifier une section')
@section('page-title', 'Modifier la section')
@section('page-description', 'Modification de la section : ' . $workoutSection->name)

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.workout-sections.update', $workoutSection) }}">
        @method('PUT')
        @csrf
        
        <div class="row g-4">
            <!-- Contenu principal -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white p-4">
                        <h5 class="mb-0">Informations de la section</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nom de la section *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $workoutSection->name) }}"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   placeholder="Ex: Natation"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold">URL personnalisée (Slug)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">{{ url('/workouts') }}/</span>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       value="{{ old('slug', $workoutSection->slug) }}"
                                       class="form-control"
                                       placeholder="natation">
                            </div>
                            <div class="form-text">Laisser vide pour génération automatique</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="form-control"
                                      placeholder="Description détaillée de la section...">{{ old('description', $workoutSection->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statut -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-toggle-on me-2"></i>Statut et organisation
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1"
                                       {{ old('is_active', $workoutSection->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Section active</strong>
                                    <div class="text-muted small">Visible sur le site public</div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="sort_order" class="form-label fw-semibold">Ordre d'affichage</label>
                            <input type="number" 
                                   name="sort_order" 
                                   id="sort_order" 
                                   value="{{ old('sort_order', $workoutSection->sort_order) }}"
                                   class="form-control"
                                   placeholder="0"
                                   min="0">
                            <div class="form-text">Plus le nombre est petit, plus la section sera affichée en premier</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="{{ route('admin.workout-sections.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Mettre à jour la section
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated) {
            const slug = this.value
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = '';
    });
});
</script>
@endpush

@push('styles')

@endpush