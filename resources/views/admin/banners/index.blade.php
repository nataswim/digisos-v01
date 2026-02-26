@extends('layouts.admin')

@section('title', 'Bannières')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="fas fa-images me-2 text-primary"></i>Bannières</h1>
            <p class="text-muted mb-0">Gérez vos bannières et carrousels</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Nouvelle bannière
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Slug</th>
                            <th class="text-center">Slides</th>
                            <th class="text-center">Hauteur</th>
                            <th class="text-center">Autoplay</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                        <tr>
                            <td class="fw-semibold">{{ $banner->name }}</td>
                            <td>
                                <code class="text-primary">{{ $banner->slug }}</code>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $banner->slides_count }}</span>
                            </td>
                            <td class="text-center">{{ $banner->height }}px</td>
                            <td class="text-center">
                                @if($banner->autoplay)
                                    <span class="badge bg-success">{{ $banner->autoplay_delay / 1000 }}s</span>
                                @else
                                    <span class="badge bg-light text-dark">Non</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($banner->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.banners.edit', $banner) }}"
                                       class="btn btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            title="Supprimer"
                                            onclick="confirmDelete('{{ route('admin.banners.destroy', $banner) }}', '{{ $banner->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-images fa-3x mb-3 d-block opacity-25"></i>
                                Aucune bannière. <a href="{{ route('admin.banners.create') }}">Créer la première</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($banners->hasPages())
        <div class="card-footer">
            {{ $banners->links() }}
        </div>
        @endif
    </div>

    {{-- Aide intégration --}}
    <div class="card mt-4 border-info">
        <div class="card-header bg-info bg-opacity-10 text-info fw-semibold">
            <i class="fas fa-code me-1"></i>Intégration dans une page Blade
        </div>
        <div class="card-body">
            <p class="mb-2">Copiez-collez ce code dans n'importe quelle vue Blade :</p>
            <pre class="bg-dark text-success p-3 rounded mb-0"><code>&#64;include('components.banner', ['slug' => 'votre-slug'])</code></pre>
        </div>
    </div>

</div>

{{-- Delete confirm modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="fas fa-trash me-2"></i>Supprimer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Supprimer la bannière <strong id="deleteTarget"></strong> et tous ses slides ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(url, name) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteTarget').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
