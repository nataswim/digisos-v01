@extends('layouts.editor')

@section('title', 'Dashboard Éditeur')

@section('content')

<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-secondary  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-white ">  <i class="fas fa-water me-2"></i>
                Tableau de bord - <Editeur->{{ auth()->user()->name }}</Editeur-></h2>
        
            </div>
        </div>
</section>

<div class="container-fluid">

    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <i class="fas fa-file-alt fa-3x text-secondary mb-3"></i>
                    <h5>Articles</h5>
                    <p class="text-muted mb-3">Créer, modifier et publier des articles</p>
                    <a href="{{ route('editor.posts.index') }}" class="btn btn-outline-primary">
                        Gérer mes articles
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <i class="fas fa-file-medical fa-3x text-secondary mb-3"></i>
                    <h5>Fiches</h5>
                    <p class="text-muted mb-3">Créer et gérer des fiches</p>
                    <a href="{{ route('editor.fiches.index') }}" class="btn btn-outline-primary">
                        Gérer mes fiches
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <i class="fas fa-video fa-3x text-secondary mb-3"></i>
                    <h5>Vidéos</h5>
                    <p class="text-muted mb-3">Uploader et gérer des vidéos</p>
                    <a href="{{ route('editor.videos.index') }}" class="btn btn-outline-primary">
                        Gérer mes vidéos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <i class="fas fa-copy fa-3x text-secondary mb-3"></i>
                    <h5>Pages</h5>
                    <p class="text-muted mb-3">Créer, modifier et publier des pages</p>
                    <a href="{{ route('editor.pages.index') }}" class="btn btn-outline-primary">
                        Gérer mes pages
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <i class="fas fa-images fa-3x text-secondary mb-3"></i>
                    <h5>Albums</h5>
                    <p class="text-muted mb-3">Créer et gérer des galleries</p>
                    <a href="{{ route('editor.photo-galleries.index') }}" class="btn btn-outline-primary">
                        Gérer mes galleries
                    </a>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <i class="fas fa-photo-video fa-3x text-secondary mb-3"></i>
                    <h5>Médiathèque</h5>
                    <p class="text-muted mb-3">Uploader et gérer des images</p>
                    <a href="{{ route('editor.media.index') }}" class="btn btn-outline-primary">
                        Gérer les Médias
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection