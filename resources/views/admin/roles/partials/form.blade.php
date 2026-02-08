@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-user-shield me-2"></i>Informations du rôle
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Nom du rôle -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nom du rôle (technique) *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', isset($role) ? $role->name : '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex: editor, moderator, contributor"
                           required>
                    <div class="form-text">Nom technique du rôle (lettres minuscules, underscores autorises)</div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nom d'affichage -->
                <div class="mb-4">
                    <label for="display_name" class="form-label fw-semibold">Nom d'affichage</label>
                    <input type="text" 
                           name="display_name" 
                           id="display_name" 
                           value="{{ old('display_name', isset($role) ? $role->display_name : '') }}"
                           class="form-control form-control-lg @error('display_name') is-invalid @enderror"
                           placeholder="Ex: editeur, Moderateur, Contributeur">
                    <div class="form-text">Nom affiche aux utilisateurs</div>
                    @error('display_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">Slug</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">{{ url('/roles') }}/</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', isset($role) ? $role->slug : '') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="editor-role">
                    </div>
                    <div class="form-text">Laisser vide pour generation automatique</div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Description detaillee du rôle et de ses responsabilites...">{{ old('description', isset($role) ? $role->description : '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Permissions -->
                <div class="border-top pt-4">
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="fas fa-key me-2"></i>Permissions
                    </h6>
                    
                    @php
                        // Recuperation securisee des permissions
                        try {
                            $allPermissions = \App\Models\Permission::orderBy('group')->orderBy('name')->get();
                            $permissionsExist = $allPermissions->count() > 0;
                            
                            // Recuperation des permissions actuelles du rôle
                            $currentPermissions = [];
                            if (isset($role) && $role && method_exists($role, 'permissions')) {
                                try {
                                    $currentPermissions = $role->permissions()->pluck('permissions.id')->toArray();
                                } catch (\Exception $e) {
                                    $currentPermissions = [];
                                }
                            }
                        } catch (\Exception $e) {
                            $allPermissions = collect();
                            $permissionsExist = false;
                            $currentPermissions = [];
                        }
                    @endphp

                    @if($permissionsExist)
                        <div class="row g-3">
                            @php
                                $groupedPermissions = [];
                                foreach ($allPermissions as $permission) {
                                    $group = $permission->group ?? 'general';
                                    if (!isset($groupedPermissions[$group])) {
                                        $groupedPermissions[$group] = [];
                                    }
                                    $groupedPermissions[$group][] = $permission;
                                }
                            @endphp

                            @foreach($groupedPermissions as $groupName => $permissions)
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-header bg-light p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-0 text-capitalize">
                                                    <i class="fas fa-folder me-2 text-white"></i>
                                                    {{ str_replace(['_', '-'], ' ', $groupName) }}
                                                </h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           id="toggle_{!! Str::slug($groupName) !!}"
                                                           onchange="toggleGroup('{!! Str::slug($groupName) !!}')">
                                                    <label class="form-check-label small" for="toggle_{!! Str::slug($groupName) !!}">
                                                        Tout
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-3" style="max-height: 300px; overflow-y: auto;">
                                            @foreach($permissions as $permission)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input permission-checkbox" 
                                                           type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}" 
                                                           id="permission_{{ $permission->id }}"
                                                           data-group="{!! Str::slug($groupName) !!}"
                                                           {{ in_array($permission->id, old('permissions', $currentPermissions)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                        <strong class="d-block">{{ $permission->name }}</strong>
                                                        @if($permission->description)
                                                            <small class="text-muted">{!! Str::limit($permission->description, 60) !!}</small>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @error('permissions')
                            <div class="text-danger mt-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <!-- Resume des permissions selectionnees -->
                        <div class="mt-3">
                            <div class="alert alert-light border">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-water text-primary me-2"></i>
                                    <span id="permissions-summary">
                                        <span id="selected-count">0</span> permission(s) selectionnee(s)
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Aucune permission disponible</strong><br>
                            Aucune permission n'a ete creee dans le systeme. 
                            <a href="{{ route('admin.permissions.create') }}" class="alert-link">Creer des permissions</a> d'abord.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Parametres du rôle -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-cog me-2"></i>Parametres
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="level" class="form-label fw-semibold">Niveau d'autorite</label>
                    <input type="number" 
                           name="level" 
                           id="level" 
                           value="{{ old('level', isset($role) ? $role->level : 1) }}"
                           class="form-control @error('level') is-invalid @enderror"
                           min="1" 
                           max="100"
                           placeholder="1">
                    <div class="form-text">Plus le niveau est eleve, plus les permissions sont importantes (1-100)</div>
                    @error('level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="is_default" 
                           id="is_default" 
                           value="1"
                           {{ old('is_default', isset($role) ? $role->is_default : false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_default">
                        <i class="fas fa-star text-warning me-1"></i>
                        Rôle par defaut
                    </label>
                    <div class="form-text">Attribue automatiquement aux nouveaux utilisateurs</div>
                </div>
            </div>
        </div>

        <!-- Statistiques (en edition) -->
        @if(isset($role) && $role->exists)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white text-primary p-4">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $role->users()->count() }}</h4>
                                <small class="text-muted">Utilisateurs</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $role->permissions()->count() }}</h4>
                                <small class="text-muted">Permissions</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($role->users()->count() > 0)
                        <div class="mt-3">
                            <a href="{{ route('admin.users.index', ['role' => $role->id]) }}" 
                               class="btn btn-sm btn-outline-info w-100">
                                <i class="fas fa-users me-2"></i>Voir les utilisateurs
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Aperçu des permissions selectionnees -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white text-primary p-4">
                <h6 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Permissions selectionnees
                </h6>
            </div>
            <div class="card-body p-4">
                <div id="selected-permissions" class="text-center text-muted">
                    <small>Aucune permission selectionnee</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                    </a>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>






.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}

.permission-checkbox:checked + label {
    background-color: #f0f9ff;
    border-radius: 0.25rem;
    padding: 0.25rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le compteur
    updatePermissionsCount();
    updateSelectedPermissions();
    
    // Auto-generation du slug et display_name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const displayNameInput = document.getElementById('display_name');
    
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            if (slugInput && (!slugInput.value || slugInput.dataset.autoGenerated)) {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
                slugInput.dataset.autoGenerated = 'true';
            }
            
            if (displayNameInput && (!displayNameInput.value || displayNameInput.dataset.autoGenerated)) {
                const displayName = this.value
                    .split(/[-_]/)
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
                displayNameInput.value = displayName;
                displayNameInput.dataset.autoGenerated = 'true';
            }
        });
    }
    
    if (slugInput) {
        slugInput.addEventListener('input', function() {
            this.dataset.autoGenerated = '';
        });
    }
    
    if (displayNameInput) {
        displayNameInput.addEventListener('input', function() {
            this.dataset.autoGenerated = '';
        });
    }
});

function toggleGroup(group) {
    const checkbox = document.getElementById('toggle_' + group);
    const groupCheckboxes = document.querySelectorAll(`input[data-group="${group}"]`);
    
    if (checkbox && groupCheckboxes.length > 0) {
        groupCheckboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
        updatePermissionsCount();
        updateSelectedPermissions();
    }
}

function updatePermissionsCount() {
    const selectedCheckboxes = document.querySelectorAll('input[name="permissions[]"]:checked');
    const countElement = document.getElementById('selected-count');
    if (countElement) {
        countElement.textContent = selectedCheckboxes.length;
    }
}

function updateSelectedPermissions() {
    const selectedCheckboxes = document.querySelectorAll('input[name="permissions[]"]:checked');
    const container = document.getElementById('selected-permissions');
    
    if (!container) return;
    
    if (selectedCheckboxes.length === 0) {
        container.innerHTML = '<small class="text-muted">Aucune permission selectionnee</small>';
    } else {
        let html = `<div class="fw-bold text-primary mb-2">${selectedCheckboxes.length} permission(s)</div>`;
        const badges = Array.from(selectedCheckboxes).slice(0, 8).map(cb => {
            const label = cb.nextElementSibling?.querySelector('strong')?.textContent || 'Permission';
            return `<span class="badge bg-primary-subtle text-primary me-1 mb-1 small">${label}</span>`;
        }).join('');
        
        html += `<div>${badges}</div>`;
        
        if (selectedCheckboxes.length > 8) {
            html += `<small class="text-muted">et ${selectedCheckboxes.length - 8} autres...</small>`;
        }
        
        container.innerHTML = html;
    }
}

// ecouter les changements sur les checkboxes de permissions
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('permission-checkbox')) {
        updatePermissionsCount();
        updateSelectedPermissions();
    }
});
</script>
@endpush