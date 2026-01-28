@extends('layouts.public')

@section('title', 'Guide - Calendrier d\'Activités')

@section('content')

<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <h1 class="display-4 fw-bold mb-0">Outil planification</h1>
                </div>
                <p class="lead mb-4">
                    Votre outil personnel pour organiser et suivre toutes vos activités.
                </p>
            </div>
            <div class="col-lg-5 text-center">
                <img src="{{ asset('assets/images/team/guide-utilisation.jpg') }}"
                     alt="Calendrier Nataswim" 
                     class="img-fluid rounded-4 shadow-lg"
                     style="max-height: 400px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<!-- Alerte Premium -->
<section class="py-3 bg-warning">
    <div class="container-lg">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-crown me-3 fs-3"></i>
                <div>
                 Le planificateur d'activités est réservé aux membres Premium pour une expérience complète et illimitée.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Table des matières -->
<section class="py-4 bg-light border-bottom sticky-top" style="top: 70px; z-index: 100;">
    <div class="container-lg">
        <nav class="d-flex flex-wrap justify-content-center gap-2">
            <a href="#vue-ensemble" class="btn btn-outline-primary btn-sm">🎯 Vue d'ensemble</a>
            <a href="#acces" class="btn btn-outline-primary btn-sm">🚪 Accéder</a>
            <a href="#planifier" class="btn btn-outline-primary btn-sm">➕ Planifier</a>
            <a href="#lier-seance" class="btn btn-outline-primary btn-sm">🏋️ Lier séance</a>
            <a href="#lier-exercices" class="btn btn-outline-primary btn-sm">🏃 Lier exercices</a>
            <a href="#modifier" class="btn btn-outline-primary btn-sm">✏️ Modifier</a>
            <a href="#completer" class="btn btn-outline-primary btn-sm">✅ Compléter</a>
            <a href="#historique" class="btn btn-outline-primary btn-sm">📊 Historique</a>
            <a href="#statistiques" class="btn btn-outline-primary btn-sm">📈 Statistiques</a>
            <a href="#conseils" class="btn btn-outline-primary btn-sm">💡 Conseils</a>
            <a href="#faq" class="btn btn-outline-primary btn-sm">❓ FAQ</a>
            <a href="#exemples" class="btn btn-outline-primary btn-sm">📝 Exemples</a>
        </nav>
    </div>
</section>

<!-- 1. Vue d'ensemble -->
<section id="vue-ensemble" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-eye text-primary" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Vue d'ensemble</h2>
            <p class="text-muted lead">Découvrez votre outil de planification tout-en-un</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-12">
                <div class="card border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-bullseye me-2"></i>Qu'est-ce que le Calendrier ?</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Le <strong>Calendrier d'Activités</strong> est votre outil personnel pour :</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                    <span>Planifier vos entraînements et événements sportifs</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                    <span>Organiser vos séances semaine après semaine</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                    <span>Lier vos activités à des programmes structurés</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                    <span>Suivre vos performances et progression</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                    <span>Analyser vos résultats avec des statistiques détaillées</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card h-100 border-info shadow-sm hover-lift">
                    <div class="card-header bg-info text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-star me-2"></i>Fonctionnalités clés</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Planification</strong></td>
                                    <td>Créer des activités avec date, heure, lieu</td>
                                </tr>
                                <tr>
                                    <td><strong>Liaison séances</strong></td>
                                    <td>Associer une séance d'entraînement complète</td>
                                </tr>
                                <tr>
                                    <td><strong>Liaison exercices</strong></td>
                                    <td>Sélectionner plusieurs exercices spécifiques</td>
                                </tr>
                                <tr>
                                    <td><strong>Suivi performances</strong></td>
                                    <td>Noter vos sensations après chaque séance</td>
                                </tr>
                                <tr>
                                    <td><strong>Statistiques</strong></td>
                                    <td>Visualiser votre progression mensuelle</td>
                                </tr>
                                <tr>
                                    <td><strong>Historique</strong></td>
                                    <td>Consulter toutes vos activités passées</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100 border-success shadow-sm hover-lift">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-list-ul me-2"></i>Types d'activités disponibles</h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex align-items-center">
                                <span class="badge bg-primary me-3">🏋️</span>
                                <div>
                                    <strong>ENTRAÎNEMENT</strong>
                                    <small class="d-block text-muted">Vos séances d'entraînement classiques</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <span class="badge bg-info me-3">📅</span>
                                <div>
                                    <strong>RENDEZ-VOUS</strong>
                                    <small class="d-block text-muted">RDV médecin, kiné, nutritionniste</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <span class="badge bg-warning me-3">🍽️</span>
                                <div>
                                    <strong>STAGE</strong>
                                    <small class="d-block text-muted">Stages sportifs, séminaires</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <span class="badge bg-danger me-3">💊</span>
                                <div>
                                    <strong>COMPÉTITION</strong>
                                    <small class="d-block text-muted">Courses, compétitions officielles</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <span class="badge bg-secondary me-3">📝</span>
                                <div>
                                    <strong>AUTRES</strong>
                                    <small class="d-block text-muted">Toute autre activité sportive</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="fas fa-crown fa-2x me-3 text-warning"></i>
                <div>
                    <strong>Accès Premium requis</strong> - Cette fonctionnalité est réservée aux membres Premium. 
                    <a href="{{ route('pricing') }}" class="alert-link">Découvrez nos offres</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Accéder au calendrier -->
<section id="acces" class="py-5 bg-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-door-open text-success" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Accéder au calendrier</h2>
            <p class="text-muted lead">Trouvez rapidement votre calendrier</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm hover-lift">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-bars me-2"></i>Depuis le menu principal</h3>
                    </div>
                    <div class="card-body">
                        <p>Accédez au calendrier via la navigation principale :</p>
                        <div class="bg-light p-4 rounded border">
                            <div class="d-flex flex-column gap-2">
                                <div class="p-2 bg-white rounded">🏠 Tableau de bord</div>
                                <div class="p-2 bg-primary text-white rounded fw-bold">📅 Calendrier [3] ← ICI</div>
                                <div class="p-2 bg-white rounded">🏋️ Mes séances</div>
                                <div class="p-2 bg-white rounded">👤 Mon profil</div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3 mb-0">
                            <small><i class="fas fa-info-circle me-2"></i>Le chiffre entre crochets <strong>[3]</strong> indique le nombre d'activités planifiées cette semaine.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100 shadow-sm hover-lift">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-home me-2"></i>Depuis le tableau de bord</h3>
                    </div>
                    <div class="card-body">
                        <p>Sur votre page d'accueil, vous verrez une carte <strong>"Mes activités"</strong> :</p>
                        <div class="card border-success">
                            <div class="card-header bg-success-subtle">
                                <strong>📅 Mes activités cette semaine</strong>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="fas fa-bell text-warning me-2"></i>
                                    <strong>3</strong> activités planifiées
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-clock text-danger me-2"></i>
                                    <strong>1</strong> activité à compléter
                                </div>
                                <div class="border-top pt-3">
                                    <div class="text-muted small mb-2">🎯 Prochaine activité :</div>
                                    <div class="fw-bold">Séance jambes - Demain 18h00</div>
                                </div>
                                <button class="btn btn-primary btn-sm mt-3 w-100">
                                    Voir tout le calendrier →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Planifier une nouvelle activité -->
<section id="planifier" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-plus-circle text-success" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Planifier une nouvelle activité</h2>
            <p class="text-muted lead">Créez votre activité en 4 étapes simples</p>
        </div>

        <!-- Étape 1 -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-step-forward me-2"></i>Étape 1 : Accéder au formulaire</h3>
                    </div>
                    <div class="card-body">
                        <p>Depuis la page Calendrier, cliquez sur le bouton bleu en haut à droite :</p>
                        <div class="bg-light p-4 rounded text-center">
                            <button class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Planifier activité
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Étape 2 -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-info shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-step-forward me-2"></i>Étape 2 : Informations générales</h3>
                    </div>
                    <div class="card-body">
                        <h4 class="h6 mb-3">📋 Section "Informations générales"</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Champ</th>
                                        <th>Description</th>
                                        <th>Exemple</th>
                                        <th>Obligatoire</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Discipline</strong></td>
                                        <td>Sport pratiqué</td>
                                        <td>Course à pied, Natation</td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Titre</strong></td>
                                        <td>Nom de votre activité</td>
                                        <td>Sortie longue endurance</td>
                                        <td><span class="badge bg-success">Oui</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Type</strong></td>
                                        <td>Catégorie</td>
                                        <td>Entraînement</td>
                                        <td><span class="badge bg-success">Oui</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Objectif</strong></td>
                                        <td>But de la séance</td>
                                        <td>Tenir 1h30 sans pause</td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-success border-0 mt-4">
                            <strong>💡 Exemple de remplissage :</strong>
                            <ul class="mb-0 mt-2">
                                <li>Discipline : Course à pied</li>
                                <li>Titre : Sortie longue dimanche</li>
                                <li>Type : 🏋️ Entraînement</li>
                                <li>Objectif : Courir 15 km à allure modérée</li>
                            </ul>
                        </div>

                        <h4 class="h6 mb-3 mt-4">📅 Section "Date & Lieu"</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Champ</th>
                                        <th>Description</th>
                                        <th>Exemple</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Date</strong></td>
                                        <td>Jour de l'activité</td>
                                        <td>15/12/2025</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Heure</strong></td>
                                        <td>Heure de début</td>
                                        <td>08:00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lieu</strong></td>
                                        <td>Où se déroulera la séance</td>
                                        <td>Parc de la Tête d'Or</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-primary border-0">
                            <i class="fas fa-lightbulb me-2"></i><strong>Astuce :</strong> Planifiez à l'avance pour être organisé !
                        </div>

                        <h4 class="h6 mb-3 mt-4">📝 Section "Détails"</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6><i class="fas fa-file-alt me-2 text-primary"></i>Description</h6>
                                        <p class="small text-muted mb-0">Détails de la séance (échauffement, séries, récup...)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6><i class="fas fa-sticky-note me-2 text-warning"></i>Remarques</h6>
                                        <p class="small text-muted mb-0">Notes personnelles, état de forme prévu</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6><i class="fas fa-toolbox me-2 text-success"></i>Matériel</h6>
                                        <p class="small text-muted mb-0">Équipement nécessaire</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6><i class="fas fa-clock me-2 text-info"></i>Durée prévue</h6>
                                        <p class="small text-muted mb-0">Temps estimé (ex: 1h30)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Étape 3 -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-warning shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="h5 mb-0"><i class="fas fa-step-forward me-2"></i>Étape 3 : Lier à un contenu (optionnel mais recommandé)</h3>
                    </div>
                    <div class="card-body">
                        <p class="lead">C'est ici que la magie opère ! Vous pouvez :</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card bg-primary-subtle border-primary h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-dumbbell fa-3x text-primary mb-3"></i>
                                        <h5>Option A</h5>
                                        <p class="mb-0">Lier une séance d'entraînement complète</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-success-subtle border-success h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-running fa-3x text-success mb-3"></i>
                                        <h5>Option B</h5>
                                        <p class="mb-0">Sélectionner plusieurs exercices spécifiques</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-danger border-0 mt-4 mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i><strong>Important :</strong> Vous ne pouvez pas lier les deux en même temps. Choisissez l'un <strong>OU</strong> l'autre.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Étape 4 -->
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-success shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-step-forward me-2"></i>Étape 4 : Enregistrer</h3>
                    </div>
                    <div class="card-body">
                        <p>Cliquez sur le bouton vert en bas :</p>
                        <div class="text-center bg-light p-4 rounded">
                            <button class="btn btn-secondary me-3">
                                <i class="fas fa-times me-2"></i>Annuler
                            </button>
                            <button class="btn btn-success btn-lg">
                                <i class="fas fa-check me-2"></i>Planifier l'activité
                            </button>
                        </div>
                        <div class="alert alert-success border-0 mt-4 mb-0">
                            <i class="fas fa-check-circle me-2"></i><strong>Votre activité est maintenant planifiée !</strong> Elle apparaîtra dans votre calendrier avec un badge coloré selon le type, les informations principales et les contenus liés.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Lier une séance d'entraînement -->
<section id="lier-seance" class="py-5 bg-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-dumbbell text-primary" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Lier une séance d'entraînement</h2>
            <p class="text-muted lead">Associez votre activité à un programme complet</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-12">
                <div class="card border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-question-circle me-2"></i>Qu'est-ce qu'une séance d'entraînement ?</h3>
                    </div>
                    <div class="card-body">
                        <p>Une <strong>séance d'entraînement</strong> (ou "Workout") est un programme complet créé par nos coachs ou par vous-même, contenant :</p>
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                                    <p class="small mb-0">Description détaillée</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-list-ol fa-2x text-success mb-2"></i>
                                    <p class="small mb-0">Exercices structurés</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-sync fa-2x text-warning mb-2"></i>
                                    <p class="small mb-0">Séries et répétitions</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-clipboard-check fa-2x text-info mb-2"></i>
                                    <p class="small mb-0">Consignes techniques</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comment lier -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-link me-2"></i>Comment lier une séance ?</h3>
                    </div>
                    <div class="card-body">
                        <!-- Étape 1 -->
                        <div class="mb-4">
                            <h5 class="text-primary"><i class="fas fa-1 me-2"></i>Étape 1 : Onglet "Séance d'entraînement"</h5>
                            <p>Dans la section "Lier à un contenu", cliquez sur l'onglet :</p>
                            <div class="bg-light p-3 rounded">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-primary">🏋️ Séance d'entraînement</button>
                                    <button class="btn btn-outline-secondary">Exercices</button>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 2 -->
                        <div class="mb-4">
                            <h5 class="text-primary"><i class="fas fa-2 me-2"></i>Étape 2 : Navigation en cascade</h5>
                            
                            <div class="accordion" id="accordionSeance">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSection">
                                            2.1 - Sélectionner une Section
                                        </button>
                                    </h2>
                                    <div id="collapseSection" class="accordion-collapse collapse show" data-bs-parent="#accordionSeance">
                                        <div class="accordion-body">
                                            <p>Les séances sont organisées par <strong>Sections</strong> (grandes catégories) :</p>
                                            <div class="row g-2">
                                                <div class="col-md-3"><span class="badge bg-primary w-100">🏃 Course à pied</span></div>
                                                <div class="col-md-3"><span class="badge bg-success w-100">🏋️ Musculation</span></div>
                                                <div class="col-md-3"><span class="badge bg-info w-100">🏊 Natation</span></div>
                                                <div class="col-md-3"><span class="badge bg-warning w-100">🚴 Cyclisme</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategorie">
                                            2.2 - Sélectionner une Catégorie
                                        </button>
                                    </h2>
                                    <div id="collapseCategorie" class="accordion-collapse collapse" data-bs-parent="#accordionSeance">
                                        <div class="accordion-body">
                                            <p>Une fois la section choisie, sélectionnez une <strong>Catégorie</strong> plus précise :</p>
                                            <ul class="list-group">
                                                <li class="list-group-item">Endurance fondamentale</li>
                                                <li class="list-group-item">Fractionné</li>
                                                <li class="list-group-item">Sortie longue</li>
                                                <li class="list-group-item">Récupération</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVisualize">
                                            2.3 - Visualiser les séances disponibles
                                        </button>
                                    </h2>
                                    <div id="collapseVisualize" class="accordion-collapse collapse" data-bs-parent="#accordionSeance">
                                        <div class="accordion-body">
                                            <p>Les séances s'affichent sous forme de <strong>cartes visuelles</strong> :</p>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="seance" id="seance1">
                                                                <label class="form-check-label" for="seance1">
                                                                    <strong>Sortie longue progressive</strong>
                                                                    <p class="small text-muted mb-0">🏋️ Séance d'endurance sur 2h avec progression de l'allure</p>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="seance" id="seance2">
                                                                <label class="form-check-label" for="seance2">
                                                                    <strong>Endurance 45 min</strong>
                                                                    <p class="small text-muted mb-0">🏋️ Séance de récupération active à allure lente</p>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 3 -->
                        <div class="mb-4">
                            <h5 class="text-primary"><i class="fas fa-3 me-2"></i>Étape 3 : Sélectionner UNE séance</h5>
                            <div class="alert alert-warning border-0">
                                <i class="fas fa-exclamation-triangle me-2"></i><strong>Une seule séance</strong> peut être sélectionnée par activité. Si vous changez d'avis, cliquez simplement sur une autre séance.
                            </div>
                        </div>

                        <!-- Résultat -->
                        <div class="alert alert-success border-0 mb-0">
                            <h5 class="alert-heading"><i class="fas fa-trophy me-2"></i>Avantages :</h5>
                            <ul class="mb-0">
                                <li>✅ Accès direct au programme complet</li>
                                <li>✅ Tous les exercices détaillés</li>
                                <li>✅ Consignes techniques disponibles</li>
                                <li>✅ Lien cliquable dans le calendrier</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. Lier des exercices -->
<section id="lier-exercices" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-running text-success" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Lier des exercices</h2>
            <p class="text-muted lead">Composez votre séance sur mesure</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card border-success shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-question-circle me-2"></i>Qu'est-ce qu'un exercice ?</h3>
                    </div>
                    <div class="card-body">
                        <p>Un <strong>exercice</strong> est un mouvement unique avec :</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Nom et description</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Muscles ciblés</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Niveau de difficulté</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Consignes de sécurité</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Vidéo explicative (optionnel)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-info shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-bullseye me-2"></i>Pourquoi lier des exercices ?</h3>
                    </div>
                    <div class="card-body">
                        <p>Parfait pour :</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Créer une séance personnalisée</li>
                            <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Cibler des muscles spécifiques</li>
                            <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Composer un circuit sur mesure</li>
                            <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Tester de nouveaux exercices</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comment lier -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-link me-2"></i>Comment lier des exercices ?</h3>
                    </div>
                    <div class="card-body">
                        <!-- Étape 1 -->
                        <div class="mb-4 pb-4 border-bottom">
                            <h5 class="text-primary"><i class="fas fa-1 me-2"></i>Étape 1 : Onglet "Exercices"</h5>
                            <div class="bg-light p-3 rounded">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-secondary">Séance d'entraînement</button>
                                    <button class="btn btn-success">🏃 Exercices</button>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 2 -->
                        <div class="mb-4 pb-4 border-bottom">
                            <h5 class="text-primary"><i class="fas fa-2 me-2"></i>Étape 2 : Filtrer par catégorie (optionnel)</h5>
                            <p>Vous pouvez filtrer les exercices par catégorie :</p>
                            <div class="row g-2">
                                <div class="col-md-3"><span class="badge bg-danger w-100">Haut du corps</span></div>
                                <div class="col-md-3"><span class="badge bg-primary w-100">Bas du corps</span></div>
                                <div class="col-md-3"><span class="badge bg-warning w-100">Abdominaux / Gainage</span></div>
                                <div class="col-md-3"><span class="badge bg-info w-100">Cardio</span></div>
                            </div>
                            <div class="alert alert-info border-0 mt-3 mb-0">
                                <i class="fas fa-lightbulb me-2"></i><strong>Astuce :</strong> Laissez "Tous les exercices" pour voir l'intégralité.
                            </div>
                        </div>

                        <!-- Étape 3 -->
                        <div class="mb-4 pb-4 border-bottom">
                            <h5 class="text-primary"><i class="fas fa-3 me-2"></i>Étape 3 : Visualiser les exercices</h5>
                            <p>Les exercices s'affichent en <strong>cartes avec checkboxes</strong> :</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="ex1">
                                                <label class="form-check-label" for="ex1">
                                                    <strong>Développé couché</strong>
                                                    <div class="mt-1">
                                                        <span class="badge bg-warning">Force</span>
                                                        <span class="badge bg-info">Intermédiaire</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="ex2">
                                                <label class="form-check-label" for="ex2">
                                                    <strong>Squat barre</strong>
                                                    <div class="mt-1">
                                                        <span class="badge bg-warning">Force</span>
                                                        <span class="badge bg-danger">Avancé</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 4 -->
                        <div class="mb-4 pb-4 border-bottom">
                            <h5 class="text-primary"><i class="fas fa-4 me-2"></i>Étape 4 : Sélectionner PLUSIEURS exercices</h5>
                            <p><strong>Cochez les exercices</strong> que vous voulez inclure. Les cartes <strong class="text-success">deviennent vertes</strong> quand elles sont sélectionnées.</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card border-success bg-success-subtle">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" checked id="ex3">
                                                <label class="form-check-label" for="ex3">
                                                    <strong>Développé couché ✓</strong>
                                                    <div class="small text-success mt-1">SÉLECTIONNÉ</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="ex4">
                                                <label class="form-check-label" for="ex4">
                                                    <strong>Squat barre</strong>
                                                    <div class="small text-muted mt-1">NON SÉLECTIONNÉ</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success bg-success-subtle">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" checked id="ex5">
                                                <label class="form-check-label" for="ex5">
                                                    <strong>Pompes ✓</strong>
                                                    <div class="small text-success mt-1">SÉLECTIONNÉ</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 5 -->
                        <div class="mb-4">
                            <h5 class="text-primary"><i class="fas fa-5 me-2"></i>Étape 5 : Compteur de sélection</h5>
                            <div class="alert alert-success border-0 mb-0">
                                <i class="fas fa-check-circle me-2"></i>✅ <strong>3 exercice(s) sélectionné(s)</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- 6. Modifier une activité -->
<section id="modifier" class="py-5 bg-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-edit text-warning" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Modifier une activité</h2>
            <p class="text-muted lead">Ajustez vos activités en toute flexibilité</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card border-success shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-check-circle me-2"></i>Vous pouvez modifier</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Activités <strong>avant</strong> qu'elles aient lieu</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Activités avec statut "Planifié"</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Tous les champs (titre, date, lieu, etc.)</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>NOUVEAU : Les contenus liés</strong> ⭐</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-danger shadow-sm h-100">
                    <div class="card-header bg-danger text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-times-circle me-2"></i>Vous ne pouvez PAS modifier</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Une activité déjà <strong>complétée</strong></li>
                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Une activité <strong>annulée</strong></li>
                        </ul>
                        <div class="alert alert-danger border-0 mt-3 mb-0">
                            <small><i class="fas fa-info-circle me-2"></i>Pour les activités terminées, seule la consultation est possible.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card shadow-sm hover-lift h-100">
                    <div class="card-header bg-primary text-white">
                        <h4 class="h6 mb-0">Option 1 : Depuis la liste du calendrier</h4>
                    </div>
                    <div class="card-body">
                        <p>Sur chaque activité, cliquez sur l'icône <strong>crayon</strong> :</p>
                        <div class="card bg-light">
                            <div class="card-body">
                                <strong>🏋️ Séance jambes - Demain 18h00</strong>
                                <div class="text-muted small mb-2">📍 Salle de sport</div>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary">👁️ Voir</button>
                                    <button class="btn btn-sm btn-warning">✏️ Modifier</button>
                                    <button class="btn btn-sm btn-outline-danger">❌ Annuler</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm hover-lift h-100">
                    <div class="card-header bg-info text-white">
                        <h4 class="h6 mb-0">Option 2 : Depuis la page de détail</h4>
                    </div>
                    <div class="card-body">
                        <p>Ouvrez l'activité puis cliquez sur <strong>"Modifier"</strong> :</p>
                        <div class="card bg-light">
                            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                                <strong>🏋️ Séance jambes</strong>
                                <button class="btn btn-sm btn-warning">✏️ Modifier</button>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-0">Détails de l'activité...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- 7. Compléter une activité -->
<section id="completer" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-check-double text-success" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Compléter une activité après réalisation</h2>
            <p class="text-muted lead">Suivez vos performances et progressez intelligemment</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-12">
                <div class="card border-info shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-question-circle me-2"></i>Pourquoi compléter ?</h3>
                    </div>
                    <div class="card-body">
                        <p>Compléter une activité permet de :</p>
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                                    <p class="small mb-0">Suivre vos <strong>performances réelles</strong></p>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-balance-scale fa-2x text-success mb-2"></i>
                                    <p class="small mb-0">Comparer objectif <strong>prévu vs réalisé</strong></p>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                                    <p class="small mb-0">Noter votre <strong>ressenti</strong> après l'effort</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-chart-bar fa-2x text-warning mb-2"></i>
                                    <p class="small mb-0">Alimenter vos <strong>statistiques</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-primary border-0 mt-3 mb-0">
                            <i class="fas fa-lightbulb me-2"></i><strong>Conseil :</strong> Complétez dans les <strong>24 heures</strong> pour des données précises.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quand compléter -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="h5 mb-0"><i class="fas fa-clock me-2"></i>Quand compléter ?</h3>
                    </div>
                    <div class="card-body">
                        <p>Une activité <strong>à compléter</strong> apparaît dans une section dédiée <strong>après</strong> sa date/heure prévue :</p>
                        <div class="card bg-light">
                            <div class="card-header">
                                <strong>⏱️ À compléter (2)</strong>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>🏋️ Séance jambes</strong>
                                            <div class="text-muted small">Hier 18h00</div>
                                        </div>
                                        <button class="btn btn-warning btn-sm">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Compléter mon retour
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>🏃 Sortie longue</strong>
                                            <div class="text-muted small">Avant-hier 08h00</div>
                                        </div>
                                        <button class="btn btn-warning btn-sm">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Compléter mon retour
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comment compléter -->
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-success shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-list-check me-2"></i>Comment compléter ?</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Section 1: Performance -->
                            <div class="col-12">
                                <h5 class="text-success"><i class="fas fa-1 me-2"></i>Section PERFORMANCE</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Champ</th>
                                                <th>Description</th>
                                                <th>Obligatoire</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Ressenti de l'effort</strong></td>
                                                <td>Échelle 1-10</td>
                                                <td><span class="badge bg-success">Oui</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Objectif</strong></td>
                                                <td>Atteint / Non atteint / Dépassé</td>
                                                <td><span class="badge bg-success">Oui</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Durée réelle</strong></td>
                                                <td>Temps effectif</td>
                                                <td><span class="badge bg-secondary">Non</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Distance réelle</strong></td>
                                                <td>Distance parcourue</td>
                                                <td><span class="badge bg-secondary">Non</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="card bg-primary-subtle mt-3">
                                    <div class="card-header">
                                        <strong>Échelle de ressenti (1-10)</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <div class="display-6">😊</div>
                                                        <strong>1-3 : FACILE</strong>
                                                        <p class="small text-muted mb-0">Je peux parler facilement</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <div class="display-6">😐</div>
                                                        <strong>4-6 : MODÉRÉ</strong>
                                                        <p class="small text-muted mb-0">Je respire plus fort</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <div class="display-6">😰</div>
                                                        <strong>7-9 : DIFFICILE</strong>
                                                        <p class="small text-muted mb-0">Je suis essoufflé</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <div class="display-6">😵</div>
                                                        <strong>10 : MAXIMAL</strong>
                                                        <p class="small text-muted mb-0">Je ne peux plus parler</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-success border-0 mt-3">
                                    <strong>Exemple :</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Ressenti : 7/10</li>
                                        <li>Objectif : ✅ Atteint</li>
                                        <li>Durée réelle : 1h32</li>
                                        <li>Distance réelle : 14.8 km</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Section 2: Conditions -->
                            <div class="col-md-6">
                                <h5 class="text-info"><i class="fas fa-2 me-2"></i>Section CONDITIONS</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <strong>Météo :</strong>
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            <span class="badge bg-warning">☀️ Ensoleillé</span>
                                            <span class="badge bg-secondary">☁️ Nuageux</span>
                                            <span class="badge bg-primary">🌧️ Pluie</span>
                                            <span class="badge bg-info">💨 Vent</span>
                                            <span class="badge bg-light text-dark">❄️ Froid</span>
                                            <span class="badge bg-danger">🔥 Chaud</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: État physique -->
                            <div class="col-md-6">
                                <h5 class="text-danger"><i class="fas fa-3 me-2"></i>Section ÉTAT PHYSIQUE</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <strong>Douleurs/Gênes :</strong>
                                        <p class="small text-muted mb-0 mt-2">Notez toute douleur ressentie</p>
                                        <div class="alert alert-light border mt-2 mb-0">
                                            <small><em>Exemple : Légère tension au mollet gauche après 1h. Rien de grave, surveiller pour la prochaine séance.</em></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-secondary me-3">
                                <i class="fas fa-times me-2"></i>Annuler
                            </button>
                            <button class="btn btn-success btn-lg">
                                <i class="fas fa-check me-2"></i>Enregistrer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 8. Consulter l'historique -->
<section id="historique" class="py-5 bg-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-history text-primary" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Consulter l'historique</h2>
            <p class="text-muted lead">Votre calendrier organisé en 4 sections</p>
        </div>

        <div class="row g-4">
            <!-- Section 1 -->
            <div class="col-lg-6">
                <div class="card border-primary shadow-sm h-100 hover-lift">
                    <div class="card-header bg-primary text-white">
                        <h4 class="h6 mb-0"><i class="fas fa-bell me-2"></i>1️⃣ Événements de cette semaine</h4>
                    </div>
                    <div class="card-body">
                        <p class="small mb-3">Toutes les activités du <strong>lundi au dimanche</strong> en cours</p>
                        <div class="card bg-light">
                            <div class="card-body p-2">
                                <div class="small">
                                    <div class="mb-2">
                                        <strong>🏋️ Lundi 16 déc - 18:00</strong>
                                        <div class="text-muted">Séance Upper Body</div>
                                        <span class="badge bg-info">Planifié</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>🏃 Mercredi 18 déc - 07:00</strong>
                                        <div class="text-muted">Course matinale</div>
                                        <span class="badge bg-info">Planifié</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <strong>Actions disponibles :</strong>
                            <div class="btn-group btn-group-sm mt-2" role="group">
                                <button class="btn btn-outline-primary">👁️ Voir</button>
                                <button class="btn btn-outline-warning">✏️ Modifier</button>
                                <button class="btn btn-outline-danger">❌ Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2 -->
            <div class="col-lg-6">
                <div class="card border-warning shadow-sm h-100 hover-lift">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="h6 mb-0"><i class="fas fa-clock me-2"></i>2️⃣ À compléter</h4>
                    </div>
                    <div class="card-body">
                        <p class="small mb-3">Activités <strong>passées</strong> mais <strong>non finalisées</strong></p>
                        <div class="card bg-light">
                            <div class="card-body p-2">
                                <div class="small">
                                    <div class="mb-2">
                                        <strong>🏋️ Dimanche 15 déc - 08:00</strong>
                                        <div class="text-muted">Sortie longue</div>
                                        <button class="btn btn-warning btn-sm mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Compléter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info border-0 mt-3 mb-0">
                            <small><i class="fas fa-info-circle me-2"></i>Cette section disparaît quand vous complétez toutes vos activités.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3 -->
            <div class="col-lg-6">
                <div class="card border-info shadow-sm h-100 hover-lift">
                    <div class="card-header bg-info text-white">
                        <h4 class="h6 mb-0"><i class="fas fa-calendar-plus me-2"></i>3️⃣ Événements à venir</h4>
                    </div>
                    <div class="card-body">
                        <p class="small mb-3">Activités planifiées <strong>après cette semaine</strong></p>
                        <div class="card bg-light">
                            <div class="card-body p-2">
                                <div class="small">
                                    <div class="mb-2">
                                        <strong>🏋️ Lundi 23 déc - 18:00</strong>
                                        <div class="text-muted">Reprise post-repos</div>
                                    </div>
                                    <div class="mb-2">
                                        <strong>💊 Dimanche 29 déc - 09:00</strong>
                                        <div class="text-muted">Semi-marathon de la ville</div>
                                        <div class="text-success small">🎯 Objectif: Passer sous 1h45</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <strong>Actions disponibles :</strong>
                            <div class="btn-group btn-group-sm mt-2" role="group">
                                <button class="btn btn-outline-primary">👁️ Voir</button>
                                <button class="btn btn-outline-warning">✏️ Modifier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4 -->
            <div class="col-lg-6">
                <div class="card border-success shadow-sm h-100 hover-lift">
                    <div class="card-header bg-success text-white">
                        <h4 class="h6 mb-0"><i class="fas fa-check-circle me-2"></i>4️⃣ Événements passés</h4>
                    </div>
                    <div class="card-body">
                        <p class="small mb-3">Les <strong>20 dernières</strong> activités terminées</p>
                        <div class="card bg-light">
                            <div class="card-body p-2">
                                <div class="small">
                                    <div class="mb-2">
                                        <strong>🏋️ Vendredi 13 déc - 18:00</strong>
                                        <div class="text-muted">Séance Upper Body</div>
                                        <div class="text-success">⭐ Ressenti: 6/10 | ✅ Objectif atteint</div>
                                    </div>
                                    <div class="mb-2">
                                        <strong>🏃 Mercredi 11 déc - 07:00</strong>
                                        <div class="text-muted">Course matinale</div>
                                        <div class="text-success">⭐ Ressenti: 5/10 | 🎯 Objectif dépassé</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <strong>Action disponible :</strong>
                            <button class="btn btn-outline-primary btn-sm mt-2">👁️ Voir détails</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. Comprendre les statistiques -->
<section id="statistiques" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-chart-line text-success" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Comprendre les statistiques</h2>
            <p class="text-muted lead">Analysez vos performances mensuelles</p>
        </div>

        <!-- Carte des stats -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card border-primary shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-chart-bar me-2"></i>📊 Statistiques du mois</h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center g-4">
                            <div class="col-md-3">
                                <div class="p-4 bg-light rounded">
                                    <div class="display-4 text-primary fw-bold">12</div>
                                    <p class="mb-0">Activités<br>réalisées</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-4 bg-light rounded">
                                    <div class="display-4 text-success fw-bold">6.8/10</div>
                                    <p class="mb-0">Ressenti<br>moyen</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-4 bg-light rounded">
                                    <div class="display-4 text-warning fw-bold">75%</div>
                                    <p class="mb-0">Objectifs<br>atteints</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-4 bg-light rounded">
                                    <div class="display-4 text-info fw-bold">9/12</div>
                                    <p class="mb-0">Ratio<br>objectifs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Les 4 indicateurs -->
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm hover-lift">
                    <div class="card-header bg-primary text-white">
                        <h4 class="h6 mb-0"><i class="fas fa-1 me-2"></i>Activités réalisées</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Définition :</strong> Nombre d'activités <strong>complétées</strong> ce mois</p>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Score</th>
                                    <th>Signification</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>&lt; 8</td>
                                    <td>Peu actif</td>
                                    <td>Augmenter la fréquence</td>
                                </tr>
                                <tr class="table-success">
                                    <td>8-15</td>
                                    <td>Rythme modéré</td>
                                    <td>Bon équilibre ✅</td>
                                </tr>
                                <tr>
                                    <td>&gt; 15</td>
                                    <td>Très actif</td>
                                    <td>Attention au surmenage</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100 shadow-sm hover-lift">
                    <div class="card-header bg-success text-white">
                        <h4 class="h6 mb-0"><i class="fas fa-2 me-2"></i>Ressenti moyen</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Définition :</strong> Moyenne des ressentis d'effort (sur 10)</p>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Score</th>
                                    <th>Signification</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>&lt; 5.0</td>
                                    <td>Trop facile</td>
                                    <td>Augmenter l'intensité</td>
                                </tr>
                                <tr class="table-success">
                                    <td>5.0-7.0</td>
                                    <td>Équilibre idéal</td>
                                    <td>Continuer ainsi ✅</td>
                                </tr>
                                <tr>
                                    <td>7.0-8.5</td>
                                    <td>Intense mais OK</td>
                                    <td>Surveiller la récupération</td>
                                </tr>
                                <tr class="table-warning">
                                    <td>&gt; 8.5</td>
                                    <td>Trop intense ⚠️</td>
                                    <td>Risque de surmenage</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="alert alert-primary border-0 mb-0">
                            <small><i class="fas fa-lightbulb me-2"></i>Un ressenti moyen entre <strong>6 et 7</strong> est optimal.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100 shadow-sm hover-lift">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="h6 mb-0"><i class="fas fa-3 me-2"></i>Objectifs atteints</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Définition :</strong> Pourcentage d'objectifs <strong>atteints</strong> ou <strong>dépassés</strong></p>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Score</th>
                                    <th>Signification</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>&lt; 50%</td>
                                    <td>Objectifs trop ambitieux</td>
                                    <td>Revoir à la baisse</td>
                                </tr>
                                <tr class="table-success">
                                    <td>50-80%</td>
                                    <td>Bon équilibre</td>
                                    <td>Challenge réaliste ✅</td>
                                </tr>
                                <tr>
                                    <td>&gt; 90%</td>
                                    <td>Objectifs trop faciles</td>
                                    <td>Viser plus haut</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="alert alert-primary border-0 mb-0">
                            <small><i class="fas fa-lightbulb me-2"></i>Entre <strong>60% et 80%</strong> = équilibre parfait entre challenge et réussite.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100 shadow-sm hover-lift">
                    <div class="card-header bg-info text-white">
                        <h4 class="h6 mb-0"><i class="fas fa-4 me-2"></i>Ratio objectifs</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Définition :</strong> Nombre d'objectifs atteints / total d'activités</p>
                        <div class="alert alert-info border-0">
                            <div class="text-center">
                                <div class="display-4 fw-bold">9/12</div>
                                <p class="mb-0 mt-2">Signifie : <strong>9 objectifs atteints</strong> sur <strong>12 activités</strong> = 75%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemples d'analyse -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="h6 mb-0">✅ Progression équilibrée</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <span class="badge bg-success fs-6">15 activités | 6.5/10 | 70% | 10/15</span>
                        </div>
                        <strong>Analyse :</strong>
                        <ul class="small mb-0">
                            <li>✅ Bonne fréquence</li>
                            <li>✅ Intensité modérée</li>
                            <li>✅ Objectifs atteignables</li>
                        </ul>
                        <div class="alert alert-success border-0 mt-2 mb-0">
                            <strong>Verdict :</strong> Continuer sur cette lancée !
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-warning h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="h6 mb-0">⚠️ Surmenage</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <span class="badge bg-warning fs-6">20 activités | 8.5/10 | 50% | 10/20</span>
                        </div>
                        <strong>Analyse :</strong>
                        <ul class="small mb-0">
                            <li>⚠️ Trop d'activités</li>
                            <li>⚠️ Intensité trop élevée</li>
                            <li>⚠️ Trop d'échecs</li>
                        </ul>
                        <div class="alert alert-warning border-0 mt-2 mb-0">
                            <strong>Verdict :</strong> Réduire la charge, ajouter du repos
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-danger h-100">
                    <div class="card-header bg-danger text-white">
                        <h5 class="h6 mb-0">📉 Sous-entraînement</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <span class="badge bg-danger fs-6">6 activités | 4.2/10 | 95% | 6/6</span>
                        </div>
                        <strong>Analyse :</strong>
                        <ul class="small mb-0">
                            <li>📉 Pas assez d'entraînements</li>
                            <li>📉 Trop facile</li>
                            <li>📉 Objectifs trop bas</li>
                        </ul>
                        <div class="alert alert-danger border-0 mt-2 mb-0">
                            <strong>Verdict :</strong> Augmenter fréquence et intensité
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 10. Conseils et bonnes pratiques -->
<section id="conseils" class="py-5 bg-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-lightbulb text-warning" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Conseils et bonnes pratiques</h2>
            <p class="text-muted lead">Optimisez votre utilisation du calendrier</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card border-success shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-thumbs-up me-2"></i>✅ À FAIRE</h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <strong>Planifiez à l'avance</strong>
                                <p class="small text-muted mb-0">Dimanche soir → Planifier la semaine complète</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Soyez réaliste</strong>
                                <p class="small text-muted mb-0">Fixez des objectifs atteignables et progressifs</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Complétez dans les 24h</strong>
                                <p class="small text-muted mb-0">Sensations fraîches = données précises</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Notez les douleurs</strong>
                                <p class="small text-muted mb-0">Même légères, pour prévenir les blessures</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Utilisez les contenus liés</strong>
                                <p class="small text-muted mb-0">Séances ou exercices pour structurer vos entraînements</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-danger shadow-sm h-100">
                    <div class="card-header bg-danger text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-ban me-2"></i>❌ À ÉVITER</h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <strong>Oublier de compléter</strong>
                                <p class="small text-muted mb-0">→ Statistiques faussées et perte d'information</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Tricher sur le ressenti</strong>
                                <p class="small text-muted mb-0">→ Données inutiles pour votre progression</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Ne pas lier de contenus</strong>
                                <p class="small text-muted mb-0">→ Perte de structure et d'organisation</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Ignorer les douleurs</strong>
                                <p class="small text-muted mb-0">→ Risque de blessure grave et arrêt prolongé</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Surcharger sans repos</strong>
                                <p class="small text-muted mb-0">→ Surmenage et contre-performance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Règles d'or -->
        <div class="row g-4">
            <div class="col-12">
                <div class="card bg-success text-white shadow-lg">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-4"><i class="fas fa-star me-2"></i>Les 5 règles d'or</h3>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6"><i class="fas fa-1 me-2"></i>Planifiez</h5>
                                    <p class="small mb-0">Une semaine à l'avance pour être organisé</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6"><i class="fas fa-2 me-2"></i>Liez</h5>
                                    <p class="small mb-0">Séances ou exercices pour structurer</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6"><i class="fas fa-3 me-2"></i>Complétez</h5>
                                    <p class="small mb-0">Systématiquement dans les 24h</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6"><i class="fas fa-4 me-2"></i>Analysez</h5>
                                    <p class="small mb-0">Vos stats mensuelles pour ajuster</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6"><i class="fas fa-5 me-2"></i>Écoutez</h5>
                                    <p class="small mb-0">Votre corps et adaptez si nécessaire</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 11. FAQ -->
<section id="faq" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-question-circle text-info" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Questions fréquentes (FAQ)</h2>
            <p class="text-muted lead">Trouvez rapidement vos réponses</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="accordion" id="faqGeneral">
                    <h4 class="mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Général</h4>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Le calendrier est-il gratuit ?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqGeneral">
                            <div class="accordion-body">
                                Non, cette fonctionnalité est réservée aux <strong>membres Premium</strong>.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Combien d'activités puis-je planifier ?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqGeneral">
                            <div class="accordion-body">
                                <strong>Illimité !</strong> Planifiez autant d'activités que vous le souhaitez.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Mes activités sont-elles privées ?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqGeneral">
                            <div class="accordion-body">
                                Oui, <strong>100% privées</strong>. Seul vous pouvez les voir.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="accordion" id="faqContenus">
                    <h4 class="mb-3"><i class="fas fa-link text-success me-2"></i>Contenus liés</h4>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Puis-je lier une séance ET des exercices ?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse show" data-bs-parent="#faqContenus">
                            <div class="accordion-body">
                                <strong>Non</strong>, vous devez choisir l'un <strong>OU</strong> l'autre.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Combien d'exercices puis-je sélectionner ?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqContenus">
                            <div class="accordion-body">
                                <strong>Autant que vous voulez</strong>, sans limite.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                Puis-je modifier les contenus liés ?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqContenus">
                            <div class="accordion-body">
                                <strong>OUI !</strong> Allez dans "Modifier" et changez votre sélection.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="accordion" id="faqModification">
                    <h4 class="mb-3"><i class="fas fa-edit text-warning me-2"></i>Modification</h4>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                Puis-je modifier une activité passée ?
                            </button>
                        </h2>
                        <div id="faq7" class="accordion-collapse collapse show" data-bs-parent="#faqModification">
                            <div class="accordion-body">
                                Non, une fois la date/heure passée, vous pouvez uniquement la <strong>compléter</strong>.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                Puis-je modifier une activité complétée ?
                            </button>
                        </h2>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqModification">
                            <div class="accordion-body">
                                <strong>Non</strong>, une activité complétée est verrouillée.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="accordion" id="faqStats">
                    <h4 class="mb-3"><i class="fas fa-chart-line text-info me-2"></i>Statistiques</h4>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">
                                Quand les stats sont mises à jour ?
                            </button>
                        </h2>
                        <div id="faq9" class="accordion-collapse collapse show" data-bs-parent="#faqStats">
                            <div class="accordion-body">
                                <strong>En temps réel</strong>, dès que vous complétez une activité.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10">
                                Comment est calculé le ressenti moyen ?
                            </button>
                        </h2>
                        <div id="faq10" class="accordion-collapse collapse" data-bs-parent="#faqStats">
                            <div class="accordion-body">
                                (Somme de tous les ressentis) ÷ (Nombre d'activités complétées)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>












<!-- CTA Final -->
<section class="py-5 bg-primary text-white">
    <div class="container-lg">
        <div class="text-center">
            <i class="fas fa-calendar-check fa-3x mb-4"></i>
            <h2 class="display-4 fw-bold mb-4">Prêt à organiser vos entraînements ?</h2>
            <p class="lead mb-4">
                Planifiez, suivez et progressez avec le Calendrier d'Activités Nataswim.<br>
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-5">
                    <i class="fas fa-star me-2"></i>Devenir Premium
                </a>
                <a href="{{ route('pricing') }}" class="btn btn-outline-light btn-lg px-5">
                    <i class="fas fa-info-circle me-2"></i>Voir les tarifs
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5">
                    <i class="fas fa-envelope me-2"></i>Nous contacter
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Points clés à retenir -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-bookmark text-warning" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Points clés à retenir</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-calendar-plus fa-3x text-primary mb-3"></i>
                        <h5>Planifiez à l'avance</h5>
                        <p class="small text-muted mb-0">Pour être organisé et préparé</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-link fa-3x text-success mb-3"></i>
                        <h5>Liez vos contenus</h5>
                        <p class="small text-muted mb-0">Séances ou exercices pour structurer</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-check-double fa-3x text-warning mb-3"></i>
                        <h5>Complétez systématiquement</h5>
                        <p class="small text-muted mb-0">Pour suivre votre progression</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                        <h5>Analysez vos stats</h5>
                        <p class="small text-muted mb-0">Mensuellement pour ajuster</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                        <h5>Écoutez votre corps</h5>
                        <p class="small text-muted mb-0">Et adaptez si nécessaire</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
                        <h5>Progressez intelligemment</h5>
                        <p class="small text-muted mb-0">Avec des objectifs réalistes</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-primary border-0 shadow-sm text-center mt-5">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-info-circle fa-2x me-3"></i>
                <div>
                    <strong>Besoin d'aide ?</strong> Notre équipe support est là pour vous accompagner. 
                    <a href="{{ route('contact') }}" class="alert-link">Contactez-nous</a>
                </div>
            </div>
        </div>
    </div>
</section>


<!--  Section -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">

        <a href="{{ route('guide') }}">

            <div class="col-lg text-center">
                <img src="{{ asset('assets/images/team/mode-emploi-nataswim.jpg') }}"
                    alt="Guide Nataswim"
                    class="img-fluid rounded-4 shadow-lg"
                    style="object-fit: cover;">
            </div>
            </a>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>




.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.card {
    border-radius: 1rem;
}

.badge {
    font-weight: 500;
}

.timeline {
    position: relative;
    padding-left: 1rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #0ea5e9, #10b981);
}

.sticky-top {
    backdrop-filter: blur(10px);
    background-color: rgba(248, 249, 250, 0.95) !important;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .display-5 {
        font-size: 1.75rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offset = 150; // Hauteur du menu sticky
                const targetPosition = target.offsetTop - offset;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Highlight du menu actif
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.sticky-top a[href^="#"]');
    
    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= (sectionTop - 200)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('btn-primary');
            link.classList.add('btn-outline-primary');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.remove('btn-outline-primary');
                link.classList.add('btn-primary');
            }
        });
    });
});
</script>
@endpush