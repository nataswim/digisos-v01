@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Gestion et apercu')

@section('content')
<div class="container-fluid">
    <!-- Cartes statistiques -->
    <div class="row g-4 mb-4">
        @php
            $stats = [
                [
                    'title' => 'Articles',
                    'value' => App\Models\Post::count(),
                    'subtitle' => 'Total articles',
                    'icon' => 'fas fa-water',
                    'color' => 'info',
                    'change' => '+12%'
                ],
                [
                    'title' => 'Utilisateurs',
                    'value' => App\Models\User::count(),
                    'subtitle' => 'Membres inscrits',
                    'icon' => 'fas fa-users',
                    'color' => 'info',
                    'change' => '+8%'
                ],
                [
                    'title' => 'Vues',
                    'value' => App\Models\Post::sum('hits'),
                    'subtitle' => 'Vues totales',
                    'icon' => 'fas fa-eye',
                    'color' => 'info',
                    'change' => '+23%'
                ],
                [
                    'title' => 'Categories',
                    'value' => App\Models\Category::count(),
                    'subtitle' => 'Categories actives',
                    'icon' => 'fas fa-folder',
                    'color' => 'info',
                    'change' => '+5%'
                ]
            ];
        @endphp
        
        @foreach($stats as $stat)
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-{{ $stat['color'] }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="{{ $stat['icon'] }} text-{{ $stat['color'] }}"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="fw-bold mb-0">{{ number_format($stat['value']) }}</h3>
                                    <span class="badge bg-success-subtle text-success">{{ $stat['change'] }}</span>
                                </div>
                                <p class="text-muted mb-0">{{ $stat['subtitle'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="row g-4">
        <!-- Articles recents -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Articles recents</h5>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @php
                        $recentPosts = App\Models\Post::with(['category', 'creator'])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @forelse($recentPosts as $post)
                        <div class="d-flex align-items-center p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-file-alt text-primary"></i>
                            </div>
                            <div class="flex-fill">
                                <h6 class="mb-1">
                                    <a href="{{ route('admin.posts.show', $post) }}" class="text-decoration-none">
                                        {!! Str::limit($post->name, 50) !!}
                                    </a>
                                </h6>
                                <div class="d-flex align-items-center text-muted">
                                    <small>{{ $post->category->name ?? 'Non categorise' }}</small>
                                    <span class="mx-2">•</span>
                                    <small>{{ $post->created_at?->format('d/m/Y') ?? 'N/A' }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'warning' }}-subtle text-{{ $post->status === 'published' ? 'success' : 'warning' }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.posts.edit', $post) }}">Modifier</a></li>
                                        <li><a class="dropdown-item" href="{{ route('posts.public.show', $post) }}" target="_blank">Voir</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            Aucun article pour le moment
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 p-4">
                    <h5 class="mb-0">Actions rapides</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="{{ route('admin.posts.create') }}" class="btn card-header bg-primary text-white p-3 d-flex align-items-center">
                            <i class="fas fa-plus me-2"></i>
                            <div class="text-start">
                                <div class="fw-semibold">Nouvel article</div>
                                <small class="opacity-75">Creer un article</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.categories.create') }}" class="btn card-header bg-primary text-white p-3 d-flex align-items-center">
                            <i class="fas fa-folder-plus me-2"></i>
                            <div class="text-start">
                                <div class="fw-semibold">Nouvelle categorie</div>
                                <small class="opacity-75">Organiser le contenu</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.users.create') }}" class="btn card-header bg-primary text-white p-3 d-flex align-items-center">
                            <i class="fas fa-user-plus me-2"></i>
                            <div class="text-start">
                                <div class="fw-semibold">Nouvel utilisateur</div>
                                <small class="opacity-75">Ajouter un membre</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Activite recente -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom-0 p-4">
                    <h5 class="mb-0">Activite recente</h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        @php
                            $recentUsers = App\Models\User::orderBy('created_at', 'desc')->limit(3)->get();
                        @endphp
                        
                        @foreach($recentUsers as $user)
                            <div class="d-flex align-items-start {{ !$loop->last ? 'mb-3' : '' }}">
                                <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 30px; height: 30px;">
                                    <i class="fas fa-user text-info" style="font-size: 12px;"></i>
                                </div>
                                <div class="flex-fill">
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <small class="text-muted">Inscrit {{ $user->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection