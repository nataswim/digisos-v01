@extends('layouts.user')

@section('title', 'Mon tableau de bord')

@section('content')
<div class="container-lg py-5">
    <!-- En-tête utilisateur -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="bg-success text-white p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h4 class="mb-2">
                                            Bonjour, {{ auth()->user()->first_name ?: auth()->user()->name }} !
                                        </h4>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <div class="text-white opacity-90">
                                    <div class="mb-1">Membre depuis</div>
                                    <div class="fw-semibold">{{ auth()->user()->created_at->format('F Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification pour les visiteurs -->
    @if(auth()->user()->hasRole('visitor'))
    <div class="row mb-4">
        <div class="col-12">
            @php
            $completedPayment = auth()->user()->payments()->where('status', 'completed')->where('admin_status', 'pending')->first();
            @endphp

            @if($completedPayment)
            <div class="alert alert-warning border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-clock text-warning fa-lg"></i>
                    </div>
                    <div class="flex-fill">
                        <h5 class="alert-heading mb-2">
                            <i class="fas fa-hourglass-half"></i> Paiement en attente de validation
                        </h5>
                        <p class="mb-2">
                            Votre paiement de <strong>{{ $completedPayment->formatted_price }}</strong> pour
                            <strong>{{ $completedPayment->plan_name }}</strong> a été reçu avec succès.
                        </p>
                        <p class="mb-0 small text-muted">
                            Un administrateur validera votre accès premium prochainement.
                            Vous recevrez une notification dès l'activation.
                        </p>
                    </div>
                </div>
            </div>
            @else
            <div class="py-5 bg-primary text-white shadow-sm p-4">
                <div class="d-flex align-items-center">

                    <div class="flex-fill">
                        <h5 class="alert-heading mb-2">
                            <i class="fas fa-lock"></i> Devenir Membre
                        </h5>
                        <p class="mb-3">
                            Votre compte actuel vous offre un aperçu limté. Pour débloquer l'accès à tous les contenus et les fonctionnalités passez à la formule MEMBRE.
                        </p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('payments.index') }}" class="btn btn-dark text-white">
                                <i class="fas fa-arrow-up me-2"></i>Choisir Un Accès illimité
                            </a>
                            <a href="https://buy.stripe.com/6oUeVd9R478716NgIvgnK00"
                                class="btn bg-warning text-white p-2"
                                target="_blank">
                                <i class="fas fa-lock-open me-2"></i>3 mois (45€)
                            </a>
                            <a href="https://buy.stripe.com/dRm28r5AOfEDaHn0JxgnK02"
                            class="btn bg-warning text-white p-2"
                            target="_blank">
                            <i class="fas fa-credit-card me-2"></i>12 mois (96€)
                        </a>
                        <a href="https://buy.stripe.com/6oU9AT7IW8cbeXD1NBgnK01"
                            class="btn bg-warning text-white p-2"
                            target="_blank">
                            <i class="fas fa-credit-card me-2"></i>6 mois (66€)
                        </a> *Paiement unique
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Notification pour les utilisateurs premium -->
    @if(auth()->user()->hasRole('user'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px;">
                        <i class="fas fa-check-circle text-success fa-lg"></i>
                    </div>
                    <div class="flex-fill">
                        <h5 class="alert-heading mb-2">
                            <i class="fas fa-crown"></i> Compte Premium
                        </h5>
                        <p class="mb-0">
                            Vous avez accès à tous les contenus de la plateforme. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques utilisateur -->
    <div class="row g-4 mb-5">



        @php
        $userStats = [
        [
        'title' => 'Mon profil',
        'icon' => 'fas fa-user-circle',
        'color' => 'primary',
        'description' => 'Je met a jour mes informations',
        'route' => route('user.profile.edit')
        ],
        [
        'title' => 'Visiteur/Premium : différences ?',
        'icon' => 'fas fa-credit-card',
        'color' => 'info',
        'description' => 'Choisissez la durée qui vous convient : 12 / 6 / 3 mois.',
        'route' => route('payments.index')
        ],
        [
        'title' => 'Jours actif',
        'value' => auth()->user()->created_at->diffInDays(now()),
        'icon' => 'fas fa-calendar-check',
        'color' => 'success',
        'description' => 'Depuis l\'inscription'
        ]
        ];
        @endphp

        @foreach($userStats as $stat)
        <div class="col-lg-4">
            @if(isset($stat['route']))
            <a href="{{ $stat['route'] }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="bg-{{ $stat['color'] }} bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="{{ $stat['icon'] }} text-{{ $stat['color'] }} fa-lg"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">{{ $stat['title'] }}</h5>
                        <p class="text-muted mb-0">{{ $stat['description'] }}</p>
                    </div>
                </div>
            </a>
            @else
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-{{ $stat['color'] }} bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="{{ $stat['icon'] }} text-{{ $stat['color'] }} fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-2">{{ number_format($stat['value']) }}</h3>
                    <p class="text-muted mb-0">{{ $stat['description'] }}</p>
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Cards d'accès rapide aux sections -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0">
                    <i class="fas fa-th-large me-2 text-primary"></i>
                    Mes categories
                </h4>
            </div>
        </div>
    </div>






    <div class="row g-4 mb-5">
        @php
        $sections = [];

        // Section mise en avant pour visiteur
        if(auth()->user()->hasRole('visitor')) {
        $sections[] = [
        'title' => 'Passer Premium',
        'description' => 'Débloquez tous les contenus et fonctionnalités',
        'icon' => 'fas fa-crown',
        'color' => 'info',
        'route' => route('payments.index'),
        'badge' => 'Recommandé',
        'badge_color' => 'warning'
        ];
        }

        // Sections pour utilisateur premium
        if(auth()->user()->hasRole('user') || auth()->user()->hasRole('editor') || auth()->user()->hasRole('admin')) {
        $sections[] = [
        'title' => 'Musculation',
        'description' => 'Accédez à vos programmes de preparartion physique',
        'icon' => 'fas fa-dumbbell',
        'color' => 'info',
        'route' => route('exercices.index'),
        'badge' => 'En Cours',
        'badge_color' => 'danger'
        ];

        // Mes Carnets
        $notebooksCount = auth()->user()->notebooks()->count();
        $notebooksItemsCount = \App\Models\NotebookItem::whereHas('notebook', function($q) {
        $q->where('user_id', auth()->id());
        })->count();

        $sections[] = [
        'title' => 'Mes Carnets',
        'description' => $notebooksCount > 0
        ? "{$notebooksCount} carnet(s) • {$notebooksItemsCount} élément(s)"
        : 'Organisez vos contenus favoris',
        'icon' => 'fas fa-book',
        'color' => 'info',
        'route' => route('user.notebooks.index'),
        'badge' => 'Premium',
        'badge_color' => 'success'
        ];
        }

        // Sections communes
        $sections = array_merge($sections, [
        [
        'title' => 'Articles Dossiers',
        'description' => 'Découvrez nos derniers articles et conseils',
        'icon' => 'fas fa-newspaper',
        'color' => 'info',
        'route' => route('posts.public.index')
        ],
        [
        'title' => 'Outils Calculateurs',
        'description' => 'Calculateurs et outils pratiques',
        'icon' => 'fas fa-tools',
        'color' => 'info',
        'route' => route('tools.index')
        ],
        [
        'title' => 'Exercices Musculation',
        'description' => 'Explorez notre bibliothèque d\'exercices',
        'icon' => 'fas fa-running',
        'color' => 'info',
        'route' => route('exercices.index')
        ],
        [
        'title' => 'Documents',
        'description' => 'Téléchargez nos guides et ressources',
        'icon' => 'fas fa-file-download',
        'color' => 'info',
        'route' => route('ebook.index')
        ],
        [
        'title' => 'Fiches techniques',
        'description' => 'Consultez nos fiches détaillées',
        'icon' => 'fas fa-file-alt',
        'color' => 'info',
        'route' => route('public.fiches.index')
        ],
        [
        'title' => 'Entrainements Plans',
        'description' => 'Programmes d\'entraînement',
        'icon' => 'fas fa-heartbeat',
        'color' => 'info',
        'route' => route('public.workouts.index')
        ],
               [
        'title' => 'Guide d\'utilisation',
        'description' => 'Découvrez tout ce que ce site peut vous offrir',
        'icon' => 'fas fa-heartbeat',
        'color' => 'info',
        'route' => route('guide')
        ],
        [
        'title' => 'Nous Contacter',
        'description' => 'Une question ? Contactez-nous',
        'icon' => 'fas fa-envelope',
        'color' => 'info',
        'route' => route('contact')
        ]
        ]);

        // Section admin
        if(auth()->user()->hasRole('admin')) {
        $sections[] = [
        'title' => 'Administration',
        'description' => 'Gérer la plateforme',
        'icon' => 'fas fa-cog',
        'color' => 'info',
        'route' => route('admin.dashboard'),
        'badge' => 'Admin',
        'badge_color' => 'danger'
        ];
        }
        @endphp

        @foreach($sections as $section)
        <div class="col-12 col-lg-6">
            <a href="{{ $section['route'] }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-{{ $section['color'] }} bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                style="width: 60px; height: 60px;">
                                <i class="{{ $section['icon'] }} text-{{ $section['color'] }} fa-2x"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h5 class="mb-0 text-dark">{{ $section['title'] }}</h5>
                                    @if(isset($section['badge']))
                                    <span class="badge bg-{{ $section['badge_color'] }}-subtle text-{{ $section['badge_color'] }}">
                                        {{ $section['badge'] }}
                                    </span>
                                    @endif
                                </div>
                                <p class="text-muted mb-0 small">{{ $section['description'] }}</p>
                            </div>
                            <div class="ms-3 text-{{ $section['color'] }}">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

</div>






<!-- NOUVELLE SECTION : Événements de la semaine -->
    @if(auth()->user()->hasRole('user') || auth()->user()->hasRole('editor') || auth()->user()->hasRole('admin'))
        @php
            $weekEvents = \App\Models\Event::forUser(auth()->id())
                ->thisWeek()
                ->planned()
                ->orderBy('event_date')
                ->orderBy('event_time')
                ->limit(5)
                ->get();
                
            $needsCompletion = \App\Models\Event::forUser(auth()->id())
                ->needsCompletion()
                ->count();
        @endphp
        
        @if($weekEvents->count() > 0 || $needsCompletion > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>Mes activités
                            </h5>
                            <a href="{{ route('user.calendar.index') }}" class="btn btn-sm btn-outline-primary">
                                Voir tout le calendrier <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="bg-primary-subtle rounded p-3 text-center">
                                    <h3 class="mb-0 text-primary">{{ $weekEvents->count() }}</h3>
                                    <small class="text-muted">Planifiées cette semaine</small>
                                </div>
                            </div>
                            @if($needsCompletion > 0)
                            <div class="col-md-6">
                                <div class="bg-warning-subtle rounded p-3 text-center">
                                    <h3 class="mb-0 text-warning">{{ $needsCompletion }}</h3>
                                    <small class="text-muted">À compléter</small>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        @if($weekEvents->count() > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">🔜 Prochaine activité</h6>
                            @php $nextEvent = $weekEvents->first(); @endphp
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px; background-color: {{ $nextEvent->type_color }}20;">
                                                <i class="{{ $nextEvent->type_icon }} fa-lg" style="color: {{ $nextEvent->type_color }};"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1">{{ $nextEvent->title }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>{{ $nextEvent->formatted_date }}
                                                <i class="fas fa-clock ms-2 me-1"></i>{{ $nextEvent->formatted_time }}
                                            </small>
                                            @if($nextEvent->objective)
                                                <br><small class="text-muted">
                                                    <i class="fas fa-bullseye me-1"></i>{{ $nextEvent->objective }}
                                                </small>
                                            @endif
                                            @if($nextEvent->location)
                                                <br><small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $nextEvent->location }}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-auto">
                                            <a href="{{ route('user.calendar.show', $nextEvent) }}" class="btn btn-sm btn-primary">
                                                Voir <i class="fas fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($weekEvents->count() > 1)
                        <div>
                            <h6 class="text-muted mb-2">📅 Autres activités cette semaine</h6>
                            <div class="list-group list-group-flush">
                                @foreach($weekEvents->skip(1) as $event)
                                <div class="list-group-item border-0 px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="{{ $event->type_icon }} me-2" style="color: {{ $event->type_color }};"></i>
                                            <strong>{{ $event->title }}</strong>
                                            <small class="text-muted ms-2">
                                                {{ $event->event_date->translatedFormat('D d') }} - {{ $event->formatted_time }}
                                            </small>
                                        </div>
                                        <a href="{{ route('user.calendar.show', $event) }}" class="btn btn-sm btn-outline-primary">
                                            Voir
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @endif
                        
                        @if($needsCompletion > 0)
                        <div class="alert alert-warning mb-0 mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>{{ $needsCompletion }}</strong> activité(s) en attente de finalisation.
                            <a href="{{ route('user.calendar.index') }}" class="alert-link">Compléter maintenant</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
    


<!--  Section -->
<section class="py-5 text-white" > 

    <div class="container-lg">
        <div class="row align-items-center">

        <a href="{{ route('guide') }}">

            <div class="col-lg text-center">
                <img src="{{ asset('assets/images/team/mode-emploi-nataswim.jpg') }}"
                    alt="Guide Nataswim"
                    class="img-fluid rounded-4 shadow-lg"
                    style="object-fit: cover;">
            </div>
            </a>
        </div>
    </div>
</section>


<section class="text-white py-5" style="background: #13b0ae;border-top: 20px solid rgb(249 245 244);border-left: 20px solid #0c5c7a;border-right: 20px solid #4190c5;border-bottom: 20px double #f9f5f4;border-radius: 0px 0px 60px 60px;margin-top: 20px;">
    <div class="container-lg">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        Articles
                    </h5>
                    <a href="{{ route('posts.public.index') }}" class="btn btn-lg btn-light d-flex align-items-center px-4">
                        <i class="fas fa-water me-1"></i> + Dossiers
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
            $recentArticles = App\Models\Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();
            @endphp

            @forelse($recentArticles as $article)
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                        style="height: 180px; overflow: hidden;">
                        @if($article->image)
                        <img src="{{ $article->image }}"
                            class="w-100 h-100"
                            style="object-fit: cover;"
                            alt="{{ $article->name }}">
                        @else
                        <i class="fas fa-newspaper fa-3x text-primary opacity-25"></i>
                        @endif
                    </div>

                    <div class="card-body p-3">
                        <span class="badge bg-primary-subtle text-primary mb-2">
                            {{ $article->category->name ?? 'Non catégorisé' }}
                        </span>
                        <h6 class="card-title mb-2">
                            <a href="{{ route('posts.public.show', $article) }}"
                                class="text-decoration-none text-dark">
                                {!! Str::limit($article->name, 50) !!}
                            </a>
                        </h6>
                        @if($article->intro)
                        <p class="card-text text-muted small mb-3">
                            {!! Str::limit(strip_tags($article->intro), 80) !!}
                        </p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ $article->hits }}
                            </small>
                            <small class="text-muted">
                                {{ $article->published_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-newspaper fa-3x mb-3 opacity-25"></i>
                    <p>Aucun article publié récemment</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="text-white py-5" style="background: linear-gradient( 58deg, #4897ce 0%, #004e67 100%);border-top: 20px solid rgb(249 245 244);border-left: 20px solid #13b0ae;border-right: 20px solid #13b0ae;border-bottom: 20px double #f9f5f4;border-radius: 0px 0px 60px 60px;margin-top: 20px;">

    <div class="container-lg">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        Fiches Pratiques
                    </h5>
                    <a href="{{ route('public.fiches.index') }}" class="btn btn-lg btn-light d-flex align-items-center px-4">
                        + Fiches
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
            $recentFiches = App\Models\Fiche::where('is_published', true)
            ->where('visibility', 'public')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
                @endphp

                @forelse($recentFiches as $fiche)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        @if($fiche->image)
                        <img src="{{ $fiche->image }}"
                            class="card-img-top"
                            style="height: 180px; object-fit: cover;"
                            alt="{{ $fiche->title }}">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                            style="height: 180px;">
                            <i class="fas fa-file-alt fa-3x text-muted opacity-25"></i>
                        </div>
                        @endif

                        <div class="card-body p-3">
                            @if($fiche->category)
                            <span class="badge bg-primary-subtle text-primary mb-2">
                                {{ $fiche->category->name }}
                            </span>
                            @endif
                            <h6 class="card-title mb-2">
                                <a href="{{ route('public.fiches.show', [$fiche->category, $fiche]) }}"
                                    class="text-decoration-none text-dark">
                                    {!! Str::limit($fiche->title, 50) !!}
                                </a>
                            </h6>
                            @if($fiche->short_description)
                            <p class="card-text text-muted small mb-3">
                                {!! Str::limit(strip_tags($fiche->short_description), 80) !!}
                            </p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-eye me-1"></i>{{ $fiche->views_count ?? 0 }}
                                </small>
                                <a href="{{ route('public.fiches.show', [$fiche->category, $fiche]) }}"
                                    class="btn btn-light d-flex align-items-center px-4">
                                    Lire
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-file-alt fa-3x mb-3 opacity-25"></i>
                        <p>Aucune fiche disponible</p>
                    </div>
                </div>
                @endforelse
        </div>
    </div>
</section>

<section class="text-white py-5" style="background: #13b0ae;border-top: 20px solid rgb(249 245 244);border-left: 20px solid #0c5c7a;border-right: 20px solid #4190c5;border-bottom: 20px double #f9f5f4;border-radius: 0px 0px 60px 60px;margin-top: 20px;">

    <div class="container-lg">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="fas fa-heartbeat me-2 text-primary"></i>Séances
                    </h5>
                    <a href="{{ route('public.workouts.index') }}" class="btn btn-lg btn-light d-flex align-items-center px-4">
                        Séances
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
            $recentWorkouts = App\Models\Workout::with(['categories.section'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
            @endphp

            @forelse($recentWorkouts as $workout)
            @php
            $firstCategory = $workout->categories->first();
            $section = $firstCategory?->section;
            @endphp
            @if($firstCategory && $section)
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center"
                        style="height: 180px; background: linear-gradient(135deg, #0ea5e9 0%, #0f172a 100%);">
                        <div class="text-warning text-center">
                            <i class="fas fa-clipboard fa-4x mb-2 opacity-75"></i>
                            <div class="small">{{ $section->name }}</div>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <span class="badge bg-info-subtle text-info mb-2">
                            {{ $firstCategory->name }}
                        </span>
                        <h6 class="card-title mb-2">
                            <a href="{{ route('public.workouts.show', [$section, $firstCategory, $workout]) }}"
                                class="text-decoration-none text-dark">
                                {!! Str::limit($workout->title, 50) !!}
                            </a>
                        </h6>
                        @if($workout->short_description)
                        <p class="card-text text-muted small mb-3">
                            {!! Str::limit(strip_tags($workout->short_description), 80) !!}
                        </p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-ruler me-1"></i>{{ $workout->formatted_total ?? 'N/A' }}
                            </small>
                            <a href="{{ route('public.workouts.show', [$section, $firstCategory, $workout]) }}"
                                class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-heartbeat fa-3x mb-3 opacity-25"></i>
                    <p>Aucune séance disponible</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
<section class="text-white py-5" style="background: linear-gradient( 58deg, #4897ce 0%, #004e67 100%);border-top: 20px solid rgb(249 245 244);border-left: 20px solid #13b0ae;border-right: 20px solid #13b0ae;border-bottom: 20px double #f9f5f4;border-radius: 0px 0px 60px 60px;margin-top: 20px;">

    <div class="container-lg">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2 text-primary"></i>Documents
                    </h5>
                    <a href="{{ route('ebook.index') }}" class="btn btn-lg btn-light d-flex align-items-center px-4">
                       + Documents
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
            $recentDownloads = App\Models\Downloadable::with('category')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
            @endphp

            @forelse($recentDownloads as $download)
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="position-relative">
                        @if($download->cover_image)
                        <img src="{{ $download->cover_image }}"
                            class="card-img-top"
                            style="height: 180px; object-fit: cover;"
                            alt="{{ $download->title }}">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                            style="height: 180px;">
                            <i class="fas fa-file-{{ $download->format === 'pdf' ? 'pdf' : ($download->format === 'mp4' ? 'video' : 'alt') }} fa-3x text-muted opacity-25"></i>
                        </div>
                        @endif
                        <div class="position-absolute top-0 start-0 p-2">
                            <span class="badge bg-dark">{{ strtoupper($download->format) }}</span>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        @if($download->category)
                        <span class="badge bg-primary-subtle text-primary mb-2">
                            {{ $download->category->name }}
                        </span>
                        @endif
                        <h6 class="card-title mb-2">
                            <a href="{{ route('ebook.show', [$download->category->slug, $download->slug]) }}"
                                class="text-decoration-none text-dark">
                                {!! Str::limit($download->title, 50) !!}
                            </a>
                        </h6>
                        @if($download->short_description)
                        <p class="card-text text-muted small mb-3">
                            {!! Str::limit(strip_tags($download->short_description), 80) !!}
                        </p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-download me-1"></i>{{ $download->download_count ?? 0 }}
                            </small>
                            <a href="{{ route('ebook.show', [$download->category->slug, $download->slug]) }}"
                                class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-book fa-3x mb-3 opacity-25"></i>
                    <p>Aucun document disponible</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="text-white py-5" style="background: #13b0ae;border-top: 20px solid rgb(249 245 244);border-left: 20px solid #0c5c7a;border-right: 20px solid #4190c5;border-bottom: 20px double #f9f5f4;border-radius: 0px 0px 60px 60px;margin-top: 20px;">

    <!-- Derniers Exercices -->
    <div class="container-lg">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="fas fa-running me-2 text-primary"></i> Exercices
                    </h5>
                    <a href="{{ route('exercices.index') }}" class="btn btn-lg btn-light d-flex align-items-center px-4">
                        + Exercices
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
            $recentExercices = App\Models\Exercice::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
            @endphp

            @forelse($recentExercices as $exercice)
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    @if($exercice->image)
                    <img src="{{ $exercice->image }}"
                        class="card-img-top"
                        style="height: 180px; object-fit: cover;"
                        alt="{{ $exercice->titre }}">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                        style="height: 180px;">
                        <i class="fas fa-running fa-3x text-muted opacity-25"></i>
                    </div>
                    @endif

                    <div class="card-body p-3">
                        <div class="d-flex gap-1 mb-2">
                            <span class="badge bg-{{ $exercice->niveau === 'debutant' ? 'success' : ($exercice->niveau === 'avance' ? 'danger' : 'warning') }}-subtle text-{{ $exercice->niveau === 'debutant' ? 'success' : ($exercice->niveau === 'avance' ? 'danger' : 'warning') }} small">
                                {{ $exercice->niveau_label }}
                            </span>
                            <span class="badge bg-primary-subtle text-primary small">
                                {{ $exercice->type_exercice_label }}
                            </span>
                        </div>
                        <h6 class="card-title mb-2">
                            <a href="{{ route('exercices.show', $exercice) }}"
                                class="text-decoration-none text-dark">
                                {!! Str::limit($exercice->titre, 50) !!}
                            </a>
                        </h6>
                        @if($exercice->description)
                        <p class="card-text text-muted small mb-3">
                            {!! Str::limit(strip_tags($exercice->description), 80) !!}
                        </p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            @if($exercice->muscles_cibles && count($exercice->muscles_cibles) > 0)
                            <small class="text-muted">
                                <i class="fas fa-crosshairs me-1"></i>{{ count($exercice->muscles_cibles) }} muscle(s)
                            </small>
                            @else
                            <small class="text-muted">&nbsp;</small>
                            @endif
                            <a href="{{ route('exercices.show', $exercice) }}"
                                class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-running fa-3x mb-3 opacity-25"></i>
                    <p>Aucun exercice disponible</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>










@endsection




@push('styles')
<style>
    .hover-bg-light:hover {
        background-color: var(--bs-light) !important;
        cursor: pointer;
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }

    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Couleurs personnalisées */
    .bg-purple {
        background-color: #6f42c1 !important;
    }

    .text-purple {
        color: #6f42c1 !important;
    }

    .bg-purple-subtle {
        background-color: rgba(111, 66, 193, 0.1) !important;
    }

    .bg-teal {
        background-color: #20c997 !important;
    }

    .text-teal {
        color: #20c997 !important;
    }

    .bg-teal-subtle {
        background-color: rgba(32, 201, 151, 0.1) !important;
    }

    .bg-orange {
        background-color: #fd7e14 !important;
    }

    .text-orange {
        color: #fd7e14 !important;
    }

    .bg-orange-subtle {
        background-color: rgba(253, 126, 20, 0.1) !important;
    }
</style>
@endpush