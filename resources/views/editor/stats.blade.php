@extends('layouts.editor')

@section('title', 'Statistiques')

@section('content')

<!-- Titre Section -->
<section class="position-relative text-white overflow-hidden">
<div class="card bg-secondary  p-3 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white "> <i class="fas fa-chart-bar text-white me-2"></i>
                 Statistiques</h5>
            </div>
        </div>
</section>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div class="btn-group">
            <a href="{{ route('editor.stats', ['period' => 7]) }}" 
               class="btn btn-sm {{ $period == 7 ? 'btn-primary' : 'btn-secondary' }}">7 jours</a>
            <a href="{{ route('editor.stats', ['period' => 30]) }}" 
               class="btn btn-sm {{ $period == 30 ? 'btn-primary' : 'btn-secondary' }}">30 jours</a>
            <a href="{{ route('editor.stats', ['period' => 90]) }}" 
               class="btn btn-sm {{ $period == 90 ? 'btn-primary' : 'btn-secondary' }}">90 jours</a>
            <a href="{{ route('editor.stats', ['period' => 365]) }}" 
               class="btn btn-sm {{ $period == 365 ? 'btn-primary' : 'btn-secondary' }}">1 an</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Articles</h6>
                        <i class="fas fa-file-alt fa-2x text-primary"></i>
                    </div>
                    <h2 class="mb-0">{{ $globalStats['my_posts'] }}</h2>
                    <small class="text-muted">Total: {{ $globalStats['total_posts'] }}</small>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar bg-primary" style="width: {{ $globalStats['total_posts'] > 0 ? ($globalStats['my_posts'] / $globalStats['total_posts'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Fiches</h6>
                        <i class="fas fa-file-medical fa-2x text-success"></i>
                    </div>
                    <h2 class="mb-0">{{ $globalStats['my_fiches'] }}</h2>
                    <small class="text-muted">Total: {{ $globalStats['total_fiches'] }}</small>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar bg-success" style="width: {{ $globalStats['total_fiches'] > 0 ? ($globalStats['my_fiches'] / $globalStats['total_fiches'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Vidéos</h6>
                        <i class="fas fa-video fa-2x text-primary"></i>
                    </div>
                    <h2 class="mb-0">{{ $globalStats['my_videos'] }}</h2>
                    <small class="text-muted">Total: {{ $globalStats['total_videos'] }}</small>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar bg-danger" style="width: {{ $globalStats['total_videos'] > 0 ? ($globalStats['my_videos'] / $globalStats['total_videos'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Médias</h6>
                        <i class="fas fa-images fa-2x text-info"></i>
                    </div>
                    <h2 class="mb-0">{{ $globalStats['my_media'] }}</h2>
                    <small class="text-muted">Total: {{ $globalStats['total_media'] }}</small>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar bg-info" style="width: {{ $globalStats['total_media'] > 0 ? ($globalStats['my_media'] / $globalStats['total_media'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Vues totales</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Articles</span>
                            <strong>{{ number_format($viewsStats['my_post_views']) }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ $viewsStats['total_post_views'] > 0 ? ($viewsStats['my_post_views'] / $viewsStats['total_post_views'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Fiches</span>
                            <strong>{{ number_format($viewsStats['my_fiche_views']) }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $viewsStats['total_fiche_views'] > 0 ? ($viewsStats['my_fiche_views'] / $viewsStats['total_fiche_views'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Vidéos</span>
                            <strong>{{ number_format($viewsStats['my_video_views']) }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-danger" 
                                 style="width: {{ $viewsStats['total_video_views'] > 0 ? ($viewsStats['my_video_views'] / $viewsStats['total_video_views'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Activité récente ({{ $period }} jours)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Articles créés</span>
                            <strong class="text-primary">{{ $recentStats['my_posts_created'] }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Fiches créées</span>
                            <strong class="text-success">{{ $recentStats['my_fiches_created'] }}</strong>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Vidéos créées</span>
                            <strong class="text-danger">{{ $recentStats['my_videos_created'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Répartition par statut</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Articles publiés</span>
                            <strong class="text-success">{{ $statusBreakdown['posts']['published'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Brouillons</span>
                            <span class="text-warning">{{ $statusBreakdown['posts']['draft'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Fiches publiées</span>
                            <strong class="text-success">{{ $statusBreakdown['fiches']['published'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Brouillons</span>
                            <span class="text-warning">{{ $statusBreakdown['fiches']['draft'] }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Vidéos publiées</span>
                            <strong class="text-success">{{ $statusBreakdown['videos']['published'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Brouillons</span>
                            <span class="text-warning">{{ $statusBreakdown['videos']['draft'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary border-bottom">
                    <h5 class="mb-0 text-white">Articles</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($myTopPosts as $post)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-truncate">{{ $post->name }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i>{{ number_format($post->hits ?? 0) }} vues
                                        </small>
                                    </div>
                                    <a href="{{ route('editor.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted">
                                Aucun article publié
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary border-bottom">
                    <h5 class="mb-0 text-white">Fiches</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($myTopFiches as $fiche)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-truncate">{{ $fiche->title }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i>{{ number_format($fiche->views_count ?? 0) }} vues
                                        </small>
                                    </div>
                                    <a href="{{ route('editor.fiches.edit', $fiche) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted">
                                Aucune fiche publiée
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary border-bottom">
                    <h5 class="mb-0 text-white">Vidéos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($myTopVideos as $video)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-truncate">{{ $video->title }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i>{{ number_format($video->views_count ?? 0) }} vues
                                        </small>
                                    </div>
                                    <a href="{{ route('editor.videos.edit', $video) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted">
                                Aucune vidéo publiée
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Activité des 30 derniers jours</h5>
        </div>
        <div class="card-body">
            <canvas id="activityChart" height="80"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('activityChart').getContext('2d');
const activityData = @json($activityData);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: activityData.map(d => d.date),
        datasets: [
            {
                label: 'Articles',
                data: activityData.map(d => d.posts),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4
            },
            {
                label: 'Fiches',
                data: activityData.map(d => d.fiches),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            },
            {
                label: 'Vidéos',
                data: activityData.map(d => d.videos),
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endpush
