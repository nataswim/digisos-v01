@extends('layouts.admin')

@section('title', 'Détails du fichier')
@section('page-title', $ebookFile->name)
@section('page-description', 'Détails du fichier eBook')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="fas {{ $ebookFile->icon }} fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">{{ $ebookFile->name }}</h5>
                            <small class="opacity-75">{{ $ebookFile->format_label }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Formulaire d'édition -->
                    <form method="POST" action="{{ route('admin.ebook-files.update', $ebookFile) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="name" class="form-label fw-semibold">Nom d'affichage</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $ebookFile->name) }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea name="description" 
                                          id="description" 
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="3">{{ old('description', $ebookFile->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <!-- Informations techniques -->
                    <h6 class="fw-semibold mb-3">Informations techniques</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted d-block">Nom du fichier</small>
                                <strong>{{ $ebookFile->file_name }}</strong>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted d-block">Nom original</small>
                                <strong>{{ $ebookFile->original_name }}</strong>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted d-block">Format</small>
                                <strong>{{ strtoupper($ebookFile->format) }}</strong>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted d-block">Taille</small>
                                <strong>{{ $ebookFile->formatted_size }}</strong>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted d-block">MIME Type</small>
                                <strong>{{ $ebookFile->mime_type }}</strong>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted d-block">Uploadé le</small>
                                <strong>{{ $ebookFile->created_at->format('d/m/Y à H:i') }}</strong>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted d-block">Par</small>
                                <strong>{{ $ebookFile->uploader->name ?? 'Inconnu' }}</strong>
                            </div>
                        </div>
                        
                        @if($ebookFile->used_at)
                        <div class="col-12">
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted d-block">Dernière utilisation</small>
                                <strong>{{ $ebookFile->used_at->format('d/m/Y à H:i') }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    @if($ebookFile->downloadables->count() > 0)
                    <hr class="my-4">
                    
                    <!-- Téléchargements utilisant ce fichier -->
                    <h6 class="fw-semibold mb-3">Utilisé par {{ $ebookFile->downloadables->count() }} téléchargement(s)</h6>
                    <div class="list-group">
                        @foreach($ebookFile->downloadables as $downloadable)
                        <a href="{{ route('admin.downloadables.show', $downloadable) }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $downloadable->title }}</h6>
                                    <small class="text-muted">
                                        {{ $downloadable->category->name ?? 'Sans catégorie' }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $downloadable->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($downloadable->status) }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.ebook-files.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                        
                        @if($ebookFile->downloadables->count() === 0)
                        <form method="POST" 
                              action="{{ route('admin.ebook-files.destroy', $ebookFile) }}"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Supprimer le fichier
                            </button>
                        </form>
                        @else
                        <button class="btn btn-outline-danger" disabled title="Fichier utilisé, suppression impossible">
                            <i class="fas fa-lock me-2"></i>Suppression bloquée
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Statistiques -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $ebookFile->downloadables->count() }}</h4>
                                <small class="text-muted">Utilisations</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $ebookFile->downloadables->sum('download_count') }}</h4>
                                <small class="text-muted">Téléchargements</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}

</style>
@endpush