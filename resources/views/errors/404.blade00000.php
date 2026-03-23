@extends('layouts.public')

@section('title', 'Page non trouvee')

@section('content')
<div class="min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="mb-5">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                         style="width: 150px; height: 150px;">
                        <span class="display-1 fw-bold text-primary">404</span>
                    </div>
                    <h1 class="display-4 fw-bold mb-3">Page non trouvee</h1>
                    <p class="lead text-muted mb-4">
                        Desole, la page que vous recherchez n'existe pas ou a ete deplacee.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Retour A l'accueil
                        </a>
                        <a href="{{ route('posts.public.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-water me-2"></i>Voir les articles
                        </a>
                    </div>
                </div>
                
                <!-- Articles populaires -->
                <div class="text-start">
                    <h5 class="fw-semibold mb-3">Articles populaires</h5>
                    <div class="list-group list-group-flush">
                        @php
                            $popularPosts = App\Models\Post::where('status', 'published')
                                ->orderBy('hits', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp
                        @foreach($popularPosts as $post)
                            <a href="{{ route('posts.public.show', $post) }}" class="list-group-item list-group-item-action border-0 px-0">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded me-3" style="width: 40px; height: 40px;">
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{!! Str::limit($post->name, 40) !!}</h6>
                                        <small class="text-muted">{{ $post->hits }} vues</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection