@extends('layouts.user')

@section('title', 'Mon tableau de bord')

@section('content')

{{-- Bandeau titre --}}
<section class="position-relative text-white overflow-hidden">
    <div class="card bg-secondary p-3 border-0 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-white">
                <i class="fas fa-water me-2"></i>
                Tableau de bord — {{ auth()->user()->first_name ?: auth()->user()->name }}
            </h2>
        </div>
    </div>
</section>

<div class="container-fluid">

    {{-- Cartes de navigation --}}
    <div class="row g-4 mt-2">

        {{-- Ma fiche --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div>
                        <i class="fas fa-id-card fa-3x text-primary mb-3"></i>
                        <h5 class="fw-semibold">Ma fiche</h5>
                        <p class="text-muted mb-3">Consultez votre fiche de profil enrichi</p>
                    </div>
                    <a href="{{ route('user.user-profile.show') }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i>Voir ma fiche
                    </a>
                </div>
            </div>
        </div>

        {{-- Mon profil --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div>
                        <i class="fas fa-user-circle fa-3x text-secondary mb-3"></i>
                        <h5 class="fw-semibold">Mon profil</h5>
                        <p class="text-muted mb-3">Mettez à jour vos informations personnelles</p>
                    </div>
                    <a href="{{ route('user.profile.edit') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-edit me-2"></i>Modifier mon profil
                    </a>
                </div>
            </div>
        </div>

        {{-- Actualités --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div>
                        <i class="fas fa-newspaper fa-3x text-secondary mb-3"></i>
                        <h5 class="fw-semibold">Actualités</h5>
                        <p class="text-muted mb-3">Consultez les derniers articles publiés</p>
                    </div>
                    <a href="{{ route('posts.public.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-eye me-2"></i>Voir les articles
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4 mt-2">

        {{-- Fiches pratiques --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div>
                        <i class="fas fa-file-medical fa-3x text-secondary mb-3"></i>
                        <h5 class="fw-semibold">Fiches pratiques</h5>
                        <p class="text-muted mb-3">Accédez aux fiches techniques disponibles</p>
                    </div>
                    <a href="{{ route('public.fiches.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-eye me-2"></i>Voir les fiches
                    </a>
                </div>
            </div>
        </div>

        {{-- Tutoriels vidéo --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div>
                        <i class="fas fa-video fa-3x text-secondary mb-3"></i>
                        <h5 class="fw-semibold">Tutoriels</h5>
                        <p class="text-muted mb-3">Visionnez les tutoriels et vidéos disponibles</p>
                    </div>
                    <a href="{{ route('public.videos.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-play me-2"></i>Voir les vidéos
                    </a>
                </div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div>
                        <i class="fas fa-book fa-3x text-secondary mb-3"></i>
                        <h5 class="fw-semibold">Documents</h5>
                        <p class="text-muted mb-3">Téléchargez les documents mis à disposition</p>
                    </div>
                    <a href="{{ route('ebook.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-download me-2"></i>Voir les documents
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
