@extends($layout)

@section('title', 'Compétitions')

@section('content')
<div class="competitions-admin">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0"><i class="fas fa-trophy text-primary me-2" aria-hidden="true"></i>Compétitions</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('competitions.index') }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
                <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Voir le calendrier public
            </a>
            <a href="{{ route($area . '.competitions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1" aria-hidden="true"></i>Nouvelle compétition
            </a>
        </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <ul class="nav nav-pills">
            @foreach (['a-venir' => 'À venir', 'passees' => 'Passées', 'brouillons' => 'Non publiées'] as $cle => $libelle)
                <li class="nav-item">
                    <a class="nav-link {{ $filter === $cle ? 'active' : '' }}"
                       href="{{ route($area . '.competitions.index', array_filter(['vue' => $cle, 'saison' => $season])) }}">{{ $libelle }}</a>
                </li>
            @endforeach
        </ul>

        @if ($seasons->isNotEmpty())
            <form method="GET" action="{{ route($area . '.competitions.index') }}" class="d-flex gap-2">
                <input type="hidden" name="vue" value="{{ $filter }}">
                <label for="saison" class="visually-hidden">Saison</label>
                <select id="saison" name="saison" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Toutes les saisons</option>
                    @foreach ($seasons as $s)
                        <option value="{{ $s }}" @selected($season === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </form>
        @endif
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive position-relative">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">Dates</th>
                        <th scope="col">Compétition</th>
                        <th scope="col">Niveau</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-center">Fiche</th>
                        <th scope="col" class="text-center">Nageurs</th>
                        <th scope="col" class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($competitions as $competition)
                        <tr>
                            <td class="ps-3 text-nowrap">
                                {{ $competition->starts_on->format('d/m/Y') }}
                                @if ($competition->duration_days > 1)
                                    <small class="text-muted d-block">→ {{ $competition->ends_on->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($competition->image)
                                    <img src="{{ $competition->image }}" alt="" class="rounded float-start me-2" style="width: 60px; height: 45px; object-fit: cover;">
                                @endif
                                <strong>{{ $competition->title }}</strong>
                                <small class="text-muted d-block">{{ $competition->venue }}, {{ $competition->city }}</small>
                                @foreach ($competition->groups as $groupe)
                                    <span class="badge me-1" style="background: {{ $groupe->background_color }}; color: {{ $groupe->text_color }};">{{ $groupe->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $competition->level_label }}</td>
                            <td>
                                <span class="badge {{ $competition->state_badge }}">{{ $competition->state_label }}</span>
                                @if (! $competition->is_published)
                                    <span class="badge bg-light text-dark border">Non publiée</span>
                                @endif
                            </td>
                            <td class="text-center small text-muted text-nowrap">
                                <span title="Réunions au programme"><i class="fas fa-list-ol" aria-hidden="true"></i> {{ $competition->sessions_count }}</span>
                                <span class="ms-2" title="Encadrement"><i class="fas fa-user-tie" aria-hidden="true"></i> {{ $competition->staff_count }}</span>
                                <span class="ms-2" title="Documents"><i class="fas fa-file-pdf" aria-hidden="true"></i> {{ $competition->documents_count }}</span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route($area . '.competitions.entries.index', $competition) }}" class="text-decoration-none" title="Convocations et engagements">
                                    @if ($competition->attendances_count)
                                        <span class="badge bg-success">{{ $competition->confirmed_count }}</span>
                                        <small class="text-muted">/ {{ $competition->attendances_count }}</small>
                                        @if ($competition->pending_count)
                                            <span class="badge bg-warning text-dark ms-1" title="Réponses en attente">{{ $competition->pending_count }} <i class="fas fa-hourglass-half" aria-hidden="true"></i></span>
                                        @endif
                                    @else
                                        <span class="btn btn-sm btn-outline-primary py-0"><i class="fas fa-user-plus" aria-hidden="true"></i> Convoquer</span>
                                    @endif
                                </a>
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <a href="{{ route('competitions.show', $competition) }}" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener" title="Voir la fiche">
                                    <i class="fas fa-eye" aria-hidden="true"></i><span class="visually-hidden">Voir la fiche</span>
                                </a>
                                <a href="{{ route($area . '.competitions.program.edit', $competition) }}" class="btn btn-sm btn-outline-primary" title="Grille des épreuves">
                                    <i class="fas fa-list-ol" aria-hidden="true"></i><span class="visually-hidden">Grille des épreuves</span>
                                </a>
                                <a href="{{ route($area . '.competitions.edit', $competition) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span>
                                </a>
                                <form action="{{ route($area . '.competitions.duplicate', $competition) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Dupliquer (ex. pour l'édition suivante)">
                                        <i class="fas fa-copy" aria-hidden="true"></i><span class="visually-hidden">Dupliquer</span>
                                    </button>
                                </form>
                                <form action="{{ route($area . '.competitions.destroy', $competition) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette compétition et ses documents ?');">
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
                            <td colspan="7" class="text-center text-muted py-5">Aucune compétition dans cette liste.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $competitions->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
