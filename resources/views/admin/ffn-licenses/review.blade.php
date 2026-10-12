@extends('layouts.admin')

@section('title', 'Aperçu de l\'import des licences FFN')

@php
    $statuts = [
        'new' => ['Nouvelle', 'bg-success'],
        'updated' => ['Modifiée', 'bg-warning text-dark'],
        'unchanged' => ['Identique', 'bg-light text-dark border'],
    ];
    $champs = [
        'civility' => 'civilité', 'gender' => 'sexe', 'last_name' => 'nom', 'first_name' => 'prénom', 'birth_date' => 'naissance',
        'nationality' => 'nationalité', 'address' => 'adresse', 'address2' => 'adresse', 'postal_code' => 'code postal', 'city' => 'ville',
        'country' => 'pays', 'phone1' => 'téléphone', 'phone2' => 'téléphone', 'phone3' => 'téléphone', 'email' => 'e-mail',
        'club_code' => 'club', 'club_name' => 'club', 'region' => 'région', 'department' => 'département',
        'medical_certificate_on' => 'certificat', 'license_type' => 'type', 'homologated_on' => 'homologation',
        'license_status' => 'statut', 'ffn_mail_consent' => 'accord mails', 'activities' => 'activités',
    ];
@endphp

@section('content')
<div class="ffn-licenses-admin">
    @include('admin.ffn-licenses._nav', ['active' => 'import'])

    <div class="alert alert-info d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span>
            <i class="fas fa-eye me-1" aria-hidden="true"></i>
            Aperçu de <strong>{{ $originalName }}</strong> — saison FFN <strong>{{ $season ?? 'non indiquée' }}</strong>
            <small class="text-muted">(encodage {{ $encoding }})</small>.
            Rien n'est encore enregistré.
        </span>
        <form method="POST" action="{{ route('admin.ffn-licenses.import.cancel', $token) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-secondary">Annuler l'import</button>
        </form>
    </div>

    {{-- Chiffres clés --}}
    <div class="row row-cols-2 row-cols-md-4 row-cols-xl-8 g-2 mb-3">
        @foreach ([
            ['Licences lues', count($rows), 'text-primary'],
            ['Nouvelles', $summary['new'], 'text-success'],
            ['Modifiées', $summary['updated'], 'text-warning-emphasis'],
            ['Identiques', $summary['unchanged'], 'text-secondary'],
            ['Erreurs', count($importErrors), count($importErrors) ? 'text-danger' : 'text-secondary'],
            ['Nageurs reconnus', $summary['match_iuf'] + $summary['match_name'], 'text-primary'],
            ['Non reconnus', $summary['unmatched'], 'text-secondary'],
            ['Comptes à rattacher', $summary['accounts'], 'text-primary'],
        ] as [$libelle, $valeur, $couleur])
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-2 px-3">
                        <div class="small text-muted text-truncate">{{ $libelle }}</div>
                        <div class="fs-4 fw-bold {{ $couleur }}">{{ $valeur }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (count($importErrors))
        <details class="card border-0 shadow-sm mb-3">
            <summary class="card-body py-2 text-danger fw-semibold">
                <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>{{ count($importErrors) }} ligne(s) ignorée(s) ou à vérifier — afficher le détail
            </summary>
            <ul class="small mb-3 mx-3">
                @foreach ($importErrors as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </details>
    @endif

    {{-- Options et confirmation --}}
    <form method="POST" action="{{ route('admin.ffn-licenses.import.store', $token) }}" class="card border-0 shadow-sm mb-4">
        @csrf
        <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-3">Options</h2>

            <div class="form-check mb-2">
                <input type="hidden" name="update_swimmers" value="0">
                <input class="form-check-input" type="checkbox" name="update_swimmers" value="1" id="update_swimmers" checked>
                <label class="form-check-label" for="update_swimmers">
                    Mettre à jour les fiches des nageurs reconnus (n° de licence, sexe, année de naissance)
                    <span class="text-muted small">— {{ $summary['match_iuf'] + $summary['match_name'] }} nageur(s)</span>
                </label>
            </div>

            <div class="form-check mb-2">
                <input type="hidden" name="create_swimmers" value="0">
                <input class="form-check-input" type="checkbox" name="create_swimmers" value="1" id="create_swimmers" data-bascule="types-a-creer">
                <label class="form-check-label" for="create_swimmers">
                    Créer une fiche nageur (sans groupe) pour les licenciés non reconnus de ces types :
                    <span class="text-muted small">— {{ $summary['unmatched'] }} licencié(s) non reconnu(s)</span>
                </label>
            </div>
            <div class="ms-4 mb-2 d-flex flex-wrap gap-3" id="types-a-creer">
                @foreach ($types as $typeLicence => $nombre)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="create_types[]" value="{{ $typeLicence }}" id="type-{{ $loop->index }}"
                               @checked(\Illuminate\Support\Str::contains(mb_strtolower($typeLicence), 'comp'))>
                        <label class="form-check-label small" for="type-{{ $loop->index }}">{{ $typeLicence }} ({{ $nombre }})</label>
                    </div>
                @endforeach
            </div>

            <div class="form-check mb-3">
                <input type="hidden" name="link_accounts" value="0">
                <input class="form-check-input" type="checkbox" name="link_accounts" value="1" id="link_accounts">
                <label class="form-check-label" for="link_accounts">
                    Rattacher au nageur le compte du site dont l'e-mail est celui de la licence (si la fiche n'a pas encore de compte)
                    <span class="text-muted small">— {{ $summary['accounts'] }} compte(s). La famille pourra alors répondre aux convocations.</span>
                </label>
            </div>

            @if ($summary['ambiguous'])
                <p class="small text-warning-emphasis mb-3">
                    <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>
                    {{ $summary['ambiguous'] }} licencié(s) correspondent à plusieurs fiches nageurs (homonymes) : ils ne seront pas liés automatiquement.
                    Renseignez le n° de licence dans la bonne fiche puis réimportez.
                </p>
            @endif

            <button type="submit" class="btn btn-success">
                <i class="fas fa-check me-1" aria-hidden="true"></i>Confirmer l'import de {{ count($rows) }} licence(s)
            </button>
        </div>
    </form>

    {{-- Détail des lignes --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h2 class="h6 fw-bold mb-0">Détail des licences</h2>
            <div>
                <label for="filtre-lignes" class="visually-hidden">Filtrer les lignes</label>
                <select id="filtre-lignes" class="form-select form-select-sm">
                    <option value="">Toutes les lignes</option>
                    <option value="new">Nouvelles</option>
                    <option value="updated">Modifiées</option>
                    <option value="unmatched">Nageur non reconnu</option>
                    <option value="ambiguous">Homonymes</option>
                </select>
            </div>
        </div>
        <div class="table-responsive position-relative">
            <table class="table table-sm align-middle mb-0" id="lignes-import">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3">Licence</th>
                        <th scope="col">Licencié</th>
                        <th scope="col">Naissance</th>
                        <th scope="col">Type</th>
                        <th scope="col">Nageur du planning</th>
                        <th scope="col">Compte du site</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $ligne)
                        @php $r = $ligne['row']; @endphp
                        <tr data-statut="{{ $ligne['status'] }}" data-match="{{ $ligne['match'] ?? 'unmatched' }}">
                            <td class="ps-3">
                                <span class="badge {{ $statuts[$ligne['status']][1] }}">{{ $statuts[$ligne['status']][0] }}</span>
                                @if ($ligne['changes'])
                                    <small class="text-muted d-block">{{ implode(', ', array_unique(array_map(fn ($c) => $champs[$c] ?? $c, $ligne['changes']))) }}</small>
                                @endif
                            </td>
                            <td>
                                <strong>{{ mb_strtoupper($r['last_name']) }}</strong> {{ $r['first_name'] }}
                                <small class="text-muted d-block">IUF {{ $r['iuf'] }}{{ $r['gender'] ? ' · ' . $r['gender'] : '' }}</small>
                            </td>
                            <td class="text-nowrap">{{ \Illuminate\Support\Carbon::parse($r['birth_date'])->format('d/m/Y') }}</td>
                            <td>
                                {{ $r['license_type'] ?? '—' }}
                                @if (! empty($r['license_status']))
                                    <small class="text-muted d-block">{{ $r['license_status'] }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($ligne['swimmer'])
                                    <i class="fas fa-link text-success me-1" aria-hidden="true"></i>{{ $ligne['swimmer']->full_name }}
                                    <small class="text-muted d-block">{{ $ligne['match'] === 'iuf' ? 'reconnu par n° de licence' : 'reconnu par nom et année' }}</small>
                                @elseif ($ligne['match'] === 'ambiguous')
                                    <span class="text-warning-emphasis"><i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>homonymes</span>
                                @else
                                    <span class="text-muted">non reconnu</span>
                                @endif
                            </td>
                            <td class="small">
                                @if ($ligne['account'])
                                    <i class="fas fa-user-check text-success me-1" aria-hidden="true"></i>{{ $ligne['account']->name }}
                                    @if ($ligne['swimmer'] && $ligne['swimmer']->user_id)
                                        <span class="text-muted d-block">fiche déjà rattachée</span>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Filtre des lignes de l'aperçu
    var filtre = document.getElementById('filtre-lignes');
    filtre.addEventListener('change', function () {
        document.querySelectorAll('#lignes-import tbody tr').forEach(function (tr) {
            var v = filtre.value;
            var visible = ! v || tr.dataset.statut === v || tr.dataset.match === v;
            tr.classList.toggle('d-none', ! visible);
        });
    });

    // Types de licences : actifs seulement si « Créer une fiche » est coché
    var creer = document.getElementById('create_swimmers');
    var types = document.getElementById('types-a-creer');
    var maj = function () {
        types.querySelectorAll('input').forEach(function (c) { c.disabled = ! creer.checked; });
        types.classList.toggle('opacity-50', ! creer.checked);
    };
    creer.addEventListener('change', maj);
    maj();
});
</script>
@endpush
