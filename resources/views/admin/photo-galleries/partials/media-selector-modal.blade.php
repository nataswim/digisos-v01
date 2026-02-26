{{-- Modal de sélection de médias pour les galeries --}}

<div class="modal fade" id="mediaSelectorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-images me-2"></i>
                    Sélectionner des photos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                {{-- Barre de recherche et filtres --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   id="mediaSearchInput"
                                   class="form-control" 
                                   placeholder="Rechercher une photo...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="mediaCategoryFilter" class="form-select">
                            <option value="">Toutes les catégories</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" 
                                class="btn btn-outline-primary w-100"
                                id="mediaRefreshBtn">
                            <i class="fas fa-sync"></i>
                        </button>
                    </div>
                </div>

                {{-- Zone de chargement --}}
                <div id="mediaLoadingSpinner" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="text-muted mt-2">Chargement des médias...</p>
                </div>

                {{-- Grille de médias --}}
                <div id="mediaGridContainer" class="row g-3" style="max-height: 500px; overflow-y: auto;">
                    {{-- Rempli dynamiquement par JavaScript --}}
                </div>

                {{-- Message si aucun média --}}
                <div id="noMediaMessage" class="text-center py-5 d-none">
                    <i class="fas fa-images fa-4x text-muted opacity-50 mb-3"></i>
                    <h5 class="text-muted">Aucune photo trouvée</h5>
                    <p class="text-muted">Uploadez des images depuis la médiathèque.</p>
                    <a href="{{ route('admin.media.index') }}" 
                       target="_blank"
                       class="btn btn-primary">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Aller à la médiathèque
                    </a>
                </div>
            </div>
            
            <div class="modal-footer">
                <div class="me-auto">
                    <span id="selectedMediaCount" class="badge bg-primary">0 photo(s) sélectionnée(s)</span>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Annuler
                </button>
                <button type="button" class="btn btn-primary" id="confirmMediaSelection">
                    <i class="fas fa-check me-2"></i>Confirmer la sélection
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.media-item-selector {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 3px solid transparent;
    position: relative;
}

.media-item-selector:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.media-item-selector.selected {
    border-color: #0d6efd;
    transform: scale(0.95);
}

.media-item-selector .selection-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    background: #0d6efd;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.media-item-selector.selected .selection-badge {
    opacity: 1;
}

.media-item-selector img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('mediaSelectorModal');
    const gridContainer = document.getElementById('mediaGridContainer');
    const loadingSpinner = document.getElementById('mediaLoadingSpinner');
    const noMediaMessage = document.getElementById('noMediaMessage');
    const searchInput = document.getElementById('mediaSearchInput');
    const categoryFilter = document.getElementById('mediaCategoryFilter');
    const refreshBtn = document.getElementById('mediaRefreshBtn');
    const confirmBtn = document.getElementById('confirmMediaSelection');
    const selectedCountBadge = document.getElementById('selectedMediaCount');
    
    let allMedia = [];
    let selectedMedia = new Set();
    let searchTimeout = null;

    // Charger les médias au premier affichage du modal
    modal.addEventListener('show.bs.modal', function() {
        if (allMedia.length === 0) {
            loadMedia();
        }
    });

    // Charger les médias depuis l'API
    function loadMedia() {
        loadingSpinner.classList.remove('d-none');
        gridContainer.classList.add('d-none');
        noMediaMessage.classList.add('d-none');

        fetch('{{ route("admin.media.api") }}?images_only=1')
            .then(response => response.json())
            .then(data => {
                allMedia = data.media || [];
                loadCategories(data.categories || []);
                renderMedia(allMedia);
                loadingSpinner.classList.add('d-none');
                
                if (allMedia.length === 0) {
                    noMediaMessage.classList.remove('d-none');
                } else {
                    gridContainer.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des médias:', error);
                loadingSpinner.classList.add('d-none');
                noMediaMessage.classList.remove('d-none');
            });
    }

    // Charger les catégories dans le filtre
    function loadCategories(categories) {
        categoryFilter.innerHTML = '<option value="">Toutes les catégories</option>';
        categories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            categoryFilter.appendChild(option);
        });
    }

    // Afficher les médias dans la grille
    function renderMedia(mediaList) {
        gridContainer.innerHTML = '';
        
        if (mediaList.length === 0) {
            noMediaMessage.classList.remove('d-none');
            gridContainer.classList.add('d-none');
            return;
        }

        mediaList.forEach(media => {
            const col = document.createElement('div');
            col.className = 'col-lg-2 col-md-3 col-sm-4 col-6';
            
            const isSelected = selectedMedia.has(media.id);
            
            col.innerHTML = `
                <div class="card media-item-selector ${isSelected ? 'selected' : ''}" 
                     data-media-id="${media.id}"
                     data-media-url="${media.url}"
                     data-media-name="${media.name}">
                    <img src="${media.url}" alt="${media.name}" class="card-img-top">
                    <div class="selection-badge">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="card-body p-2">
                        <small class="text-truncate d-block" title="${media.name}">
                            ${media.name}
                        </small>
                    </div>
                </div>
            `;
            
            gridContainer.appendChild(col);
        });

        // Ajouter les événements de clic
        document.querySelectorAll('.media-item-selector').forEach(item => {
            item.addEventListener('click', function() {
                toggleMediaSelection(this);
            });
        });
    }

    // Basculer la sélection d'un média
    function toggleMediaSelection(element) {
        const mediaId = parseInt(element.dataset.mediaId);
        
        // Si mode sélection d'image de couverture
        if (window.coverImageSelectionMode) {
            setCoverImage(mediaId, element.dataset.mediaUrl);
            return;
        }
        
        if (selectedMedia.has(mediaId)) {
            selectedMedia.delete(mediaId);
            element.classList.remove('selected');
        } else {
            selectedMedia.add(mediaId);
            element.classList.add('selected');
        }
        
        updateSelectedCount();
    }

    // Mettre à jour le compteur
    function updateSelectedCount() {
        selectedCountBadge.textContent = `${selectedMedia.size} photo(s) sélectionnée(s)`;
    }

    // Recherche
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterMedia();
        }, 300);
    });

    // Filtrage par catégorie
    categoryFilter.addEventListener('change', function() {
        filterMedia();
    });

    // Filtrer les médias
    function filterMedia() {
        const searchTerm = searchInput.value.toLowerCase();
        const categoryId = categoryFilter.value;
        
        let filtered = allMedia;
        
        if (searchTerm) {
            filtered = filtered.filter(m => 
                m.name.toLowerCase().includes(searchTerm) ||
                (m.alt_text && m.alt_text.toLowerCase().includes(searchTerm))
            );
        }
        
        if (categoryId) {
            filtered = filtered.filter(m => m.media_category_id == categoryId);
        }
        
        renderMedia(filtered);
    }

    // Rafraîchir
    refreshBtn.addEventListener('click', function() {
        loadMedia();
    });

    // Confirmer la sélection
    confirmBtn.addEventListener('click', function() {
        if (window.GalleryMediaSelector && selectedMedia.size > 0) {
            const selectedMediaData = allMedia.filter(m => selectedMedia.has(m.id));
            window.GalleryMediaSelector.addPhotos(selectedMediaData);
            
            // Réinitialiser
            selectedMedia.clear();
            updateSelectedCount();
            
            // Fermer le modal
            bootstrap.Modal.getInstance(modal).hide();
        }
    });
});
</script>
@endpush
