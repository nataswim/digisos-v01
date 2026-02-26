/**
 * gallery-media-selector.js
 *
 * Deux responsabilités :
 *  1. GalleryMediaSelector — gère la liste de photos du formulaire (rendu, drag-drop, légendes)
 *  2. _GalleryPickerModal  — modal de sélection paginé (réutilise l'API /admin/media-api)
 *
 * Fonctions globales exposées :
 *  - openGallerySelector()    → sélection multiple → GalleryMediaSelector.addPhotos()
 *  - openCoverImageSelector() → sélection unique   → remplit #cover_image_id + preview
 */

(function () {
    'use strict';

    // =========================================================================
    // 1. GESTIONNAIRE DE PHOTOS DU FORMULAIRE
    // =========================================================================

    window.GalleryMediaSelector = {
        photos: [],
        container: null,
        emptyMessage: null,

        init() {
            this.container    = document.getElementById('selectedPhotosContainer');
            this.emptyMessage = document.getElementById('emptyPhotosMessage');
            if (!this.container) return;
            this.setupSortable();
        },

        addPhotos(mediaList) {
            mediaList.forEach(media => {
                if (!this.photos.find(p => p.id === media.id)) {
                    this.photos.push({ id: media.id, url: media.url, name: media.name, caption: '' });
                }
            });
            this.render();
        },

        loadExistingPhotos(existingPhotos) {
            this.photos = existingPhotos.sort((a, b) => a.sort_order - b.sort_order);
            this.render();
        },

        removePhoto(index) {
            if (confirm('Retirer cette photo ?')) { this.photos.splice(index, 1); this.render(); }
        },

        updateCaption(index, caption) {
            if (this.photos[index]) this.photos[index].caption = caption;
        },

        render() {
            if (!this.container) return;

            if (this.photos.length === 0) {
                this.emptyMessage.classList.remove('d-none');
                this.container.innerHTML = '';
                this.container.appendChild(this.emptyMessage);
                return;
            }

            this.emptyMessage.classList.add('d-none');
            this.container.innerHTML = '';

            this.photos.forEach((photo, index) => {
                const col = document.createElement('div');
                col.className = 'col-lg-3 col-md-4 col-sm-6';
                col.dataset.photoIndex = index;
                col.innerHTML = `
                    <div class="card border-0 shadow-sm h-100 gallery-photo-item">
                        <div class="position-relative">
                            <img src="${photo.url}" alt="${photo.name}"
                                 class="card-img-top"
                                 style="height:200px; object-fit:cover; cursor:move;">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-primary">
                                    <i class="fas fa-arrows-alt me-1"></i>${index + 1}
                                </span>
                            </div>
                            <div class="position-absolute top-0 end-0 m-2">
                                <button type="button"
                                        class="btn btn-sm btn-danger rounded-circle"
                                        onclick="window.GalleryMediaSelector.removePhoto(${index})"
                                        title="Retirer">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title text-truncate mb-2" title="${photo.name}">${photo.name}</h6>
                            <div class="mb-0">
                                <label class="form-label small fw-semibold mb-1">Légende (optionnelle)</label>
                                <textarea class="form-control form-control-sm" rows="2"
                                          placeholder="Description de la photo..."
                                          onchange="window.GalleryMediaSelector.updateCaption(${index}, this.value)">${photo.caption || ''}</textarea>
                            </div>
                        </div>
                        <input type="hidden" name="photos[]"   value="${photo.id}">
                        <input type="hidden" name="captions[]" value="${photo.caption || ''}">
                    </div>`;
                this.container.appendChild(col);
            });

            this.setupSortable();
        },

        setupSortable() {
            if (typeof Sortable === 'undefined') return;
            new Sortable(this.container, {
                animation: 150,
                handle: 'img',
                draggable: '.col-lg-3',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: (evt) => {
                    const moved = this.photos.splice(evt.oldIndex, 1)[0];
                    this.photos.splice(evt.newIndex, 0, moved);
                    this.render();
                }
            });
        }
    };

    // =========================================================================
    // 2. MODAL PICKER PAGINÉ (réutilise /admin/media-api)
    // =========================================================================

    const _GalleryPickerModal = {
        modal: null,
        selected: new Map(),   // id → {id, url, name}
        singleMode: false,     // true = sélection unique (cover image)
        onConfirm: null,
        currentPage: 1,
        perPage: 24,
        _timer: null,

        // ── Ouverture ─────────────────────────────────────────────────────────
        open(opts = {}) {
            this.onConfirm  = opts.onConfirm  || null;
            this.singleMode = opts.singleMode || false;
            this.selected.clear();

            if (!this.modal) this._build();
            this._updateCount();

            this.modal.querySelector('#_gpmTitle').textContent =
                this.singleMode ? 'Choisir une image' : 'Ajouter des photos';

            this._loadCategories().then(() => this._loadPage(1));
            bootstrap.Modal.getOrCreateInstance(this.modal).show();
        },

        // ── Construction du DOM ───────────────────────────────────────────────
        _build() {
            document.getElementById('_galleryPickerModal')?.remove();
            document.body.insertAdjacentHTML('beforeend', `
            <div class="modal fade" id="_galleryPickerModal" tabindex="-1">
              <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="_gpmTitle">Ajouter des photos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row g-2 mb-3">
                      <div class="col-md-6">
                        <input type="text" id="_gpmSearch" class="form-control" placeholder="Rechercher…">
                      </div>
                      <div class="col-md-4">
                        <select id="_gpmCategory" class="form-select">
                          <option value="">Toutes les catégories</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <button type="button" class="btn btn-outline-secondary w-100"
                                id="_gpmRefresh" title="Actualiser">
                          <i class="fas fa-sync"></i>
                        </button>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <small class="text-muted" id="_gpmInfo">Chargement…</small>
                      <select id="_gpmPerPage" class="form-select form-select-sm w-auto">
                        <option value="24">24 / page</option>
                        <option value="48">48 / page</option>
                        <option value="96">96 / page</option>
                      </select>
                    </div>
                    <div id="_gpmGrid" class="row g-3"></div>
                    <div id="_gpmPagination" class="mt-3 d-none">
                      <nav><ul class="pagination justify-content-center mb-0" id="_gpmPaginationList"></ul></nav>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <span class="me-auto">
                      <span class="badge bg-primary" id="_gpmCount">0</span>
                      <span id="_gpmCountLabel"> photo(s) sélectionnée(s)</span>
                    </span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="_gpmConfirm">
                      <i class="fas fa-check me-2"></i>Confirmer
                    </button>
                  </div>
                </div>
              </div>
            </div>`);

            this.modal = document.getElementById('_galleryPickerModal');
            this._bindEvents();
        },

        _bindEvents() {
            this.modal.querySelector('#_gpmSearch').addEventListener('input', () => {
                clearTimeout(this._timer);
                this._timer = setTimeout(() => this._loadPage(1), 300);
            });
            this.modal.querySelector('#_gpmCategory').addEventListener('change', () => this._loadPage(1));
            this.modal.querySelector('#_gpmPerPage').addEventListener('change', (e) => {
                this.perPage = parseInt(e.target.value);
                this._loadPage(1);
            });
            this.modal.querySelector('#_gpmRefresh').addEventListener('click', () => this._loadPage(1));
            this.modal.querySelector('#_gpmConfirm').addEventListener('click', () => {
                if (this.onConfirm && this.selected.size > 0) {
                    this.onConfirm([...this.selected.values()]);
                }
                bootstrap.Modal.getInstance(this.modal).hide();
            });
        },

        // ── Catégories ────────────────────────────────────────────────────────
        async _loadCategories() {
            try {
                const r = await fetch('/admin/media-categories-api', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin'
                });
                if (!r.ok) return;
                const cats = await r.json();
                const sel  = this.modal.querySelector('#_gpmCategory');
                sel.innerHTML = '<option value="">Toutes les catégories</option>';
                cats.forEach(c => {
                    const o = document.createElement('option');
                    o.value = c.id; o.textContent = c.name; sel.appendChild(o);
                });
            } catch (e) { /* silencieux */ }
        },

        // ── Chargement paginé (même format que media-selector.js) ────────────
        async _loadPage(page = 1) {
            const search   = this.modal.querySelector('#_gpmSearch').value;
            const category = this.modal.querySelector('#_gpmCategory').value;

            const params = new URLSearchParams({ page, per_page: this.perPage, images_only: 1 });
            if (search)   params.set('search', search);
            if (category) params.set('category', category);

            this._showLoader();

            try {
                const r = await fetch(`/admin/media-api?${params}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin'
                });
                if (!r.ok) throw new Error('HTTP ' + r.status);
                const data = await r.json();
                this._renderGrid(data.data || []);
                this._renderPagination(data);
                this._renderInfo(data);
                this.currentPage = data.current_page;
            } catch (e) {
                this.modal.querySelector('#_gpmGrid').innerHTML =
                    `<div class="col-12"><div class="alert alert-danger">Erreur : ${e.message}</div></div>`;
            }
        },

        _showLoader() {
            this.modal.querySelector('#_gpmGrid').innerHTML =
                `<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>`;
            this.modal.querySelector('#_gpmPagination').classList.add('d-none');
        },

        // ── Rendu de la grille ────────────────────────────────────────────────
        _renderGrid(items) {
            const grid = this.modal.querySelector('#_gpmGrid');

            if (!items.length) {
                grid.innerHTML = `
                  <div class="col-12 text-center py-5">
                    <i class="fas fa-images fa-4x text-muted mb-3 d-block"></i>
                    <p class="text-muted">Aucune image trouvée</p>
                    <a href="/admin/media" target="_blank" class="btn btn-primary">
                      <i class="fas fa-upload me-2"></i>Médiathèque
                    </a>
                  </div>`;
                return;
            }

            grid.innerHTML = items.map(media => {
                // Même fallback URL que media-selector.js
                const url  = media.url || `/storage/${media.path}`;
                const name = (media.name || media.original_name || 'Sans nom').replace(/"/g, '&quot;');
                const sel  = this.selected.has(media.id);

                return `
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                  <div class="_gpm-item card position-relative"
                       data-id="${media.id}" data-url="${url}" data-name="${name}"
                       style="cursor:pointer; border:3px solid ${sel ? '#0d6efd' : 'transparent'}; transition:.15s;">
                    <img src="${url}" alt="${name}"
                         class="card-img-top" style="height:110px; object-fit:cover;" loading="lazy"
                         onerror="this.style.background='#f0f0f0'; this.style.display='none'">
                    <div class="position-absolute top-0 end-0 m-1 bg-primary text-white rounded-circle
                                d-flex align-items-center justify-content-center"
                         style="width:22px; height:22px; font-size:10px;
                                opacity:${sel ? 1 : 0}; transition:.15s;">
                      <i class="fas fa-check"></i>
                    </div>
                    <div class="card-body p-1">
                      <small class="d-block text-truncate" title="${name}">${name}</small>
                    </div>
                  </div>
                </div>`;
            }).join('');

            grid.querySelectorAll('._gpm-item').forEach(card => {
                card.addEventListener('click', () => this._toggle(card));
            });
        },

        _toggle(card) {
            const id    = parseInt(card.dataset.id);
            const badge = card.querySelector('.bg-primary.rounded-circle');

            if (this.singleMode) {
                // Désélectionner tout
                this.selected.clear();
                this.modal.querySelectorAll('._gpm-item').forEach(c => {
                    c.style.borderColor = 'transparent';
                    c.querySelector('.bg-primary.rounded-circle').style.opacity = '0';
                });
            }

            if (!this.singleMode && this.selected.has(id)) {
                this.selected.delete(id);
                card.style.borderColor = 'transparent';
                badge.style.opacity    = '0';
            } else {
                this.selected.set(id, { id, url: card.dataset.url, name: card.dataset.name });
                card.style.borderColor = '#0d6efd';
                badge.style.opacity    = '1';
            }
            this._updateCount();
        },

        _updateCount() {
            const n = this.selected.size;
            this.modal.querySelector('#_gpmCount').textContent = n;
            this.modal.querySelector('#_gpmCountLabel').textContent =
                this.singleMode ? (n ? ' image sélectionnée' : '') : ` photo(s) sélectionnée(s)`;
        },

        // ── Pagination ────────────────────────────────────────────────────────
        _renderPagination(data) {
            const wrap = this.modal.querySelector('#_gpmPagination');
            const list = this.modal.querySelector('#_gpmPaginationList');
            if (data.last_page <= 1) { wrap.classList.add('d-none'); return; }
            wrap.classList.remove('d-none');

            const cur  = data.current_page;
            const last = data.last_page;
            const me   = '_GalleryPickerModal';
            const lnk  = (p, lbl, dis = false, act = false) =>
                `<li class="page-item ${dis ? 'disabled' : ''} ${act ? 'active' : ''}">
                   <a class="page-link" href="#"
                      onclick="event.preventDefault(); ${me}._loadPage(${p})">${lbl}</a>
                 </li>`;

            let html = lnk(cur - 1, '<i class="fas fa-chevron-left"></i>', cur === 1);
            let s = Math.max(1, cur - 2), e = Math.min(last, s + 4);
            if (e - s < 4) s = Math.max(1, e - 4);
            if (s > 1) { html += lnk(1, '1'); if (s > 2) html += `<li class="page-item disabled"><span class="page-link">…</span></li>`; }
            for (let i = s; i <= e; i++) html += lnk(i, i, false, i === cur);
            if (e < last) { if (e < last - 1) html += `<li class="page-item disabled"><span class="page-link">…</span></li>`; html += lnk(last, last); }
            html += lnk(cur + 1, '<i class="fas fa-chevron-right"></i>', cur === last);

            list.innerHTML = html;
        },

        _renderInfo(data) {
            const s = (data.current_page - 1) * this.perPage + 1;
            const e = Math.min(data.current_page * this.perPage, data.total);
            this.modal.querySelector('#_gpmInfo').textContent = `${s}–${e} sur ${data.total} image(s)`;
        }
    };

    // Exposer pour la pagination (onclick inline)
    window._GalleryPickerModal = _GalleryPickerModal;

    // =========================================================================
    // 3. FONCTIONS GLOBALES UTILISÉES DANS LES VUES BLADE
    // =========================================================================

    /**
     * Bouton "Ajouter des photos" — sélection multiple
     */
    window.openGallerySelector = function () {
        _GalleryPickerModal.open({
            singleMode: false,
            onConfirm(photos) {
                window.GalleryMediaSelector.addPhotos(photos);
            }
        });
    };

    /**
     * Bouton "Choisir une image de couverture" — sélection unique
     */
    window.openCoverImageSelector = function () {
        _GalleryPickerModal.open({
            singleMode: true,
            onConfirm(photos) {
                if (!photos.length) return;
                const p = photos[0];
                document.getElementById('cover_image_id').value = p.id;
                document.getElementById('coverImagePreview').innerHTML =
                    `<img src="${p.url}" alt="Couverture"
                          class="img-fluid rounded shadow-sm mb-2" id="coverImageImg">`;
            }
        });
    };

    // ── Initialisation ────────────────────────────────────────────────────────
    const init = () => window.GalleryMediaSelector.init();
    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', init)
        : init();

})();