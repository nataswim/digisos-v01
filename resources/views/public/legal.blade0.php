@extends('layouts.public')

@section('title', 'Mentions Légales')

@section('content')

<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-gavel me-3 fs-1"></i>
                    <h1 class="display-4 fw-bold mb-0">Mentions Légales</h1>
                </div>
                <p class="lead mb-3">
                    Conformément aux dispositions de la loi n° 2004-575 du 21 juin 2004 pour la confiance en 
                    l'économie numérique, voici les informations légales concernant ce site.
                </p>
                <p class="mb-0 opacity-75">
                    Dernière mise à jour : {{ now()->format('d/m/Y') }}
                </p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                     style="width: 200px; height: 200px;">
                    <i class="fas fa-building" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contenu principal -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="alert alert-info mb-5">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-question-circle text-primary fs-3"></i>
                        </div>
                        <div>
                            <p class="mb-0">
                                Les présentes mentions légales sont susceptibles d'être modifiées à tout moment. 
                                Nous vous invitons à les consulter régulièrement.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Éditeur du site -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-building text-info fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">Éditeur du site</h3>
                        </div>
                        <div class="card p-4 bg-light border-0">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Raison sociale :</strong> SNS (6201Z)</p>
                                    <p class="mb-1"><strong>Adresse du siège social :</strong> 45 Avenue Albert Camus, 78200 Paris, France</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Directeur de la publication :</strong> Med H EL HAOUAT</p>
                                    <p class="mb-0"><strong>Contact :</strong> natation.swimming@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Hébergeur -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-server text-success fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">Hébergeur</h3>
                        </div>
                        <div class="card p-4 bg-light border-0">
                            <p class="mb-1"><strong>Raison sociale :</strong> O2Switch - HOSTINGER</p>
                        </div>
                    </div>
                </article>

                <!-- Propriété intellectuelle -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-copyright text-warning fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">Propriété intellectuelle</h3>
                        </div>
                        <p class="mb-3">
                            L'ensemble de ce site (structure, présentation, textes, logos, images, photographies, vidéos, 
                            sons, applications informatiques, etc.) constitue une œuvre protégée par la législation française 
                            et internationale relative à la propriété intellectuelle.
                        </p>
                        <p class="mb-3">
                            Nataswim est titulaire exclusif de tous les droits de propriété intellectuelle sur le site et 
                            son contenu. Sauf autorisation préalable et expresse de Nataswim, toute représentation, 
                            reproduction, modification, publication ou adaptation de tout ou partie du site ou de son contenu, 
                            sur quelque support que ce soit et par quelque procédé que ce soit, est interdite.
                        </p>
                        <p class="mb-0">
                            Le non-respect de cette interdiction constitue une contrefaçon susceptible d'engager la 
                            responsabilité civile et pénale du contrefacteur.
                        </p>
                    </div>
                </article>

                <!-- Liens hypertextes -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-link text-primary fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">Liens hypertextes</h3>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h4 class="h6 mb-3 fw-bold">Liens vers notre site</h4>
                                        <p class="mb-0">
                                            La mise en place d'un lien hypertexte vers notre site nécessite une 
                                            autorisation préalable et écrite. Veuillez nous contacter si vous souhaitez 
                                            établir un lien vers notre site.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h4 class="h6 mb-3 fw-bold">Liens depuis notre site</h4>
                                        <p class="mb-0">
                                            Notre site peut contenir des liens hypertextes redirigeant vers d'autres 
                                            sites internet. Nataswim n'a pas la possibilité de vérifier le contenu de 
                                            ces sites et n'assumera aucune responsabilité de ce fait quant aux contenus 
                                            de ces sites.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Limitation de responsabilité -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-exclamation-triangle text-danger fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">Limitation de responsabilité</h3>
                        </div>
                        <p class="mb-3">
                            Nataswim s'efforce d'assurer au mieux de ses possibilités l'exactitude et la mise à jour des 
                            informations diffusées sur son site. Cependant, Nataswim ne peut garantir l'exactitude, la 
                            précision ou l'exhaustivité des informations mises à la disposition sur ce site.
                        </p>
                        <p class="mb-3">
                            Nataswim décline toute responsabilité :
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="mb-3">
                                    <li>Pour toute interruption du site</li>
                                    <li>Pour toute survenance de bogues</li>
                                    <li>Pour toute inexactitude ou omission dans les informations disponibles sur ce site</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="mb-0">
                                    <li>Pour tous dommages résultant d'une intrusion frauduleuse d'un tiers</li>
                                    <li>Et plus généralement de tout dommage direct ou indirect, quelles qu'en soient les causes</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Gestion des données -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-globe-europe text-info fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">Gestion des données personnelles</h3>
                        </div>
                        <p class="mb-0">
                            Les informations concernant la collecte et le traitement des données personnelles sont détaillées 
                            dans notre Politique de Confidentialité</a> 
                            et notre Politique de Cookies</a>.
                        </p>
                    </div>
                </article>

                <!-- Droit applicable -->
                <article class="card mb-5 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-gavel text-secondary fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">Droit applicable et juridiction compétente</h3>
                        </div>
                        <p class="mb-0">
                            Les présentes mentions légales sont régies par le droit français. En cas de litige relatif à 
                            l'interprétation ou à l'exécution des présentes, les tribunaux français seront seuls compétents.
                        </p>
                    </div>
                </article>

                <!-- Accessibilité -->
                <article class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-universal-access text-primary fs-3"></i>
                            </div>
                            <h3 class="h5 mb-0">Accessibilité</h3>
                        </div>
                        <p class="mb-0">
                            Notre engagement en matière d'accessibilité est détaillé dans notre 
                            <a href="{{ route('accessibility') }}" class="text-primary">Déclaration d'Accessibilité</a>.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- CTA Contact -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container-lg py-3">
        <h2 class="mb-4 fw-bold">Nous Contacter</h2>
        <p class="lead mb-4 mx-auto" style="max-width: 700px;">
            Pour toute question concernant les présentes mentions légales, n'hésitez pas à nous contacter.
        </p>
        <div class="d-flex justify-content-center">
            <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                <i class="fas fa-envelope me-2"></i>
                Contactez-nous
            </a>
        </div>
        <p class="mt-4 small opacity-75">
            Ces mentions légales sont fournies à titre informatif.
        </p>
    </div>
</section>

@endsection