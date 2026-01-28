<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Accueil') - {{ config('app.name') }}</title>
    
    <meta name="description" content="@yield('meta_description', 'Plateforme complète d\'entraînement sportif avec programmes, exercices, outils de calcul et ressources pour tous niveaux')">
    <meta name="keywords" content="@yield('meta_keywords', 'entraînement sportif, natation, musculation, plans d\'entraînement, exercices, outils calcul, performance sportive')">
    
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="@yield('og_title', 'Sport Net Systèmes - Plateforme d\'entraînement sportif')">
    <meta property="og:description" content="@yield('og_description', 'Plateforme complète d\'entraînement sportif avec programmes, exercices, outils de calcul et ressources pour tous niveaux')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/team/nataswim-application-banner-4.jpg'))">
    <meta property="og:image:secure_url" content="@yield('og_image', asset('assets/images/team/nataswim-application-banner-4.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="@yield('og_image_alt', 'Sport Net Systèmes - Plateforme d\'entraînement sportif')">
    <meta property="og:locale" content="fr_FR">
    <meta property="fb:pages" content="Sports.Ressources">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Sport Net Systèmes - Plateforme d\'entraînement sportif')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Plateforme complète d\'entraînement sportif avec programmes et outils')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('assets/images/team/nataswim-application-banner-4.jpg'))">
    <meta name="twitter:image:alt" content="@yield('twitter_image_alt', 'Sport Net Systèmes')">
    
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/social-share.css') }}">

    <meta name="msvalidate.01" content="8D79868FFCAC25E19818E1971977FC3F" />

    @stack('styles')
</head>
<body class="bg-light">
    @include('layouts.partials.public-header')
    
    @if(session('success') || session('error') || session('warning'))
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif
    
    <main>
        @yield('content')
    </main>
    
    @include('layouts.partials.public-footer')
    
    <script src="{{ mix('js/app.js') }}"></script>
    
    @include('layouts.partials.social-share')
    <script src="{{ asset('js/social-share.js') }}"></script>
    
    @stack('scripts')

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RDDF8VW92G"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-RDDF8VW92G');
    </script>
</body>
</html>