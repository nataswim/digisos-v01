{{--
    Mise en page commune des espaces connectés (style « extraNat ») :
    menu vertical à gauche, barre du haut (compte), contenu sur fond gris clair.

    Utilisée par layouts.admin, layouts.editor, layouts.coach, layouts.member (adhérent / visiteur).
    Paramètre : $espaceArea = admin | editor | coach | member | visitor.
    Menus : App\Support\EspaceMenu ; styles : public/css/espaces.css puis public/css/espace-shell.css.
--}}
@php
    use App\Support\EspaceMenu;
    $espaceArea = $espaceArea ?? 'member';
    $espace = EspaceMenu::AREAS[$espaceArea];
    $menuGroups = EspaceMenu::build($espaceArea);
    $me = auth()->user();
    $roleLabel = \App\Services\MessageService::roleLabel($me) ?: $espace['label'];
    $displayName = $me?->first_name ? trim($me->first_name . ' ' . $me->last_name) : $me?->name;
    $initials = collect(preg_split('/\s+/', (string) $displayName))->filter()->take(2)->map(fn ($m) => mb_strtoupper(mb_substr($m, 0, 1)))->implode('') ?: '?';
    $unread = rescue(fn () => \App\Models\Conversation::unreadCountFor($me), 0, false);
    $messagesRoute = match ($espaceArea) { 'admin' => 'admin.messages.index', 'editor' => 'editor.messages.index', 'coach' => 'coach.messages.index', default => 'user.messages.index' };
    $profileRoute = in_array($espaceArea, ['member']) && Route::has('user.profile.edit') ? 'user.profile.edit' : 'profile.edit';

    // Autres espaces accessibles avec ce compte
    $slug = $me?->role?->slug;
    $switch = collect([
        'admin' => $slug === 'admin',
        'editor' => in_array($slug, ['admin', 'editor'], true),
        'coach' => in_array($slug, ['admin', 'coach'], true),
        'member' => $slug !== 'visitor',
    ])->filter()->keys()->reject(fn ($a) => $a === $espaceArea)
      ->filter(fn ($a) => Route::has(EspaceMenu::AREAS[$a]['home']));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title') - {{ $espace['label'] }} · CNBB</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @if (in_array($espaceArea, ['admin', 'editor', 'coach'], true))
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endif
    @include('layouts.partials.espace-head')
    @stack('styles')
</head>
<body class="espace espace--{{ $espaceArea }} espace--shell">
    <a href="#contenu" class="visually-hidden-focusable esp-skip">Aller au contenu</a>

    <div class="esp-shell">
        {{-- Menu de gauche (volet sur mobile) --}}
        <aside class="esp-sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="espSidebar" aria-labelledby="espSidebarLabel">
            <div class="esp-sidebar-head">
                <a href="{{ route($espace['home']) }}" class="esp-logo">
                    <img src="{{ asset('assets/images/logo/cnbb-logo-800.png') }}" alt="" width="44" height="44">
                    <span id="espSidebarLabel"><strong>CNBB</strong><small>{{ $espace['label'] }}</small></span>
                </a>
                <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#espSidebar" aria-label="Fermer le menu"></button>
            </div>

            <nav class="esp-nav" aria-label="Menu {{ mb_strtolower($espace['label']) }}">
                @foreach ($menuGroups as $group)
                    @if ($group['heading'])
                        <p class="esp-nav-heading">{{ $group['heading'] }}</p>
                    @endif
                    <ul class="esp-nav-list">
                        @foreach ($group['items'] as $item)
                            @if (isset($item['links']))
                                <li class="esp-nav-group {{ $item['active'] ? 'is-active' : '' }}">
                                    <button type="button" class="esp-nav-link esp-nav-toggle {{ $item['active'] ? '' : 'collapsed' }}"
                                            data-bs-toggle="collapse" data-bs-target="#{{ $item['id'] }}"
                                            aria-expanded="{{ $item['active'] ? 'true' : 'false' }}" aria-controls="{{ $item['id'] }}">
                                        <i class="fas {{ $item['icon'] }} fa-fw" aria-hidden="true"></i>
                                        <span>{{ $item['label'] }}</span>
                                        <i class="fas fa-chevron-right esp-nav-caret" aria-hidden="true"></i>
                                    </button>
                                    <ul class="esp-nav-sub collapse {{ $item['active'] ? 'show' : '' }}" id="{{ $item['id'] }}">
                                        @foreach ($item['links'] as $link)
                                            <li>
                                                <a href="{{ $link['url'] }}" class="esp-nav-sublink {{ $link['active'] ? 'active' : '' }}"
                                                   @if ($link['active']) aria-current="page" @endif
                                                   @if ($link['external']) target="_blank" rel="noopener" @endif>
                                                    {{ $link['label'] }}
                                                    @if ($link['external'])<i class="fas fa-arrow-up-right-from-square esp-ext" aria-hidden="true"></i><span class="visually-hidden"> (nouvel onglet)</span>@endif
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $item['url'] }}" class="esp-nav-link {{ $item['active'] ? 'active' : '' }}" @if ($item['active']) aria-current="page" @endif
                                       @if ($item['external']) target="_blank" rel="noopener" @endif>
                                        <i class="fas {{ $item['icon'] }} fa-fw" aria-hidden="true"></i>
                                        <span>{{ $item['label'] }}</span>
                                        @if (! empty($item['badge']))<span class="esp-badge">{{ $item['badge'] }}<span class="visually-hidden"> non lu(s)</span></span>@endif
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endforeach
            </nav>

            <div class="esp-sidebar-foot">
                <a href="{{ route('home') }}" class="esp-nav-link" target="_blank" rel="noopener">
                    <i class="fas fa-globe fa-fw" aria-hidden="true"></i><span>Voir le site</span>
                </a>
            </div>
        </aside>

        <div class="esp-main">
            {{-- Barre du haut --}}
            <header class="esp-topbar">
                <button class="esp-burger d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#espSidebar" aria-controls="espSidebar" aria-label="Ouvrir le menu">
                    <i class="fas fa-bars" aria-hidden="true"></i>
                </button>
                <span class="esp-area-chip"><i class="fas {{ $espace['icon'] }} me-2" aria-hidden="true"></i>{{ $espace['label'] }}</span>

                <div class="esp-topbar-right">
                    @if (Route::has($messagesRoute))
                        <a href="{{ route($messagesRoute) }}" class="esp-icon-btn" aria-label="Messages{{ $unread ? ' : ' . $unread . ' non lu(s)' : '' }}">
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            @if ($unread)<span class="esp-icon-badge" aria-hidden="true">{{ $unread }}</span>@endif
                        </a>
                    @endif

                    <div class="dropdown">
                        <button class="esp-account dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="esp-account-avatar" aria-hidden="true">{{ $initials }}</span>
                            <span class="esp-account-text d-none d-md-flex">
                                <strong>{{ $me?->email }}</strong>
                                <small>{{ $roleLabel }}</small>
                            </span>
                            <span class="visually-hidden d-md-none">Mon compte</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end esp-account-menu">
                            <li class="dropdown-header d-md-none"><strong>{{ $displayName }}</strong><br><small>{{ $roleLabel }}</small></li>
                            @if (Route::has($profileRoute))
                                <li><a class="dropdown-item" href="{{ route($profileRoute) }}"><i class="fas fa-user fa-fw me-2" aria-hidden="true"></i>Mon profil</a></li>
                            @endif
                            @if ($switch->isNotEmpty())
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Changer d'espace</h6></li>
                                @foreach ($switch as $autre)
                                    <li><a class="dropdown-item" href="{{ route(EspaceMenu::AREAS[$autre]['home']) }}"><i class="fas {{ EspaceMenu::AREAS[$autre]['icon'] }} fa-fw me-2" aria-hidden="true"></i>{{ EspaceMenu::AREAS[$autre]['label'] }}</a></li>
                                @endforeach
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank" rel="noopener"><i class="fas fa-globe fa-fw me-2" aria-hidden="true"></i>Voir le site</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt fa-fw me-2" aria-hidden="true"></i>Se déconnecter</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main id="contenu" class="esp-content" tabindex="-1">
                @foreach (['success' => ['success', 'fa-check-circle'], 'error' => ['danger', 'fa-exclamation-circle'], 'warning' => ['warning', 'fa-exclamation-triangle']] as $cle => [$type, $icone])
                    @if (session($cle))
                        <div class="alert alert-{{ $type }} alert-dismissible fade show mb-4" role="{{ $type === 'success' ? 'status' : 'alert' }}">
                            <i class="fas {{ $icone }} me-2" aria-hidden="true"></i>{{ session($cle) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    @endif
                @endforeach

                @yield('content')
            </main>

            <footer class="esp-footer">
                <span>© {{ date('Y') }} Cercle des Nageurs du Bocage Bressuirais</span>
                <span><a href="{{ route('home') }}" target="_blank" rel="noopener">Voir le site</a>@if (Route::has('legal')) · <a href="{{ route('legal') }}" target="_blank" rel="noopener">Mentions légales</a>@endif</span>
            </footer>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
    @if (in_array($espaceArea, ['admin', 'editor', 'coach'], true))
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
        <script src="{{ asset('js/quill-advanced.js').'?v='.filemtime(public_path('js/quill-advanced.js')) }}"></script>
        <script src="{{ asset('js/media-selector.js').'?v='.filemtime(public_path('js/media-selector.js')) }}"></script>
    @endif
    @stack('scripts')
    @if (in_array($espaceArea, ['admin', 'editor', 'coach'], true))
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endif
</body>
</html>
