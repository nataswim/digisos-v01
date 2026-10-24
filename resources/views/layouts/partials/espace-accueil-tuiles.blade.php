{{--
    Grille de 4 tuiles de liens pour l'accueil d'un espace (visiteur, adhérent).
    Paramètres : titre, masquerTitre (bool), tuiles = [[icon, titre, texte, route], …]
    Une tuile dont la route n'existe pas est ignorée.
--}}
@php
    $tuiles = array_values(array_filter($tuiles, fn ($t) => Route::has($t['route'])));
    $idTitre = 'tuiles-' . Str::slug($titre);
@endphp

@if ($tuiles)
    <section class="mb-5" aria-labelledby="{{ $idTitre }}">
        <h2 id="{{ $idTitre }}" class="{{ ($masquerTitre ?? false) ? 'visually-hidden' : 'h4 fw-bold mb-3' }}">{{ $titre }}</h2>
        <ul class="row g-3 list-unstyled mb-0">
            @foreach ($tuiles as $t)
                <li class="col-6 col-lg-3">
                    <a href="{{ route($t['route']) }}" class="espace-tuile card border-0 shadow-sm h-100 text-decoration-none">
                        <span class="card-body p-4 d-flex flex-column align-items-center text-center">
                            <span class="espace-tuile-icon mb-3" aria-hidden="true"><i class="fas {{ $t['icon'] }}"></i></span>
                            <span class="h5 fw-bold mb-2">{{ $t['titre'] }}</span>
                            <span class="small text-muted d-none d-sm-block">{{ $t['texte'] }}</span>
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
    @media (prefers-reduced-motion: reduce) {
        .espace-tuile, .espace-tuile:hover { transition: none; transform: none; }
    }
</style>
@endpush
@endonce
