@extends($layout)

@section('title', 'Importer le tableau des adhérents')

@section('content')
@php use App\Models\Membership as M; $r = fn ($n, $p = []) => route($area . '.members.' . $n, $p); @endphp
<div class="container-fluid">
    <h1 class="h3 fw-bold mb-3">Importer le tableau des adhérents</h1>
    @include('admin.members._nav')

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $r('import.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <label for="season" class="form-label fw-semibold">Saison</label>
                    <input type="text" id="season" name="season" class="form-control" value="{{ $season }}" pattern="\d{4}-\d{4}" required>
                </div>
                <div class="col-md-9">
                    <ol class="small mb-0">
                        <li>Dans Excel (ou LibreOffice), sélectionnez le tableau <strong>avec la ligne de titres</strong> (Observations, …, NOM, Prénom, …, Montant), puis copiez.</li>
                        <li>Collez ci-dessous, ou bien choisissez le fichier enregistré en CSV.</li>
                        <li>« Lire le tableau » affiche un aperçu ; rien n'est enregistré avant « Importer ».</li>
                    </ol>
                    <p class="small text-muted mb-0 mt-1">Un adhérent déjà connu (même nom, prénom et date de naissance) est mis à jour ; une cellule vide n'efface rien. Les catégories inconnues sont créées (vérifiez ensuite leur tarif).</p>
                </div>
                <div class="col-12">
                    <label for="text" class="form-label fw-semibold">Tableau copié</label>
                    <textarea id="text" name="text" rows="8" class="form-control font-monospace small">{{ $text }}</textarea>
                </div>
                <div class="col-md-6">
                    <label for="file" class="form-label fw-semibold">ou fichier CSV</label>
                    <input type="file" id="file" name="file" accept=".csv,.txt,.tsv" class="form-control">
                </div>
            </div>
            <div class="card-footer bg-white text-end">
                <button class="btn btn-outline-primary"><i class="fas fa-search me-1" aria-hidden="true"></i>Lire le tableau</button>
            </div>
        </div>

        @if ($parsed)
            <section class="card border-0 shadow-sm mb-4" aria-labelledby="apercu">
                <div class="card-header bg-white"><h2 id="apercu" class="h5 fw-bold mb-0">Aperçu : {{ count($parsed['rows']) }} adhérent(s)</h2></div>
                @if ($parsed['missing'])
                    <div class="card-body text-danger">Colonnes introuvables : {{ implode(', ', $parsed['missing']) }}. Vérifiez que la ligne de titres a bien été copiée.</div>
                @else
                    @if ($parsed['warnings'])
                        <div class="card-body border-bottom small text-warning-emphasis"><ul class="mb-0">@foreach ($parsed['warnings'] as $w)<li>{{ $w }}</li>@endforeach</ul></div>
                    @endif
                    <div class="table-responsive position-relative" style="max-height: 520px">
                        <table class="table table-sm align-middle mb-0 small">
                            <thead class="table-light sticky-top"><tr><th scope="col">Action</th><th scope="col">NOM Prénom</th><th scope="col">Naissance</th><th scope="col">Ville</th><th scope="col">Catégorie</th><th scope="col">Tarif</th><th scope="col">Remise</th><th scope="col">Situation</th><th scope="col">Statut</th><th scope="col">Dossier</th><th scope="col">P.S.</th><th scope="col">Image</th><th scope="col">Certif.</th></tr></thead>
                            <tbody>
                                @foreach ($parsed['rows'] as $i => $row)
                                    <tr>
                                        <td>@if ($preview[$i]['member'])<span class="badge bg-info">Mise à jour</span>@else<span class="badge bg-success">Nouveau</span>@endif</td>
                                        <th scope="row">{{ $row['last_name'] }} {{ $row['first_name'] }}</th>
                                        <td>{{ $row['birth_date'] ? \Illuminate\Support\Carbon::parse($row['birth_date'])->format('d/m/Y') : '—' }}</td>
                                        <td>{{ $row['city'] }}</td>
                                        <td>{{ $row['category'] }}</td>
                                        <td>{{ $row['price_cents'] !== null ? M::money($row['price_cents']) : '' }}</td>
                                        <td>{{ $row['discount_cents'] ? M::money($row['discount_cents']) : '' }}</td>
                                        <td>{{ M::SITUATIONS[$row['situation']] ?? '' }}</td>
                                        <td>{{ $row['status'] ? M::STATUSES[$row['status']][0] : '(auto)' }}</td>
                                        <td>{{ $row['file_complete'] ? 'O' : 'N' }}</td>
                                        <td>{{ $row['pass_sport'] ? 'O' : '' }}</td>
                                        <td>{{ $row['image_rights'] === null ? '' : ($row['image_rights'] ? 'O' : 'N') }}</td>
                                        <td>{{ $row['medical_certificate_year'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($parsed['rows'])
                        <div class="card-footer bg-white text-end">
                            <button class="btn btn-primary" name="confirm" value="1"><i class="fas fa-check me-1" aria-hidden="true"></i>Importer {{ count($parsed['rows']) }} adhérent(s) dans la saison {{ $season }}</button>
                        </div>
                    @endif
                @endif
            </section>
        @endif
    </form>
</div>
@endsection
