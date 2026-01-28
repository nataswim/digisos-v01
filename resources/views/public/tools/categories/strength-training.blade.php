@extends('layouts.public')

@section('title', 'Outils Force & Musculation - Approche Securisee et Progressive Evidence-Based')
@section('meta_description', 'Outils scientifiques pour musculation securisee : calcul charges, progression graduelle, prevention blessures. Approche equilibree privilegiant sante et developpement harmonieux.')

@section('content')
<!-- Section titre -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('tools.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-home me-1"></i>Outils
                    </a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page">
                    Force & Musculation
                </li>
            </ol>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-dumbbell me-3"></i>
                    Force & Musculation
                </h1>
                <p class="lead mb-4">
                    Developpement de la force avec une approche securisee et progressive. 
                    Outils bases sur la physiologie musculaire, la biomecanique securitaire et la prevention des blessures.
                </p>
                <div class="alert alert-warning border-0 bg-white bg-opacity-25">
                    <small>
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>1 outil disponible</strong> - Securite et progression graduelle prioritaires
                    </small>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-circle p-4 d-inline-flex align-items-center justify-content-center" 
                     style="width: 150px; height: 150px;">
                    <i class="fas fa-dumbbell text-white" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avertissement securite -->
<section class="py-4 bg-danger text-white">
    <div class="container">
        <div class="alert alert-light border-0 mb-0">
            <div class="row align-items-center">
                <div class="col-md-1 text-center">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                </div>
                <div class="col-md-11">
                    <h6 class="fw-bold mb-2 text-danger">Securite en Musculation - Priorite Absolue</h6>
                    <p class="mb-0 small text-dark">
                        <strong>La musculation presente des risques de blessures graves sans supervision et progression appropriees.</strong> 
                        Apprentissage technique obligatoire avec professionnel qualifie, progression tres graduelle, 
                        echauffement systematique et arrêt immediat en cas de douleur. 
                        Consultation medicale recommandee avant debut, particulierement apres 40 ans ou antecedents articulaires/cardiaques.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Outils de la categorie -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            
            <!-- 1. Calculateur 1RM & Charges -->
            <div class="col-lg-8 mx-auto">
                <a href="{{ route('tools.onermcalculator') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-danger bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-weight-hanging text-danger" style="font-size: 2rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h4 class="card-title mb-0 text-dark fw-bold">Calculateur 1RM & Charges d'Entraînement</h4>
                                        <span class="badge bg-primary ms-2">Avance</span>
                                    </div>
                                    <p class="card-text text-muted mb-4">
                                        Estimation de la repetition maximale (1RM) et calcul des pourcentages d'entraînement securises. 
                                        Planification progressive des charges respectant les principes physiologiques 
                                        et la prevention des blessures pour un developpement harmonieux et durable.
                                    </p>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-graduate text-warning me-2"></i>
                                                <small class="text-warning fw-semibold">Supervision requise</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-chart-line text-success me-2"></i>
                                                <small class="text-success fw-semibold">Progression graduelle</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-heart text-danger me-2"></i>
                                                <small class="text-danger fw-semibold">Sante prioritaire</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-primary fw-bold fs-5">Acceder A l'outil →</span>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <small>5-8 min</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <!-- Message supervision obligatoire -->
        <div class="text-center mt-5">
            <div class="card border-danger">
                <div class="card-body py-3">
                    <h6 class="text-danger mb-2">
                        <i class="fas fa-user-tie me-2"></i>Supervision Professionnelle Recommandee
                    </h6>
                    <p class="small text-muted mb-3">
                        La musculation necessite un apprentissage technique rigoureux. 
                        L'accompagnement d'un professionnel qualifie accelere les progres et previent les blessures.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <span class="badge bg-outline-primary">Coach sportif diplôme</span>
                        <span class="badge bg-outline-success">Kinesitherapeute</span>
                        <span class="badge bg-outline-warning">Preparateur physique</span>
                        <span class="badge bg-outline-info">educateur APA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="row g-3 mt-5">
            <div class="col-md-6">
                <a href="{{ route('tools.category.running') }}" class="btn btn-outline-dark btn-lg w-100">
                    <i class="fas fa-arrow-left me-2"></i>Course & Endurance
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('tools.category.practical') }}" class="btn btn-dark btn-lg w-100">
                    Outils Pratiques <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contenu educatif -->
<section class="py-5">
    <div class="container">
        
        <!-- Securite en musculation -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h3 class="mb-2">
                    <i class="fas fa-shield-alt me-2"></i>
                    Securite en Musculation - Priorite Absolue
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-danger">Risques et Accidents Courants</h6>
                        <ul class="small">
                            <li><strong>Blessures musculaires :</strong> elongations, dechirures par charge excessive</li>
                            <li><strong>Traumatismes articulaires :</strong> Entorses, luxations par mauvaise technique</li>
                            <li><strong>Blessures rachidiennes :</strong> Hernies, tassements vertebraux</li>
                            <li><strong>Accidents materiel :</strong> Chute de charges, coincement</li>
                            <li><strong>Malaises cardiovasculaires :</strong> Manœuvre de Valsalva excessive</li>
                        </ul>
                        
                        <div class="alert alert-danger alert-sm">
                            <h6 class="small">Statistiques Alarmantes</h6>
                            <p class="small mb-0">
                                La musculation represente 15-25% des blessures sportives, 
                                souvent graves et invalidantes. <strong>La prevention est cruciale.</strong>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Regles de Securite Fondamentales</h6>
                        <ul class="small">
                            <li><strong>Apprentissage technique :</strong> Mouvement parfait avant charge</li>
                            <li><strong>echauffement obligatoire :</strong> 15-20 minutes minimum</li>
                            <li><strong>Progression graduelle :</strong> 5-10% augmentation maximum/semaine</li>
                            <li><strong>Supervision :</strong> Presence partenaire ou coach pour exercices lourds</li>
                            <li><strong>Materiel verifie :</strong> etat barres, disques, attaches</li>
                            <li><strong>Environnement securise :</strong> Espace degage, sol stable</li>
                        </ul>
                        
                        <div class="alert alert-success alert-sm">
                            <h6 class="small">Principe Cardinal</h6>
                            <p class="small mb-0">
                                <strong>"Mieux vaut sous-estimer ses capacites que risquer la blessure."</strong> 
                                La prudence permet la progression long terme.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-4">
                    <h6><i class="fas fa-stop me-2"></i>Arrêt Immediat Obligatoire</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <ul class="small mb-0">
                                <li>Douleur articulaire aiguë</li>
                                <li>Sensation de "claquage" musculaire</li>
                                <li>Vertiges, malaise, nausees</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="small mb-0">
                                <li>Perte de contrôle technique</li>
                                <li>Fatigue excessive compromettant securite</li>
                                <li>Douleur rachidienne (dos, nuque)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Physiologie et progression -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-chart-line me-2"></i>
                    Physiologie de la Force et Progression Saine
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-primary">Adaptations Physiologiques</h6>
                        <p class="small">
                            Le developpement de la force implique des adaptations nerveuses (coordination, recrutement) 
                            puis structurelles (hypertrophie musculaire). Ces adaptations necessitent du temps 
                            et une stimulation progressive pour s'installer durablement.
                        </p>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Periode</th>
                                        <th>Adaptation Principale</th>
                                        <th>Gains Attendus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>0-4 semaines</td>
                                        <td>Neuromusculaire</td>
                                        <td>+15-25% force</td>
                                    </tr>
                                    <tr>
                                        <td>4-8 semaines</td>
                                        <td>Mixte</td>
                                        <td>+10-20% force</td>
                                    </tr>
                                    <tr>
                                        <td>8-16 semaines</td>
                                        <td>Hypertrophie</td>
                                        <td>+5-15% force/volume</td>
                                    </tr>
                                    <tr>
                                        <td>4+ mois</td>
                                        <td>Structurelle</td>
                                        <td>Progression plus lente</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Principes de Progression Securisee</h6>
                        <ul class="small">
                            <li><strong>Progression lineaire :</strong> Augmentation graduelle et reguliere</li>
                            <li><strong>Periodisation :</strong> Alternance phases volume/intensite</li>
                            <li><strong>Recuperation integree :</strong> 48-72h entre seances muscle</li>
                            <li><strong>Individualisation :</strong> Adaptation selon reponse personnelle</li>
                            <li><strong>Monitoring :</strong> Suivi fatigue, motivation, performances</li>
                        </ul>
                        
                        <h6 class="text-warning mt-3">Signaux de Surcharge</h6>
                        <ul class="small">
                            <li>Stagnation ou regression performances</li>
                            <li>Fatigue persistante malgre repos</li>
                            <li>Douleurs articulaires chroniques</li>
                            <li>Troubles du sommeil, irritabilite</li>
                            <li>Perte d'envie, demotivation</li>
                        </ul>
                        
                        <div class="alert alert-info alert-sm">
                            <h6 class="small">Patience Necessaire</h6>
                            <p class="small mb-0">
                                Les adaptations durables prennent des mois A s'installer. 
                                Forcer la progression augmente le risque de blessure sans gain.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prevention troubles comportementaux -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-balance-scale me-2"></i>
                    Approche equilibree et Prevention des Derives
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-warning">Risques Comportementaux</h6>
                        <p class="small">
                            La musculation peut developper des comportements compulsifs ou une dysmorphie corporelle. 
                            L'obsession du physique, la comparaison constante ou l'entraînement excessif 
                            peuvent nuire au bien-être mental et social.
                        </p>
                        
                        <h6 class="text-danger mt-3">Signaux d'Alarme</h6>
                        <ul class="small">
                            <li>Impossibilite de manquer une seance</li>
                            <li>Entraînement malgre blessures</li>
                            <li>Insatisfaction permanente physique</li>
                            <li>Comparaison obsessionnelle avec autres</li>
                            <li>Negligence obligations sociales/professionnelles</li>
                            <li>Utilisation substances pour "ameliorer" resultats</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Approche Saine Recommandee</h6>
                        <ul class="small">
                            <li><strong>Objectifs realistes :</strong> Progression graduelle sur plusieurs annees</li>
                            <li><strong>equilibre vie :</strong> Musculation complement, non centre existence</li>
                            <li><strong>Plaisir preserve :</strong> Entraînement source satisfaction</li>
                            <li><strong>Flexibilite :</strong> Adaptation selon contraintes vie</li>
                            <li><strong>Image corporelle saine :</strong> Acceptation morphologie individuelle</li>
                            <li><strong>Perspective long terme :</strong> Sante et mobilite A vie</li>
                        </ul>
                        
                        <div class="alert alert-success alert-sm">
                            <h6 class="small">Benefices Sante Reels</h6>
                            <p class="small mb-0">
                                Musculation bien pratiquee ameliore force fonctionnelle, 
                                densite osseuse, metabolisme et confiance en soi durablement.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-4">
                    <h6><i class="fas fa-hands-helping me-2"></i>Recherche d'Aide Professionnelle</h6>
                    <p class="mb-0 small">
                        Si la musculation devient source d'anxiete, d'obsession ou nuit A votre bien-être global, 
                        <strong>consultez un psychologue specialise en sport ou troubles alimentaires.</strong> 
                        Il n'y a aucune honte A chercher de l'aide pour maintenir un rapport sain A l'exercice. 
                        Votre equilibre mental et social est plus important que n'importe quel objectif physique.
                    </p>
                </div>
            </div>
        </div>

        <!-- Materiel et environnement -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-tools me-2"></i>
                    Materiel et Environnement Securises
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-secondary">equipements de Securite</h6>
                        <ul class="small">
                            <li><strong>Barres et disques :</strong> Verification etat, serrage colliers</li>
                            <li><strong>Bancs et supports :</strong> Stabilite, reglages securises</li>
                            <li><strong>Câbles et poulies :</strong> Usure, lubrification</li>
                            <li><strong>Halteres :</strong> Serrage, equilibre</li>
                            <li><strong>Protections :</strong> Gants, ceinture si approprie</li>
                        </ul>
                        
                        <div class="alert alert-warning alert-sm">
                            <h6 class="small">Attention Materiel Defaillant</h6>
                            <p class="small mb-0">
                                Ne jamais utiliser materiel douteux. 
                                Signaler immediatement tout probleme au personnel.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info">Environnement Optimal</h6>
                        <ul class="small">
                            <li><strong>Espace suffisant :</strong> Mouvements libres, pas d'obstacle</li>
                            <li><strong>Sol stable :</strong> Antiderapant, niveau</li>
                            <li><strong>eclairage adapte :</strong> Visibilite parfaite</li>
                            <li><strong>Temperature :</strong> Ni trop chaud, ni trop froid</li>
                            <li><strong>Ventilation :</strong> Air renouvele</li>
                            <li><strong>Proximite secours :</strong> Personnel forme disponible</li>
                        </ul>
                        
                        <h6 class="text-success mt-3">Preparation Seance</h6>
                        <ul class="small">
                            <li>Planification exercices et charges</li>
                            <li>Verification materiel necessaire</li>
                            <li>echauffement specifique complet</li>
                            <li>Hydratation avant/pendant seance</li>
                            <li>Partenaire ou supervision si exercices lourds</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Credit et Contact -->
     <div class="card mb-4">
            <a href="{{ route('tools.index') }}" class="btn btn-success btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Essayer d'autres outils
            </a>
        </div>
<section class="py-5 bg-primary text-white">

    <div class="container">


        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">A Propos de nos Outils</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-info mb-2">Developpement & Expertise</h6>
                        <p class="mb-3">
                            Contenus et outils developpes par 
                            <a href="https://www.facebook.com/Sports.Ressources/" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="text-warning fw-bold text-decoration-none">
                                Med H El Haouat
                                <i class="fas fa-external-link-alt ms-1 small"></i>
                            </a>
                        </p>
                        <p class="small text-light opacity-75">
                            Expert en sciences du sport, physiologie de l'exercice et developpement 
                            d'outils d'aide A la performance sportive evidence-based.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success mb-2">Collaboration & Amelioration</h6>
                        <p class="mb-3 small">
                            Si vous constatez une erreur dans nos calculateurs ou souhaitez suggerer 
                            de nouveaux outils, n'hesitez pas A nous contacter.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-envelope me-2"></i>Nous Contacter
                            </a>
                            <a href="https://www.facebook.com/Sports.Ressources/" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fab fa-linkedin me-2"></i>LinkedIn
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="bg-white bg-opacity-10 rounded-circle p-2 d-inline-flex align-items-center justify-content-center" 
                     style="width: 150px; height: 150px; overflow: hidden;">
                    <img src="{{ asset('assets/images/team/med_Hassan_EL_HAOUAT.png') }}" 
                         alt="Med H El Haouat - Expert en sciences du sport" 
                         class="w-100 h-100 rounded-circle"
                         style="object-fit: cover;">
                </div>
                <div class="mt-3">
                    <h6 class="text-warning mb-1">Evidence-Based</h6>
                    <small class="text-light opacity-75">Recherches 2024 integrees</small>
                </div>
            </div>
        </div>
    </div>
</section>





<!-- Dernieres Publications -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-water text-primary me-2"></i>Dernieres Publications
            </h2>
            <a href="{{ route('posts.public.index') }}" class="btn btn-outline-primary">
                Tous les articles <i class="fas fa-angle-right ms-1"></i>
            </a>
        </div>
        
        @php
            $latestPosts = App\Models\Post::with('category')
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->limit(3)
                ->get();
        @endphp
        
        @if($latestPosts->count() > 0)
            <div class="row g-4">
                @foreach($latestPosts as $post)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm hover-lift border-0">
                            <div style="height: 180px; overflow: hidden;">
                                @if($post->image)
                                    <img src="{{ $post->image }}" 
                                         alt="{{ $post->name }}"
                                         class="card-img-top"
                                         style="height: 100%; width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;">
                                        <i class="fas fa-swimmer text-muted" style="font-size: 2.5rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                @if($post->category)
                                    <div class="mb-2">
                                        <span class="badge bg-primary">{{ $post->category->name }}</span>
                                    </div>
                                @endif
                                <h3 class="card-title h5 mb-3">{{ $post->name }}</h3>
                                @if($post->intro)
                                    <p class="card-text text-muted small">
                                        {!! Str::limit(strip_tags($post->intro), 100) !!}
                                    </p>
                                @endif
                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                                <small class="text-muted d-flex align-items-center">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $post->published_at->format('d/m/Y') }}
                                </small>
                                <a href="{{ route('posts.public.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                    Lire la suite
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info" role="alert">
                <i class="fas fa-water me-2"></i>Aucun article n'est disponible actuellement.
            </div>
        @endif
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
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255,255,255,0.7);
}

.breadcrumb-item.active {
    color: rgba(255,255,255,0.9);
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.table th {
    border-top: none;
}

.badge.bg-outline-primary {
    color: #0d6efd;
    border: 1px solid #0d6efd;
    background: transparent;
}

.badge.bg-outline-success {
    color: #198754;
    border: 1px solid #198754;
    background: transparent;
}

.badge.bg-outline-warning {
    color: #ffc107;
    border: 1px solid #ffc107;
    background: transparent;
}

.badge.bg-outline-info {
    color: #0dcaf0;
    border: 1px solid #0dcaf0;
    background: transparent;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entree pour les cards
    const cards = document.querySelectorAll('.hover-lift');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush