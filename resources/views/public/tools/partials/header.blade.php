{{--
    En-tête compact des outils (sans vidéo) : fil d'Ariane, titre, une ligne d'introduction.
    Paramètres : title, lead (facultatif), icon (Font Awesome), home (true sur la page d'accueil des outils)
--}}
@php($home = $home ?? false)
<section class="tools-header py-3 border-bottom">
    <div class="container">
        <nav aria-label="Fil d'Ariane" class="small mb-1">
            <a href="{{ route('home') }}">Accueil</a>
            <span class="mx-1" aria-hidden="true">›</span>
            @if ($home)
                <span aria-current="page">Outils &amp; calculateurs</span>
            @else
                <a href="{{ route('tools.index') }}">Outils &amp; calculateurs</a>
            @endif
        </nav>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <h1 class="h3 fw-bold mb-0">
                <i class="fas {{ $icon ?? 'fa-calculator' }} me-2" aria-hidden="true"></i>{{ $title }}
            </h1>
            @unless ($home)
                <a href="{{ route('tools.index') }}" class="btn btn-sm btn-outline-primary ms-auto">
                    <i class="fas fa-th me-1" aria-hidden="true"></i>Tous les outils
                </a>
            @endunless
        </div>
        @if (! empty($lead))
            <p class="text-muted mb-0 mt-1 small">{{ $lead }}</p>
        @endif
    </div>
</section>

@once
    @push('styles')
        <style>
            .tools-header { background: #f4f9fb; }
            .tools-header nav a { text-decoration: none; }
        </style>
    @endpush
@endonce
