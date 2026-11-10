{{--
    Grille de 4 tuiles de liens pour l'accueil d'un espace (visiteur, adhérent).
    Paramètres : titre, masquerTitre (bool), intro (facultatif), variante (facultatif : « entraineur » = tuiles turquoise #00ACC0),
    tuiles = [[icon, titre, texte, route OU url (lien externe, ouvert dans un nouvel onglet), badge (facultatif)], …]
    Une tuile dont la route n'existe pas est ignorée.
--}}
@php
    $tuiles = array_values(array_filter($tuiles, fn ($t) => ! empty($t['url']) || Route::has($t['route'] ?? '')));
    $idTitre = 'tuiles-' . Str::slug($titre);
@endphp

@if ($tuiles)
    <section class="mb-5 {{ ! empty($variante) ? 'espace-tuiles--' . $variante : '' }}" aria-labelledby="{{ $idTitre }}">
        <h2 id="{{ $idTitre }}" class="{{ ($masquerTitre ?? false) ? 'visually-hidden' : 'h4 fw-bold ' . (empty($intro) ? 'mb-3' : 'mb-1') }}">{{ $titre }}</h2>
        @if (! empty($intro))<p class="text-muted mb-3">{{ $intro }}</p>@endif
        <ul class="row g-3 list-unstyled mb-0">
            @foreach ($tuiles as $t)
                <li class="col-6 col-lg-3">
                    <a href="{{ ! empty($t['url']) ? $t['url'] : route($t['route']) }}" class="espace-tuile card border-0 shadow-sm h-100 text-decoration-none"
                       @if (! empty($t['url'])) target="_blank" rel="noopener" @endif>
                        <span class="card-body p-4 d-flex flex-column align-items-center text-center">
                            <span class="espace-tuile-icon mb-3" aria-hidden="true"><i class="fas {{ $t['icon'] }}"></i></span>
                            <span class="h5 fw-bold mb-2">{{ $t['titre'] }}@if (! empty($t['url']))<i class="fas fa-arrow-up-right-from-square espace-tuile-ext ms-2" aria-hidden="true"></i><span class="visually-hidden"> (site externe, nouvel onglet)</span>@endif</span>
                            <span class="small text-muted d-none d-sm-block">{{ $t['texte'] }}</span>
                            @if (! empty($t['badge']))
                                <span class="badge rounded-pill espace-tuile-badge mt-2">{{ $t['badge'] }}</span>
                            @endif
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
@endif

@once
@push('styles')
<style>
    .espace-tuile { border-radius: 14px; color: #1d293d; transition: transform .2s ease, box-shadow .2s ease; }
    .espace-tuile:hover, .espace-tuile:focus { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 144, 212, .18) !important; color: #1d293d; }
    .espace-tuile:focus-visible { outline: 3px solid #0090D4; outline-offset: 2px; }
    .espace-tuile-icon {
        display: inline-flex; width: 60px; height: 60px; border-radius: 50%;
        align-items: center; justify-content: center; background: #E0F2FE; color: #0090D4; font-size: 1.5rem;
    }
    .espace-tuile-badge { background: #FBF6EC; color: #7a5518; font-weight: 600; }
    .espace-tuile-ext { font-size: .7em; opacity: .7; vertical-align: .15em; }
    /* Variante « entraineur » : tuiles turquoise #00ACC0 (pages de l'entraîneur, sites externes).
       Texte bleu nuit : le blanc serait trop peu lisible sur ce turquoise clair. */
    .espace-tuiles--entraineur .espace-tuile { background: #00acc0 !important; color: #062a33; }
    .espace-tuiles--entraineur .espace-tuile .h5 { color: #062a33; }
    .espace-tuiles--entraineur .espace-tuile .text-muted { color: #0b3640 !important; }
    .espace-tuiles--entraineur .espace-tuile-icon { background: #fff; color: #00838f; }
    .espace-tuiles--entraineur .espace-tuile:hover, .espace-tuiles--entraineur .espace-tuile:focus { color: #062a33; background: #00b8cd !important; box-shadow: 0 10px 22px rgba(0, 120, 135, .35) !important; }
    .espace-tuiles--entraineur .espace-tuile:focus-visible { outline-color: #062a33; }
    @media (prefers-reduced-motion: reduce) {
        .espace-tuile, .espace-tuile:hover { transition: none; transform: none; }
    }
</style>
@endpush
@endonce
