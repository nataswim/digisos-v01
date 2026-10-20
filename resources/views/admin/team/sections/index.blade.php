@extends('layouts.admin')

@section('title', 'L\'équipe du club — sections')

@section('content')
<div class="team-admin">
    @include('admin.team._nav', ['active' => 'sections'])

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <p class="text-muted small mb-0">Les sections s'affichent sur la page publique dans l'ordre ci-dessous. Une section sans membre affiché n'apparaît pas.</p>
        <a href="{{ route('admin.team.sections.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter une section
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">Ordre</th>
                        <th scope="col">Section</th>
                        <th scope="col" class="text-center">Membres</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sections as $section)
                        <tr>
                            <td class="ps-3 text-muted">{{ $section->sort_order }}</td>
                            <td>
                                <strong>{{ $section->name }}</strong>
                                @if ($section->description)
                                    <br><small class="text-muted">{{ Str::limit($section->description, 80) }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.team.members.index', ['section' => $section->id]) }}">{{ $section->members_count }}</a>
                            </td>
                            <td>
                                @if ($section->is_active)
                                    <span class="badge bg-success">Affichée</span>
                                @else
                                    <span class="badge bg-secondary">Masquée</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <a href="{{ route('admin.team.members.create', ['section' => $section->id]) }}" class="btn btn-sm btn-outline-success" title="Ajouter un membre dans cette section">
                                    <i class="fas fa-user-plus" aria-hidden="true"></i><span class="visually-hidden">Ajouter un membre dans cette section</span>
                                </a>
                                <a href="{{ route('admin.team.sections.edit', $section) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span>
                                </a>
                                <form action="{{ route('admin.team.sections.destroy', $section) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer la section « {{ addslashes($section->name) }} » ? Ses membres ne sont pas supprimés : ils sont seulement retirés de cette section.');">
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
                            <td colspan="5" class="text-center text-muted py-5">
                                Aucune section. <a href="{{ route('admin.team.sections.create') }}">Créer la première</a> (par exemple « Comité directeur »).
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
