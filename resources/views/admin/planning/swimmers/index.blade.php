@extends($layout)

@section('title', 'Planning — nageurs')

@section('content')
<div class="planning-admin">
    @include('admin.planning._nav', ['active' => 'swimmers'])

    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
        <form method="GET" action="{{ route($area . '.planning.swimmers.index') }}" class="d-flex flex-wrap gap-2" role="search">
            <div>
                <label for="q" class="visually-hidden">Rechercher un nageur</label>
                <input type="search" id="q" name="q" value="{{ $search }}" class="form-control" placeholder="Nom ou prénom…">
            </div>
            <div>
                <label for="groupe" class="visually-hidden">Groupe</label>
                <select id="groupe" name="groupe" class="form-select">
                    <option value="">Tous les groupes</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" @selected($groupId === $group->id)>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search me-1" aria-hidden="true"></i>Filtrer</button>
            @if ($search !== '' || $groupId)
                <a href="{{ route($area . '.planning.swimmers.index') }}" class="btn btn-link">Réinitialiser</a>
            @endif
        </form>

        <div class="d-flex flex-wrap gap-2">
            @if ($area === 'admin' && Route::has('admin.planning.swimmers.from-licenses'))
                <a href="{{ route('admin.planning.swimmers.from-licenses', array_filter(['groupe' => $groupId])) }}" class="btn btn-outline-primary">
                    <i class="fas fa-id-card me-1" aria-hidden="true"></i>Ajouter depuis les licences FFN
                </a>
            @endif
            <a href="{{ route($area . '.planning.swimmers.create', array_filter(['groupe' => $groupId])) }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-1" aria-hidden="true"></i>Ajouter un nageur
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive position-relative">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">Nageur</th>
                        <th scope="col">Naissance</th>
                        <th scope="col">Groupe(s)</th>
                        <th scope="col">Compte du site</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($swimmers as $swimmer)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="swimmer-thumb" aria-hidden="true">
                                        @if ($swimmer->photo)
                                            <img src="{{ $swimmer->photo }}" alt="">
                                        @else
                                            {{ $swimmer->initials }}
                                        @endif
                                    </span>
                                    <span class="fw-semibold">{{ $swimmer->full_name }}</span>
                                    @if ($swimmer->license_number)
                                        <span class="badge bg-light text-dark border small" title="Licence FFN">FFN</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $swimmer->birth_year }}</td>
                            <td>
                                @forelse ($swimmer->groups as $group)
                                    <span class="badge me-1" style="background: {{ $group->background_color }}; color: {{ $group->text_color }};">{{ $group->name }}</span>
                                @empty
                                    <span class="text-muted small">Aucun</span>
                                @endforelse
                            </td>
                            <td class="small">{{ $swimmer->user?->email }}</td>
                            <td>
                                @if ($swimmer->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <a href="{{ route($area . '.planning.swimmers.edit', $swimmer) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span>
                                </a>
                                <form action="{{ route($area . '.planning.swimmers.destroy', $swimmer) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer {{ addslashes($swimmer->full_name) }} ?');">
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
                            <td colspan="6" class="text-center text-muted py-5">Aucun nageur trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $swimmers->links('pagination::bootstrap-5') }}</div>

    <p class="small text-muted mt-3 mb-0">
        <i class="fas fa-user-shield me-1" aria-hidden="true"></i>
        Sur le site, les noms (prénom et initiale) ne sont visibles que par les adhérents connectés.
        Les notes internes ne sont jamais affichées.
    </p>
</div>
@endsection

@push('styles')
<style>
    .swimmer-thumb {
        display: inline-flex;
        width: 40px;
        height: 40px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        overflow: hidden;
        background: #e8f2f9;
        color: #2f80b8;
        font-size: 0.8rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .swimmer-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush
