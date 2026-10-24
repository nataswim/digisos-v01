@extends('layouts.admin')

@section('title', 'Liens utiles — liens')

@section('content')
<div class="links-admin">
    @include('admin.links._nav', ['active' => 'items'])

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        {{-- Filtre par catégorie --}}
        <ul class="nav nav-pills small">
            <li class="nav-item">
                <a class="nav-link {{ $categoryId ? '' : 'active' }}" href="{{ route('admin.links.items.index') }}">
                    Tous <span class="badge bg-light text-dark ms-1">{{ $categories->sum('links_count') }}</span>
                </a>
            </li>
            @foreach ($categories as $cat)
                <li class="nav-item">
                    <a class="nav-link {{ $categoryId === $cat->id ? 'active' : '' }}" href="{{ route('admin.links.items.index', ['categorie' => $cat->id]) }}">
                        {{ $cat->name }} <span class="badge bg-light text-dark ms-1">{{ $cat->links_count }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="d-flex flex-wrap gap-2">
            <form method="GET" action="{{ route('admin.links.items.index') }}" class="links-admin-search" role="search">
                @if ($categoryId)
                    <input type="hidden" name="categorie" value="{{ $categoryId }}">
                @endif
                <label for="q" class="visually-hidden">Rechercher un lien</label>
                <input type="search" id="q" name="q" value="{{ $search }}" class="form-control" placeholder="Rechercher (titre, adresse, mot-clé)">
            </form>
            <a href="{{ route('admin.links.items.create', $categoryId ? ['categorie' => $categoryId] : []) }}" class="btn btn-primary">
                <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter un lien
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">Image</th>
                        <th scope="col">Lien</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col">Mots-clés</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($links as $link)
                        <tr>
                            <td class="ps-3">
                                <span class="links-admin-thumb">
                                    @if ($link->image)
                                        <img src="{{ $link->image }}" alt="">
                                    @else
                                        <i class="fas fa-globe" aria-hidden="true"></i>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <strong>{{ $link->title }}</strong>
                                <br><a href="{{ $link->url }}" target="_blank" rel="noopener" class="small">{{ $link->host }}</a>
                                @foreach ($link->social_links as $reseau)
                                    <a href="{{ $reseau['url'] }}" target="_blank" rel="noopener" class="small text-muted ms-2" title="{{ $reseau['label'] }}">
                                        <i class="{{ $reseau['icon'] }}" aria-hidden="true"></i><span class="visually-hidden">{{ $reseau['label'] }}</span>
                                    </a>
                                @endforeach
                            </td>
                            <td>{{ $link->category->name ?? '—' }}</td>
                            <td class="small">
                                @foreach ($link->keyword_list as $mot)
                                    <span class="badge bg-light text-dark border">{{ $mot }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($link->is_active)
                                    <span class="badge bg-success">Affiché</span>
                                @else
                                    <span class="badge bg-secondary">Masqué</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <a href="{{ route('admin.links.items.edit', $link) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span>
                                </a>
                                <form action="{{ route('admin.links.items.destroy', $link) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer le lien « {{ addslashes($link->title) }} » ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                @if ($categories->isEmpty())
                                    Commencez par <a href="{{ route('admin.links.categories.create') }}">créer une catégorie</a> (par exemple « Fédérations »).
                                @elseif ($search !== '')
                                    Aucun lien ne correspond à « {{ $search }} ».
                                @else
                                    Aucun lien {{ $categoryId ? 'dans cette catégorie' : 'pour l\'instant' }}.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .links-admin-thumb {
        display: inline-flex;
        width: 56px;
        height: 56px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background: #fff;
        overflow: hidden;
        color: #adb5bd;
    }

    .links-admin-thumb img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .links-admin-search {
        min-width: 260px;
    }
</style>
@endpush
