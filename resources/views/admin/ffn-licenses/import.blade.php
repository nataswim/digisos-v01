@extends('layouts.admin')

@section('title', 'Importer les licences FFN')

@section('content')
<div class="ffn-licenses-admin">
    @include('admin.ffn-licenses._nav', ['active' => 'import'])

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Déposer l'extraction extraNat</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $erreur)
                                <div>{{ $erreur }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if (session('import_errors'))
                        <div class="alert alert-warning small" role="alert">
                            <ul class="mb-0">
                                @foreach (array_slice(session('import_errors'), 0, 20) as $erreur)
                                    <li>{{ $erreur }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.ffn-licenses.import.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <label for="file" class="form-label fw-semibold">Fichier CSV des licences</label>
                        <input type="file" id="file" name="file" accept=".csv,text/csv" required
                               class="form-control @error('file') is-invalid @enderror" aria-describedby="file_aide">
                        <div id="file_aide" class="form-text">
                            Export « extraction licences » d'extraNat, tel quel (séparateur « ; », 5 Mo maximum).
                            Rien n'est enregistré avant la confirmation : l'étape suivante affiche un aperçu.
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-search me-1" aria-hidden="true"></i>Lire le fichier et prévisualiser
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 small">
                    <h2 class="h6 fw-bold mb-3"><i class="fas fa-info-circle text-primary me-1" aria-hidden="true"></i>Ce que fait l'import</h2>
                    <ul class="mb-0 ps-3">
                        <li class="mb-1">Enregistre ou met à jour chaque licence de la saison (clé : n° IUF). Réimporter le même fichier ne crée pas de doublon.</li>
                        <li class="mb-1">Reconnaît les nageurs du planning par leur n° de licence, sinon par nom, prénom et année de naissance.</li>
                        <li class="mb-1">En option : met à jour leur fiche (n° de licence, sexe, année de naissance), crée les fiches manquantes et rattache le compte du site dont l'e-mail correspond.</li>
                        <li>Le fichier déposé n'est pas conservé : seules les données importées restent en base.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <h2 class="h6 fw-bold mb-0">Derniers imports</h2>
        </div>
        @if ($imports->isEmpty())
            <div class="card-body text-muted small">Aucun import pour l'instant.</div>
        @else
            <div class="table-responsive position-relative">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">Date</th>
                            <th scope="col">Fichier</th>
                            <th scope="col">Saison</th>
                            <th scope="col" class="text-center">Nouvelles</th>
                            <th scope="col" class="text-center">Mises à jour</th>
                            <th scope="col" class="text-center">Erreurs</th>
                            <th scope="col" class="text-center">Nageurs liés</th>
                            <th scope="col">Par</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($imports as $import)
                            <tr>
                                <td class="ps-3 text-nowrap">{{ $import->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-break">{{ $import->original_name }}</td>
                                <td>{{ $import->season }}</td>
                                <td class="text-center">{{ $import->created_count }}</td>
                                <td class="text-center">{{ $import->updated_count }}</td>
                                <td class="text-center">
                                    @if ($import->error_count)
                                        <span class="badge bg-warning text-dark" title="{{ implode(' | ', array_slice($import->errors ?? [], 0, 5)) }}">{{ $import->error_count }}</span>
                                    @else
                                        0
                                    @endif
                                </td>
                                <td class="text-center">{{ $import->swimmers_linked }}{{ $import->swimmers_created ? ' (+' . $import->swimmers_created . ' créés)' : '' }}</td>
                                <td>{{ optional($import->user)->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
