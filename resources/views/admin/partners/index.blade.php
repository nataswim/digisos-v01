@extends($layout)

@section('title', 'Partenaires')

@section('content')
<div class="partners-admin">
    @include('admin.partners._header')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        {{-- Filtre par niveau --}}
        <ul class="nav nav-pills small">
            <li class="nav-item">
                <a class="nav-link {{ $level ? '' : 'active' }}" href="{{ route($area . '.partners.index') }}">
                    Tous <span class="badge bg-light text-dark ms-1">{{ $counts->sum() }}</span>
                </a>
            </li>
            @foreach (\App\Models\Partner::LEVELS as $cle => $info)
                <li class="nav-item">
                    <a class="nav-link {{ $level === $cle ? 'active' : '' }}" href="{{ route($area . '.partners.index', ['niveau' => $cle]) }}">
                        {{ $info['short'] }} <span class="badge bg-light text-dark ms-1">{{ $counts[$cle] ?? 0 }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <a href="{{ route($area . '.partners.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter un partenaire
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">Logo</th>
                        <th scope="col">Partenaire</th>
                        <th scope="col">Niveau</th>
                        <th scope="col">Depuis</th>
                        <th scope="col" class="text-center">Ordre</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($partners as $partner)
                        <tr>
                            <td class="ps-3">
                                @if ($partner->logo_url)
                                    <img src="{{ $partner->logo_url }}" alt="" class="partners-admin-logo">
                                @else
                                    <span class="partners-admin-logo partners-admin-initials" aria-hidden="true">{{ $partner->initials }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $partner->name }}</strong>
                                @if ($partner->category_label)
                                    <br><small class="text-muted">{{ $partner->category_label }}</small>
                                @endif
                                @if ($partner->website)
                                    <br><a href="{{ $partner->website }}" target="_blank" rel="noopener" class="small">{{ $partner->website_host }}</a>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background: {{ $partner->level_info['bg'] }}; color: {{ $partner->level_info['text'] }};">
                                    {{ $partner->level_info['short'] }}
                                </span>
                            </td>
                            <td>{{ $partner->partner_since ?: '—' }}</td>
                            <td class="text-center text-muted">{{ $partner->sort_order }}</td>
                            <td>
                                @if ($partner->is_active)
                                    <span class="badge bg-success">Affiché</span>
                                @else
                                    <span class="badge bg-secondary">Masqué</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                @if ($partner->is_active)
                                    <a href="{{ route('partners.show', $partner) }}" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener" title="Voir la fiche publique">
                                        <i class="fas fa-eye" aria-hidden="true"></i><span class="visually-hidden">Voir la fiche publique</span>
                                    </a>
                                @endif
                                <a href="{{ route($area . '.partners.edit', $partner) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span>
                                </a>
                                <form action="{{ route($area . '.partners.destroy', $partner) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer le partenaire « {{ addslashes($partner->name) }} » et son logo ?');">
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
                            <td colspan="7" class="text-center text-muted py-5">
                                Aucun partenaire {{ $level ? 'à ce niveau' : 'pour l\'instant' }}.
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
    .partners-admin-logo {
        width: 64px;
        height: 40px;
        object-fit: contain;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 2px;
    }

    .partners-admin-initials {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #2f80b8;
    }
</style>
@endpush
