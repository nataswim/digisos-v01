@extends('layouts.admin')

@section('title', 'L\'équipe du club — membres')

@section('content')
<div class="team-admin">
    @include('admin.team._nav', ['active' => 'members'])

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        {{-- Filtre par section --}}
        <ul class="nav nav-pills small">
            <li class="nav-item">
                <a class="nav-link {{ $sectionId ? '' : 'active' }}" href="{{ route('admin.team.members.index') }}">
                    Tous
                </a>
            </li>
            @foreach ($sections as $sec)
                <li class="nav-item">
                    <a class="nav-link {{ $sectionId === $sec->id ? 'active' : '' }}" href="{{ route('admin.team.members.index', ['section' => $sec->id]) }}">
                        {{ $sec->name }} <span class="badge bg-light text-dark ms-1">{{ $sec->members_count }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <a href="{{ route('admin.team.members.create', $sectionId ? ['section' => $sectionId] : []) }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-1" aria-hidden="true"></i>Ajouter un membre
        </a>
    </div>

    @if ($withoutSection > 0 && ! $sectionId)
        <div class="alert alert-warning small" role="status">
            <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>
            {{ $withoutSection }} {{ $withoutSection > 1 ? 'membres ne sont' : 'membre n\'est' }} dans aucune section et n'{{ $withoutSection > 1 ? 'apparaissent' : 'apparaît' }} donc pas sur la page publique.
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">Photo</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Sections et fonctions</th>
                        <th scope="col">Liens</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td class="ps-3">
                                @if ($member->photo)
                                    <img src="{{ $member->photo }}" alt="" class="team-admin-thumb">
                                @else
                                    <span class="team-admin-thumb team-admin-thumb--empty" aria-hidden="true">{{ $member->initials }}</span>
                                @endif
                            </td>
                            <td><strong>{{ $member->last_name }}</strong> {{ $member->first_name }}</td>
                            <td>
                                @forelse ($member->sections as $sec)
                                    <div class="small">
                                        <span class="badge bg-light text-dark border">{{ $sec->name }}</span>
                                        {{ $sec->pivot->position ?: '—' }}
                                    </div>
                                @empty
                                    <span class="badge bg-warning text-dark">Aucune section</span>
                                @endforelse
                            </td>
                            <td class="text-nowrap">
                                @foreach ($member->links as $link)
                                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="text-muted me-1" title="{{ $link['label'] }}">
                                        <i class="{{ $link['icon'] }}" aria-hidden="true"></i><span class="visually-hidden">{{ $link['label'] }}</span>
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @if ($member->is_active)
                                    <span class="badge bg-success">Affiché</span>
                                @else
                                    <span class="badge bg-secondary">Masqué</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <a href="{{ route('admin.team.members.edit', $member) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span>
                                </a>
                                <form action="{{ route('admin.team.members.destroy', $member) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Retirer « {{ addslashes($member->full_name) }} » de l\'équipe ? La photo reste dans la médiathèque.');">
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
                                Aucun membre {{ $sectionId ? 'dans cette section' : 'pour l\'instant' }}.
                                <a href="{{ route('admin.team.members.create', $sectionId ? ['section' => $sectionId] : []) }}">Ajouter un membre</a>
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
    .team-admin-thumb {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #e9ecef;
        background: #fff;
    }

    .team-admin-thumb--empty {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e8f2f9;
        color: #2f80b8;
        font-weight: 700;
        font-size: 0.85rem;
    }
</style>
@endpush
