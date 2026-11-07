{{--
    Style commun des espaces connectés : administration, éditeur, coachs, adhérents et visiteurs.
    Police : Manrope. Feuilles : public/css/espaces.css (composants) puis public/css/espace-shell.css
    (cadre : menu de gauche, barre du haut, cartes). Fichiers CSS simples, sans compilation.
--}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/espaces.css') . '?v=' . filemtime(public_path('css/espaces.css')) }}" rel="stylesheet">
<link href="{{ asset('css/espace-shell.css') . '?v=' . filemtime(public_path('css/espace-shell.css')) }}" rel="stylesheet">
