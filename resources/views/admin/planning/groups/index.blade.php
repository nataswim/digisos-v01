@extends($layout)

@section('title', 'Planning — groupes')

@section('content')
<div class="planning-admin">
    @include('admin.planning._nav', ['active' => 'groups'])

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route($area . '.planning.groups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter un groupe
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive position-relative">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">Ordre</th>
                        <th scope="col">Groupe</th>
                        <th scope="col">Section</th>
                        <th scope="col" class="text-center">Nageurs</th>
                        <th scope="col" class="text-center">Créneaux</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($groups as $group)
                        <tr>
                            <td class="ps-3 text-muted">{{ $group->sort_order }}</td>
                            <td>
                                <span class="d-inline-block rounded-circle me-2 align-middle" aria-hidden="true"
                                      style="width: 0.9rem; height: 0.9rem; background: {{ $group->background_color }};"></span>
                                <strong>{{ $group->name }}</strong>
                            </td>
                            <td>{{ $group->section }}</td>
                            <td class="text-center">
                                <a href="{{ route($area . '.planning.swimmers.index', ['groupe' => $group->id]) }}">{{ $group->swimmers_count }}</a>
                            </td>
                            <td class="text-center">{{ $group->slots_count }}</td>
                            <td>
                                @if ($group->is_active)
                                    <span class="badge bg-success">Affiché</span>
                                @else
                                    <span class="badge bg-secondary">Masqué</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <a href="{{ route($area . '.planning.swimmers.create', ['groupe' => $group->id]) }}" class="btn btn-sm btn-outline-success" title="Ajouter un nageur à ce groupe">
                                    <i class="fas fa-user-plus" aria-hidden="true"></i><span class="visually-hidden">Ajouter un nageur</span>
                                </a>
                                <a href="{{ route($area . '.planning.groups.edit', $group) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span>
                                </a>
                                <form action="{{ route($area . '.planning.groups.destroy', $group) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer le groupe « {{ addslashes($group->name) }} » et ses {{ $group->slots_count }} créneau(x) ? Les nageurs sont conservés.');">
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
                            <td colspan="7" class="text-center text-muted py-5">Aucun groupe pour l'instant.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
