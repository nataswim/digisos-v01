@extends('layouts.public')

@section('title', 'Erreur serveur')

@section('content')
<div class="min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                     style="width: 150px; height: 150px;">
                    <span class="display-1 fw-bold text-danger">500</span>
                </div>
                <h1 class="display-4 fw-bold mb-3">Erreur serveur</h1>
                <p class="lead text-muted mb-4">
                    Une erreur s'est produite sur notre serveur. Nos equipes techniques ont ete notifiees 
                    et travaillent A resoudre ce probleme.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Retour A l'accueil
                    </a>
                    <button onclick="window.location.reload()" class="btn btn-outline-primary">
                        <i class="fas fa-redo me-2"></i>Reessayer
                    </button>
                </div>
                
                <div class="mt-5 p-4 bg-white rounded border">
                    <h6 class="fw-semibold mb-3">Besoin d'aide ?</h6>
                    <p class="text-muted mb-3">
                        Si le probleme persiste, n'hesitez pas A nous contacter.
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-envelope me-2"></i>Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection