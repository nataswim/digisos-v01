@extends('layouts.admin')

@section('title', 'Grilles de qualification')

@section('content')
<div class="container-lg">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-table-cells text-primary me-2" aria-hidden="true"></i>Grilles de qualification</h1>
            <p class="text-muted mb-0">Temps à réaliser par catégorie d'âge, Dames et Messieurs, en bassin de 25 m et de 50 m. Une grille publiée apparaît sur le site, sans aucun nom de nageur.</p>
        </div>
        <a href="{{ route('admin.qualifications.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1" aria-hidden="true"></i>Nouvelle grille</a>
    </div>

    @if ($grids->isEmpty())
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-5">
            <p class="mb-3">Aucune grille pour l'instant.</p>
            <a href="{{ route('admin.qualifications.create') }}" class="btn btn-primary">Créer la première grille</a>
        </div></div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive position-relative">
                <table class="table align-middle mb-0">
                    <thead><tr>
                        <th scope="col">Grille</th>
                        <th scope="col">Période</th>
                        <th scope="col" class="text-center">Catégories</th>
                        <th scope="col" class="text-center">Temps</th>
                        <th scope="col">État</th>
                        <th scope="col" class="text-end"><span class="visually-hidden">Actions</span></th>
                    </tr></thead>
                    <tbody>
                        @foreach ($grids as $grid)
                            <tr>
                                <td><a href="{{ route('admin.qualifications.edit', $grid) }}" class="fw-semibold">{{ $grid->name }}</a></td>
                                <td class="small">{{ $grid->period_label ?? '—' }}</td>
                                <td class="text-center">{{ $grid->categories_count }}</td>
                                <td class="text-center">{{ $grid->times_count }}</td>
                                <td>
                                    @if ($grid->is_published)
                                        <span class="badge bg-success-subtle text-success-emphasis">Publiée</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis">Brouillon</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('admin.qualifications.times', $grid) }}" class="btn btn-sm btn-outline-primary" title="Temps"><i class="fas fa-stopwatch" aria-hidden="true"></i><span class="visually-hidden">Temps de {{ $grid->name }}</span></a>
                                    <a href="{{ route('coach.qualifications.show', $grid) }}" class="btn btn-sm btn-outline-success" title="Nageurs qualifiés"><i class="fas fa-medal" aria-hidden="true"></i><span class="visually-hidden">Nageurs qualifiés, {{ $grid->name }}</span></a>
                                    <form method="POST" action="{{ route('admin.qualifications.duplicate', $grid) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-secondary" title="Dupliquer"><i class="fas fa-copy" aria-hidden="true"></i><span class="visually-hidden">Dupliquer {{ $grid->name }}</span></button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.qualifications.destroy', $grid) }}" class="d-inline" onsubmit="return confirm('Supprimer cette grille et tous ses temps ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer {{ $grid->name }}</span></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
