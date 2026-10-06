@php
    // ------------------------------------------------------------------
    // Identité du site : valeurs par défaut des balises de partage et de référencement.
    // Chaque page peut les remplacer avec @section('title'), @section('meta_description'), etc.
    // ------------------------------------------------------------------
    $siteName        = 'CNBB — Cercle des Nageurs du Bocage Bressuirais';
    $siteDescription = 'Site officiel du Cercle des Nageurs du Bocage Bressuirais (CNBB), club de natation de Bressuire affilié à la Fédération Française de Natation : actualités, infos pratiques, inscriptions.';
    $siteKeywords    = 'CNBB, club de natation, Bressuire, Deux-Sèvres, natation course, école de natation, Cœur d\'O, FFN';
    $siteImage       = asset('assets/images/logo/Logo-CNBB-Natation-9.png');

    // Mesure d'audience : désactivée tant qu'aucun identifiant n'est configuré.
    // Pour l'activer : GOOGLE_ANALYTICS_ID dans .env + entrée « google_analytics » dans config/services.php.
    $gaId = config('services.google_analytics.id');

    // Données structurées du club (moteurs de recherche)
    $organisation = [
        '@context'  => 'https://schema.org',
        '@type'     => 'SportsOrganization',
        'name'      => 'Cercle des Nageurs du Bocage Bressuirais',
        'alternateName' => 'CNBB',
        'sport'     => 'Natation',
        'url'       => route('home'),
        'logo'      => $siteImage,
        'email'     => 'cnbb079@gmail.com',
        'telephone' => '+33602350843',
        'foundingDate' => '1954-03-22',
        'address'   => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => '40 boulevard de la République',
            'postalCode'      => '79300',
            'addressLocality' => 'Bressuire',
            'addressCountry'  => 'FR',
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2f80b8">

    <title>@yield('title', 'Club de natation à Bressuire') - {{ config('app.name') }}</title>

    <meta name="description" content="@yield('meta_description', $siteDescription)">
    <meta name="keywords" content="@yield('meta_keywords', $siteKeywords)">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="@yield('og_title', $siteName)">
    <meta property="og:description" content="@yield('og_description', $siteDescription)">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', $siteImage)">
    <meta property="og:image:alt" content="@yield('og_image_alt', 'Logo du Cercle des Nageurs du Bocage Bressuirais')">
    <meta property="og:locale" content="fr_FR">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', $siteName)">
    <meta name="twitter:description" content="@yield('twitter_description', $siteDescription)">
    <meta name="twitter:image" content="@yield('twitter_image', $siteImage)">
    <meta name="twitter:image:alt" content="@yield('twitter_image_alt', 'Logo du Cercle des Nageurs du Bocage Bressuirais')">

    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/social-share.css') }}">

    {{-- Vérification du site auprès de Bing Webmaster Tools --}}
    <meta name="msvalidate.01" content="8D79868FFCAC25E19818E1971977FC3F" />

    @stack('styles')
</head>
<body class="bg-light">
    <a href="#contenu" class="skip-link">Aller au contenu</a>

    @include('layouts.partials.public-header')

    @if (session('success') || session('error') || session('warning'))
        <div class="container-lg" aria-live="polite">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="status">
                    <i class="fas fa-check-circle me-2" aria-hidden="true"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2" aria-hidden="true"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif
        </div>
    @endif

    <main id="contenu" tabindex="-1">
        @yield('content')
    </main>

    @include('layouts.partials.public-footer', ['gaId' => $gaId])

    <script type="application/ld+json">{!! json_encode($organisation, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>

    <script src="{{ mix('js/app.js') }}"></script>

    @include('layouts.partials.social-share')
    <script src="{{ asset('js/social-share.js') }}"></script>

    @stack('scripts')

    {{-- Mesure d'audience : chargée uniquement si un identifiant est configuré ET si le visiteur a accepté --}}
    @if ($gaId)
        @include('layouts.partials.cookie-consent', ['gaId' => $gaId])
    @endif
</body>
</html>
