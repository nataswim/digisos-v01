@extends('layouts.public')

@section('title', 'Guide - Carnets Personnalisés')

@section('content')

<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <h1 class="display-4 fw-bold mb-0">Carnets Personnalisés</h1>
                </div>
                <p class="lead mb-4">
                    Organisez, sauvegardez et consultez tous vos contenus préférés en un seul endroit. Créez des collections thématiques pour vos programmes d'entraînement, vos exercices favoris et bien plus encore.
                </p>
                
            </div>
            <div class="col-lg-5 text-center">
                                <img src="{{ asset('assets/images/team/guide-utilisation.jpg') }}"

                     alt="Carnets Nataswim" 
                     class="img-fluid rounded-4 shadow-lg"
                     style="max-height: 400px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>


<!-- Table des matières -->
<section class="py-4 bg-light border-bottom sticky-top" style="top: 70px; z-index: 100;">
    <div class="container-lg">
        <nav class="d-flex flex-wrap justify-content-center gap-2">
            <a href="#vue-ensemble" class="btn btn-outline-primary btn-sm">🎯 Vue d'ensemble</a>
            <a href="#creation" class="btn btn-outline-primary btn-sm">➕ Création</a>
            <a href="#types-contenus" class="btn btn-outline-primary btn-sm">📚 Types de contenus</a>
            <a href="#ajouter-contenus" class="btn btn-outline-primary btn-sm">📥 Ajouter contenus</a>
            <a href="#notes" class="btn btn-outline-primary btn-sm">📝 Notes</a>
            <a href="#organiser" class="btn btn-outline-primary btn-sm">🔄 Organiser</a>
            <a href="#export" class="btn btn-outline-primary btn-sm">📄 Export PDF</a>
            <a href="#gestion" class="btn btn-outline-primary btn-sm">⚙️ Gestion</a>
            <a href="#conseils" class="btn btn-outline-primary btn-sm">💡 Conseils</a>
            <a href="#faq" class="btn btn-outline-primary btn-sm">❓ FAQ</a>
            <a href="#exemples" class="btn btn-outline-primary btn-sm">📖 Exemples</a>
        </nav>
    </div>
</section>

<!-- 1. Vue d'ensemble -->
<section id="vue-ensemble" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="mt-3 display-5 fw-bold">Vue d'ensemble</h2>
            <p class="text-muted lead">Qu'est-ce qu'un carnet personnalisé ?</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-12">
                <div class="card border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-book-open me-2"></i>Définition</h3>
                    </div>
                    <div class="card-body">
                        <p class="lead mb-4">Un <strong>Carnet Personnalisé</strong> est votre bibliothèque privée pour organiser et sauvegarder tous les contenus Nataswim qui vous intéressent.</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-2 mt-1 fa-lg"></i>
                                    <div>
                                        <strong>Organisez vos favoris</strong>
                                        <p class="small text-muted mb-0">Créez des collections thématiques (articles, exercices, plans...)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-2 mt-1 fa-lg"></i>
                                    <div>
                                        <strong>Ajoutez vos notes</strong>
                                        <p class="small text-muted mb-0">Annotez chaque contenu avec vos remarques personnelles</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-2 mt-1 fa-lg"></i>
                                    <div>
                                        <strong>Accédez partout</strong>
                                        <p class="small text-muted mb-0">Vos carnets sont disponibles 24/7 sur tous vos appareils</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-2 mt-1 fa-lg"></i>
                                    <div>
                                        <strong>Exportez en PDF</strong>
                                        <p class="small text-muted mb-0">Téléchargez vos carnets pour les consulter hors ligne</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Les 4 piliers -->
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-plus-circle fa-2x text-primary"></i>
                        </div>
                        <h4 class="h5">1️⃣ Création</h4>
                        <ul class="list-unstyled small text-start mt-3">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Titre personnalisé</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Description détaillée</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Organisation par thèmes</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Carnets illimités</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-success-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-folder-open fa-2x text-success"></i>
                        </div>
                        <h4 class="h5">2️⃣ Contenu</h4>
                        <ul class="list-unstyled small text-start mt-3">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Articles favoris</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Vidéos sélectionnées</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Exercices enregistrés</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Notes personnelles</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-sort fa-2x text-warning"></i>
                        </div>
                        <h4 class="h5">3️⃣ Organisation</h4>
                        <ul class="list-unstyled small text-start mt-3">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Réorganisation facile</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Duplication de carnets</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Export PDF</li>
                            <li class="mb-2"><i class="fas fa-hourglass-half text-warning me-2"></i>Partage (bientôt)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-info-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-star fa-2x text-info"></i>
                        </div>
                        <h4 class="h5">4️⃣ Avantages</h4>
                        <ul class="list-unstyled small text-start mt-3">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Accès 24/7</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Sauvegarde cloud</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Multi-appareils</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Confidentiel</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning border-0 shadow-sm mt-5">
            <div class="d-flex align-items-center">
                <i class="fas fa-crown fa-2x me-3 text-warning"></i>
                <div>
                    <strong>Accès Premium requis</strong> - Cette fonctionnalité est réservée aux membres Premium avec carnets illimités. 
                    <a href="{{ route('pricing') }}" class="alert-link">Découvrez nos offres</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Création d'un carnet -->
<section id="creation" class="py-5 bg-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-plus-circle text-success" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Créer un carnet</h2>
            <p class="text-muted lead">En 3 étapes simples</p>
        </div>

        <div class="row g-4 mb-5">
            <!-- Étape 1 -->
            <div class="col-lg-4">
                <div class="card h-100 border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="h6 mb-0"><i class="fas fa-1 me-2"></i>Accéder à la création</h4>
                    </div>
                    <div class="card-body">
                        <p class="small">Depuis votre espace membre, allez dans <strong>"Mes Carnets"</strong> et cliquez sur :</p>
                        <div class="text-center my-3">
                            <button class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer un carnet
                            </button>
                        </div>
                        <div class="alert alert-info border-0 mb-0">
                            <small><i class="fas fa-lightbulb me-2"></i>Vous pouvez créer <strong>autant de carnets que vous voulez</strong> !</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 2 -->
            <div class="col-lg-4">
                <div class="card h-100 border-success shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h4 class="h6 mb-0"><i class="fas fa-2 me-2"></i>Remplir les informations</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong class="d-block mb-1">Titre *</strong>
                            <small class="text-muted">Ex: "Mon programme perte de poids"</small>
                        </div>
                        <div class="mb-3">
                            <strong class="d-block mb-1">Type de contenu *</strong>
                            <small class="text-muted">Articles, Exercices, Plans, etc.</small>
                        </div>
                        <div class="mb-3">
                            <strong class="d-block mb-1">Description</strong>
                            <small class="text-muted">Optionnelle mais recommandée</small>
                        </div>
                        <div>
                            <strong class="d-block mb-1">Couleur</strong>
                            <small class="text-muted">Pour identifier visuellement</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 3 -->
            <div class="col-lg-4">
                <div class="card h-100 border-warning shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="h6 mb-0"><i class="fas fa-3 me-2"></i>Valider la création</h4>
                    </div>
                    <div class="card-body">
                        <p class="small mb-3">Cliquez sur <strong>"Créer le carnet"</strong> pour finaliser.</p>
                        <div class="card bg-light mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-dumbbell text-white"></i>
                                    </div>
                                    <div>
                                        <strong>Exercices Haut du Corps</strong>
                                        <div class="small text-muted">0 élément</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-success border-0 mb-0">
                            <small><i class="fas fa-check-circle me-2"></i>Votre carnet est créé et prêt à être rempli !</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire exemple -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="h5 mb-0">Exemple de formulaire de création</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Titre du carnet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Ex: Programme Marathon 2025" maxlength="200">
                            <small class="text-muted">Maximum 200 caractères</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Type de contenu <span class="text-danger">*</span></label>
                            <select class="form-select">
                                <option value="">Sélectionner un type</option>
                                <option value="posts">📰 Articles</option>
                                <option value="fiches">📋 Fiches Pratiques</option>
                                <option value="exercices">🏃 Exercices</option>
                                <option value="workouts">💪 Séances d'Entraînement</option>
                                <option value="plans">📅 Plans d'Entraînement</option>
                                <option value="downloadables">📚 Documents / eBooks</option>
                            </select>
                            <small class="text-muted">Un carnet ne peut contenir qu'un seul type de contenu</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Décrivez l'objectif de ce carnet..."></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Couleur</label>
                            <input type="color" class="form-control form-control-color" value="#007bff">
                            <small class="text-muted">Choisissez une couleur pour identifier rapidement votre carnet</small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary">Annuler</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Créer le carnet
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Types de contenus -->
<section id="types-contenus" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-layer-group text-info" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Types de contenus disponibles</h2>
            <p class="text-muted lead">6 types de contenus à organiser</p>
        </div>

        <div class="row g-4">
            <!-- Articles -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-newspaper fa-2x text-primary"></i>
                        </div>
                        <h4 class="h5">📰 Articles</h4>
                        <p class="small text-muted mb-3">Tous les articles du blog Nataswim</p>
                        <div class="alert alert-primary-subtle border-0 mb-0">
                            <small><strong>Idéal pour :</strong> Guides techniques, conseils nutrition, actualités</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fiches Pratiques -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-success-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-file-alt fa-2x text-success"></i>
                        </div>
                        <h4 class="h5">📋 Fiches Pratiques</h4>
                        <p class="small text-muted mb-3">Fiches récapitulatives et mémos</p>
                        <div class="alert alert-success-subtle border-0 mb-0">
                            <small><strong>Idéal pour :</strong> Synthèses rapides, points clés, aide-mémoire</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exercices -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-running fa-2x text-warning"></i>
                        </div>
                        <h4 class="h5">🏃 Exercices</h4>
                        <p class="small text-muted mb-3">Mouvements et exercices détaillés</p>
                        <div class="alert alert-warning-subtle border-0 mb-0">
                            <small><strong>Idéal pour :</strong> Bibliothèque d'exercices, circuits personnalisés</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Séances d'Entraînement -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-danger-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-heartbeat fa-2x text-danger"></i>
                        </div>
                        <h4 class="h5">💪 Séances d'Entraînement</h4>
                        <p class="small text-muted mb-3">Workouts complets structurés</p>
                        <div class="alert alert-danger-subtle border-0 mb-0">
                            <small><strong>Idéal pour :</strong> Programmes hebdomadaires, séances préférées</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plans d'Entraînement -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-info-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-dumbbell fa-2x text-info"></i>
                        </div>
                        <h4 class="h5">📅 Plans d'Entraînement</h4>
                        <p class="small text-muted mb-3">Plans complets sur plusieurs semaines</p>
                        <div class="alert alert-info-subtle border-0 mb-0">
                            <small><strong>Idéal pour :</strong> Préparation compétitions, objectifs long terme</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents / eBooks -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-secondary-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-book fa-2x text-secondary"></i>
                        </div>
                        <h4 class="h5">📚 Documents / eBooks</h4>
                        <p class="small text-muted mb-3">Livres numériques et PDF téléchargeables</p>
                        <div class="alert alert-secondary-subtle border-0 mb-0">
                            <small><strong>Idéal pour :</strong> Bibliothèque d'eBooks, ressources Premium</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info border-0 shadow-sm mt-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="alert-heading mb-2"><i class="fas fa-exclamation-circle me-2"></i>Règle importante</h5>
                    <p class="mb-0">Un carnet ne peut contenir <strong>qu'un seul type de contenu</strong>. Vous ne pouvez pas mélanger des articles avec des exercices dans le même carnet.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-folder fa-4x text-info opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Ajouter des contenus -->
<section id="ajouter-contenus" class="py-5 bg-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-plus-square text-success" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Ajouter des contenus</h2>
            <p class="text-muted lead">Comment remplir vos carnets</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card h-100 border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-mouse-pointer me-2"></i>Méthode 1 : Depuis le contenu</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>La méthode la plus simple !</strong></p>
                        <ol class="mb-3">
                            <li class="mb-2">Naviguez sur Nataswim (article, exercice, etc.)</li>
                            <li class="mb-2">Trouvez le bouton <span class="badge bg-primary">📚 Ajouter au carnet</span></li>
                            <li class="mb-2">Sélectionnez le carnet de destination</li>
                            <li class="mb-2">Le contenu est ajouté instantanément !</li>
                        </ol>
                        <div class="alert alert-success border-0 mb-0">
                            <small><i class="fas fa-lightbulb me-2"></i><strong>Astuce :</strong> Si vous n'avez pas encore de carnet du bon type, vous pouvez en créer un directement depuis le modal !</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100 border-success shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-hand-pointer me-2"></i>Méthode 2 : Depuis le carnet</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Pour une organisation méthodique</strong></p>
                        <ol class="mb-3">
                            <li class="mb-2">Ouvrez votre carnet</li>
                            <li class="mb-2">Naviguez dans Nataswim pour trouver les contenus</li>
                            <li class="mb-2">Utilisez le bouton "Ajouter au carnet" sur chaque contenu</li>
                            <li class="mb-2">Tous vos contenus s'accumulent dans le carnet</li>
                        </ol>
                        <div class="alert alert-info border-0 mb-0">
                            <small><i class="fas fa-info-circle me-2"></i>Vous pouvez ajouter <strong>autant de contenus que vous voulez</strong> dans un même carnet.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemple visuel -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="h5 mb-0">Exemple : Ajouter un exercice à un carnet</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-5">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5>Développé couché</h5>
                                        <p class="small text-muted mb-3">Exercice de force pour pectoraux</p>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary">👁️ Voir</button>
                                            <button class="btn btn-sm btn-primary">
                                                <i class="fas fa-book me-1"></i>Ajouter au carnet
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <i class="fas fa-arrow-right fa-2x text-primary"></i>
                            </div>
                            <div class="col-md-5">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h6>Sélectionner un carnet :</h6>
                                        <div class="list-group list-group-flush">
                                            <button class="list-group-item list-group-item-action">
                                                <i class="fas fa-dumbbell text-primary me-2"></i>
                                                Exercices Haut du Corps (5)
                                            </button>
                                            <button class="list-group-item list-group-item-action">
                                                <i class="fas fa-running text-success me-2"></i>
                                                Programme Force (12)
                                            </button>
                                        </div>
                                        <button class="btn btn-sm btn-outline-success mt-2 w-100">
                                            <i class="fas fa-plus me-1"></i>Créer un nouveau carnet
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. Notes personnelles -->
<section id="notes" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-sticky-note text-warning" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Ajouter des notes personnelles</h2>
            <p class="text-muted lead">Annotez vos contenus avec vos remarques</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card border-warning shadow-sm h-100">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="h5 mb-0"><i class="fas fa-question-circle me-2"></i>Pourquoi ajouter des notes ?</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Personnalisez vos contenus</strong>
                                <p class="small text-muted mb-0 ms-4">Ajoutez vos propres remarques et observations</p>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Suivez vos progrès</strong>
                                <p class="small text-muted mb-0 ms-4">Notez vos performances, sensations, modifications</p>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Ne perdez rien</strong>
                                <p class="small text-muted mb-0 ms-4">Toutes vos notes sont sauvegardées automatiquement</p>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Exportez en PDF</strong>
                                <p class="small text-muted mb-0 ms-4">Vos notes apparaissent dans l'export PDF</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-primary shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-pen me-2"></i>Comment ajouter une note ?</h3>
                    </div>
                    <div class="card-body">
                        <ol class="mb-0">
                            <li class="mb-3">
                                <strong>Ouvrez votre carnet</strong>
                                <p class="small text-muted mb-0">Accédez au carnet contenant le contenu à annoter</p>
                            </li>
                            <li class="mb-3">
                                <strong>Cliquez sur l'icône note</strong>
                                <p class="small text-muted mb-0">À côté de chaque contenu, vous verrez : <button class="btn btn-sm btn-outline-primary"><i class="fas fa-sticky-note"></i></button></p>
                            </li>
                            <li class="mb-3">
                                <strong>Rédigez votre note</strong>
                                <p class="small text-muted mb-0">Maximum 1000 caractères pour rester synthétique</p>
                            </li>
                            <li class="mb-3">
                                <strong>Enregistrez</strong>
                                <p class="small text-muted mb-0">La note est sauvegardée et visible sous le contenu</p>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemples de notes -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="h5 mb-0">Exemples de notes utiles</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card bg-success-subtle">
                                    <div class="card-body">
                                        <h6 class="text-success mb-2">✅ Bonne note</h6>
                                        <p class="small mb-0"><em>"Essayé le 15/01/2025 - Séries de 3x12 réussies. Progression possible vers 4 séries la semaine prochaine. Bien sentir la contraction."</em></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-success-subtle">
                                    <div class="card-body">
                                        <h6 class="text-success mb-2">✅ Bonne note</h6>
                                        <p class="small mb-0"><em>"Article très complet sur la nutrition. À relire avant la préparation du prochain plan alimentaire. Focus protéines : 2g/kg."</em></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-danger-subtle">
                                    <div class="card-body">
                                        <h6 class="text-danger mb-2">❌ Note peu utile</h6>
                                        <p class="small mb-0"><em>"Bien"</em></p>
                                        <small class="text-muted">Trop vague, manque de détails</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-danger-subtle">
                                    <div class="card-body">
                                        <h6 class="text-danger mb-2">❌ Note peu utile</h6>
                                        <p class="small mb-0"><em>"À faire"</em></p>
                                        <small class="text-muted">Manque de contexte et de détails</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. Organiser -->
<section id="organiser" class="py-5 bg-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-arrows-alt text-info" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Réorganiser vos contenus</h2>
            <p class="text-muted lead">Glissez-déposez pour réorganiser</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card border-info shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-grip-vertical me-2"></i>Drag & Drop</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Réorganisez facilement vos contenus par simple glisser-déposer !</strong></p>
                        <ol class="mb-4">
                            <li class="mb-2">Ouvrez votre carnet</li>
                            <li class="mb-2">Cliquez sur l'icône <i class="fas fa-grip-vertical text-muted"></i> à gauche du contenu</li>
                            <li class="mb-2">Maintenez le clic et déplacez le contenu</li>
                            <li class="mb-2">Relâchez à l'emplacement souhaité</li>
                        </ol>
                        <div class="alert alert-success border-0 mb-0">
                            <i class="fas fa-save me-2"></i>L'ordre est <strong>sauvegardé automatiquement</strong> !
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-success shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-lightbulb me-2"></i>Conseils d'organisation</h3>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3">Organisez par :</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <i class="fas fa-sort-numeric-down text-primary me-2"></i>
                                <strong>Ordre chronologique</strong>
                                <p class="small text-muted mb-0">Du plus ancien au plus récent</p>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-star text-warning me-2"></i>
                                <strong>Priorité / Importance</strong>
                                <p class="small text-muted mb-0">Les plus importants en premier</p>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-layer-group text-info me-2"></i>
                                <strong>Catégories</strong>
                                <p class="small text-muted mb-0">Groupez par thème ou type</p>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-chart-line text-success me-2"></i>
                                <strong>Progression</strong>
                                <p class="small text-muted mb-0">Du plus simple au plus complexe</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Démonstration visuelle -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="h5 mb-0">Exemple de réorganisation</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-5">
                                <h6 class="text-center mb-3">Avant</h6>
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <i class="fas fa-grip-vertical text-muted me-2"></i>
                                        <strong>3.</strong> Étirements post-effort
                                    </div>
                                    <div class="list-group-item">
                                        <i class="fas fa-grip-vertical text-muted me-2"></i>
                                        <strong>1.</strong> Échauffement articulaire
                                    </div>
                                    <div class="list-group-item">
                                        <i class="fas fa-grip-vertical text-muted me-2"></i>
                                        <strong>2.</strong> Développé couché
                                    </div>
                                </div>
                                <div class="text-center mt-2 text-danger">
                                    <small><i class="fas fa-exclamation-triangle me-1"></i>Ordre illogique</small>
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-arrow-right fa-3x text-primary"></i>
                            </div>

                            <div class="col-md-5">
                                <h6 class="text-center mb-3">Après réorganisation</h6>
                                <div class="list-group">
                                    <div class="list-group-item bg-success-subtle">
                                        <i class="fas fa-grip-vertical text-muted me-2"></i>
                                        <strong>1.</strong> Échauffement articulaire
                                    </div>
                                    <div class="list-group-item bg-success-subtle">
                                        <i class="fas fa-grip-vertical text-muted me-2"></i>
                                        <strong>2.</strong> Développé couché
                                    </div>
                                    <div class="list-group-item bg-success-subtle">
                                        <i class="fas fa-grip-vertical text-muted me-2"></i>
                                        <strong>3.</strong> Étirements post-effort
                                    </div>
                                </div>
                                <div class="text-center mt-2 text-success">
                                    <small><i class="fas fa-check-circle me-1"></i>Ordre logique respecté !</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. Export PDF -->
<section id="export" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-file-pdf text-danger" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Exporter en PDF</h2>
            <p class="text-muted lead">Téléchargez vos carnets pour les consulter hors ligne</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card border-danger shadow-sm h-100">
                    <div class="card-header bg-danger text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-download me-2"></i>Comment exporter ?</h3>
                    </div>
                    <div class="card-body">
                        <ol class="mb-4">
                            <li class="mb-3">
                                <strong>Ouvrez votre carnet</strong>
                                <p class="small text-muted mb-0">Accédez au carnet que vous souhaitez exporter</p>
                            </li>
                            <li class="mb-3">
                                <strong>Cliquez sur le bouton PDF</strong>
                                <p class="small text-muted mb-0">En haut à droite : <button class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf me-1"></i>PDF</button></p>
                            </li>
                            <li class="mb-3">
                                <strong>Le PDF se télécharge</strong>
                                <p class="small text-muted mb-0">Format : <code>carnet-nom-du-carnet.pdf</code></p>
                            </li>
                        </ol>
                        <div class="alert alert-info border-0 mb-0">
                            <small><i class="fas fa-bolt me-2"></i>L'export est <strong>instantané</strong>, même pour les gros carnets !</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-primary shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-list-check me-2"></i>Contenu du PDF</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Le PDF contient :</p>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Informations du carnet</strong>
                                <p class="small text-muted mb-0">Titre, description, type de contenu</p>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Liste de tous les contenus</strong>
                                <p class="small text-muted mb-0">Dans l'ordre que vous avez défini</p>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Vos notes personnelles</strong>
                                <p class="small text-muted mb-0">Toutes vos annotations sont incluses</p>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Date d'export</strong>
                                <p class="small text-muted mb-0">Pour suivre les versions</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cas d'usage -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <i class="fas fa-print fa-3x text-primary mb-3"></i>
                        <h5>Impression</h5>
                        <p class="small text-muted mb-0">Imprimez vos carnets pour les avoir au format papier lors de vos entraînements</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <i class="fas fa-plane fa-3x text-success mb-3"></i>
                        <h5>Hors connexion</h5>
                        <p class="small text-muted mb-0">Consultez vos carnets même sans Internet (voyage, salle de sport...)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <i class="fas fa-user-friends fa-3x text-warning mb-3"></i>
                        <h5>Partage (bientôt)</h5>
                        <p class="small text-muted mb-0">Partagez vos programmes avec votre coach ou partenaires d'entraînement</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 8. Gestion des carnets -->
<section id="gestion" class="py-5 bg-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-cogs text-secondary" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Gérer vos carnets</h2>
            <p class="text-muted lead">Modifier, favoris, supprimer</p>
        </div>

        <div class="row g-4">
            <!-- Modifier un carnet -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-edit fa-2x text-primary"></i>
                        </div>
                        <h5>Modifier</h5>
                        <p class="small text-muted mb-3">Changez le titre, la description ou la couleur de votre carnet</p>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i>Éditer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Favoris -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                        <h5>Favoris</h5>
                        <p class="small text-muted mb-3">Marquez vos carnets les plus utilisés en favoris pour un accès rapide</p>
                        <button class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-star me-1"></i>Favori
                        </button>
                    </div>
                </div>
            </div>

            <!-- Supprimer contenu -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-info-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-minus-circle fa-2x text-info"></i>
                        </div>
                        <h5>Retirer contenu</h5>
                        <p class="small text-muted mb-3">Supprimez un contenu spécifique de votre carnet</p>
                        <button class="btn btn-sm btn-outline-info">
                            <i class="fas fa-times me-1"></i>Retirer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Supprimer carnet -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-danger-subtle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-trash fa-2x text-danger"></i>
                        </div>
                        <h5>Supprimer</h5>
                        <p class="small text-muted mb-3">Supprimez définitivement un carnet et tout son contenu</p>
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash me-1"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-danger border-0 shadow-sm mt-5">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle fa-2x me-3 mt-1"></i>
                <div>
                    <h5 class="alert-heading">⚠️ Attention à la suppression</h5>
                    <p class="mb-2">La suppression d'un carnet est <strong>définitive et irréversible</strong>. Toutes les notes personnelles seront perdues.</p>
                    <p class="mb-0"><strong>Conseil :</strong> Exportez votre carnet en PDF avant de le supprimer si vous voulez conserver une copie.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. Conseils et bonnes pratiques -->
<section id="conseils" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-lightbulb text-warning" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Conseils et bonnes pratiques</h2>
            <p class="text-muted lead">Optimisez l'utilisation de vos carnets</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card border-success shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-thumbs-up me-2"></i>✅ Bonnes pratiques</h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <strong>Nommez clairement vos carnets</strong>
                                <p class="small text-muted mb-0">"Programme Marathon 2025" plutôt que "Carnet 1"</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Ajoutez des descriptions</strong>
                                <p class="small text-muted mb-0">Expliquez l'objectif du carnet en quelques mots</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Utilisez les couleurs</strong>
                                <p class="small text-muted mb-0">Codez par couleur : bleu = force, vert = endurance, rouge = HIIT...</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Annotez systématiquement</strong>
                                <p class="small text-muted mb-0">Ajoutez des notes dès que vous testez un contenu</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Organisez logiquement</strong>
                                <p class="small text-muted mb-0">Suivez une progression, un ordre chronologique...</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Exportez régulièrement</strong>
                                <p class="small text-muted mb-0">Gardez des sauvegardes PDF de vos carnets importants</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-danger shadow-sm h-100">
                    <div class="card-header bg-danger text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-ban me-2"></i>❌ À éviter</h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <strong>Trop de carnets vides</strong>
                                <p class="small text-muted mb-0">Créez un carnet seulement quand vous avez du contenu à y mettre</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Noms génériques</strong>
                                <p class="small text-muted mb-0">"Mes trucs", "À voir" → impossible de s'y retrouver</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Mélanger les objectifs</strong>
                                <p class="small text-muted mb-0">Un carnet = un objectif précis</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Ne jamais réorganiser</strong>
                                <p class="small text-muted mb-0">Gardez vos carnets à jour et bien organisés</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Oublier d'annoter</strong>
                                <p class="small text-muted mb-0">Sans notes, vous perdez l'historique de vos essais</p>
                            </div>
                            <div class="list-group-item">
                                <strong>Accumuler sans trier</strong>
                                <p class="small text-muted mb-0">Faites le ménage : retirez ce qui ne vous sert plus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Idées de carnets -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary text-white shadow-lg">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-4"><i class="fas fa-lightbulb me-2"></i>💡 Idées de carnets utiles</h3>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6">🎯 Programme en cours</h5>
                                    <p class="small mb-0">Tous les exercices et séances de votre programme actuel</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6">⭐ Mes favoris</h5>
                                    <p class="small mb-0">Les meilleurs articles, exercices que vous voulez garder</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6">📚 À lire</h5>
                                    <p class="small mb-0">Articles et eBooks que vous voulez consulter plus tard</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6">🏃 Cardio</h5>
                                    <p class="small mb-0">Tous vos exercices et plans de cardio</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6">💪 Force</h5>
                                    <p class="small mb-0">Séances et exercices de musculation</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <h5 class="h6">🍽️ Nutrition</h5>
                                    <p class="small mb-0">Guides alimentaires et conseils nutrition</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 10. FAQ -->
<section id="faq" class="py-5 bg-light">
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
                                Les carnets sont-ils gratuits ?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqGeneral">
                            <div class="accordion-body">
                                Non, cette fonctionnalité est réservée aux <strong>membres Premium</strong>. Vous pouvez créer <strong>autant de carnets que vous voulez</strong> avec un compte Premium.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Combien de carnets puis-je créer ?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqGeneral">
                            <div class="accordion-body">
                                <strong>Illimité !</strong> Créez autant de carnets que nécessaire pour organiser tous vos contenus.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Mes carnets sont-ils privés ?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqGeneral">
                            <div class="accordion-body">
                                Oui, <strong>100% confidentiels</strong>. Seul vous pouvez voir vos carnets et leur contenu. Personne d'autre n'y a accès.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Puis-je accéder à mes carnets sur mobile ?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqGeneral">
                            <div class="accordion-body">
                                Oui ! Vos carnets sont <strong>synchronisés sur tous vos appareils</strong> (ordinateur, tablette, smartphone). Sauvegarde automatique dans le cloud.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="accordion" id="faqContenu">
                    <h4 class="mb-3"><i class="fas fa-folder text-success me-2"></i>Contenu</h4>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Puis-je mélanger différents types de contenus ?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse show" data-bs-parent="#faqContenu">
                            <div class="accordion-body">
                                <strong>Non</strong>. Un carnet ne peut contenir qu'un seul type de contenu (soit des articles, soit des exercices, etc.). Créez plusieurs carnets si vous voulez organiser différents types de contenus.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                Combien de contenus par carnet ?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqContenu">
                            <div class="accordion-body">
                                <strong>Illimité !</strong> Ajoutez autant de contenus que vous voulez dans chaque carnet.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                Puis-je ajouter le même contenu à plusieurs carnets ?
                            </button>
                        </h2>
                        <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqContenu">
                            <div class="accordion-body">
                                <strong>Oui !</strong> Un même exercice ou article peut être présent dans plusieurs carnets différents.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                Les notes sont-elles limitées en taille ?
                            </button>
                        </h2>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqContenu">
                            <div class="accordion-body">
                                Oui, maximum <strong>1000 caractères</strong> par note pour rester synthétique et lisible.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="accordion mt-4" id="faqExport">
                    <h4 class="mb-3"><i class="fas fa-file-pdf text-danger me-2"></i>Export & Partage</h4>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">
                                L'export PDF est-il illimité ?
                            </button>
                        </h2>
                        <div id="faq9" class="accordion-collapse collapse show" data-bs-parent="#faqExport">
                            <div class="accordion-body">
                                <strong>Oui, totalement illimité !</strong> Exportez vos carnets en PDF autant de fois que vous voulez.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10">
                                Puis-je partager mes carnets ?
                            </button>
                        </h2>
                        <div id="faq10" class="accordion-collapse collapse" data-bs-parent="#faqExport">
                            <div class="accordion-body">
                                La fonctionnalité de partage arrive <strong>bientôt</strong> ! En attendant, vous pouvez exporter en PDF et partager le fichier manuellement.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="accordion mt-4" id="faqTechnique">
                    <h4 class="mb-3"><i class="fas fa-cog text-secondary me-2"></i>Technique</h4>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq11">
                                Mes carnets sont-ils sauvegardés automatiquement ?
                            </button>
                        </h2>
                        <div id="faq11" class="accordion-collapse collapse show" data-bs-parent="#faqTechnique">
                            <div class="accordion-body">
                                <strong>Oui !</strong> Toute modification (ajout, suppression, note, réorganisation) est <strong>sauvegardée instantanément</strong> dans le cloud.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq12">
                                Que se passe-t-il si un contenu est supprimé de Nataswim ?
                            </button>
                        </h2>
                        <div id="faq12" class="accordion-collapse collapse" data-bs-parent="#faqTechnique">
                            <div class="accordion-body">
                                Le contenu reste dans votre carnet mais sera marqué comme <em>"Contenu supprimé"</em>. Vos notes personnelles sont conservées.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 11. Exemples d'utilisation -->
<section id="exemples" class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-book-reader text-primary" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Exemples d'utilisation</h2>
            <p class="text-muted lead">Inspirez-vous de ces cas pratiques</p>
        </div>

        <div class="row g-4">
            <!-- Exemple 1 -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm hover-lift">
                    <div class="card-header bg-primary text-white">
                        <h4 class="h5 mb-0"><i class="fas fa-running me-2"></i>Préparation Marathon</h4>
                    </div>
                    <div class="card-body">
                        <p class="small mb-3"><strong>Objectif :</strong> Marie prépare son premier marathon en 16 semaines</p>
                        
                        <div class="mb-3">
                            <span class="badge bg-primary mb-2">Carnets créés</span>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>📅 Plan Marathon 16 sem</strong> - Plans d'entraînement</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>🏃 Exercices Course</strong> - Exercices spécifiques</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>📰 Conseils Marathon</strong> - Articles nutrition/récup</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-success border-0 mb-0">
                            <small><strong>Résultat :</strong> Organisation parfaite, tout au même endroit ! Marie exporte ses carnets en PDF pour les consulter hors ligne pendant ses sorties.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exemple 2 -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm hover-lift">
                    <div class="card-header bg-success text-white">
                        <h4 class="h5 mb-0"><i class="fas fa-dumbbell me-2"></i>Prise de Masse</h4>
                    </div>
                    <div class="card-body">
                        <p class="small mb-3"><strong>Objectif :</strong> Thomas veut prendre 5 kg de muscle en 3 mois</p>
                        
                        <div class="mb-3">
                            <span class="badge bg-success mb-2">Carnets créés</span>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>💪 Séances Push</strong> - Workouts haut du corps</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>🦵 Séances Pull/Legs</strong> - Workouts bas du corps</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>🍖 Nutrition Prise de Masse</strong> - Articles alimentation</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded mb-3">
                            <small class="text-muted d-block mb-2">Note sur "Développé couché" :</small>
                            <small><em>"12/01: 4x10 à 80kg ✓ - 15/01: 4x10 à 82.5kg ✓ - Progression régulière, objectif 85kg pour fin janvier"</em></small>
                        </div>

                        <div class="alert alert-success border-0 mb-0">
                            <small><strong>Résultat :</strong> Suivi précis de la progression grâce aux notes personnelles sur chaque exercice.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exemple 3 -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm hover-lift">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="h5 mb-0"><i class="fas fa-swimmer me-2"></i>Natation Débutant</h4>
                    </div>
                    <div class="card-body">
                        <p class="small mb-3"><strong>Objectif :</strong> Sophie apprend à nager et veut progresser</p>
                        
                        <div class="mb-3">
                            <span class="badge bg-warning mb-2">Carnets créés</span>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>🏊 Techniques de Base</strong> - Fiches pratiques</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>📚 Guides Débutant</strong> - eBooks et documents</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>🏊 Séances Semaine</strong> - Workouts natation</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-success border-0 mb-0">
                            <small><strong>Résultat :</strong> Sophie imprime ses carnets (export PDF) et les emmène à la piscine dans une pochette plastique pour suivre ses fiches techniques.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exemple 4 -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm hover-lift">
                    <div class="card-header bg-info text-white">
                        <h4 class="h5 mb-0"><i class="fas fa-heartbeat me-2"></i>Remise en Forme</h4>
                    </div>
                    <div class="card-body">
                        <p class="small mb-3"><strong>Objectif :</strong> Lucas reprend le sport après 2 ans d'arrêt</p>
                        
                        <div class="mb-3">
                            <span class="badge bg-info mb-2">Carnets créés</span>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>⭐ Mes Favoris</strong> - Mix de tout ce qu'il aime</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>📖 À Lire Plus Tard</strong> - Articles sauvegardés</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card card-body p-2 bg-light">
                                        <small><strong>🎯 Objectif -10kg</strong> - Programme personnalisé</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-success border-0 mb-0">
                            <small><strong>Résultat :</strong> Lucas utilise les couleurs pour différencier ses carnets (vert = nutrition, bleu = cardio, rouge = force). Accès rapide via favoris.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Points clés -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="text-center mb-5">
            <i class="fas fa-key text-warning" style="font-size: 3rem;"></i>
            <h2 class="mt-3 display-5 fw-bold">Points clés à retenir</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-infinity fa-3x text-primary mb-3"></i>
                        <h5>Carnets illimités</h5>
                        <p class="small text-muted mb-0">Créez autant de carnets que nécessaire</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-sticky-note fa-3x text-warning mb-3"></i>
                        <h5>Notes personnelles</h5>
                        <p class="small text-muted mb-0">Annotez tous vos contenus</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-arrows-alt fa-3x text-info mb-3"></i>
                        <h5>Réorganisation facile</h5>
                        <p class="small text-muted mb-0">Drag & drop intuitif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                        <h5>Export PDF</h5>
                        <p class="small text-muted mb-0">Consultez hors ligne</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-cloud fa-3x text-success mb-3"></i>
                        <h5>Sauvegarde cloud</h5>
                        <p class="small text-muted mb-0">Synchronisé automatiquement</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-center h-100 shadow-sm hover-lift">
                    <div class="card-body">
                        <i class="fas fa-lock fa-3x text-secondary mb-3"></i>
                        <h5>100% Confidentiel</h5>
                        <p class="small text-muted mb-0">Vos carnets sont privés</p>
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
                const offset = 150;
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