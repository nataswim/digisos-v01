@csrf

<div class="row g-4">
    <!-- Contenu principal -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-key me-2"></i>Informations de la permission
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Nom de la permission -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nom de la permission *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', isset($permission) ? $permission->name : '') }}"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           placeholder="Ex: posts.create, users.edit, admin.access"
                           required>
                    <div class="form-text">Utiliser la notation en points pour organiser les permissions (module.action)</div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">Slug technique</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">permission:</span>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               value="{{ old('slug', isset($permission) ? $permission->slug : '') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="posts-create">
                    </div>
                    <div class="form-text">Identifiant technique unique (generation automatique)</div>
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
                              placeholder="Description detaillee de ce que permet cette permission...">{{ old('description', isset($permission) ? $permission->description : '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Exemples d'utilisation -->
                <div class="border-top pt-4">
                    <h6 class="fw-semibold mb-3 text-secondary">
                        <i class="fas fa-lightbulb me-2"></i>Exemples d'utilisation
                    </h6>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <h6 class="small fw-semibold text-primary mb-2">Gestion des articles</h6>
                                    <ul class="small mb-0">
                                        <li><code>posts.create</code> - Creer des articles</li>
                                        <li><code>posts.edit</code> - Modifier les articles</li>
                                        <li><code>posts.delete</code> - Supprimer les articles</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <h6 class="small fw-semibold text-success mb-2">Administration</h6>
                                    <ul class="small mb-0">
                                        <li><code>admin.access</code> - Acces au panel admin</li>
                                        <li><code>users.manage</code> - Gerer les utilisateurs</li>
                                        <li><code>settings.edit</code> - Modifier les parametres</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Groupe et categorisation -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-layer-group me-2"></i>Categorisation
                </h6>
            </div>
            <div class="card-body p-4">
                <label for="group" class="form-label fw-semibold">Groupe</label>
                <input type="text" 
                       name="group" 
                       id="group" 
                       value="{{ old('group', isset($permission) ? $permission->group : '') }}"
                       class="form-control @error('group') is-invalid @enderror"
                       placeholder="Ex: posts, users, admin">
                <div class="form-text">Groupe logique pour organiser les permissions</div>
                @error('group')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- Suggestions de groupes existants -->
                @php
                    $existingGroups = \App\Models\Permission::whereNotNull('group')
                        ->distinct()
                        ->pluck('group')
                        ->take(8);
                @endphp
                
                @if($existingGroups->count() > 0)
                    <div class="mt-3">
                        <small class="text-muted d-block mb-2">Groupes existants :</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($existingGroups as $group)
                                <button type="button" 
                                        class="badge bg-info-subtle text-info border-0" 
                                        onclick="document.getElementById('group').value='{{ $group }}'">
                                    {{ $group }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistiques (en edition) -->
        @if(isset($permission))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white p-4">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Utilisation
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-primary mb-1">{{ $permission->roles()->count() }}</h4>
                                <small class="text-muted">Rôles</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <h4 class="fw-bold text-success mb-1">{{ $permission->roles()->withCount('users')->get()->sum('users_count') }}</h4>
                                <small class="text-muted">Utilisateurs</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($permission->roles()->count() > 0)
                        <div class="mt-3 pt-3 border-top">
                            <small class="text-muted d-block mb-2">Assignee aux rôles :</small>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($permission->roles()->limit(5)->get() as $role)
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $role->display_name ?? $role->name }}
                                    </span>
                                @endforeach
                                @if($permission->roles()->count() > 5)
                                    <span class="badge bg-light text-dark">
                                        +{{ $permission->roles()->count() - 5 }} autres
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Guide de bonnes pratiques -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4">
                <h6 class="mb-0">
                    <i class="fas fa-book me-2"></i>Bonnes pratiques
                </h6>
            </div>
            <div class="card-body p-4">
                <ul class="small mb-0">
                    <li class="mb-2">Utilisez une nomenclature coherente (module.action)</li>
                    <li class="mb-2">Soyez specifique dans les descriptions</li>
                    <li class="mb-2">Groupez les permissions par module</li>
                    <li class="mb-2">evitez les permissions trop generiques</li>
                    <li class="mb-0">Testez les permissions apres creation</li>
                </ul>
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
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour A la liste
                    </a>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>{{ $submitLabel ?? 'Enregistrer' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>