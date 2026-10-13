@extends($layout)

@section('title', $competition->exists ? 'Modifier une compétition' : 'Nouvelle compétition')

@php
    // Lignes répétables : valeurs saisies (après une erreur) ou valeurs enregistrées
    $staffRows = old('staff', $competition->exists
        ? $competition->staff->map(fn ($s) => $s->only(['name', 'role', 'phone']))->all()
        : [['name' => '', 'role' => 'entraineur', 'phone' => '']]);

    $sessionRows = old('sessions', $competition->exists
        ? $competition->sessions->map(fn ($s) => [
            'id' => $s->id,
            'day' => $s->day->format('Y-m-d'),
            'label' => $s->label,
            'doors_open_at' => \App\Models\CompetitionSession::inputTime($s->doors_open_at),
            'warmup_at' => \App\Models\CompetitionSession::inputTime($s->warmup_at),
            'starts_at' => \App\Models\CompetitionSession::inputTime($s->starts_at),
            'events' => $s->events,
        ])->all()
        : []);

    $dateInput = fn ($value) => $value ? \Illuminate\Support\Carbon::parse($value)->format('Y-m-d') : '';
    $dateTimeInput = fn ($value) => $value ? \Illuminate\Support\Carbon::parse($value)->format('Y-m-d\TH:i') : '';

    // Onglet à rouvrir après une erreur de validation
    $ongletErreur = collect([
        'logistique' => ['meeting_point', 'departure_at', 'return_at', 'transport', 'accommodation', 'meals', 'logistics_notes', 'response_deadline'],
        'encadrement' => ['staff'],
        'programme' => ['sessions'],
        'documents' => ['new_documents', 'documents'],
    ])->filter(fn ($champs) => collect($errors->keys())->contains(fn ($k) => collect($champs)->contains(fn ($c) => str_starts_with($k, $c))))->keys()->first() ?? 'general';
@endphp

@section('content')
<div class="competitions-admin">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0">
            <i class="fas fa-trophy text-primary me-2" aria-hidden="true"></i>{{ $competition->exists ? $competition->title : 'Nouvelle compétition' }}
        </h1>
        <div class="d-flex gap-2">
            @if ($competition->exists)
                <a href="{{ route($area . '.competitions.program.edit', $competition) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-list-ol me-1" aria-hidden="true"></i>Grille des épreuves
                </a>
                <a href="{{ route($area . '.competitions.entries.index', $competition) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-users me-1" aria-hidden="true"></i>Convocations et engagements
                </a>
                <a href="{{ route('competitions.show', $competition) }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
                    <i class="fas fa-eye me-1" aria-hidden="true"></i>{{ $competition->is_published ? 'Voir la fiche' : 'Prévisualiser' }}
                </a>
            @endif
            <a href="{{ route($area . '.competitions.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Retour à la liste
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Le formulaire contient des erreurs :</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $competition->exists ? route($area . '.competitions.update', $competition) : route($area . '.competitions.store') }}">
        @csrf
        @if ($competition->exists) @method('PUT') @endif

        <ul class="nav nav-tabs" role="tablist">
            @foreach ([
                'general' => ['Informations', 'fa-info-circle'],
                'logistique' => ['Logistique', 'fa-bus'],
                'encadrement' => ['Encadrement', 'fa-user-tie'],
                'programme' => ['Programme', 'fa-list-ol'],
                'documents' => ['Documents', 'fa-file-pdf'],
            ] as $cle => [$libelle, $icone])
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $ongletErreur === $cle ? 'active' : '' }}" id="onglet-{{ $cle }}" type="button"
                            data-bs-toggle="tab" data-bs-target="#panneau-{{ $cle }}" role="tab"
                            aria-controls="panneau-{{ $cle }}" aria-selected="{{ $ongletErreur === $cle ? 'true' : 'false' }}">
                        <i class="fas {{ $icone }} me-1" aria-hidden="true"></i>{{ $libelle }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content card border-0 border-top-0 shadow-sm rounded-0 rounded-bottom mb-4">
            {{-- ============================================================ INFORMATIONS --}}
            <div class="tab-pane fade {{ $ongletErreur === 'general' ? 'show active' : '' }} p-4" id="panneau-general" role="tabpanel" aria-labelledby="onglet-general">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="title" class="form-label fw-semibold">Titre de la compétition</label>
                        <input type="text" id="title" name="title" maxlength="150" required
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $competition->title) }}" placeholder="ex. Meeting départemental de Niort">
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <label for="starts_on" class="form-label fw-semibold">Du</label>
                        <input type="date" id="starts_on" name="starts_on" required
                               class="form-control @error('starts_on') is-invalid @enderror"
                               value="{{ old('starts_on', $dateInput($competition->starts_on)) }}">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <label for="ends_on" class="form-label fw-semibold">Au</label>
                        <input type="date" id="ends_on" name="ends_on" required
                               class="form-control @error('ends_on') is-invalid @enderror"
                               value="{{ old('ends_on', $dateInput($competition->ends_on)) }}">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <label for="level" class="form-label fw-semibold">Niveau</label>
                        <select id="level" name="level" class="form-select @error('level') is-invalid @enderror" required>
                            @foreach (\App\Models\Competition::LEVELS as $cle => $libelle)
                                <option value="{{ $cle }}" @selected(old('level', $competition->level) === $cle)>{{ $libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <label for="kind" class="form-label fw-semibold">Type</label>
                        <select id="kind" name="kind" class="form-select @error('kind') is-invalid @enderror" required>
                            @foreach (\App\Models\Competition::KINDS as $cle => $libelle)
                                <option value="{{ $cle }}" @selected(old('kind', $competition->kind) === $cle)>{{ $libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="venue" class="form-label fw-semibold">Lieu</label>
                        <input type="text" id="venue" name="venue" maxlength="150" required
                               class="form-control @error('venue') is-invalid @enderror"
                               value="{{ old('venue', $competition->venue) }}" placeholder="ex. Centre aquatique Pré-Leroy">
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="form-label fw-semibold">Ville</label>
                        <input type="text" id="city" name="city" maxlength="100" required
                               class="form-control @error('city') is-invalid @enderror"
                               value="{{ old('city', $competition->city) }}">
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label fw-semibold">Adresse <span class="text-muted fw-normal">(facultatif)</span></label>
                        <input type="text" id="address" name="address" maxlength="255"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $competition->address) }}">
                    </div>

                    <div class="col-sm-4">
                        <label for="pool_length" class="form-label fw-semibold">Bassin</label>
                        <select id="pool_length" name="pool_length" class="form-select @error('pool_length') is-invalid @enderror">
                            <option value="">—</option>
                            @foreach ([25, 50] as $longueur)
                                <option value="{{ $longueur }}" @selected((int) old('pool_length', $competition->pool_length) === $longueur)>{{ $longueur }} m</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="lanes" class="form-label fw-semibold">Couloirs</label>
                        <input type="number" id="lanes" name="lanes" min="1" max="12"
                               class="form-control @error('lanes') is-invalid @enderror"
                               value="{{ old('lanes', $competition->lanes) }}">
                    </div>
                    <div class="col-sm-4">
                        <label for="organizer" class="form-label fw-semibold">Organisateur</label>
                        <input type="text" id="organizer" name="organizer" maxlength="150"
                               class="form-control @error('organizer') is-invalid @enderror"
                               value="{{ old('organizer', $competition->organizer) }}" placeholder="ex. Comité des Deux-Sèvres">
                    </div>

                    <div class="col-md-6">
                        <label for="extranat_url" class="form-label fw-semibold">Lien extraNat <span class="text-muted fw-normal">(facultatif)</span></label>
                        <input type="url" id="extranat_url" name="extranat_url" maxlength="255" placeholder="https://"
                               class="form-control @error('extranat_url') is-invalid @enderror"
                               value="{{ old('extranat_url', $competition->extranat_url) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="live_url" class="form-label fw-semibold">Résultats en direct (liveFFN…) <span class="text-muted fw-normal">(facultatif)</span></label>
                        <input type="url" id="live_url" name="live_url" maxlength="255" placeholder="https://"
                               class="form-control @error('live_url') is-invalid @enderror"
                               value="{{ old('live_url', $competition->live_url) }}">
                    </div>

                    <div class="col-lg-8">
                        <label for="description" class="form-label fw-semibold">Présentation publique <span class="text-muted fw-normal">(facultatif)</span></label>
                        {{-- Éditeur Quill (mêmes outils que les fiches : titres, listes, liens, images de la médiathèque, vidéos, tableaux) --}}
                        <div id="description-editor" class="competition-editor @error('description') is-invalid @enderror"></div>
                        <textarea id="description" name="description" class="d-none">{{ old('description', $competition->description) }}</textarea>
                        <div class="form-text">Affichée sur la fiche publique de la compétition : présentation, consignes, liens utiles…</div>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4">
                        <label for="image" class="form-label fw-semibold">Image de couverture <span class="text-muted fw-normal">(facultatif)</span></label>
                        <div class="input-group">
                            <input type="text" id="image" name="image" maxlength="255"
                                   class="form-control @error('image') is-invalid @enderror"
                                   value="{{ old('image', $competition->image) }}"
                                   placeholder="/storage/media/… ou https://…" aria-describedby="image_aide">
                            <button type="button" class="btn btn-outline-primary" onclick="openMediaSelector('image', 'imagePreview')" title="Choisir dans la médiathèque">
                                <i class="fas fa-images" aria-hidden="true"></i><span class="visually-hidden">Choisir dans la médiathèque</span>
                            </button>
                        </div>
                        <div id="image_aide" class="form-text">Médiathèque ou adresse d'une image. Format paysage conseillé (1200 × 630 px).</div>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @php $imageActuelle = old('image', $competition->image); @endphp
                        <div id="currentImagePreview" class="mt-3 {{ $imageActuelle ? '' : 'd-none' }}">
                            <img id="imagePreview" src="{{ $imageActuelle }}" alt="Aperçu de l'image de couverture"
                                 class="img-fluid rounded shadow-sm competition-cover-preview">
                            <button type="button" class="btn btn-sm btn-link text-danger px-0 d-block" id="retirer-image">
                                <i class="fas fa-times me-1" aria-hidden="true"></i>Retirer l'image
                            </button>
                        </div>
                    </div>

                    <div class="col-12">
                        <span class="form-label fw-semibold d-block mb-2">Groupes concernés</span>
                        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-1">
                            @foreach ($groups as $groupe)
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="groups[]" value="{{ $groupe->id }}" id="groupe-{{ $groupe->id }}"
                                               @checked(in_array($groupe->id, array_map('intval', old('groups', $selectedGroups)), true))>
                                        <label class="form-check-label" for="groupe-{{ $groupe->id }}">{{ $groupe->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="state_override" class="form-label fw-semibold">Statut</label>
                        <select id="state_override" name="state_override" class="form-select @error('state_override') is-invalid @enderror">
                            <option value="">Automatique selon les dates (à venir, en cours, terminée)</option>
                            @foreach (\App\Models\Competition::STATE_OVERRIDES as $cle => $libelle)
                                <option value="{{ $cle }}" @selected(old('state_override', $competition->state_override) === $cle)>{{ $libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input type="hidden" name="is_published" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1"
                                   @checked(old('is_published', $competition->is_published))>
                            <label class="form-check-label fw-semibold" for="is_published">Publier sur le calendrier du site</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================ LOGISTIQUE --}}
            <div class="tab-pane fade {{ $ongletErreur === 'logistique' ? 'show active' : '' }} p-4" id="panneau-logistique" role="tabpanel" aria-labelledby="onglet-logistique">
                <p class="text-muted small"><i class="fas fa-lock me-1" aria-hidden="true"></i>Ces informations ne sont visibles que par les adhérents connectés.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="meeting_point" class="form-label fw-semibold">Lieu de rendez-vous</label>
                        <input type="text" id="meeting_point" name="meeting_point" maxlength="255"
                               class="form-control @error('meeting_point') is-invalid @enderror"
                               value="{{ old('meeting_point', $competition->meeting_point) }}" placeholder="ex. Parking du centre aquatique Cœur d'O">
                    </div>
                    <div class="col-md-6">
                        <label for="transport" class="form-label fw-semibold">Transport</label>
                        <select id="transport" name="transport" class="form-select @error('transport') is-invalid @enderror">
                            <option value="">—</option>
                            @foreach (\App\Models\Competition::TRANSPORTS as $cle => $libelle)
                                <option value="{{ $cle }}" @selected(old('transport', $competition->transport) === $cle)>{{ $libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="departure_at" class="form-label fw-semibold">Départ</label>
                        <input type="datetime-local" id="departure_at" name="departure_at"
                               class="form-control @error('departure_at') is-invalid @enderror"
                               value="{{ old('departure_at', $dateTimeInput($competition->departure_at)) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="return_at" class="form-label fw-semibold">Retour prévu</label>
                        <input type="datetime-local" id="return_at" name="return_at"
                               class="form-control @error('return_at') is-invalid @enderror"
                               value="{{ old('return_at', $dateTimeInput($competition->return_at)) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="accommodation" class="form-label fw-semibold">Hébergement</label>
                        <textarea id="accommodation" name="accommodation" rows="3" maxlength="3000"
                                  class="form-control @error('accommodation') is-invalid @enderror"
                                  placeholder="Hôtel, adresse, répartition des chambres…">{{ old('accommodation', $competition->accommodation) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="meals" class="form-label fw-semibold">Repas et collations</label>
                        <textarea id="meals" name="meals" rows="3" maxlength="3000"
                                  class="form-control @error('meals') is-invalid @enderror"
                                  placeholder="Pique-nique à prévoir, repas du soir pris en charge…">{{ old('meals', $competition->meals) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label for="logistics_notes" class="form-label fw-semibold">Informations complémentaires</label>
                        <textarea id="logistics_notes" name="logistics_notes" rows="3" maxlength="3000"
                                  class="form-control @error('logistics_notes') is-invalid @enderror"
                                  placeholder="Affaires à prévoir, autorisation parentale, coût du déplacement…">{{ old('logistics_notes', $competition->logistics_notes) }}</textarea>
                    </div>
                </div>

                <hr class="my-4">
                <h2 class="h6 fw-bold mb-3"><i class="fas fa-envelope-open-text text-primary me-2" aria-hidden="true"></i>Convocations</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="response_deadline" class="form-label fw-semibold">Date limite de réponse des familles</label>
                        <input type="date" id="response_deadline" name="response_deadline"
                               class="form-control @error('response_deadline') is-invalid @enderror"
                               value="{{ old('response_deadline', $dateInput($competition->response_deadline)) }}"
                               aria-describedby="response_deadline_aide">
                        <div id="response_deadline_aide" class="form-text">Vide : {{ \App\Models\Competition::DEFAULT_RESPONSE_DAYS }} jours avant le début (règlement du club). Après cette date, les familles ne peuvent plus répondre en ligne.</div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input type="hidden" name="entries_visible" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="entries_visible" name="entries_visible" value="1"
                                   @checked(old('entries_visible', $competition->entries_visible))>
                            <label class="form-check-label fw-semibold" for="entries_visible">Afficher la liste des engagés sur la fiche publique</label>
                            <div class="form-text">Prénom et initiale pour le public, nom complet pour les adhérents connectés.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================ ENCADREMENT --}}
            <div class="tab-pane fade {{ $ongletErreur === 'encadrement' ? 'show active' : '' }} p-4" id="panneau-encadrement" role="tabpanel" aria-labelledby="onglet-encadrement">
                <p class="text-muted small"><i class="fas fa-lock me-1" aria-hidden="true"></i>Les numéros de téléphone ne sont visibles que par les adhérents connectés. Une ligne sans nom est ignorée.</p>

                <div id="lignes-encadrement" data-next="{{ count($staffRows) }}">
                    @foreach ($staffRows as $i => $ligne)
                        @include('admin.competitions.partials.staff-row', ['i' => $i, 'ligne' => $ligne])
                    @endforeach
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm" data-ajouter="encadrement">
                    <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter une personne
                </button>

                <template id="modele-encadrement">
                    @include('admin.competitions.partials.staff-row', ['i' => '__INDEX__', 'ligne' => ['name' => '', 'role' => 'entraineur', 'phone' => '']])
                </template>
            </div>

            {{-- ============================================================ PROGRAMME --}}
            <div class="tab-pane fade {{ $ongletErreur === 'programme' ? 'show active' : '' }} p-4" id="panneau-programme" role="tabpanel" aria-labelledby="onglet-programme">
                <p class="text-muted small">Une réunion par bloc (ex. « Samedi matin — séries »). Une réunion sans date est ignorée.</p>
                @if ($competition->exists && $competition->sessions->isNotEmpty())
                    <div class="alert alert-info d-flex flex-wrap align-items-center justify-content-between gap-2 py-2">
                        <span class="small"><i class="fas fa-th me-1" aria-hidden="true"></i>Cochez les épreuves de chaque réunion (Dames / Messieurs, séries, finales A, B, C) dans la grille du programme.</span>
                        <a href="{{ route($area . '.competitions.program.edit', $competition) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-list-ol me-1" aria-hidden="true"></i>Grille des épreuves
                        </a>
                    </div>
                @elseif ($competition->exists)
                    <p class="small text-muted"><i class="fas fa-info-circle me-1" aria-hidden="true"></i>Ajoutez les réunions puis enregistrez : la grille des épreuves sera alors disponible.</p>
                @else
                    <p class="small text-muted"><i class="fas fa-info-circle me-1" aria-hidden="true"></i>Enregistrez la compétition avec ses réunions : la grille des épreuves sera alors disponible.</p>
                @endif

                <div id="lignes-programme" data-next="{{ count($sessionRows) }}">
                    @foreach ($sessionRows as $i => $ligne)
                        @include('admin.competitions.partials.session-row', ['i' => $i, 'ligne' => $ligne])
                    @endforeach
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm" data-ajouter="programme">
                    <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter une réunion
                </button>

                <template id="modele-programme">
                    @include('admin.competitions.partials.session-row', ['i' => '__INDEX__', 'ligne' => []])
                </template>
            </div>

            {{-- ============================================================ DOCUMENTS --}}
            <div class="tab-pane fade {{ $ongletErreur === 'documents' ? 'show active' : '' }} p-4" id="panneau-documents" role="tabpanel" aria-labelledby="onglet-documents">
                @if ($competition->exists && $competition->documents->isNotEmpty())
                    <h2 class="h6 fw-bold">Documents enregistrés</h2>
                    <div class="table-responsive position-relative mb-4">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr><th scope="col">Document</th><th scope="col">Visible par</th><th scope="col" class="text-center">Supprimer</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($competition->documents as $document)
                                    <tr>
                                        <td>
                                            <a href="{{ route('competitions.document', [$competition, $document]) }}" target="_blank" rel="noopener">
                                                <i class="fas fa-file-pdf text-danger me-1" aria-hidden="true"></i>{{ $document->title }}
                                            </a>
                                            <small class="text-muted">({{ $document->size_label }})</small>
                                        </td>
                                        <td>
                                            <label for="doc-vis-{{ $document->id }}" class="visually-hidden">Visibilité de {{ $document->title }}</label>
                                            <select id="doc-vis-{{ $document->id }}" name="documents[{{ $document->id }}][visibility]" class="form-select form-select-sm">
                                                @foreach (\App\Models\CompetitionDocument::VISIBILITIES as $cle => $libelle)
                                                    <option value="{{ $cle }}" @selected($document->visibility === $cle)>{{ $libelle }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" name="documents[{{ $document->id }}][delete]" value="1"
                                                   aria-label="Supprimer {{ $document->title }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <h2 class="h6 fw-bold">Ajouter des documents (PDF, 10 Mo maximum)</h2>
                <div id="lignes-documents" data-next="1">
                    @include('admin.competitions.partials.document-row', ['i' => 0])
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" data-ajouter="documents">
                    <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter un autre document
                </button>
                <template id="modele-documents">
                    @include('admin.competitions.partials.document-row', ['i' => '__INDEX__'])
                </template>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
            </button>
            <a href="{{ route($area . '.competitions.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .competitions-admin .competition-editor { height: 360px; background: #fff; }
    .competitions-admin .competition-editor.is-invalid { border-color: #dc3545; }
    .competitions-admin .competition-cover-preview { max-height: 160px; width: 100%; object-fit: cover; }
</style>
@endpush

@push('scripts')
@once
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="{{ asset('js/media-selector.js') }}"></script>
<script src="{{ asset('js/quill-advanced.js') }}"></script>
@endonce
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Éditeur de la présentation (même configuration que les fiches)
        var editeur = null;
        if (document.getElementById('description-editor') && typeof initQuillEditor === 'function') {
            editeur = initQuillEditor('#description-editor', 'description');
        }
        var formulaire = document.getElementById('description').form;
        formulaire.addEventListener('submit', function () {
            if (editeur) {
                document.getElementById('description').value = editeur.root.innerHTML;
            }
        });

        // Image de couverture : aperçu et retrait
        var champ = document.getElementById('image');
        var apercu = document.getElementById('imagePreview');
        var bloc = document.getElementById('currentImagePreview');
        champ.addEventListener('input', function () {
            var url = champ.value.trim();
            apercu.src = url;
            bloc.classList.toggle('d-none', url === '');
        });
        document.getElementById('retirer-image').addEventListener('click', function () {
            champ.value = '';
            apercu.removeAttribute('src');
            bloc.classList.add('d-none');
        });
    });
</script>
<script>
    (function () {
        // Ajout d'une ligne (encadrement, réunion, document) à partir de son modèle <template>
        document.querySelectorAll('[data-ajouter]').forEach(function (bouton) {
            bouton.addEventListener('click', function () {
                var cle = bouton.dataset.ajouter;
                var liste = document.getElementById('lignes-' + cle);
                var modele = document.getElementById('modele-' + cle);
                var index = parseInt(liste.dataset.next, 10);
                liste.dataset.next = index + 1;
                liste.insertAdjacentHTML('beforeend', modele.innerHTML.replace(/__INDEX__/g, index));
                var premier = liste.lastElementChild.querySelector('input, select, textarea');
                if (premier) premier.focus();
            });
        });

        // Retrait d'une ligne
        document.addEventListener('click', function (e) {
            var bouton = e.target.closest('[data-retirer]');
            if (bouton) {
                bouton.closest('.ligne-repetable').remove();
            }
        });
    })();
</script>
@endpush
