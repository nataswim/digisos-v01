// ========================================
// Protection   redéclaration
// ========================================
if (typeof window.QuillMediaManager === 'undefined') {

class QuillMediaManager {
    constructor() {
        this.quillInstances = new Map();
        this.currentQuillInstance = null;
    }

    /**
     * Enregistrer le format Table personnalisé dans Quill
     */
    registerTableBlot() {
        // Vérifier si déjà enregistré
        if (window.QuillTableBlotRegistered) return;
        
        const BlockEmbed = Quill.import('blots/block/embed');
        
        class TableBlot extends BlockEmbed {
            static create(value) {
                const node = super.create();
                node.innerHTML = value;
                node.contentEditable = 'false';
                node.style.cssText = 'width: 100%; margin: 15px 0; cursor: pointer;';
                return node;
            }
            
            static value(node) {
                return node.innerHTML;
            }
        }
        
        TableBlot.blotName = 'table';
        TableBlot.tagName = 'div';
        TableBlot.className = 'ql-table-wrapper';
        
        Quill.register(TableBlot);
        window.QuillTableBlotRegistered = true;
    }

    initQuillEditor(selector, textareaId) {
        const container = document.querySelector(selector);
        const textarea = document.getElementById(textareaId);
        
        if (!container || !textarea) return;

        // Enregistrer le format Table personnalisé AVANT d'initialiser Quill
        this.registerTableBlot();

        // Configuration de la toolbar
        const toolbarOptions = [
            // Ligne 1: Titres, Police, Taille
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }, { 'font': [] }],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            
            // Ligne 2: Styles de base
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            
            // Ligne 3: Scripts, Listes, Indentation
            [{ 'script': 'sub'}, { 'script': 'super' }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            
            // Ligne 4: Alignement, Liens, Médias
            [{ 'align': [] }],
            ['link', 'image', 'video', 'blockquote', 'code-block'],
            
            // Ligne 5: Nettoyage
            ['clean']
        ];

        const quill = new Quill(container, {
            theme: 'snow',
            placeholder: 'Rédigez votre contenu...',
            modules: {
                toolbar: {
                    container: toolbarOptions,
                    handlers: {
                        'image': () => this.showImageSelector(quill),
                        'video': () => this.handleVideoInsert(quill)
                    }
                }
            }
        });

        // Ajouter les boutons personnalisés après l'initialisation
        this.addCustomButtons(quill, container);

        // Synchroniser avec le textarea
        quill.on('text-change', () => {
            textarea.value = quill.root.innerHTML;
        });

        // Charger le contenu initial
        if (textarea.value) {
            // Préserver les tableaux en les convertissant en format personnalisé
            let content = textarea.value;
            
            // Rechercher et préserver les tableaux
            const tableRegex = /<table[^>]*>[\s\S]*?<\/table>/gi;
            const tables = content.match(tableRegex);
            
            if (tables) {
                tables.forEach((tableHtml, index) => {
                    const placeholder = `___TABLE_PLACEHOLDER_${index}___`;
                    content = content.replace(tableHtml, placeholder);
                });
                
                // Charger le contenu sans les tableaux
                quill.root.innerHTML = content;
                
                // Réinsérer les tableaux avec le format personnalisé
                tables.forEach((tableHtml, index) => {
                    const placeholder = `___TABLE_PLACEHOLDER_${index}___`;
                    const placeholderElement = Array.from(quill.root.querySelectorAll('*')).find(
                        el => el.textContent.includes(placeholder)
                    );
                    
                    if (placeholderElement) {
                        const range = quill.getSelection();
                        const text = quill.getText();
                        const placeholderIndex = text.indexOf(placeholder);
                        
                        if (placeholderIndex !== -1) {
                            quill.deleteText(placeholderIndex, placeholder.length);
                            quill.insertEmbed(placeholderIndex, 'table', tableHtml, Quill.sources.USER);
                        }
                    }
                });
            } else {
                quill.root.innerHTML = textarea.value;
            }
            
            this.makeImagesResponsive(quill);
        }

        this.quillInstances.set(textareaId, quill);
        return quill;
    }

    /**
     * Ajouter les boutons personnalisés à la toolbar
     */
    addCustomButtons(quill, container) {
        const toolbar = container.previousElementSibling;
        if (!toolbar || !toolbar.classList.contains('ql-toolbar')) return;

        // Créer un groupe de boutons personnalisés
        const customGroup = document.createElement('span');
        customGroup.className = 'ql-formats';
        
        // Bouton Tableau
        const tableBtn = document.createElement('button');
        tableBtn.type = 'button';
        tableBtn.className = 'ql-table-custom';
        tableBtn.innerHTML = '<i class="fas fa-table"></i>';
        tableBtn.title = 'Insérer un tableau';
        tableBtn.addEventListener('click', () => this.handleTableInsert(quill));
        
        // Bouton HTML
        const htmlBtn = document.createElement('button');
        htmlBtn.type = 'button';
        htmlBtn.className = 'ql-html-custom';
        htmlBtn.innerHTML = '<i class="fas fa-code"></i>';
        htmlBtn.title = 'Éditer le HTML';
        htmlBtn.addEventListener('click', () => this.handleHtmlEdit(quill, quill.root.parentElement.nextElementSibling));
        
        customGroup.appendChild(tableBtn);
        customGroup.appendChild(htmlBtn);
        
        // Ajouter avant le bouton "clean"
        const cleanGroup = toolbar.querySelector('.ql-formats:last-child');
        if (cleanGroup) {
            toolbar.insertBefore(customGroup, cleanGroup);
        } else {
            toolbar.appendChild(customGroup);
        }
    }

    makeImagesResponsive(quill) {
        const images = quill.root.querySelectorAll('img');
        images.forEach(img => {
            if (!img.classList.contains('responsive-image')) {
                this.applyResponsiveStyles(img);
                img.addEventListener('click', () => this.showImageResizeOptions(img));
            }
        });
    }

    applyResponsiveStyles(img) {
        img.style.maxWidth = '100%';
        img.style.height = 'auto';
        img.style.display = 'block';
        img.style.margin = '10px auto';
        img.style.borderRadius = '4px';
        img.style.cursor = 'pointer';
        img.classList.add('responsive-image');
    }

    /**
     * Utiliser MediaFieldSelector pour insérer des images
     */
    showImageSelector(quillInstance) {
        this.currentQuillInstance = quillInstance;
        
        if (typeof window.mediaFieldSelector === 'undefined') {
            this.showNotification('❌ Sélecteur de médias non disponible', 'error');
            return;
        }

        const originalSelectMedia = window.mediaFieldSelector.selectMedia.bind(window.mediaFieldSelector);
        
        window.mediaFieldSelector.selectMedia = (imageUrl, imageName) => {
            this.insertImageInQuill(imageUrl, imageName);
            window.mediaFieldSelector.selectMedia = originalSelectMedia;
        };
        
        window.mediaFieldSelector.openForField(null, null);
    }

    /**
     * Insérer l'image dans Quill
     */
    insertImageInQuill(imageUrl, altText = '') {
        if (!this.currentQuillInstance) return;

        const range = this.currentQuillInstance.getSelection() || { 
            index: this.currentQuillInstance.getLength() 
        };
        
        this.currentQuillInstance.insertEmbed(range.index, 'image', imageUrl);
        
        setTimeout(() => {
            const imgElement = this.currentQuillInstance.root.querySelector(`img[src="${imageUrl}"]`);
            if (imgElement) {
                imgElement.setAttribute('alt', altText || 'Image insérée');
                this.applyResponsiveStyles(imgElement);
                
                imgElement.addEventListener('click', () => {
                    this.showImageResizeOptions(imgElement);
                });
            }
        }, 100);
        
        this.currentQuillInstance.setSelection(range.index + 1);
        this.showNotification('✅ Image insérée avec succès', 'success');
    }

    /**
     * Convertir une URL YouTube/Vimeo en format embed
     */
    convertToEmbedUrl(url) {
        // YouTube - format watch
        if (url.includes('youtube.com/watch')) {
            const videoId = url.split('v=')[1];
            if (videoId) {
                const ampersandPosition = videoId.indexOf('&');
                const cleanId = ampersandPosition !== -1 ? videoId.substring(0, ampersandPosition) : videoId;
                return `https://www.youtube.com/embed/${cleanId}`;
            }
        }
        
        // YouTube - format court youtu.be
        if (url.includes('youtu.be/')) {
            const videoId = url.split('youtu.be/')[1];
            if (videoId) {
                const cleanId = videoId.split('?')[0];
                return `https://www.youtube.com/embed/${cleanId}`;
            }
        }
        
        // Vimeo
        if (url.includes('vimeo.com/')) {
            const videoId = url.split('vimeo.com/')[1];
            if (videoId) {
                const cleanId = videoId.split('?')[0];
                return `https://player.vimeo.com/video/${cleanId}`;
            }
        }
        
        // Si déjà au format embed, retourner tel quel
        if (url.includes('youtube.com/embed/') || url.includes('player.vimeo.com/video/')) {
            return url;
        }
        
        return null;
    }

    /**
     * Gestion de l'insertion de vidéo
     */
    handleVideoInsert(quillInstance) {
        const modalId = 'videoInsertModal_' + Date.now();
        const modalHtml = `
            <div class="modal fade" id="${modalId}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-video me-2"></i>Insérer une vidéo
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="videoUrl_${modalId}" class="form-label fw-semibold">URL de la vidéo</label>
                                <input type="url" 
                                       class="form-control" 
                                       id="videoUrl_${modalId}" 
                                       placeholder="https://www.youtube.com/watch?v=... ou https://vimeo.com/..."
                                       autocomplete="off">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Formats supportés : YouTube, Vimeo
                                </div>
                            </div>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-lightbulb me-2"></i>
                                <strong>Astuce :</strong> Copiez l'URL complète depuis votre navigateur
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Annuler
                            </button>
                            <button type="button" class="btn btn-primary" id="insertVideoBtn_${modalId}">
                                <i class="fas fa-check me-1"></i>Insérer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        const modalElement = document.getElementById(modalId);
        const modal = new bootstrap.Modal(modalElement);
        const videoUrlInput = document.getElementById('videoUrl_' + modalId);
        const insertBtn = document.getElementById('insertVideoBtn_' + modalId);
        
        // Nettoyer le modal après fermeture
        modalElement.addEventListener('hidden.bs.modal', () => {
            modalElement.remove();
        });
        
        insertBtn.addEventListener('click', () => {
            const url = videoUrlInput.value.trim();
            
            if (!url) {
                this.showNotification('⚠️ Veuillez saisir une URL', 'warning');
                return;
            }
            
            // Convertir l'URL en format embed
            const embedUrl = this.convertToEmbedUrl(url);
            
            if (!embedUrl) {
                this.showNotification('❌ URL non supportée. Utilisez YouTube ou Vimeo', 'error');
                return;
            }
            
            const range = quillInstance.getSelection() || { index: quillInstance.getLength() };
            quillInstance.insertEmbed(range.index, 'video', embedUrl);
            quillInstance.setSelection(range.index + 1);
            
            modal.hide();
            this.showNotification('✅ Vidéo insérée avec succès', 'success');
        });
        
        videoUrlInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                insertBtn.click();
            }
        });
        
        modal.show();
        
        modalElement.addEventListener('shown.bs.modal', () => {
            videoUrlInput.focus();
        });
    }

    /**
     * Gestion de l'insertion de tableau (modification directe du HTML)
     */
    handleTableInsert(quillInstance) {
        const modalId = 'tableInsertModal_' + Date.now();
        const modalHtml = `
            <div class="modal fade" id="${modalId}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-table me-2"></i>Insérer un tableau
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label for="tableRows_${modalId}" class="form-label fw-semibold">Lignes</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="tableRows_${modalId}" 
                                           min="1" 
                                           max="20" 
                                           value="3">
                                </div>
                                <div class="col-6">
                                    <label for="tableCols_${modalId}" class="form-label fw-semibold">Colonnes</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="tableCols_${modalId}" 
                                           min="1" 
                                           max="10" 
                                           value="3">
                                </div>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="tableHeader_${modalId}" checked>
                                <label class="form-check-label" for="tableHeader_${modalId}">
                                    Inclure une ligne d'en-tête
                                </label>
                            </div>
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-lightbulb me-2"></i>
                                <strong>Astuce :</strong> Vous pourrez modifier le contenu après création
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Annuler
                            </button>
                            <button type="button" class="btn btn-success" id="insertTableBtn_${modalId}">
                                <i class="fas fa-check me-1"></i>Créer le tableau
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        const modalElement = document.getElementById(modalId);
        const modal = new bootstrap.Modal(modalElement);
        const insertBtn = document.getElementById('insertTableBtn_' + modalId);
        
        // Nettoyer le modal après fermeture
        modalElement.addEventListener('hidden.bs.modal', () => {
            modalElement.remove();
        });
        
        insertBtn.addEventListener('click', () => {
            const rows = parseInt(document.getElementById('tableRows_' + modalId).value) || 3;
            const cols = parseInt(document.getElementById('tableCols_' + modalId).value) || 3;
            const hasHeader = document.getElementById('tableHeader_' + modalId).checked;
            
            if (rows < 1 || rows > 20 || cols < 1 || cols > 10) {
                this.showNotification('⚠️ Dimensions invalides (1-20 lignes, 1-10 colonnes)', 'warning');
                return;
            }
            
            // Créer le tableau HTML
            let tableHtml = '<table style="width:100%;border-collapse:collapse;border:1px solid #dee2e6">';
            
            // En-tête
            if (hasHeader) {
                tableHtml += '<thead><tr>';
                for (let j = 0; j < cols; j++) {
                    tableHtml += `<th style="border:1px solid #dee2e6;padding:10px;background-color:#f8f9fa;font-weight:600;text-align:left">En-tête ${j + 1}</th>`;
                }
                tableHtml += '</tr></thead>';
            }
            
            // Corps
            tableHtml += '<tbody>';
            const bodyRows = hasHeader ? rows - 1 : rows;
            for (let i = 0; i < bodyRows; i++) {
                tableHtml += '<tr>';
                for (let j = 0; j < cols; j++) {
                    tableHtml += `<td style="border:1px solid #dee2e6;padding:10px">Cellule ${i + 1}-${j + 1}</td>`;
                }
                tableHtml += '</tr>';
            }
            tableHtml += '</tbody></table>';
            
            // Obtenir la position d'insertion
            const selection = quillInstance.getSelection(true);
            const index = selection ? selection.index : quillInstance.getLength();
            
            // Insérer le tableau en utilisant le format personnalisé
            quillInstance.insertEmbed(index, 'table', tableHtml, Quill.sources.USER);
            quillInstance.insertText(index + 1, '\n', Quill.sources.USER);
            quillInstance.setSelection(index + 2, Quill.sources.SILENT);
            
            modal.hide();
            this.showNotification('✅ Tableau créé avec succès', 'success');
        });
        
        modal.show();
    }

    /**
     * Édition du code HTML
     */
    handleHtmlEdit(quillInstance, textarea) {
        const currentHtml = quillInstance.root.innerHTML;
        const modalId = 'htmlEditModal_' + Date.now();
        
        const modalHtml = `
            <div class="modal fade" id="${modalId}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-code me-2"></i>Édition HTML
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="htmlEditor_${modalId}" class="form-label fw-semibold">Code HTML</label>
                                <textarea class="form-control font-monospace" 
                                          id="htmlEditor_${modalId}" 
                                          rows="15" 
                                          style="font-size: 13px; line-height: 1.5; background-color: #1e293b; color: #e2e8f0; border: 1px solid #334155;">${this.escapeHtml(currentHtml)}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-exclamation-triangle me-1 text-warning"></i>
                                    <strong>Attention :</strong> Assurez-vous que le code HTML est valide
                                </div>
                            </div>
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-shield-alt me-2"></i>
                                <strong>Sécurité :</strong> Évitez d'insérer du code malveillant (scripts, iframes non sécurisés)
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Annuler
                            </button>
                            <button type="button" class="btn btn-dark" id="applyHtmlBtn_${modalId}">
                                <i class="fas fa-check me-1"></i>Appliquer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        const modalElement = document.getElementById(modalId);
        const modal = new bootstrap.Modal(modalElement);
        const htmlEditor = document.getElementById('htmlEditor_' + modalId);
        const applyBtn = document.getElementById('applyHtmlBtn_' + modalId);
        
        // Nettoyer le modal après fermeture
        modalElement.addEventListener('hidden.bs.modal', () => {
            modalElement.remove();
        });
        
        applyBtn.addEventListener('click', () => {
            const newHtml = htmlEditor.value;
            
            if (!newHtml.trim()) {
                this.showNotification('⚠️ Le contenu HTML est vide', 'warning');
                return;
            }
            
            quillInstance.root.innerHTML = newHtml;
            if (textarea) {
                textarea.value = newHtml;
            }
            
            this.makeImagesResponsive(quillInstance);
            
            modal.hide();
            this.showNotification('✅ HTML mis à jour avec succès', 'success');
        });
        
        modal.show();
    }

    /**
     * Échapper le HTML pour affichage dans textarea
     */
    escapeHtml(html) {
        const div = document.createElement('div');
        div.textContent = html;
        return div.innerHTML;
    }

    /**
     * Options de redimensionnement au clic sur l'image
     */
    showImageResizeOptions(imgElement) {
        document.querySelectorAll('.image-resize-control').forEach(el => el.remove());
        
        const resizeControl = document.createElement('div');
        resizeControl.className = 'image-resize-control';
        resizeControl.style.cssText = `
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        `;
        
        const sizes = {
            'Petite': '40%',
            'Moyenne': '60%',
            'Grande': '80%',
            'Pleine': '100%'
        };
        
        Object.entries(sizes).forEach(([label, width]) => {
            const btn = document.createElement('button');
            btn.textContent = label;
            btn.style.cssText = `
                padding: 4px 8px;
                border: 1px solid #ddd;
                background: #f8f9fa;
                cursor: pointer;
                font-size: 11px;
                border-radius: 2px;
            `;
            btn.addEventListener('click', () => {
                imgElement.style.width = width;
                imgElement.style.maxWidth = width;
                resizeControl.remove();
            });
            resizeControl.appendChild(btn);
        });
        
        const rect = imgElement.getBoundingClientRect();
        resizeControl.style.left = rect.left + 'px';
        resizeControl.style.top = (rect.bottom + 5) + 'px';
        document.body.appendChild(resizeControl);
        
        setTimeout(() => {
            const removeControl = (e) => {
                if (!resizeControl.contains(e.target) && e.target !== imgElement) {
                    resizeControl.remove();
                    document.removeEventListener('click', removeControl);
                }
            };
            document.addEventListener('click', removeControl);
        }, 100);
    }

    showNotification(message, type = 'info') {
        const existing = document.querySelectorAll('.quill-notification');
        existing.forEach(el => el.remove());
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#0ea5e9'
        };
        
        const notification = document.createElement('div');
        notification.className = `quill-notification quill-notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 90px;
            right: 20px;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 999999;
            font-size: 14px;
            font-weight: 500;
            min-width: 300px;
            max-width: 500px;
            background: white;
            border-left: 4px solid ${colors[type]};
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            transition: all 0.3s ease;
            animation: slideInRight 0.3s ease;
        `;
        
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas ${icons[type]}" style="color: ${colors[type]}; font-size: 20px;"></i>
                <div style="flex: 1; color: #1f2937;">${message}</div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(function() {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
        
        notification.style.cursor = 'pointer';
        notification.addEventListener('click', function() {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => notification.remove(), 300);
        });
    }
}

// ========================================
// Enregistrement global
// ========================================
window.QuillMediaManager = QuillMediaManager;

if (!window.quillManager) {
    window.quillManager = new QuillMediaManager();
}

} // Fin de la protection

// ========================================
// Fonctions publiques
// ========================================
function initQuillEditor(selector, textareaId) {
    return window.quillManager.initQuillEditor(selector, textareaId);
}

// ========================================
// Animation CSS (une seule fois)
// ========================================
if (!document.getElementById('quill-advanced-styles')) {
    const styleElement = document.createElement('style');
    styleElement.id = 'quill-advanced-styles';
    styleElement.textContent = `
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(400px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Styles pour les boutons personnalisés */
        .ql-table-custom,
        .ql-html-custom {
            width: 28px;
            height: 24px;
            padding: 3px 5px;
            font-size: 14px;
        }
        
        .ql-table-custom:hover,
        .ql-html-custom:hover {
            color: #06c;
        }
        
        /* Styles pour le wrapper de table */
        .ql-table-wrapper {
            margin: 15px 0;
            padding: 0;
            overflow-x: auto;
        }
        
        .ql-table-wrapper table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .ql-table-wrapper table td,
        .ql-table-wrapper table th {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        
        .ql-table-wrapper table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
    `;
    document.head.appendChild(styleElement);
}