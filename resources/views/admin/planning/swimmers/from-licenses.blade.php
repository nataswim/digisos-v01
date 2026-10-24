@extends($layout)

@section('title', 'Planning — ajouter des nageurs depuis les licences FFN')

@section('content')
<div class="planning-admin">
    @include('admin.planning._nav', ['active' => 'swimmers'])

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h2 class="h5 fw-bold mb-0">
            <i class="fas fa-id-card text-primary me-2" aria-hidden="true"></i>Ajouter des nageurs depuis les licences FFN
        </h2>
        <a href="{{ route('admin.planning.swimmers.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Retour aux nageurs
        </a>
    </div>

    @if ($seasons->isEmpty())
        <div class="alert alert-info" role="status">
            Aucune licence importée pour l'instant.
            <a href="{{ route('admin.ffn-licenses.import.create') }}">Importer l'extraction des licences depuis extraNat</a>.
        </div>
    @else
        {{-- Filtres --}}
        <form method="GET" action="{{ route('admin.planning.swimmers.from-licenses') }}" class="d-flex flex-wrap gap-2 align-items-end mb-3" role="search">
            <div>
                <label for="saison" class="form-label small mb-1">Saison</label>
                <select id="saison" name="saison" class="form-select" onchange="this.form.submit()">
                    @foreach ($seasons as $s)
                        <option value="{{ $s }}" @selected((int) $s === $season)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="type" class="form-label small mb-1">Type de licence</label>
                <select id="type" name="type" class="form-select">
                    <option value="">Tous ({{ $remaining }})</option>
                    @foreach ($types as $libelle => $total)
                        <option value="{{ $libelle }}" @selected($type === $libelle)>{{ $libelle ?: 'Non renseigné' }} ({{ $total }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="q" class="form-label small mb-1">Nom ou prénom</label>
                <input type="search" id="q" name="q" value="{{ $search }}" class="form-control" placeholder="Rechercher…">
            </div>
            @if ($selectedGroup)
                <input type="hidden" name="groupe" value="{{ $selectedGroup }}">
            @endif
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-filter me-1" aria-hidden="true"></i>Filtrer</button>
        </form>

        @if ($licenses->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-check-circle fa-2x text-success mb-3 d-block" aria-hidden="true"></i>
                    @if ($remaining === 0)
                        Tous les licenciés de la saison {{ $season }} ont déjà une fiche nageur.
                    @else
                        Aucun licencié sans fiche ne correspond à ces filtres.
                    @endif
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('admin.planning.swimmers.from-licenses.store') }}" id="form-licences">
                @csrf

                <div class="card border-0 shadow-sm mb-3">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-3" style="width: 48px;">
                                        <input type="checkbox" class="form-check-input" id="tout-cocher" aria-label="Tout cocher">
                                    </th>
                                    <th scope="col">Licencié</th>
                                    <th scope="col">Naissance</th>
                                    <th scope="col">Type de licence</th>
                                    <th scope="col">Fiche nageur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($licenses as $license)
                                    @php $existant = $matches->get($license->id); @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <input type="checkbox" class="form-check-input case-licence" name="licenses[]" value="{{ $license->id }}"
                                                   id="licence-{{ $license->id }}" @checked(in_array($license->id, array_map('intval', old('licenses', [])), true))>
                                        </td>
                                        <td>
                                            <label for="licence-{{ $license->id }}" class="fw-semibold mb-0">{{ $license->full_name }}</label>
                                            <div class="small text-muted">
                                                {{ $license->gender === 'F' ? 'Fille / femme' : ($license->gender === 'M' ? 'Garçon / homme' : '') }}
                                                · licence {{ $license->iuf }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ $license->birth_date?->year }}
                                            @if ($license->age_in_year !== null)
                                                <span class="small text-muted">({{ $license->age_in_year }} ans cette année)</span>
                                            @endif
                                        </td>
                                        <td class="small">
                                            {{ $license->license_type ?: '—' }}
                                            @if ($license->license_status)
                                                <br><span class="text-muted">{{ $license->license_status }}</span>
                                            @endif
                                        </td>
                                        <td class="small">
                                            @if ($existant)
                                                <span class="badge bg-info-subtle text-info-emphasis border">
                                                    <i class="fas fa-link me-1" aria-hidden="true"></i>Reprendre « {{ $existant->full_name }} »
                                                </span>
                                            @else
                                                <span class="badge bg-success-subtle text-success-emphasis border">
                                                    <i class="fas fa-plus me-1" aria-hidden="true"></i>Nouvelle fiche
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6 fw-bold mb-3">Pour les licenciés cochés <span class="text-muted fw-normal">(<span id="nb-coches">0</span> sélectionné(s))</span></h3>

                        <span class="form-label fw-semibold d-block mb-2">Ajouter au(x) groupe(s) <span class="text-muted fw-normal">(facultatif)</span></span>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-1 mb-3">
                            @foreach ($groups as $group)
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="groups[]" value="{{ $group->id }}" id="groupe-{{ $group->id }}"
                                               @checked(in_array($group->id, array_map('intval', old('groups', array_filter([$selectedGroup]))), true))>
                                        <label class="form-check-label" for="groupe-{{ $group->id }}">
                                            {{ $group->name }} <small class="text-muted">— {{ $group->section }}</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input type="hidden" name="link_accounts" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="link_accounts" name="link_accounts" value="1" @checked(old('link_accounts', true))>
                            <label class="form-check-label" for="link_accounts">
                                Rattacher le compte du site dont l'adresse e-mail est celle de la licence (la famille pourra répondre aux convocations)
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" id="bouton-ajouter" disabled>
                            <i class="fas fa-user-plus me-1" aria-hidden="true"></i>Ajouter les nageurs cochés
                        </button>
                        <p class="small text-muted mt-3 mb-0">
                            <i class="fas fa-info-circle me-1" aria-hidden="true"></i>
                            « Reprendre » : une fiche nageur existe déjà avec le même nom, prénom et année de naissance ;
                            elle est complétée (licence, sexe) au lieu d'en créer une seconde.
                            Seuls le nom, le prénom, l'année de naissance, le sexe et le n° de licence sont copiés dans la fiche nageur.
                        </p>
                    </div>
                </div>
            </form>
        @endif
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tout = document.getElementById('tout-cocher');
        var cases = Array.prototype.slice.call(document.querySelectorAll('.case-licence'));
        var compteur = document.getElementById('nb-coches');
        var bouton = document.getElementById('bouton-ajouter');
        if (!cases.length) return;

        function maj() {
            var n = cases.filter(function (c) { return c.checked; }).length;
            compteur.textContent = n;
            bouton.disabled = n === 0;
            tout.checked = n === cases.length;
            tout.indeterminate = n > 0 && n < cases.length;
        }

        tout.addEventListener('change', function () {
            cases.forEach(function (c) { c.checked = tout.checked; });
            maj();
        });
        cases.forEach(function (c) { c.addEventListener('change', maj); });
        maj();
    });
</script>
@endpush
