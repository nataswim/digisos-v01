@extends('layouts.admin')

@section('title', 'Détail de la Section')
@section('page-title', $catalogueSection->name)
@section('page-description', 'Détails de la section')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-layer-group me-2"></i>{{ $catalogueSection->name }}
                        </h5>
                        <span class="badge bg-light text-dark">
                            {{ $catalogueSection->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($catalogueSection->image)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Image</h6>
                            <img src="{{ $catalogueSection->image }}" 
                                 alt="{{ $catalogueSection->name }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    @if($catalogueSection->short_description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description courte</h6>
                            <div class="bg-light p-3 rounded">
                                {!! $catalogueSection->short_description !!}
                            </div>
                        </div>
                    @endif

                    @if($catalogueSection->long_description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description complète</h6>
                            <div class="content-display">
                                {!! $catalogueSection->long_description !!}
                            </div>
                        </div>
                    @endif

                    @if($catalogueSection->modules_count > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-th me-2 text-primary"></i>Modules ({{ $catalogueSection->modules_count }})
                            </h6>
                            <div class="list-group">
                                @foreach($catalogueSection->modules as $module)
                                    <a href="{{ route('admin.catalogue-modules.show', $module) }}" 
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $module->name }}</strong>
                                            @if($module->short_description)
                                                <br><small class="text-muted">{{ Str::limit(strip_tags($module->short_description), 100) }}</small>
                                            @endif
                                        </div>
                                        <span class="badge bg-primary">{{ $module->units_count ?? 0 }} unité(s)</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Ordre</small>
                            <strong>{{ $catalogueSection->order }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Slug</small>
                            <strong>{{ $catalogueSection->slug }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Modules</small>
                            <strong>{{ $catalogueSection->modules_count }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Créé le</small>
                            <strong>{{ $catalogueSection->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        @if($catalogueSection->creator)
                            <div class="col-12">
                                <small class="text-muted d-block">Créé par</small>
                                <strong>{{ $catalogueSection->creator->name }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.catalogue-sections.edit', $catalogueSection) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($catalogueSection->is_active)
                            <a href="{{ route('public.catalogue.section', $catalogueSection) }}" target="_blank" class="btn btn-outline-info">
                                <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                            </a>
                        @endif
                        <a href="{{ route('admin.catalogue-sections.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('admin.catalogue-sections.destroy', $catalogueSection) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette section ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')

@endpush