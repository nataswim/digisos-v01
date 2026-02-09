@extends('layouts.public')

@section('title', $post->meta_title ?: $post->name)
@section('meta_description', $post->meta_description ?: Str::limit(strip_tags($post->intro ?? $post->content), 160))
@section('meta_keywords', $post->meta_keywords ?: '')

@section('og_type', 'article')
@section('og_title', $post->name)
@section('og_description', Str::limit(strip_tags($post->intro ?? $post->content), 200))
@section('og_url', route('posts.public.show', $post))
@if($post->image)
@section('og_image', $post->image)
@section('og_image_alt', $post->name)
@endif

@section('twitter_title', $post->name)
@section('twitter_description', Str::limit(strip_tags($post->intro ?? $post->content), 200))
@if($post->image)
@section('twitter_image', $post->image)
@section('twitter_image_alt', $post->name)
@endif

@section('content')

<section class="nataswim-titre1 position-relative  text-white">
    <div class="position-relative ">
        <div class="row align-items-center" style="min-height: 250px;">
            <div class="col-lg-10 mx-auto">
                <div class="mb-4 animate-slide-up">
                    <h1 class="display-3 fw-bold mb-4 text-white">{{ $post->name }}</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<article class="py-5 bg-aqua-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg col-xl">

                @if($post->intro)
                <div class="card-aqua mb-4 animate-fade-in">
                    <div class="lead article-intro">{!! $post->intro !!}</div>
                </div>
                @endif

                @if($post->image)
                <div class="mb-4 animate-fade-in animation-delay-1">
                    <div class="article-image-wrapper">
                        <img src="{{ $post->image }}" alt="{{ $post->name }}" class="article-image">
                    </div>
                </div>
                @endif

                <div class="card-aqua mb-4 animate-fade-in animation-delay-2">
                    <div class="article-content">
                        @if($contentVisible)
                            {!! $post->content !!}
                        @else
                            <div class="alert alert-info border-0 mb-4">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-lock fs-2 text-primary"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2">Contenu exclusif membre</h5>
                                        <p class="mb-3">Creez votre compte pour continuer la lecture.</p>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm text-white">
                                                <i class="fas fa-user-plus me-1"></i>Inscription
                                            </a>
                                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-sign-in-alt me-1"></i>Connexion
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="content-preview position-relative">
                                <div class="text-muted p-3 border rounded" style="max-height: 150px; overflow: hidden;">
                                    {!! Str::limit(strip_tags($post->content), 500) !!}
                                </div>
                                <div class="position-absolute bottom-0 start-0 w-100 text-center py-2"
                                     style="background: linear-gradient(transparent, #f9f5f4, #f9f5f4);">
                                    <small class="text-muted">Contenu complet disponible pour les membres</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($post->tags->isNotEmpty())
                <div class="card-aqua mb-4">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        
                        @foreach($post->tags as $tag)
                        <a href="{{ route('posts.public.tag', $tag) }}" class="badge badge-secondary text-decoration-none">
                            {{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($recentPosts) && $recentPosts->count() > 0)
                <div class="card-aqua">
                    <h5 class="mb-4">
                        <i class="fas fa-newspaper me-2 text-primary"></i>Articles recents
                    </h5>

                    <div class="row g-3">
                        @foreach($recentPosts->take(4) as $recentPost)
                        <div class="col-md-6">
                            <div class="recent-post-item">
                                <div class="row g-3 align-items-center">
                                    @if($recentPost->image)
                                    <div class="col-auto">
                                        <div class="recent-post-image-wrapper">
                                            <img src="{{ $recentPost->image }}" 
                                                 class="recent-post-image"
                                                 alt="{{ $recentPost->name }}">
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col">
                                        <a href="{{ route('posts.public.show', $recentPost) }}" 
                                           class="text-decoration-none text-dark hover-primary">
                                            <h6 class="mb-1">{{ Str::limit($recentPost->name, 50) }}</h6>
                                        </a>
                                        <div class="d-flex flex-wrap gap-2 small text-muted">
                                            <span>
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $recentPost->published_at?->format('d/m/Y') ?? $recentPost->created_at->format('d/m/Y') }}
                                            </span>
                                            <span>
                                                <i class="fas fa-eye me-1"></i>{{ number_format($recentPost->hits) }}
                                            </span>
                                            @if($recentPost->visibility === 'authenticated')
                                            <span class="badge badge-info badge-sm">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($recentPosts->count() > 4)
                    <div class="text-center mt-4 pt-4 border-top">
                        <a href="{{ route('posts.public.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-arrow-right me-2"></i>Voir tous les articles
                        </a>
                    </div>
                    @endif
                </div>
                @endif

            </div>
        </div>
    </div>
</article>

<section class="py-5 bg-secondary">
    <div class="container-lg">


        @if(isset($categories) && $categories->count() > 0)
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card-aqua h-100">
                    <div class="card-image-wrapper mb-3 position-relative">
                        @if($category->image)
                        <img src="{{ $category->image }}"
                            alt="{{ $category->name }}"
                            class="card-image">
                        @else
                        <div class="card-image-placeholder">
                            <i class="fas fa-folder fa-3x text-secondary opacity-25"></i>
                        </div>
                        @endif

                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge badge-primary">
                                <i class="fas fa-file-alt me-1"></i>
                                {{ $category->posts_count }}
                            </span>
                        </div>
                    </div>

                    <h6 class="card-title mb-2">
                        <a href="{{ route('posts.public.category', $category) }}"
                            class="text-decoration-none text-dark hover-primary">
                            {{ $category->name }}
                        </a>
                    </h6>

                    @if($category->description)
                    <p class="card-text text-muted small mb-3">
                        {{ Str::limit($category->description, 120) }}
                    </p>
                    @endif

                    @if($category->group_name)
                    <div class="card-meta">
                        <span class="badge badge-secondary">
                            <i class="fas fa-layer-group me-1"></i>{{ $category->group_name }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
            <h3 class="text-muted">Aucune categorie disponible</h3>
            <p class="text-muted">Les categories seront bientot disponibles.</p>
        </div>
        @endif
    </div>
</section>

@endsection

@push('styles')
<style>
.hero-video-section {
    min-height: 300px;
    position: relative;
}

.hero-video {
position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
    border-top: 20px solid #4097b5;
    border-bottom: 20px solid #4097b5;
    border-left: 20px solid #f9f5f4;
    border-right: 20px solid #f9f5f4;
}

.hero-image-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('{{ $post->image }}');
    background-size: cover;
    background-position: center;
    z-index: 1;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(56, 133, 155, 0.85) 0%, rgba(73, 170, 202, 0.75) 100%);
    z-index: 2;
}

.hero-content {
    z-index: 3;
}

.min-vh-50 {
    min-height: 50vh;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out;
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animation-delay-1 {
    animation-delay: 0.2s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.animation-delay-2 {
    animation-delay: 0.4s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.animation-delay-3 {
    animation-delay: 0.6s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.article-intro {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #4a5568;
}

.article-image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 0.75rem;
    box-shadow: 0 4px 12px rgba(56, 133, 155, 0.15);
}

.article-image {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 0.75rem;
}

.article-content {
    font-size: 1rem;
    line-height: 1.8;
    color: #4a5568;
}

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
    color: #1db8c5;
}

.article-content h2 {
    font-size: 1.5rem;
    color: #49aaca;
}

.article-content h3 {
    font-size: 1.3rem;
    color: #4fa79c;
}

.article-content p {
    margin-bottom: 1.5rem;
}

.article-content .ql-video {
    width: 100%;
    display: block;
    margin: 1.5rem auto;
    height: 480px;
    max-width: 100%;
    border-radius: 0.75rem;
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
    border-left: 4px solid #1db8c5;
    padding: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    background: rgba(56, 133, 155, 0.05);
    border-radius: 0.375rem;
    color: #2d3748;
}

.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.75rem;
    margin: 2rem auto;
    box-shadow: 0 4px 12px rgba(56, 133, 155, 0.15);
    display: block;
}

.article-content pre {
    background: #303030;
    color: #e2e8f0;
    padding: 1.5rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 2rem 0;
    font-size: 0.875rem;
    line-height: 1.6;
}

.article-content code {
    background-color: rgba(56, 133, 155, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    color: #1db8c5;
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
    background-color: rgba(56, 133, 155, 0.05);
    font-weight: 600;
}

.content-preview {
    opacity: 0.7;
}

.recent-post-item {
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid rgba(56, 133, 155, 0.1);
}

.recent-post-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.recent-post-image-wrapper {
    width: 80px;
    height: 60px;
    overflow: hidden;
    border-radius: 0.5rem;
}

.recent-post-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.recent-post-item:hover .recent-post-image {
    transform: scale(1.1);
}

.card-image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 0.75rem;
    height: 180px;
    background: linear-gradient(135deg, rgba(56, 133, 155, 0.05) 0%, rgba(73, 170, 202, 0.05) 100%);
}

.card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.card-aqua:hover .card-image {
    transform: scale(1.05);
}

.card-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(56, 133, 155, 0.05) 0%, rgba(73, 170, 202, 0.05) 100%);
}

.card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.hover-primary {
    transition: color 0.2s ease;
}

.hover-primary:hover {
    color: #1db8c5 !important;
}

@media (max-width: 768px) {
    .hero-video-section {
        min-height: 500px;
    }

    .display-3 {
        font-size: 2rem !important;
    }

    .article-intro {
        font-size: 1rem;
    }

    .article-content {
        font-size: 0.95rem;
    }

    .article-content .ql-video {
        height: 250px;
    }
}

html {
    scroll-behavior: smooth;
}
</style>
@endpush