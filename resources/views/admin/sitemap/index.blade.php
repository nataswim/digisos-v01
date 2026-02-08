@extends('layouts.admin')

@section('title', 'Gestion du Sitemap')
@section('page-title', 'Sitemap SEO')
@section('page-description', 'Gestion des URLs pour les moteurs de recherche')

@section('content')
<div class="container-fluid">
    {{-- En-tête avec actions --}}
    <div class="d-flex bg-white justify-content-between align-items-center text-primary mb-4 p-4">
        <div>
            <h2><i class="fas fa-sitemap me-2"></i>Gestion du Sitemap</h2>
            <small class="text-muted">{{ $urls->total() ?? $urls->count() }} URL(s) au total</small>
        </div>
        <div class="btn-group">
            <button type="button" class="btn bg-warning p-2" data-bs-toggle="modal" data-bs-target="#discoverModal">
                <i class="fas fa-search me-2"></i>Découvrir
            </button>
            <button type="button" class="btn bg-warning p-2" data-bs-toggle="modal" data-bs-target="#generateModal">
                <i class="fas fa-rocket me-2"></i>Générer
            </button>
        </div>
    </div>

    {{-- Messages Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistiques en haut --}}
    <div class="row g-4 mb-4">
        <!-- Statistiques générales -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-link fa-2x text-white"></i>
                    </div>
                    <h3 class="fw-bold text-primary mb-1">{{ $statistics['total_urls'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Total URLs</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-circle fa-2x text-white"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-1">{{ $statistics['approved_urls'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Approuvées</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock fa-2x text-white"></i>
                    </div>
                    <h3 class="fw-bold text-warning mb-1">{{ $statistics['pending_approval'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">En attente</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-calendar fa-2x text-white"></i>
                    </div>
                    <h3 class="fw-bold text-info mb-1">
                        @if($statistics['last_generated'] ?? false)
                            {{ $statistics['last_generated']->format('d/m/Y') }}
                        @else
                            Jamais
                        @endif
                    </h3>
                    <p class="text-muted mb-0">Dernière génération</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Répartitions --}}
    <div class="row g-4 mb-4">
        <!-- Répartition par type -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-layer-group me-2"></i>Répartition par type
                    </h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $total = max(1, $statistics['total_urls'] ?? 0);
                    @endphp
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-semibold text-primary">
                                <i class="fas fa-file-code me-1"></i>Statique
                            </span>
                            <span class="badge bg-primary-subtle text-primary">{{ $statistics['static_urls'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ (($statistics['static_urls'] ?? 0) / $total) * 100 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-semibold text-info">
                                <i class="fas fa-database me-1"></i>Dynamique
                            </span>
                            <span class="badge bg-info-subtle text-info">{{ $statistics['dynamic_urls'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" 
                                 style="width: {{ (($statistics['dynamic_urls'] ?? 0) / $total) * 100 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-semibold text-success">
                                <i class="fas fa-hand-pointer me-1"></i>Manuel
                            </span>
                            <span class="badge bg-success-subtle text-success">{{ $statistics['manual_urls'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ (($statistics['manual_urls'] ?? 0) / $total) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition par source -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-sitemap me-2"></i>Répartition par source
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row text-center g-2">
                        <div class="col-6">
                            <div class="bg-light rounded p-2">
                                <strong class="d-block">{{ $statistics['posts_urls'] ?? 0 }}</strong>
                                <small class="text-muted">Articles</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-2">
                                <strong class="d-block">{{ $statistics['fiches_urls'] ?? 0 }}</strong>
                                <small class="text-muted">Fiches</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-2">
                                <strong class="d-block">{{ $statistics['downloadables_urls'] ?? 0 }}</strong>
                                <small class="text-muted">Téléchargements</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-2">
                                <strong class="d-block">{{ $statistics['exercices_urls'] ?? 0 }}</strong>
                                <small class="text-muted">Exercices</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-2">
                                <strong class="d-block">{{ $statistics['plans_urls'] ?? 0 }}</strong>
                                <small class="text-muted">Plans</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-2">
                                <strong class="d-block">{{ $statistics['workouts_urls'] ?? 0 }}</strong>
                                <small class="text-muted">Workouts</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions et Liens -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-success p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#discoverModal">
                            <i class="fas fa-search me-2"></i>Découvrir les URLs
                        </button>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#generateModal">
                            <i class="fas fa-rocket me-2"></i>Générer le Sitemap
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#addUrlModal">
                            <i class="fas fa-plus me-2"></i>Ajouter une URL
                        </button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning p-3">
                    <h6 class="mb-0">
                        <i class="fas fa-link me-2"></i>Liens SEO
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ config('app.url') }}/sitemap.xml" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-file-code me-2"></i>Voir le Sitemap
                        </a>
                        <a href="https://search.google.com/search-console" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fab fa-google me-2"></i>Search Console
                        </a>
                        <a href="https://www.bing.com/webmasters" target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="fab fa-microsoft me-2"></i>Bing Webmaster
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau principal --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Liste des URLs
                    </h5>
                </div>
                
                <!-- Filtres -->
                <div class="card-body border-bottom p-4 bg-light">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="form-control border-start-0"
                                       placeholder="Rechercher une URL...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="type" class="form-select">
                                <option value="">Tous les types</option>
                                <option value="static" {{ request('type') === 'static' ? 'selected' : '' }}>Statique</option>
                                <option value="dynamic" {{ request('type') === 'dynamic' ? 'selected' : '' }}>Dynamique</option>
                                <option value="manual" {{ request('type') === 'manual' ? 'selected' : '' }}>Manuel</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="approved" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="true" {{ request('approved') === 'true' ? 'selected' : '' }}>Approuvées</option>
                                <option value="false" {{ request('approved') === 'false' ? 'selected' : '' }}>En attente</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="source" class="form-select">
                                <option value="">Toutes sources</option>
                                <option value="posts" {{ request('source') === 'posts' ? 'selected' : '' }}>Articles</option>
                                <option value="fiches" {{ request('source') === 'fiches' ? 'selected' : '' }}>Fiches</option>
                                <option value="downloadables" {{ request('source') === 'downloadables' ? 'selected' : '' }}>Téléchargements</option>
                                <option value="exercices" {{ request('source') === 'exercices' ? 'selected' : '' }}>Exercices</option>
                                <option value="plans" {{ request('source') === 'plans' ? 'selected' : '' }}>Plans</option>
                                <option value="workouts" {{ request('source') === 'workouts' ? 'selected' : '' }}>Workouts</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-filter me-2"></i>Filtrer
                                </button>
                                @if(request()->hasAny(['search', 'type', 'approved', 'source']))
                                    <a href="{{ route('admin.sitemap.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Actions en masse -->
                <div id="bulkActions" class="alert alert-info m-4 mb-0 d-none">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><span id="selectedCount">0</span> URL(s) sélectionnée(s)</strong>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-success" onclick="bulkApprove()">
                                <i class="fas fa-check-circle me-1"></i>Approuver
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" onclick="bulkDisapprove()">
                                <i class="fas fa-times-circle me-1"></i>Désapprouver
                            </button>
                        </div>
                    </div>
                </div>

                <!-- URLs -->
                <div class="card-body p-0">
                    @if($urls->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3" width="50">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th class="border-0 px-4 py-3">URL</th>
                                        <th class="border-0 px-4 py-3">Type</th>
                                        <th class="border-0 px-4 py-3">Source</th>
                                        <th class="border-0 px-4 py-3">Priorité</th>
                                        <th class="border-0 px-4 py-3">Fréquence</th>
                                        <th class="border-0 px-4 py-3">Statut</th>
                                        <th class="border-0 px-4 py-3">Dernière Modif.</th>
                                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($urls as $url)
                                        <tr class="border-bottom hover-bg">
                                            <td class="px-4 py-3">
                                                <input type="checkbox" class="form-check-input url-checkbox" value="{{ $url->id }}">
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="{{ $url->url }}" target="_blank" class="text-decoration-none text-dark">
                                                    {{ Str::limit($url->url, 80) }}
                                                    <i class="fas fa-external-link-alt text-muted small ms-1"></i>
                                                </a>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-{{ $url->type_badge }}-subtle text-{{ $url->type_badge }}">
                                                    {{ ucfirst($url->type) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($url->source)
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        {{ $url->source_label }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <strong class="text-primary">{{ number_format($url->priority, 1) }}</strong>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-{{ $url->changefreq_badge }}-subtle text-{{ $url->changefreq_badge }}">
                                                    {{ $url->changefreq }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex flex-column gap-1">
                                                    @if($url->is_approved)
                                                        <span class="badge bg-success-subtle text-success">
                                                            <i class="fas fa-check me-1"></i>Approuvée
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning">
                                                            <i class="fas fa-clock me-1"></i>En attente
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex flex-column">
                                                    <small class="fw-semibold">
                                                        {{ $url->last_modified ? $url->last_modified->format('d/m/Y') : $url->updated_at->format('d/m/Y') }}
                                                    </small>
                                                    <small class="text-muted">
                                                        {{ $url->last_modified ? $url->last_modified->format('H:i') : $url->updated_at->format('H:i') }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary border-0" 
                                                            data-bs-toggle="dropdown" 
                                                            aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.sitemap.toggle', $url) }}" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                                                    @if($url->is_approved)
                                                                        <i class="fas fa-times-circle me-2 text-warning"></i>Désapprouver
                                                                    @else
                                                                        <i class="fas fa-check-circle me-2 text-success"></i>Approuver
                                                                    @endif
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" 
                                                               href="{{ $url->url }}" 
                                                               target="_blank">
                                                                <i class="fas fa-external-link-alt me-2 text-white"></i>Ouvrir l'URL
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="POST" 
                                                                  action="{{ route('admin.sitemap.destroy', $url) }}" 
                                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette URL ?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="dropdown-item d-flex align-items-center text-danger">
                                                                    <i class="fas fa-trash me-2"></i>Supprimer
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($urls->hasPages())
                            <div class="card-footer bg-white border-top p-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-muted">
                                        Affichage de {{ $urls->firstItem() }} à {{ $urls->lastItem() }} 
                                        sur {{ $urls->total() }} résultat(s)
                                    </div>
                                    {{ $urls->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-sitemap fa-3x text-muted mb-3 opacity-25"></i>
                            <h5>Aucune URL trouvée</h5>
                            @if(request()->hasAny(['search', 'type', 'approved', 'source']))
                                <p class="text-muted mb-3">Aucun résultat ne correspond à vos critères de recherche.</p>
                                <a href="{{ route('admin.sitemap.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Voir toutes les URLs
                                </a>
                            @else
                                <p class="text-muted mb-3">Commencez par découvrir les URLs de votre site</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#discoverModal">
                                    <i class="fas fa-search me-2"></i>Découvrir les URLs
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
@include('admin.sitemap.partials.modals')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const urlCheckboxes = document.querySelectorAll('.url-checkbox');
    const bulkActionsDiv = document.getElementById('bulkActions');
    const selectedCountSpan = document.getElementById('selectedCount');

    function updateBulkActions() {
        const checkedCount = document.querySelectorAll('.url-checkbox:checked').length;
        
        if (checkedCount > 0) {
            bulkActionsDiv.classList.remove('d-none');
            selectedCountSpan.textContent = checkedCount;
        } else {
            bulkActionsDiv.classList.add('d-none');
            selectedCountSpan.textContent = '0';
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            urlCheckboxes.forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
            updateBulkActions();
        });
    }

    urlCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (!this.checked && selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            }
            
            const allChecked = Array.from(urlCheckboxes).every(cb => cb.checked);
            if (allChecked && selectAllCheckbox) {
                selectAllCheckbox.checked = true;
            }
            
            updateBulkActions();
        });
    });

    window.bulkApprove = function() {
        const checkedIds = Array.from(document.querySelectorAll('.url-checkbox:checked'))
            .map(cb => cb.value);

        if (checkedIds.length === 0) {
            alert('Veuillez sélectionner au moins une URL');
            return false;
        }

        if (!confirm(`Approuver ${checkedIds.length} URL(s) ?`)) {
            return false;
        }

        submitBulkAction(checkedIds, true);
        return false;
    };

    window.bulkDisapprove = function() {
        const checkedIds = Array.from(document.querySelectorAll('.url-checkbox:checked'))
            .map(cb => cb.value);

        if (checkedIds.length === 0) {
            alert('Veuillez sélectionner au moins une URL');
            return false;
        }

        if (!confirm(`Désapprouver ${checkedIds.length} URL(s) ?`)) {
            return false;
        }

        submitBulkAction(checkedIds, false);
        return false;
    };

    function submitBulkAction(ids, approved) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.sitemap.bulk-approve") }}';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        const approvedInput = document.createElement('input');
        approvedInput.type = 'hidden';
        approvedInput.name = 'approved';
        approvedInput.value = approved ? '1' : '0';
        form.appendChild(approvedInput);

        ids.forEach(function(id) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'url_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    updateBulkActions();
});
</script>
@endpush

@push('styles')
<style>






.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}

.hover-bg:hover {
    background-color: #f8f9fa;
}

.dropdown-menu {
    border: 0;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
@endpush