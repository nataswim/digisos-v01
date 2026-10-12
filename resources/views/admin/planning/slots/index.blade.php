@extends($layout)

@section('title', 'Planning — créneaux')

@section('content')
<div class="planning-admin">
    @include('admin.planning._nav', ['active' => 'slots'])

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <ul class="nav nav-pills">
            @foreach (\App\Models\PlanningSlot::PERIODS as $cle => $libelle)
                <li class="nav-item">
                    <a class="nav-link {{ $period === $cle ? 'active' : '' }}"
                       href="{{ route($area . '.planning.slots.index', ['periode' => $cle]) }}">
                        {{ $libelle }} <span class="badge bg-light text-dark ms-1">{{ $counts[$cle] ?? 0 }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
        <a href="{{ route($area . '.planning.slots.create', ['periode' => $period]) }}" class="btn btn-primary">
            <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter un créneau
        </a>
    </div>

    @if ($slots->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-calendar-times fa-2x mb-3 opacity-50" aria-hidden="true"></i>
                <p class="mb-0">Aucun créneau pour « {{ \App\Models\PlanningSlot::PERIODS[$period] }} ».</p>
            </div>
        </div>
    @endif

    @foreach (\App\Models\PlanningSlot::DAYS as $numero => $jour)
        @if ($slots->has($numero))

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h6 fw-bold mb-0">{{ $jour }}</h2>
                <a href="{{ route($area . '.planning.slots.create', ['periode' => $period, 'jour' => $numero]) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus" aria-hidden="true"></i><span class="visually-hidden">Ajouter un créneau le {{ $jour }}</span>
                </a>
            </div>
            <div class="table-responsive position-relative">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">Horaire</th>
                            <th scope="col">Groupe</th>
                            <th scope="col">Lieu</th>
                            <th scope="col">Espace</th>
                            <th scope="col">Note</th>
                            <th scope="col" class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slots[$numero] as $slot)
                            <tr class="{{ $slot->is_active ? '' : 'text-muted' }}">
                                <td class="ps-3 text-nowrap fw-semibold">
                                    {{ $slot->time_range }}
                                    @if (! ($slot->is_active))
                                        <span class="badge bg-secondary ms-1">masqué</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background: {{ $slot->group->background_color }}; color: {{ $slot->group->text_color }};">
                                        {{ $slot->group->name }}
                                    </span>
                                </td>
                                <td>{{ $slot->location }}</td>
                                <td>{{ $slot->lanes }}</td>
                                <td class="small">{{ $slot->notes }}</td>
                                <td class="text-end pe-3 text-nowrap">
                                    <a href="{{ route($area . '.planning.slots.edit', $slot) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit" aria-hidden="true"></i><span class="visually-hidden">Modifier</span>
                                    </a>
                                    <form action="{{ route($area . '.planning.slots.duplicate', $slot) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary"
                                                title="Copier vers « {{ $period === 'scolaire' ? 'Vacances scolaires' : 'Période scolaire' }} »">
                                            <i class="fas fa-copy" aria-hidden="true"></i><span class="visually-hidden">Copier vers l'autre période</span>
                                        </button>
                                    </form>
                                    <form action="{{ route($area . '.planning.slots.destroy', $slot) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer ce créneau ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endforeach
</div>
@endsection
