@extends('layouts.admin')

@section('title', 'Licences FFN')

@section('content')
<div class="ffn-licenses-admin">
    @include('admin.ffn-licenses._nav', ['active' => 'index'])

    @if ($seasons->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5 text-center text-muted">
                <i class="fas fa-file-import fa-2x text-primary mb-3 d-block" aria-hidden="true"></i>
                Aucune licence importée pour l'instant.
                <div class="mt-3"><a href="{{ route('admin.ffn-licenses.import.create') }}" class="btn btn-primary">Importer l'extraction extraNat</a></div>
            </div>
        </div>
    @else
        {{-- Saison et chiffres clés --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <form method="GET" action="{{ route('admin.ffn-licenses.index') }}" class="d-flex align-items-center gap-2">
                <label for="saison" class="fw-semibold text-nowrap">Saison FFN</label>
                <select id="saison" name="saison" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach ($seasons as $s)
                        <option value="{{ $s }}" @selected($season === (int) $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </form>
            @if ($lastImport)
                <small class="text-muted">
                    Dernier import le {{ $lastImport->created_at->format('d/m/Y à H\hi') }}{{ $lastImport->user ? ' par ' . $lastImport->user->name : '' }}
                </small>
            @endif
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-2 mb-3">
            @foreach ([
                ['Licenciés', $stats['total'], 'fa-id-card'],
                ['Nouvelles licences', $stats['new'], 'fa-star'],
                ['Filles / garçons', $stats['girls'] . ' / ' . $stats['boys'], 'fa-venus-mars'],
                ['Liés à un nageur', $stats['linked'], 'fa-link'],
                ['Types de licence', $types->count(), 'fa-tags'],
            ] as [$libelle, $valeur, $icone])
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-2 px-3">
                            <div class="small text-muted text-truncate"><i class="fas {{ $icone }} text-primary me-1" aria-hidden="true"></i>{{ $libelle }}</div>
                            <div class="fs-4 fw-bold">{{ $valeur }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Filtres --}}
        <form method="GET" action="{{ route('admin.ffn-licenses.index') }}" class="card border-0 shadow-sm mb-3">
            <div class="card-body py-3">
                <input type="hidden" name="saison" value="{{ $season }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label for="q" class="form-label small mb-1">Rechercher</label>
                        <input type="search" id="q" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Nom, prénom, IUF ou e-mail">
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="form-label small mb-1">Type de licence</label>
                        <select id="type" name="type" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            @foreach ($types as $libelle => $nombre)
                                @if ($libelle !== '')
                                    <option value="{{ $libelle }}" @selected($type === $libelle)>{{ $libelle }} ({{ $nombre }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="lien" class="form-label small mb-1">Nageur du planning</label>
                        <select id="lien" name="lien" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            <option value="lie" @selected($lien === 'lie')>Lié à une fiche nageur</option>
                            <option value="non-lie" @selected($lien === 'non-lie')>Sans fiche nageur</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-filter me-1" aria-hidden="true"></i>Filtrer</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Liste --}}
        <div class="card border-0 shadow-sm">
            <div class="table-responsive position-relative">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">Licencié</th>
                            <th scope="col">Naissance</th>
                            <th scope="col">Licence</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Nageur du planning</th>
                            <th scope="col" class="text-end pe-3"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($licenses as $licence)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('admin.ffn-licenses.show', $licence->id) }}" class="fw-semibold text-decoration-none">{{ $licence->full_name }}</a>
                                    <small class="text-muted d-block">IUF {{ $licence->iuf }}{{ $licence->gender ? ' · ' . $licence->gender : '' }}</small>
                                </td>
                                <td class="text-nowrap">
                                    {{ $licence->birth_date->format('d/m/Y') }}
                                    <small class="text-muted d-block">{{ $licence->age }} ans</small>
                                </td>
                                <td>
                                    {{ $licence->license_type ?? '—' }}
                                    @if ($licence->license_status)
                                        <small class="d-block {{ str_starts_with($licence->license_status, 'Nouvel') ? 'text-success' : 'text-muted' }}">{{ $licence->license_status }}</small>
                                    @endif
                                </td>
                                <td class="small">
                                    @foreach (array_slice($licence->phones, 0, 1) as $tel)
                                        <a href="tel:{{ \App\Models\FfnLicense::phoneHref($tel) }}" class="text-nowrap d-block">{{ $tel }}</a>
                                    @endforeach
                                    @if ($licence->email)
                                        <a href="mailto:{{ $licence->email }}" class="text-break">{{ $licence->email }}</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($licence->swimmer)
                                        <a href="{{ route('admin.planning.swimmers.edit', $licence->swimmer->id) }}" class="text-decoration-none">
                                            <i class="fas fa-link text-success me-1" aria-hidden="true"></i>{{ $licence->swimmer->full_name }}
                                        </a>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('admin.ffn-licenses.show', $licence->id) }}" class="btn btn-sm btn-outline-primary" title="Fiche complète">
                                        <i class="fas fa-eye" aria-hidden="true"></i><span class="visually-hidden">Fiche de {{ $licence->full_name }}</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-5">Aucun licencié ne correspond à ces filtres.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">{{ $licenses->links('pagination::bootstrap-5') }}</div>

        {{-- RGPD : suppression d'une saison --}}
        <div class="card border-danger-subtle mt-4">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                <p class="small text-muted mb-0">
                    <i class="fas fa-trash-alt me-1" aria-hidden="true"></i>
                    Ne conservez pas les données plus longtemps que nécessaire : supprimez les licences d'une saison terminée.
                    Les fiches nageurs du planning ne sont pas supprimées.
                </p>
                <form method="POST" action="{{ route('admin.ffn-licenses.season.destroy', $season) }}"
                      onsubmit="return confirm('Supprimer définitivement les {{ $stats['total'] }} licences de la saison {{ $season }} ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer la saison {{ $season }}</button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
