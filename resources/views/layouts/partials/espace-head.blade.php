{{--
    Style commun des espaces connectés : administration, éditeur, adhérents et visiteurs.
    Polices : Prompt (titres) et Plus Jakarta Sans (texte). Feuille : public/css/espaces.css
    (fichier CSS simple, sans compilation : il suffit de le modifier puis de recharger la page).
--}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Prompt:wght@500;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('css/espaces.css') . '?v=' . filemtime(public_path('css/espaces.css')) }}" rel="stylesheet">
