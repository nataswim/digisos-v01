@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
<div class="container-fluid py-4">
    
    {{-- En-tête --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Statistiques
                </h1>
                
                {{-- Filtres période --}}
                <div class="btn-group" role="group">
                    <a href="?period=7" class="btn btn-sm {{ $period == 7 ? 'btn-primary' : 'btn-outline-primary' }}">
                        7 jours
                    </a>
                    <a href="?period=30" class="btn btn-sm {{ $period == 30 ? 'btn-primary' : 'btn-outline-primary' }}">
                        30 jours
                    </a>
                    <a href="?period=90" class="btn btn-sm {{ $period == 90 ? 'btn-primary' : 'btn-outline-primary' }}">
                        90 jours
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        {{-- ========== COLONNE GAUCHE : MES STATS (Editor + Admin uniquement) ========== --}}
        @if($myStats)
        <div class="col-lg-6 mb-4">
            <h2 class="h5 mb-3">
                <i class="fas fa-user-circle me-2"></i>
                Mes statistiques
            </h2>
            
            {{-- Mes contenus --}}
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h3 class="h6 mb-0">Mes contenus</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 mb-0">{{ $myStats['my_posts'] }}</div>
                                <small class="text-muted">Posts</small>
                                <div class="small mt-1">
                                    <span class="badge bg-success">{{ $myStats['my_published_posts'] }} publiés</span>
                                    <span class="badge bg-secondary">{{ $myStats['my_draft_posts'] }} brouillons</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 mb-0">{{ $myStats['my_fiches'] }}</div>
                                <small class="text-muted">Fiches</small>
                                <div class="small mt-1">
                                    <span class="badge bg-success">{{ $myStats['my_published_fiches'] }} publiées</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 mb-0">{{ $myStats['my_videos'] }}</div>
                                <small class="text-muted">Vidéos</small>
                                <div class="small mt-1">
                                    <span class="badge bg-success">{{ $myStats['my_published_videos'] }} publiées</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 mb-0">{{ $myStats['my_media'] }}</div>
                                <small class="text-muted">Médias</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mes vues --}}
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h3 class="h6 mb-0">Mes vues totales</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="text-center">
                                <div class="h5 mb-0">{{ number_format($myStats['my_post_views']) }}</div>
                                <small class="text-muted">Posts</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center">
                                <div class="h5 mb-0">{{ number_format($myStats['my_fiche_views']) }}</div>
                                <small class="text-muted">Fiches</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center">
                                <div class="h5 mb-0">{{ number_format($myStats['my_video_views']) }}</div>
                                <small class="text-muted">Vidéos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mon activité récente --}}
            @if($myRecentActivity)
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="h6 mb-0">Mon activité ({{ $period }} derniers jours)</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-newspaper me-2 text-primary"></i>
                            <strong>{{ $myRecentActivity['my_posts_created'] }}</strong> posts créés
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-file-alt me-2 text-success"></i>
                            <strong>{{ $myRecentActivity['my_fiches_created'] }}</strong> fiches créées
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-video me-2 text-danger"></i>
                            <strong>{{ $myRecentActivity['my_videos_created'] }}</strong> vidéos ajoutées
                        </li>
                        <li>
                            <i class="fas fa-image me-2 text-warning"></i>
                            <strong>{{ $myRecentActivity['my_media_uploaded'] }}</strong> médias uploadés
                        </li>
                    </ul>
                </div>
            </div>
            @endif

            {{-- Top contenus --}}
            @if($myTopContent && count($myTopContent['top_posts']) > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="h6 mb-0">Mes posts les plus vus</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($myTopContent['top_posts'] as $post)
                        <li class="mb-2">
                            <a href="{{ route('posts.public.show', $post->slug) }}" target="_blank" class="text-decoration-none">
                                {{ $post->title }}
                            </a>
                            <span class="badge bg-primary float-end">{{ number_format($post->hits) }} vues</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- ========== COLONNE DROITE : STATS GLOBALES (tous) ========== --}}
        <div class="{{ $myStats ? 'col-lg-6' : 'col-12' }} mb-4">
            <h2 class="h5 mb-3">
                <i class="fas fa-globe me-2"></i>
                Statistiques globales du site
            </h2>
            
            {{-- Contenus totaux --}}
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h3 class="h6 mb-0">Contenus totaux</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 mb-0">{{ $globalStats['total_posts'] }}</div>
                                <small class="text-muted">Posts</small>
                                <div class="small mt-1">
                                    <span class="badge bg-success">{{ $globalStats['published_posts'] }}</span>
                                    <span class="badge bg-secondary">{{ $globalStats['draft_posts'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 mb-0">{{ $globalStats['total_fiches'] }}</div>
                                <small class="text-muted">Fiches</small>
                                <div class="small mt-1">
                                    <span class="badge bg-success">{{ $globalStats['published_fiches'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 mb-0">{{ $globalStats['total_videos'] }}</div>
                                <small class="text-muted">Vidéos</small>
                                <div class="small mt-1">
                                    <span class="badge bg-success">{{ $globalStats['published_videos'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 mb-0">{{ $globalStats['total_media'] }}</div>
                                <small class="text-muted">Médias</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vues totales --}}
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h3 class="h6 mb-0">Vues totales du site</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="text-center">
                                <div class="h5 mb-0">{{ number_format($globalStats['total_post_views']) }}</div>
                                <small class="text-muted">Posts</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center">
                                <div class="h5 mb-0">{{ number_format($globalStats['total_fiche_views']) }}</div>
                                <small class="text-muted">Fiches</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center">
                                <div class="h5 mb-0">{{ number_format($globalStats['total_video_views']) }}</div>
                                <small class="text-muted">Vidéos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activité récente globale --}}
            @if($recentActivity)
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="h6 mb-0">Activité globale ({{ $period }} derniers jours)</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-newspaper me-2 text-primary"></i>
                            <strong>{{ $recentActivity['posts_created'] }}</strong> posts créés
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-file-alt me-2 text-success"></i>
                            <strong>{{ $recentActivity['fiches_created'] }}</strong> fiches créées
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-video me-2 text-danger"></i>
                            <strong>{{ $recentActivity['videos_created'] }}</strong> vidéos ajoutées
                        </li>
                        <li>
                            <i class="fas fa-image me-2 text-warning"></i>
                            <strong>{{ $recentActivity['media_uploaded'] }}</strong> médias uploadés
                        </li>
                    </ul>
                </div>
            </div>
            @endif

            {{-- Stats utilisateurs (Admin uniquement) --}}
            @if($usersStats)
            <div class="card mb-3">
                <div class="card-header bg-danger text-white">
                    <h3 class="h6 mb-0">Utilisateurs</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="h5 mb-0">{{ $usersStats['total_users'] }}</div>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="h5 mb-0">{{ $usersStats['active_users'] }}</div>
                                <small class="text-muted">Actifs</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">Admins :</small>
                            <strong class="float-end">{{ $usersStats['admins'] }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Éditeurs :</small>
                            <strong class="float-end">{{ $usersStats['editors'] }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Users :</small>
                            <strong class="float-end">{{ $usersStats['users'] }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Visitors :</small>
                            <strong class="float-end">{{ $usersStats['visitors'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top contributeurs --}}
            @if($topContributors && count($topContributors) > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="h6 mb-0">Top contributeurs</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($topContributors as $contributor)
                        <li class="mb-2">
                            <strong>{{ $contributor['name'] }}</strong>
                            <span class="badge bg-info float-end">{{ $contributor['posts_count'] }} posts</span>
                            <br>
                            <small class="text-muted">{{ $contributor['role'] }}</small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
@endsection