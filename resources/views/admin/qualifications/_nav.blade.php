{{-- Onglets d'une grille de qualification (administration). Reçoit $grid. --}}
<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
    <div>
        <p class="small text-muted mb-1"><a href="{{ route('admin.qualifications.index') }}">Grilles de qualification</a></p>
        <h1 class="h3 mb-1">{{ $grid->name }}
            @if ($grid->is_published)
                <span class="badge bg-success-subtle text-success-emphasis fs-6 align-middle">Publiée</span>
            @else
                <span class="badge bg-secondary-subtle text-secondary-emphasis fs-6 align-middle">Brouillon</span>
            @endif
        </h1>
        @if ($grid->period_label)<p class="text-muted mb-0 small"><i class="far fa-calendar me-1" aria-hidden="true"></i>Performances prises en compte {{ $grid->period_label }}</p>@endif
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if ($grid->is_published)
            <a href="{{ route('qualifications.show', $grid->slug) }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener"><i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Page publique</a>
        @endif
        <a href="{{ route('coach.qualifications.show', $grid) }}" class="btn btn-outline-success btn-sm"><i class="fas fa-medal me-1" aria-hidden="true"></i>Nageurs qualifiés</a>
    </div>
</div>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.qualifications.edit') ? 'active' : '' }}" href="{{ route('admin.qualifications.edit', $grid) }}" @if (request()->routeIs('admin.qualifications.edit')) aria-current="page" @endif><i class="fas fa-sliders-h me-1" aria-hidden="true"></i>Grille et catégories</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.qualifications.times') ? 'active' : '' }}" href="{{ route('admin.qualifications.times', $grid) }}" @if (request()->routeIs('admin.qualifications.times')) aria-current="page" @endif><i class="fas fa-stopwatch me-1" aria-hidden="true"></i>Temps</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.qualifications.import*') ? 'active' : '' }}" href="{{ route('admin.qualifications.import', $grid) }}" @if (request()->routeIs('admin.qualifications.import*')) aria-current="page" @endif><i class="fas fa-file-import me-1" aria-hidden="true"></i>Importer un tableau</a></li>
</ul>
@if ($errors->any())
    <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
