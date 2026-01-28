@extends('layouts.admin')

@section('title', 'Détail de l\'Unité')
@section('page-title', $catalogueUnit->title)

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>{{ $catalogueUnit->title }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($catalogueUnit->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description</h6>
                            <div class="bg-light p-3 rounded">
                                {!! $catalogueUnit->description !!}
                            </div>
                        </div>
                    @endif

                    @if($catalogueUnit->unitable)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-link me-2 text-primary"></i>Contenu lié
                            </h6>
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Type :</strong> {{ $catalogueUnit->content_type_label }}<br>
                                        <strong>Titre :</strong> {{ $catalogueUnit->unitable->title ?? $catalogueUnit->unitable->name ?? 'N/A' }}
                                    </div>
                                    @if($catalogueUnit->content_url)
                                        <a href="{{ $catalogueUnit->content_url }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-external-link-alt me-1"></i>Voir le contenu
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Aucun contenu lié à cette unité
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white p-3">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Module</small>
                            <strong>{{ $catalogueUnit->module->name }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Section</small>
                            <strong>{{ $catalogueUnit->module->section->name }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Ordre</small>
                            <strong>{{ $catalogueUnit->order }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Type de contenu</small>
                            <strong>{{ $catalogueUnit->content_type_label }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.catalogue-units.edit', $catalogueUnit) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($catalogueUnit->content_url)
                            <a href="{{ $catalogueUnit->content_url }}" target="_blank" class="btn btn-outline-success">
                                <i class="fas fa-external-link-alt me-2"></i>Voir le contenu
                            </a>
                        @endif
                        <a href="{{ route('admin.catalogue-units.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                    <hr class="my-3">
                    <form method="POST" action="{{ route('admin.catalogue-units.destroy', $catalogueUnit) }}" 
                          onsubmit="return confirm('Supprimer cette unité ?')">
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