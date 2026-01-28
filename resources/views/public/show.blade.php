@extends('layouts.public')

{{-- SEO Meta --}}
@section('title', $post->meta_title ?: $post->name)
@section('meta_description', $post->meta_description ?: Str::limit(strip_tags($post->intro ?? $post->content), 160))
@section('meta_keywords', $post->meta_keywords ?: '')

{{-- Open Graph --}}
@section('og_type', 'article')
@section('og_title', $post->name)
@section('og_description', Str::limit(strip_tags($post->intro ?? $post->content), 200))
@section('og_url', route('posts.public.show', $post))
@if($post->image)
@section('og_image', $post->image)
@section('og_image_alt', $post->name)
@endif

{{-- Twitter Card --}}
@section('twitter_title', $post->name)
@section('twitter_description', Str::limit(strip_tags($post->intro ?? $post->content), 200))
@if($post->image)
@section('twitter_image', $post->image)
@section('twitter_image_alt', $post->name)
@endif

@section('content')

<!-- En-tête Article -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container-lg">
        <h1 class="fw-bold mb-4">{{ $post->name }}</h1>
        
        @if($post->image)
        <img src="{{ $post->image }}" 
             alt="{{ $post->name }}" 
             class="img-fluid rounded shadow" 
             style="max-width: 800px; width: 100%;">
        @endif
    </div>
</section>

<!-- Contenu Article -->
<article class="py-4">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                <!-- Introduction -->
                @if($post->intro)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="lead">{!! $post->intro !!}</div>
                    </div>
                </div>
                @endif

                <!-- Contenu Principal -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="article-content">
                            @if($contentVisible)
                                {!! $post->content !!}
                            @else
                                <!-- Accès Restreint -->
                                <div class="alert alert-info border-0 mb-4">
                                    <div class="d-flex align-items-start gap-3">
                                        <i class="fas fa-lock fs-2 text-primary"></i>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-2">Contenu exclusif membre</h5>
                                            <p class="mb-3">Créez votre compte pour continuer la lecture.</p>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-user-plus me-1"></i>Inscription
                                                </a>
                                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-sign-in-alt me-1"></i>Connexion
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Aperçu -->
                                <div class="content-preview position-relative">
                                    <div class="text-muted p-3 border rounded" style="max-height: 150px; overflow: hidden;">
                                        {!! Str::limit(strip_tags($post->content), 500) !!}
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 w-100 text-center py-2"
                                         style="background: linear-gradient(transparent, white, white);">
                                        <small class="text-muted">Contenu complet disponible pour les membres</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Métadonnées Article -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-3 small">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="fas fa-folder me-1"></i>Catégorie
                                    </span>
                                    <strong>{{ $post->category->name ?? 'Non catégorisé' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>Publié le
                                    </span>
                                    <strong>{{ $post->published_at?->format('d/m/Y') ?? $post->created_at->format('d/m/Y') }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="fas fa-eye me-1"></i>Vues
                                    </span>
                                    <strong>{{ $post->hits }}</strong>
                                </div>
                            </div>
                            @if($post->tags->isNotEmpty())
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="text-muted">
                                        <i class="fas fa-tags me-1"></i>Tags:
                                    </span>
                                    @foreach($post->tags as $tag)
                                    <a href="{{ route('posts.public.tag', $tag) }}" 
                                       class="badge bg-secondary text-decoration-none">
                                        {{ $tag->name }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Articles Récents -->
                @if(isset($recentPosts) && $recentPosts->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Articles récents</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($recentPosts->take(4) as $recentPost)
                        <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="row align-items-center g-3">
                                @if($recentPost->image)
                                <div class="col-auto">
                                    <img src="{{ $recentPost->image }}" 
                                         class="rounded" 
                                         style="width: 80px; height: 60px; object-fit: cover;"
                                         alt="{{ $recentPost->name }}">
                                </div>
                                @endif
                                <div class="col">
                                    <a href="{{ route('posts.public.show', $recentPost) }}" 
                                       class="text-decoration-none">
                                        <h6 class="mb-1">{{ Str::limit($recentPost->name, 60) }}</h6>
                                    </a>
                                    <div class="small text-muted d-flex gap-3">
                                        <span>
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $recentPost->published_at?->format('d/m/Y') ?? $recentPost->created_at->format('d/m/Y') }}
                                        </span>
                                        <span>
                                            <i class="fas fa-eye me-1"></i>{{ $recentPost->hits }}
                                        </span>
                                        @if($recentPost->visibility === 'authenticated')
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($recentPosts->count() > 4)
                    <div class="card-footer bg-light text-center">
                        <a href="{{ route('posts.public.index') }}" class="btn btn-sm btn-outline-primary">
                            Voir tous les articles
                        </a>
                    </div>
                    @endif
                </div>
                @endif

            </div>
        </div>
    </div>
</article>

@endsection

@push('styles')
<style>
.article-content h1,
.article-content h2,
.article-content h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
    line-height: 1.3;
}

.article-content h1 {
    font-size: 1.7rem;
    color: #0d7f8a;
}

.article-content h2 {
    font-size: 1.5rem;
    color: #0a7db1;
}

.article-content h3 {
    font-size: 1.3rem;
    color: #6a1414;
}

.article-content p {
    margin-bottom: 1.5rem;
    line-height: 1.8;
    text-align: justify;
    color: #4a5568;
}

.article-content .ql-video {
    width: 100%;
    display: block;
    margin: 15px auto;
    height: 480px;
    max-width: 100%;
}

.article-content ul,
.article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
    line-height: 1.7;
}

.article-content li {
    margin-bottom: 0.5rem;
}

.article-content blockquote {
    border-left: 4px solid #3182ce;
    padding: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    background: #f7fafc;
    border-radius: 0.375rem;
    color: #2d3748;
}

.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 2rem auto;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: block;
}

.article-content pre {
    background: #1a202c;
    color: #e2e8f0;
    padding: 1.5rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 2rem 0;
    font-size: 0.875rem;
    line-height: 1.6;
}

.article-content code {
    background-color: #edf2f7;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    color: #d63384;
    font-family: 'Courier New', monospace;
}

.article-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.article-content th,
.article-content td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.article-content th {
    background-color: #f7fafc;
    font-weight: 600;
}

.content-preview {
    opacity: 0.7;
}

@media (max-width: 768px) {
    .article-content {
        font-size: 0.95rem;
    }
    
    .article-content .ql-video {
        height: 250px;
    }
}
</style>
@endpush