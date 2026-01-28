@extends('layouts.admin')

@section('title', 'Détail du Module')
@section('page-title', $catalogueModule->name)

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white p-4">
                    <h5 class="mb-0">
                        <i class="fas fa-th me-2"></i>{{ $catalogueModule->name }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($catalogueModule->image)
                        <div class="mb-4">
                            <img src="{{ $catalogueModule->image }}" class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                    @endif

                    @if($catalogueModule->short_description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description courte</h6>
                            <div class="bg-light p-3 rounded">
                                {!! $catalogueModule->short_description !!}
                            </div>
                        </div>
                    @endif

                    @if($catalogueModule->long_description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Description complète</h6>
                            <div>{!! $catalogueModule->long_description !!}</div>
                        </div>
                    @endif

                    @if($catalogueModule->units_count > 0)
                        <div class="border-top pt-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-file-alt me-2 text-primary"></i>Unités ({{ $catalogueModule->units_count }})
                            </h6>
                            <div class="list-group">
                                @foreach($catalogueModule->units as $unit)
                                    <a href="{{ route('admin.catalogue-units.show', $unit) }}" 
                                       class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $unit->title }}</strong>
                                                <br><small class="text-muted">{{ $unit->content_type_label }}</small>
                                            </div>
                                            <span class="badge bg-secondary">Ordre: {{ $unit->order }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
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
                            <small class="text-muted d-block">Section</small>
                            <strong>{{ $catalogueModule->section->name }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Ordre</small>
                            <strong>{{ $catalogueModule->order }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Unités</small>
                            <strong>{{ $catalogueModule->units_count }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.catalogue-modules.edit', $catalogueModule) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($catalogueModule->is_active && $catalogueModule->section)
                            <a href="{{ route('public.catalogue.module', [$catalogueModule->section, $catalogueModule]) }}" 
                               target="_blank" class="btn btn-outline-info">
                                <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                            </a>
                        @endif
                        <a href="{{ route('admin.catalogue-modules.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                    <hr class="my-3">
                    <form method="POST" action="{{ route('admin.catalogue-modules.destroy', $catalogueModule) }}" 
                          onsubmit="return confirm('Supprimer ce module ?')">
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