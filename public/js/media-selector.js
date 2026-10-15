// ========================================
// Protection contre la redéclaration
// ========================================
// Espace de l'utilisateur : les éditeurs n'ont pas accès à /admin, ils passent par /editor
function mediaSelectorBase() {
    return window.location.pathname.indexOf('/editor/') === 0 ? '/editor' : '/admin';
}

if (typeof window.MediaFieldSelector === 'undefined') {

// Selecteur de medias reutilisable avec pagination
class MediaFieldSelector {
    constructor() {
        this.currentFieldId = null;
        this.previewElementId = null;
        this.modal = null;
        this.currentPage = 1;
        this.lastPage = 1;
        this.perPage = 12;
        this.categories = [];
    }

    openForField(fieldId, previewElementId = null) {
        this.currentFieldId = fieldId;
        this.previewElementId = previewElementId;
        this.currentPage = 1;
        
        if (!this.modal) {
            this.createModal();
        }
        
        // Charger les catégories puis les médias
        this.loadCategories().then(() => {
            this.loadMedias(1);
        });
        
        this.showModal();
    }

    createModal() {
        const modalHTML = `
            <div class="modal fade" id="mediaFieldSelectorModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-images me-2"></i>Selectionner une image
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        
                        <div class="modal-body">
                            <!-- Recherche -->
                            <div class="mb-3">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" id="mediaFieldSearch" class="form-control" 
                                               placeholder="Rechercher une image...">
                                    </div>
                                    <div class="col-md-4">
                                        <select id="mediaFieldCategory" class="form-select">
                                            <option value="">Toutes les categories</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary w-100" onclick="window.mediaFieldSelector.loadMedias(1)">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Info pagination -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted" id="mediaFieldInfo">Chargement...</small>
                                <select id="mediaFieldPerPage" class="form-select form-select-sm w-auto">
                                    <option value="12">12 par page</option>
                                    <option value="24">24 par page</option>
                                    <option value="48">48 par page</option>
                                </select>
                            </div>

                            <!-- Grille d'images -->
                            <div id="mediaFieldGrid" class="row g-3" style="max-height: 400px; overflow-y: auto;">
                                <div class="col-12 text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Chargement...</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div id="mediaFieldPagination" class="mt-3 d-none">
                                <nav>
                                    <ul class="pagination justify-content-center mb-0">
                                        <!-- Généré dynamiquement -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Annuler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        this.modal = document.getElementById('mediaFieldSelectorModal');
        this.setupEvents();
    }

    setupEvents() {
        // Recherche
        const searchInput = this.modal.querySelector('#mediaFieldSearch');
        searchInput.addEventListener('input', (e) => {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.loadMedias(1);
            }, 300);
        });

        // Changement du nombre d'images par page
        const perPageSelect = this.modal.querySelector('#mediaFieldPerPage');
        perPageSelect.addEventListener('change', (e) => {
            this.perPage = parseInt(e.target.value);
            this.loadMedias(1);
        });

        // Changement de catégorie
        const categorySelect = this.modal.querySelector('#mediaFieldCategory');
        categorySelect.addEventListener('change', (e) => {
            this.loadMedias(1);
        });
    }

    async loadCategories() {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const response = await fetch(mediaSelectorBase() + '/media-categories-api', {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                console.warn('Impossible de charger les catégories');
                return;
            }
            
            const categories = await response.json();
            this.categories = categories;
            this.renderCategories();
            
        } catch (error) {
            console.error('Erreur chargement catégories:', error);
        }
    }

    renderCategories() {
        const categorySelect = this.modal.querySelector('#mediaFieldCategory');
        
        let optionsHTML = '<option value="">Toutes les categories</option>';
        
        this.categories.forEach(category => {
            optionsHTML += `<option value="${category.id}">${category.name}</option>`;
        });
        
        categorySelect.innerHTML = optionsHTML;
    }

    async loadMedias(page = 1) {
        try {
            const search = document.getElementById('mediaFieldSearch').value;
            const category = document.getElementById('mediaFieldCategory').value;
            
            const params = new URLSearchParams();
            params.append('page', page);
            params.append('per_page', this.perPage);
            if (search) params.append('search', search);
            if (category) params.append('category', category);

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            this.showLoader();
            
            const response = await fetch(`${mediaSelectorBase()}/media-api?${params}`, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) throw new Error('Erreur de chargement');
            
            const data = await response.json();
            
            this.currentPage = data.current_page;
            this.lastPage = data.last_page;
            
            this.renderMedias(data.data || []);
            this.renderPagination(data);
            this.updateInfo(data);
            
        } catch (error) {
            console.error('Erreur:', error);
            this.showError('Erreur lors du chargement des medias');
        }
    }

    showLoader() {
        const grid = this.modal.querySelector('#mediaFieldGrid');
        grid.innerHTML = `
            <div class="col-12 text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
        `;
    }

    renderMedias(medias) {
        const grid = this.modal.querySelector('#mediaFieldGrid');
        
        if (!medias || medias.length === 0) {
            grid.innerHTML = `
                <div class="col-12 text-center py-4">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune image trouvee</h5>
                    <p class="text-muted">Uploadez des images dans votre mediatheque.</p>
                    <a href="${mediaSelectorBase()}/media" class="btn btn-primary" target="_blank">
                        <i class="fas fa-upload me-2"></i>Gerer les medias
                    </a>
                </div>
            `;
            return;
        }

        grid.innerHTML = medias.map(media => {
            const imageUrl = media.url || `/storage/${media.path}`;
            const imageName = media.name || media.original_name || 'Sans nom';
            
            return `
                <div class="col-md-3 col-sm-4 col-6">
                    <div class="card h-100 media-select-item" style="cursor: pointer;" 
                         onclick="window.mediaFieldSelector.selectMedia('${imageUrl}', '${imageName}')">
                        <div class="card-img-top" style="height: 120px; overflow: hidden;">
                            <img src="${imageUrl}" alt="${imageName}" 
                                 class="img-fluid w-100 h-100" style="object-fit: cover;"
                                 loading="lazy">
                        </div>
                        <div class="card-body p-2">
                            <p class="card-text small text-truncate mb-0" title="${imageName}">
                                ${imageName}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    renderPagination(data) {
        const paginationContainer = this.modal.querySelector('#mediaFieldPagination');
        const paginationList = paginationContainer.querySelector('ul');
        
        if (data.last_page <= 1) {
            paginationContainer.classList.add('d-none');
            return;
        }
        
        paginationContainer.classList.remove('d-none');
        
        let paginationHTML = '';
        
        paginationHTML += `
            <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="event.preventDefault(); window.mediaFieldSelector.loadMedias(${data.current_page - 1})">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `;
        
        const maxVisible = 5;
        let startPage = Math.max(1, data.current_page - Math.floor(maxVisible / 2));
        let endPage = Math.min(data.last_page, startPage + maxVisible - 1);
        
        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }
        
        if (startPage > 1) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="event.preventDefault(); window.mediaFieldSelector.loadMedias(1)">1</a>
                </li>
            `;
            if (startPage > 2) {
                paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <li class="page-item ${i === data.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="event.preventDefault(); window.mediaFieldSelector.loadMedias(${i})">${i}</a>
                </li>
            `;
        }
        
        if (endPage < data.last_page) {
            if (endPage < data.last_page - 1) {
                paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="event.preventDefault(); window.mediaFieldSelector.loadMedias(${data.last_page})">${data.last_page}</a>
                </li>
            `;
        }
        
        paginationHTML += `
            <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="event.preventDefault(); window.mediaFieldSelector.loadMedias(${data.current_page + 1})">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `;
        
        paginationList.innerHTML = paginationHTML;
    }

    updateInfo(data) {
        const infoElement = this.modal.querySelector('#mediaFieldInfo');
        const start = (data.current_page - 1) * this.perPage + 1;
        const end = Math.min(data.current_page * this.perPage, data.total);
        infoElement.textContent = `Affichage de ${start} à ${end} sur ${data.total} image(s)`;
    }

    selectMedia(imageUrl, imageName) {
        const field = document.getElementById(this.currentFieldId);
        if (field) {
            field.value = imageUrl;
            field.setCustomValidity('');
            field.dispatchEvent(new Event('input', { bubbles: true }));
            field.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (this.previewElementId) {
            const preview = document.getElementById(this.previewElementId);
            if (preview) {
                preview.src = imageUrl;
                preview.alt = imageName;
                preview.style.display = 'block';
                
                const previewContainer = preview.closest('.mt-3, .d-none');
                if (previewContainer && previewContainer.classList.contains('d-none')) {
                    previewContainer.classList.remove('d-none');
                }
            }
        }

        this.hideModal();
        this.showNotification('Image selectionnee avec succes');
    }

    showModal() {
        if (typeof bootstrap !== 'undefined') {
            const modal = new bootstrap.Modal(this.modal);
            modal.show();
        } else {
            this.modal.style.display = 'block';
            this.modal.classList.add('show');
        }
    }

    hideModal() {
        if (typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getInstance(this.modal);
            if (modal) modal.hide();
        } else {
            this.modal.style.display = 'none';
            this.modal.classList.remove('show');
        }
    }

    showError(message) {
        const grid = this.modal.querySelector('#mediaFieldGrid');
        grid.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${message}
                </div>
            </div>
        `;
    }

    showNotification(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success position-fixed';
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        alert.innerHTML = `<i class="fas fa-check me-2"></i>${message}`;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }
}

// ========================================
// Enregistrement global (une seule fois)
// ========================================
window.MediaFieldSelector = MediaFieldSelector;

// Instance globale
if (!window.mediaFieldSelector) {
    window.mediaFieldSelector = new MediaFieldSelector();
}

} // Fin de la protection

// ========================================
// Fonctions utilitaires (toujours disponibles)
// ========================================
function openMediaSelector(fieldId, previewId = null) {
    window.mediaFieldSelector.openForField(fieldId, previewId);
}