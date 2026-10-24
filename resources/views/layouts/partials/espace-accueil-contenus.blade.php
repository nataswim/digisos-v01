{{--
    4 derniers articles, fiches et pages pour l'accueil d'un espace (visiteur, adhérent).
    Variables : $posts, $fiches, $pages (fournies par le trait LatestContent).
    Une rubrique sans contenu n'est pas affichée.
--}}
@php
    $rubriques = [
        ['id' => 'articles', 'titre' => 'Derniers articles', 'icon' => 'fa-newspaper', 'items' => $posts, 'tout' => 'posts.public.index', 'libelle' => 'Toutes les actualités'],
        ['id' => 'fiches', 'titre' => 'Dernières fiches', 'icon' => 'fa-file-alt', 'items' => $fiches, 'tout' => 'public.fiches.index', 'libelle' => 'Toutes les fiches'],
        ['id' => 'pages', 'titre' => 'Dernières pages', 'icon' => 'fa-book-open', 'items' => $pages, 'tout' => 'public.pages.index', 'libelle' => 'Toutes les pages'],
    ];
@endphp

@foreach ($rubriques as $r)
    @continue($r['items']->isEmpty())
    <section class="mb-5" aria-labelledby="titre-{{ $r['id'] }}">
        <div class="espace-section-head">
            <h2 id="titre-{{ $r['id'] }}" class="h4 fw-bold mb-0">
                <i class="fas {{ $r['icon'] }} me-2 text-primary" aria-hidden="true"></i>{{ $r['titre'] }}
            </h2>
            @if (Route::has($r['tout']))
                <a href="{{ route($r['tout']) }}" class="btn btn-sm btn-outline-primary">{{ $r['libelle'] }}</a>
            @endif
        </div>
        <div class="row g-4">
            @foreach ($r['items'] as $item)
                <div class="col-sm-6 col-lg-3">
                    @if ($r['id'] === 'articles')
                        <x-public.post-card :post="$item" :intro-limit="80" />
                    @elseif ($r['id'] === 'fiches')
                        <x-public.content-card :item="$item" :url="$item->url !== '#' ? $item->url : null" :excerpt-limit="80" />
                    @else
                        <x-public.content-card :item="$item" :url="$item->category ? $item->url : null" :excerpt-limit="80" />
                    @endif
                </div>
            @endforeach
        </div>
    </section>
@endforeach

@once
@push('styles')
<style>
    .espace-section-head {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
        gap: .75rem; margin-bottom: 1.25rem; padding-bottom: .75rem; border-bottom: 2px solid #E0F2FE;
    }
</style>
@endpush
@endonce
