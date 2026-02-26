@extends('layouts.editor')

@section('title', 'Voir la page')
@section('page-title', $page->title)
@section('page-description', 'Détails de la page')

@section('content')
<div class="container-fluid">
    
    <!-- Actions en haut -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('editor.pages.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                        <div class="d-flex gap-2">
                            @if($page->url)
                                <a href="{{ $page->url }}" 
                                   class="btn btn-outline-info" 
                                   target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                                </a>
                            @endif
                            <a href="{{ route('editor.pages.edit', $page) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <!-- Informations générales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0">Informations</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td width="200" class="fw-semibold">Titre :</td>
                                <td>{{ $page->title }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Slug :</td>
                                <td><code>{{ $page->slug }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Catégorie :</td>
                                <td>
                                    @if($page->category)
                                        <span class="badge bg-primary">{{ $page->category->name }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Description courte :</td>
                                <td>{{ $page->short_description }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Contenu long -->
            @if($page->long_description)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0">Contenu complet</h5>
                </div>
                <div class="card-body p-4">
                    <div class="content-preview">
                        {!! $page->long_description !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- SEO -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2 text-primary"></i>Référencement (SEO)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td width="200" class="fw-semibold">Titre SEO :</td>
                                <td>{{ $page->meta_title ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Description SEO :</td>
                                <td>{{ $page->meta_description ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Mots-clés :</td>
                                <td>{{ $page->meta_keywords ?? '—' }}</td>
                            </tr>
                            @if($page->meta_og_image)
                            <tr>
                                <td class="fw-semibold">Image Open Graph :</td>
                                <td>
                                    <img src="{{ $page->meta_og_image }}" 
                                         alt="OG Image" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px;">
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statut de publication -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-success"></i>Statut
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="small text-muted">Publication :</label>
                        <div>
                            @if($page->is_published)
                                <span class="badge bg-success">Publié</span>
                            @else
                                <span class="badge bg-secondary">Brouillon</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Visibilité :</label>
                        <div>
                            @if($page->visibility === 'public')
                                <span class="badge bg-success">Public</span>
                            @else
                                <span class="badge bg-warning">Authentifié</span>
                            @endif
                        </div>
                    </div>

                    @if($page->published_at)
                    <div class="mb-3">
                        <label class="small text-muted">Date de publication :</label>
                        <div>{{ $page->published_at->format('d/m/Y H:i') }}</div>
                    </div>
                    @endif

                    <div>
                        <label class="small text-muted">Ordre d'affichage :</label>
                        <div>{{ $page->sort_order ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Image -->
            @if($page->image)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h6 class="mb-0">
                        <i class="fas fa-image me-2 text-info"></i>Image
                    </h6>
                </div>
                <div class="card-body p-4">
                    <img src="{{ $page->image }}" 
                         alt="{{ $page->title }}" 
                         class="img-fluid rounded">
                </div>
            </div>
            @endif

            <!-- Métadonnées -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2 text-secondary"></i>Métadonnées
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="small">
                        <div class="mb-3">
                            <label class="text-muted">Créée par :</label>
                            <div>{{ $page->creator ? $page->creator->name : $page->created_by_name }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted">Créée le :</label>
                            <div>{{ $page->created_at->format('d/m/Y H:i') }}</div>
                        </div>

                        @if($page->updated_at && $page->updated_at != $page->created_at)
                        <div class="mb-3">
                            <label class="text-muted">Modifiée le :</label>
                            <div>{{ $page->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @endif

                        @if($page->updater)
                        <div>
                            <label class="text-muted">Modifiée par :</label>
                            <div>{{ $page->updater->name }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.content-preview {
    line-height: 1.6;
}
.content-preview img {
    max-width: 100%;
    height: auto;
}
.content-preview h1, .content-preview h2, .content-preview h3 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}
</style>
@endpush