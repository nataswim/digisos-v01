@extends('layouts.public')

@section('title', 'Carte Interactive Lieux Entraînement - Piscines & Salles Sport')
@section('meta_description', 'Trouvez facilement les piscines, salles de sport et lieux d\'entraînement pres de chez vous avec notre carte interactive. Geolocalisation, recherche et itineraires inclus.')

@section('content')
<!-- Section titre -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container py-3">
        <h1 class="display-4 fw-bold d-flex align-items-center justify-content-center gap-3 mb-3">
            Carte Interactive
        </h1>
 
    </div>
</section>

<!-- Interface de recherche -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="input-group input-group-lg shadow-sm">
                    <input type="text" id="searchInput" class="form-control border-primary" 
                           placeholder="Rechercher un lieu (ex: Piscine Jean Bouin, Paris)">
                    <button class="btn btn-primary d-flex align-items-center gap-2" 
                            id="searchButton">
                        <i class="fas fa-search"></i>
                        <span id="searchButtonText">Rechercher</span>
                    </button>
                </div>
            </div>
            <div class="col-lg-6 d-flex flex-column flex-md-row align-items-md-center justify-content-md-end gap-3">
                <select id="mapTypeSelect" class="form-select form-select-lg border-primary shadow-sm" 
                        style="max-width: 180px;">
                    <option value="streets">Plan de rue</option>
                    <option value="satellite">Satellite</option>
                </select>
                <button id="directionsButton" class="btn btn-success btn-lg shadow-sm d-none">
                    <i class="fas fa-route me-2"></i>Itineraire Google Maps
                </button>
            </div>
        </div>
        
        <!-- Messages d'erreur/succes -->
        <div id="alertContainer"></div>
    </div>
</section>

<!-- Carte principale -->
<section class="py-4">
    <div class="container">
        <div id="mapContainer" class="shadow-lg" 
             style="height: 80vh; width: 100%; border-radius: 0.75rem; overflow: hidden; border: 1px solid #dee2e6;">
            <!-- La carte Leaflet sera initialisee ici -->
        </div>
    </div>
</section>

<!-- Instructions d'utilisation -->
<section class="py-5">
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Comment utiliser la carte interactive ?
                </h3>
            </div>
            <div class="card-body">
                <p class="lead">
                    Notre carte interactive vous permet de trouver facilement des lieux d'entraînement sportif.
                </p>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-primary h-100">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="fas fa-location-arrow me-2"></i>Geolocalisation
                                </h6>
                                <p class="card-text">
                                    A l'ouverture, la carte tente de detecter votre position actuelle pour vous centrer automatiquement.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-warning h-100">
                            <div class="card-body">
                                <h6 class="card-title text-warning">
                                    <i class="fas fa-search me-2"></i>Recherche
                                </h6>
                                <p class="card-text">
                                    Utilisez la barre de recherche pour trouver des piscines, salles de sport, parcs ou tout autre lieu specifique.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-success h-100">
                            <div class="card-body">
                                <h6 class="card-title text-success">
                                    <i class="fas fa-map me-2"></i>Type de carte
                                </h6>
                                <p class="card-text">
                                    Basculez entre la vue "Plan de rue" et "Satellite" pour une meilleure visualisation du terrain.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-info h-100">
                            <div class="card-body">
                                <h6 class="card-title text-info">
                                    <i class="fas fa-route me-2"></i>Itineraire
                                </h6>
                                <p class="card-text">
                                    Si votre position est detectee et que vous avez recherche un lieu, obtenez un itineraire via Google Maps.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-success mt-4">
                    <h6><i class="fas fa-lightbulb me-2"></i>Conseil Pro</h6>
                    <p class="mb-0">
                        Pour de meilleurs resultats de recherche, soyez specifique : "Piscine municipale + nom de ville" 
                        ou "Salle de sport + adresse precise".
                    </p>
                </div>
            </div>
        </div>

        <!-- Call to action vers autres outils -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">
                    <i class="fas fa-tools me-2"></i>
                    Optimisez vos entraînements avec nos outils
                </h3>
            </div>
            <div class="card-body">
                <p class="lead">
                    Apres avoir trouve votre lieu ideal, profitez de nos autres outils pour maximiser votre potentiel :
                </p>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('tools.calories') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-utensils me-2"></i>
                            Calories Sport
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('tools.fitness') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-heartbeat me-2"></i>
                            Calculateur Fitness
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('tools.hydratation') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-tint me-2"></i>
                            Besoins Hydriques
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('posts.public.index') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-list me-2"></i>
                            Tous nos Outils
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemples de recherches populaires -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-fire me-2"></i>
                    Recherches Populaires
                </h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <h6 class="text-primary">Natation</h6>
                        <div class="d-flex flex-wrap gap-1">
                            <button class="btn btn-outline-primary btn-sm search-suggestion" data-search="piscine municipale">Piscine municipale</button>
                            <button class="btn btn-outline-primary btn-sm search-suggestion" data-search="centre aquatique">Centre aquatique</button>
                            <button class="btn btn-outline-primary btn-sm search-suggestion" data-search="piscine olympique">Piscine olympique</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-success">Fitness</h6>
                        <div class="d-flex flex-wrap gap-1">
                            <button class="btn btn-outline-success btn-sm search-suggestion" data-search="salle de sport">Salle de sport</button>
                            <button class="btn btn-outline-success btn-sm search-suggestion" data-search="fitness center">Fitness center</button>
                            <button class="btn btn-outline-success btn-sm search-suggestion" data-search="crossfit box">CrossFit box</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-warning">Exterieur</h6>
                        <div class="d-flex flex-wrap gap-1">
                            <button class="btn btn-outline-warning btn-sm search-suggestion" data-search="parcours sante">Parcours sante</button>
                            <button class="btn btn-outline-warning btn-sm search-suggestion" data-search="piste cyclable">Piste cyclable</button>
                            <button class="btn btn-outline-warning btn-sm search-suggestion" data-search="parc public">Parc public</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section Credit et Contact -->
     <div class="card mb-4">
            <a href="{{ route('tools.index') }}" class="btn btn-success btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Essayer d'autres outils
            </a>
        </div>
<section class="py-5 bg-primary text-white">

    <div class="container">


        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">A Propos de nos Outils</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-info mb-2">Developpement & Expertise</h6>
                        <p class="mb-3">
                            Contenus et outils developpes par 
                            <a href="https://www.facebook.com/Sports.Ressources/" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="text-warning fw-bold text-decoration-none">
                                Med H El Haouat
                                <i class="fas fa-external-link-alt ms-1 small"></i>
                            </a>
                        </p>
                        <p class="small text-light opacity-75">
                            Expert en sciences du sport, physiologie de l'exercice et developpement 
                            d'outils d'aide A la performance sportive evidence-based.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success mb-2">Collaboration & Amelioration</h6>
                        <p class="mb-3 small">
                            Si vous constatez une erreur dans nos calculateurs ou souhaitez suggerer 
                            de nouveaux outils, n'hesitez pas A nous contacter.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-envelope me-2"></i>Nous Contacter
                            </a>
                            <a href="https://www.facebook.com/Sports.Ressources/" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fab fa-linkedin me-2"></i>LinkedIn
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="bg-white bg-opacity-10 rounded-circle p-2 d-inline-flex align-items-center justify-content-center" 
                     style="width: 150px; height: 150px; overflow: hidden;">
                    <img src="{{ asset('assets/images/team/med_Hassan_EL_HAOUAT.png') }}" 
                         alt="Med H El Haouat - Expert en sciences du sport" 
                         class="w-100 h-100 rounded-circle"
                         style="object-fit: cover;">
                </div>
                <div class="mt-3">
                    <h6 class="text-warning mb-1">Evidence-Based</h6>
                    <small class="text-light opacity-75">Recherches 2024 integrees</small>
                </div>
            </div>
        </div>
    </div>
</section>





<!-- Dernieres Publications -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-water text-primary me-2"></i>Dernieres Publications
            </h2>
            <a href="{{ route('posts.public.index') }}" class="btn btn-outline-primary">
                Tous les articles <i class="fas fa-angle-right ms-1"></i>
            </a>
        </div>
        
        @php
            $latestPosts = App\Models\Post::with('category')
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->limit(3)
                ->get();
        @endphp
        
        @if($latestPosts->count() > 0)
            <div class="row g-4">
                @foreach($latestPosts as $post)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm hover-lift border-0">
                            <div style="height: 180px; overflow: hidden;">
                                @if($post->image)
                                    <img src="{{ $post->image }}" 
                                         alt="{{ $post->name }}"
                                         class="card-img-top"
                                         style="height: 100%; width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;">
                                        <i class="fas fa-swimmer text-muted" style="font-size: 2.5rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                @if($post->category)
                                    <div class="mb-2">
                                        <span class="badge bg-primary">{{ $post->category->name }}</span>
                                    </div>
                                @endif
                                <h3 class="card-title h5 mb-3">{{ $post->name }}</h3>
                                @if($post->intro)
                                    <p class="card-text text-muted small">
                                        {!! Str::limit(strip_tags($post->intro), 100) !!}
                                    </p>
                                @endif
                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                                <small class="text-muted d-flex align-items-center">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $post->published_at->format('d/m/Y') }}
                                </small>
                                <a href="{{ route('posts.public.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                    Lire la suite
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info" role="alert">
                <i class="fas fa-water me-2"></i>Aucun article n'est disponible actuellement.
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
.leaflet-container {
    font-family: inherit;
}

.search-suggestion {
    font-size: 0.75rem;
    margin: 2px;
}

.search-suggestion:hover {
    transform: translateY(-1px);
}

#mapContainer {
    position: relative;
}

.leaflet-popup-content-wrapper {
    border-radius: 8px;
}

.leaflet-popup-content {
    font-family: inherit;
    font-size: 14px;
}

.map-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
class InteractiveMap {
    constructor() {
        this.map = null;
        this.userLocation = null;
        this.selectedLocation = null;
        this.userMarker = null;
        this.selectedMarker = null;
        this.locationName = '';
        this.isSearching = false;
        
        this.initializeMap();
        this.setupEventListeners();
        this.requestGeolocation();
    }
    
    initializeMap() {
        // Initialiser la carte centree sur Paris par defaut
        this.map = L.map('mapContainer', {
            center: [48.8566, 2.3522],
            zoom: 13,
            zoomControl: true
        });
        
        // Couche de tuiles par defaut (OpenStreetMap)
        this.streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(this.map);
        
        // Couche satellite
        this.satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
            maxZoom: 19
        });
        
        // Icônes personnalisees
        this.userIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            shadowSize: [41, 41]
        });
        
        this.destinationIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            shadowSize: [41, 41]
        });
        
        console.log('Carte initialisee avec succes');
    }
    
    setupEventListeners() {
        // Bouton de recherche
        document.getElementById('searchButton').addEventListener('click', () => this.handleSearch());
        
        // Recherche au clavier (Entree)
        document.getElementById('searchInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.handleSearch();
            }
        });
        
        // Changement de type de carte
        document.getElementById('mapTypeSelect').addEventListener('change', (e) => {
            this.changeMapType(e.target.value);
        });
        
        // Boutons de suggestions de recherche
        document.querySelectorAll('.search-suggestion').forEach(button => {
            button.addEventListener('click', () => {
                const searchTerm = button.dataset.search;
                document.getElementById('searchInput').value = searchTerm;
                this.handleSearch();
            });
        });
        
        // Bouton d'itineraire
        document.getElementById('directionsButton').addEventListener('click', () => {
            this.openDirections();
        });
    }
    
    requestGeolocation() {
        if (navigator.geolocation) {
            this.showAlert('info', 'Tentative de geolocalisation en cours...', 2000);
            
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.userLocation = [position.coords.latitude, position.coords.longitude];
                    this.addUserMarker();
                    this.map.setView(this.userLocation, 15);
                    this.showAlert('success', 'Position detectee avec succes !', 3000);
                    this.updateDirectionsButton();
                },
                (error) => {
                    let errorMessage = 'Erreur de geolocalisation : ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Acces refuse par l\'utilisateur.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Position indisponible.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Delai d\'attente depasse.';
                            break;
                        default:
                            errorMessage += 'Erreur inconnue.';
                    }
                    this.showAlert('warning', errorMessage + ' Vous pouvez rechercher manuellement un lieu.', 5000);
                    console.warn('Geolocation error:', error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000 // 5 minutes
                }
            );
        } else {
            this.showAlert('error', 'La geolocalisation n\'est pas supportee par votre navigateur.', 5000);
        }
    }
    
    addUserMarker() {
        if (this.userMarker) {
            this.map.removeLayer(this.userMarker);
        }
        
        if (this.userLocation) {
            this.userMarker = L.marker(this.userLocation, { icon: this.userIcon })
                .addTo(this.map)
                .bindPopup('<strong><i class="fas fa-user me-2"></i>Votre position</strong>')
                .openPopup();
        }
    }
    
    async handleSearch() {
        const query = document.getElementById('searchInput').value.trim();
        
        if (!query) {
            this.showAlert('warning', 'Veuillez entrer un lieu A rechercher.', 3000);
            return;
        }
        
        if (this.isSearching) return;
        
        this.isSearching = true;
        this.updateSearchButton(true);
        
        try {
            this.showAlert('info', 'Recherche en cours...', 1000);
            
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&addressdetails=1&extratags=1`
            );
            
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data && data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lon = parseFloat(result.lon);
                
                this.selectedLocation = [lat, lon];
                this.locationName = result.display_name;
                
                this.addSelectedMarker();
                this.map.setView(this.selectedLocation, 16);
                this.updateDirectionsButton();
                
                this.showAlert('success', `Lieu trouve : ${this.locationName}`, 4000);
            } else {
                this.showAlert('error', 'Lieu non trouve. Veuillez affiner votre recherche ou essayer un autre terme.', 5000);
                this.selectedLocation = null;
                this.locationName = '';
                this.removeSelectedMarker();
                this.updateDirectionsButton();
            }
        } catch (error) {
            console.error('Erreur de recherche:', error);
            this.showAlert('error', 'Erreur lors de la recherche. Veuillez verifier votre connexion et reessayer.', 5000);
        } finally {
            this.isSearching = false;
            this.updateSearchButton(false);
        }
    }
    
    addSelectedMarker() {
        if (this.selectedMarker) {
            this.map.removeLayer(this.selectedMarker);
        }
        
        if (this.selectedLocation) {
            this.selectedMarker = L.marker(this.selectedLocation, { icon: this.destinationIcon })
                .addTo(this.map)
                .bindPopup(`<strong><i class="fas fa-map-marker-alt me-2"></i>Destination</strong><br><small>${this.locationName}</small>`)
                .openPopup();
        }
    }
    
    removeSelectedMarker() {
        if (this.selectedMarker) {
            this.map.removeLayer(this.selectedMarker);
            this.selectedMarker = null;
        }
    }
    
    changeMapType(type) {
        if (type === 'satellite') {
            this.map.removeLayer(this.streetLayer);
            this.map.addLayer(this.satelliteLayer);
        } else {
            this.map.removeLayer(this.satelliteLayer);
            this.map.addLayer(this.streetLayer);
        }
    }
    
    updateSearchButton(isSearching) {
        const button = document.getElementById('searchButton');
        const buttonText = document.getElementById('searchButtonText');
        
        if (isSearching) {
            button.disabled = true;
            buttonText.textContent = 'Recherche...';
            button.querySelector('i').className = 'fas fa-spinner fa-spin';
        } else {
            button.disabled = false;
            buttonText.textContent = 'Rechercher';
            button.querySelector('i').className = 'fas fa-search';
        }
    }
    
    updateDirectionsButton() {
        const directionsButton = document.getElementById('directionsButton');
        
        if (this.userLocation && this.selectedLocation) {
            directionsButton.classList.remove('d-none');
        } else {
            directionsButton.classList.add('d-none');
        }
    }
    
    openDirections() {
        if (this.userLocation && this.selectedLocation) {
            const url = `https://www.google.com/maps/dir/${this.userLocation[0]},${this.userLocation[1]}/${this.selectedLocation[0]},${this.selectedLocation[1]}`;
            window.open(url, '_blank', 'noopener,noreferrer');
        }
    }
    
    showAlert(type, message, duration = 5000) {
        const alertContainer = document.getElementById('alertContainer');
        
        // Supprimer les anciennes alertes
        alertContainer.innerHTML = '';
        
        const alertClass = {
            'success': 'alert-success',
            'error': 'alert-danger',
            'warning': 'alert-warning',
            'info': 'alert-info'
        }[type] || 'alert-info';
        
        const iconClass = {
            'success': 'fas fa-check-circle',
            'error': 'fas fa-exclamation-circle',
            'warning': 'fas fa-exclamation-triangle',
            'info': 'fas fa-water'
        }[type] || 'fas fa-water';
        
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show shadow-sm`;
        alert.innerHTML = `
            <i class="${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Auto-dismiss apres la duree specifiee
        if (duration > 0) {
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    alert.remove();
                }
            }, duration);
        }
    }
}

// Initialiser la carte au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Attendre que Leaflet soit completement charge
    if (typeof L !== 'undefined') {
        new InteractiveMap();
    } else {
        // Fallback si Leaflet n'est pas encore charge
        setTimeout(() => {
            new InteractiveMap();
        }, 100);
    }
});
</script>
@endpush