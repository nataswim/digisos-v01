@extends('layouts.app')

@section('title', 'Dashboard Visiteur')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-tachometer-alt text-primary me-2"></i>
                Dashboard Visiteur
            </h2>
            <p class="text-muted">Bienvenue, {{ auth()->user()->name }}</p>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-newspaper fa-3x text-primary mb-3"></i>
                    <h5>Articles</h5>
                    <p class="text-muted mb-3">Consultez nos articles</p>
                    <a href="{{ route('posts.public.index') }}" class="btn btn-primary">
                        Voir les articles
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                    <h5>Fiches</h5>
                    <p class="text-muted mb-3">Consultez nos fiches</p>
                    <a href="{{ route('public.fiches.index') }}" class="btn btn-success">
                        Voir les fiches
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-video fa-3x text-danger mb-3"></i>
                    <h5>Vidéos</h5>
                    <p class="text-muted mb-3">Consultez nos vidéos</p>
                    <a href="{{ route('public.videos.index') }}" class="btn btn-danger">
                        Voir les vidéos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Mon Profil
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Email :</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Rôle :</strong> {{ auth()->user()->role?->name ?? 'Visiteur' }}</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Modifier mon profil
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Bienvenue sur votre espace personnel. Vous pouvez consulter tous les contenus publics du site.
                    </p>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i>Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
