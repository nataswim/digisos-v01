{{--
    ╔══════════════════════════════════════════════════════════════╗
    ║  Composant bannière / carrousel Bootstrap 5                  ║
    ║  Usage : @include('components.banner', ['slug' => 'mon-slug'])║
    ╚══════════════════════════════════════════════════════════════╝
--}}

@php
    $bannerData = \App\Models\Banner::findBySlug($slug ?? '');
@endphp

@if($bannerData && $bannerData->activeSlides->isNotEmpty())

@php
    $bannerId   = 'banner-' . $bannerData->id;
    $slides     = $bannerData->activeSlides;
    $transition = $bannerData->transition === 'fade' ? 'carousel-fade' : '';
@endphp

<div id="{{ $bannerId }}"
     class="carousel {{ $transition }} slide banner-carousel"
     style="height: {{ $bannerData->height }}px;"
     data-bs-ride="{{ $bannerData->autoplay ? 'carousel' : 'false' }}"
     @if($bannerData->autoplay) data-bs-interval="{{ $bannerData->autoplay_delay }}" @endif
     @if($bannerData->pause_on_hover) data-bs-pause="hover" @else data-bs-pause="false" @endif>

    {{-- Indicateurs --}}
    @if($bannerData->show_indicators && $slides->count() > 1)
    <div class="carousel-indicators">
        @foreach($slides as $i => $slide)
        <button type="button"
                data-bs-target="#{{ $bannerId }}"
                data-bs-slide-to="{{ $i }}"
                class="{{ $i === 0 ? 'active' : '' }}"
                aria-label="Slide {{ $i + 1 }}">
        </button>
        @endforeach
    </div>
    @endif

    {{-- Slides --}}
    <div class="carousel-inner h-100">
        @foreach($slides as $i => $slide)
        <div class="carousel-item h-100 {{ $i === 0 ? 'active' : '' }}">

            {{-- Image de fond --}}
            <div class="banner-slide-bg h-100"
                 style="background-image: url('{{ $slide->image_url }}');
                        background-size: cover;
                        background-position: center;
                        background-repeat: no-repeat;">

                {{-- Overlay --}}
                @if($slide->overlay_opacity > 0)
                <div class="banner-overlay"
                     style="position:absolute;inset:0;
                            background:rgba(0,0,0,{{ $slide->overlay_css_opacity }});
                            pointer-events:none;">
                </div>
                @endif

                {{-- Contenu texte --}}
                @if($slide->title || $slide->subtitle || $slide->body || $slide->has_cta)
                <div class="carousel-caption h-100 d-flex flex-column justify-content-center {{ $slide->text_align_class }}"
                     style="color: {{ $slide->text_color }}; position:relative; z-index:1;">

                    @if($slide->title)
                    <h2 class="display-5 fw-bold mb-2 text-shadow">
                        {{ $slide->title }}
                    </h2>
                    @endif

                    @if($slide->subtitle)
                    <p class="fs-5 mb-2 text-shadow">
                        {{ $slide->subtitle }}
                    </p>
                    @endif

                    @if($slide->body)
                    <p class="mb-3 text-shadow">
                        {{ $slide->body }}
                    </p>
                    @endif

                    @if($slide->has_cta)
                    <div>
                        <a href="{{ $slide->cta_url }}"
                           class="btn {{ $slide->cta_style }} btn-lg"
                           target="{{ $slide->cta_target }}"
                           @if($slide->cta_target === '_blank') rel="noopener noreferrer" @endif>
                            {{ $slide->cta_label }}
                        </a>
                    </div>
                    @endif

                </div>
                @endif

            </div>
        </div>
        @endforeach
    </div>

    {{-- Contrôles navigation --}}
    @if($bannerData->show_controls && $slides->count() > 1)
    <button class="carousel-control-prev" type="button"
            data-bs-target="#{{ $bannerId }}" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button"
            data-bs-target="#{{ $bannerId }}" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Suivant</span>
    </button>
    @endif

</div>

<style>
.banner-carousel {
    position: relative;
    overflow: hidden;
}
.banner-carousel .carousel-inner,
.banner-carousel .carousel-item {
    height: 100%;
}
.banner-slide-bg {
    position: relative;
}
.text-shadow {
    text-shadow: 0 2px 6px rgba(0,0,0,0.5);
}
</style>

@endif
