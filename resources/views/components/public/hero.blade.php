{{--
    En-tête vidéo commun aux pages publiques du CNBB.

    Exemple :
        <x-public.hero title="Titre de la page" eyebrow="Le club" lead="Texte d'introduction.">
            <a href="#" class="btn btn-primary btn-lg">Bouton</a>
        </x-public.hero>

    Paramètres :
        title       Titre de la page (obligatoire)
        eyebrow     Petit sur-titre affiché au-dessus du titre
        lead        Texte d'introduction
        icon        Classe Font Awesome affichée devant le titre (ex. "fa-swimmer")
        video       Chemin de la vidéo dans /public
        titleClass  Taille du titre (display-3 par défaut)

    Zones :
        contenu par défaut   Boutons affichés sous l'introduction
        <x-slot:aside>       Colonne de droite (logo, encadré…)

    Styles : resources/scss/layout/_public.scss
--}}
@props([
    'title',
    'eyebrow'    => null,
    'lead'       => null,
    'icon'       => null,
    'video'      => 'assets/images/team/CNBB-natation-2.mp4',
    'titleClass' => 'display-3',
])

<section {{ $attributes->class(['hero-video-section position-relative text-white overflow-hidden']) }}>
    {{-- Vidéo décorative : muette, masquée aux lecteurs d'écran --}}
    <video class="hero-video" autoplay muted loop playsinline preload="metadata" aria-hidden="true" tabindex="-1">
        <source src="{{ asset($video) }}" type="video/mp4">
    </video>

    <div class="container-lg py-5 position-relative hero-content">
        <div class="row align-items-center min-vh-50">
            <div class="{{ isset($aside) ? 'col-lg-8' : 'col-12' }}">
                @if ($eyebrow)
                    <p class="hero-eyebrow text-uppercase fw-semibold mb-2 animate-slide-up">{{ $eyebrow }}</p>
                @endif

                <h1 class="text-white {{ $titleClass }} fw-bold mb-4 animate-slide-up">
                    @if ($icon)
                        <i class="fas {{ $icon }} hero-icon me-2" aria-hidden="true"></i>
                    @endif
                    {{ $title }}
                </h1>

                @if ($lead)
                    <p class="lead mb-4 animate-slide-up animation-delay-1">{{ $lead }}</p>
                @endif

                @if ($slot->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2 animate-slide-up animation-delay-2">
                        {{ $slot }}
                    </div>
                @endif
            </div>

            @isset($aside)
                <div class="col-lg-4 text-center mt-4 mt-lg-0 animate-fade-in">
                    {{ $aside }}
                </div>
            @endisset
        </div>
    </div>
</section>
