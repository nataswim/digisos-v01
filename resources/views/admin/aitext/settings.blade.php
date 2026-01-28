@extends('layouts.admin')

@section('title', 'Configuration AI Text Optimizer')
@section('page-title', 'AI Text Optimizer')
@section('page-description', 'Configuration de l\'intelligence artificielle pour l\'optimisation de contenu')

@section('content')
<div class="container-fluid">
    <form id="aitext-config-form">
        @csrf
        
        <!-- Sélection du fournisseur -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white p-4">
                <h5 class="mb-0">
                    <i class="fas fa-robot me-2"></i>Sélectionnez votre fournisseur IA
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    @foreach($providers as $key => $provider)
                    <div class="col-md-6 col-lg-4">
                        <div class="provider-card {{ $currentProvider === $key ? 'active' : '' }}" 
                             data-provider="{{ $key }}"
                             onclick="selectProvider('{{ $key }}')">
                            <input type="radio" 
                                   name="provider" 
                                   id="provider-{{ $key }}" 
                                   value="{{ $key }}"
                                   {{ $currentProvider === $key ? 'checked' : '' }}
                                   class="d-none">
                            
                            <div class="provider-icon">{{ $provider['icon'] }}</div>
                            
                            <h6 class="provider-name mb-2">{{ $provider['name'] }}</h6>
                            
                            <p class="provider-description text-muted small mb-3">
                                {{ $provider['description'] }}
                            </p>
                            
                            <div class="provider-status">
                                @if($provider['status'] === 'free')
                                    <span class="badge bg-success-subtle text-success">Gratuit</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">Payant</span>
                                @endif
                                
                                @if($provider['has_api_key'])
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i class="fas fa-check-circle"></i> Configuré
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="fas fa-times-circle"></i> Non configuré
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Configuration du provider sélectionné -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-cog me-2"></i>Configuration : 
                            <span id="config-provider-name">{{ $providers[$currentProvider]['name'] }}</span>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Clé API -->
                        <div class="mb-4">
                            <label for="api_key" class="form-label fw-semibold">
                                Clé API *
                                <a href="#" id="api-key-help-link" class="text-decoration-none ms-2" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> Obtenir une clé
                                </a>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-key" id="api-key-icon"></i>
                                </span>
                                <input type="password" 
                                       id="api_key" 
                                       name="api_key"
                                       class="form-control" 
                                       value=""
                                       placeholder="Saisissez votre clé API"
                                       required>
                                <button type="button" class="btn btn-outline-secondary" onclick="toggleApiKeyVisibility()">
                                    <i class="fas fa-eye" id="toggle-icon"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                Votre clé API sera stockée de manière sécurisée dans la base de données
                            </div>
                        </div>

                        <!-- Modèle -->
                        <div class="mb-4">
                            <label for="model" class="form-label fw-semibold">Modèle IA *</label>
                            <select id="model" name="model" class="form-select" required>
                                <!-- Options chargées dynamiquement -->
                            </select>
                            <div class="form-text">
                                Choisissez le modèle à utiliser
                            </div>
                        </div>

                        <!-- Paramètres avancés -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="temperature" class="form-label fw-semibold">Température</label>
                                <input type="number" 
                                       id="temperature" 
                                       name="temperature"
                                       class="form-control" 
                                       value="{{ $currentConfig['temperature'] }}"
                                       min="0" 
                                       max="1" 
                                       step="0.1"
                                       required>
                                <div class="form-text">Créativité (0 = conservateur, 1 = créatif)</div>
                            </div>
                            <div class="col-md-6">
                                <label for="max_tokens" class="form-label fw-semibold">Tokens maximum</label>
                                <input type="number" 
                                       id="max_tokens" 
                                       name="max_tokens"
                                       class="form-control" 
                                       value="{{ $currentConfig['max_tokens'] }}"
                                       min="100" 
                                       max="131072"
                                       required>
                                <div class="form-text">Longueur maximale de la réponse</div>
                            </div>
                        </div>

                        <!-- Bouton de sauvegarde -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Enregistrer la configuration
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Test de connexion -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-vial me-2"></i>Test de connexion
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <button type="button" 
                                id="test-connection-btn" 
                                class="btn btn-success w-100 mb-3">
                            <i class="fas fa-plug me-2"></i>Tester la connexion API
                        </button>

                        <div id="test-result" class="d-none"></div>
                    </div>
                </div>

                <!-- Statut -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-white p-4">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Statut actuel
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span class="text-muted">Provider</span>
                            <strong id="status-provider">{{ ucfirst($currentProvider) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span class="text-muted">Modèle</span>
                            <strong class="small" id="status-model">{{ $currentConfig['model'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span class="text-muted">Clé API</span>
                            <span id="status-key-badge">
                                @if($providers[$currentProvider]['has_api_key'])
                                    <span class="badge bg-success">Configurée</span>
                                @else
                                    <span class="badge bg-danger">Non configurée</span>
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Accès</span>
                            <span class="badge bg-primary">Admin uniquement</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>



.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}

/* Cartes de providers */
.provider-card {
    position: relative;
    padding: 24px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    height: 100%;
}

.provider-card:hover {
    border-color: #0ea5e9;
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(14, 165, 233, 0.2);
}

.provider-card.active {
    border-color: #0ea5e9;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    box-shadow: 0 10px 30px rgba(14, 165, 233, 0.3);
}

.provider-card.active::before {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 12px;
    right: 12px;
    width: 28px;
    height: 28px;
    background: #0ea5e9;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.provider-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.provider-name {
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.provider-description {
    margin-bottom: 12px;
}

.provider-status {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}
</style>
@endpush

@push('scripts')
<script>
// Données des providers et modèles
const providersData = @json($providers);
const modelsData = @json($models);
const currentProvider = '{{ $currentProvider }}';

// Liens d'aide pour obtenir les clés API
const apiKeyHelpLinks = {
    gemini: 'https://makersuite.google.com/app/apikey',
    groq: 'https://console.groq.com/keys',
    openai: 'https://platform.openai.com/api-keys',
    cohere: 'https://dashboard.cohere.ai/api-keys',
    huggingface: 'https://huggingface.co/settings/tokens'
};

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    loadProviderConfig(currentProvider);
});

// Sélectionner un provider
function selectProvider(provider) {
    // Mettre à jour l'UI
    document.querySelectorAll('.provider-card').forEach(card => {
        card.classList.remove('active');
    });
    document.querySelector(`.provider-card[data-provider="${provider}"]`).classList.add('active');
    
    // Cocher le radio button
    document.getElementById(`provider-${provider}`).checked = true;
    
    // Charger la configuration du provider
    loadProviderConfig(provider);
}

// Charger la configuration d'un provider
function loadProviderConfig(provider) {
    const providerData = providersData[provider];
    
    // Mettre à jour le nom du provider
    document.getElementById('config-provider-name').textContent = providerData.name;
    
    // Mettre à jour le lien d'aide
    document.getElementById('api-key-help-link').href = apiKeyHelpLinks[provider];
    
    // Charger la clé API
    document.getElementById('api_key').value = providerData.api_key || '';
    
    // Mettre à jour l'icône de la clé
    const apiKeyIcon = document.getElementById('api-key-icon');
    if (providerData.has_api_key) {
        apiKeyIcon.className = 'fas fa-check-circle text-success';
    } else {
        apiKeyIcon.className = 'fas fa-exclamation-triangle text-warning';
    }
    
    // Charger les modèles
    loadModels(provider);
    
    // Mettre à jour le statut
    document.getElementById('status-provider').textContent = providerData.name;
}

// Charger les modèles d'un provider
function loadModels(provider) {
    const modelSelect = document.getElementById('model');
    modelSelect.innerHTML = '';
    
    if (modelsData[provider]) {
        Object.entries(modelsData[provider]).forEach(([value, label]) => {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = label;
            modelSelect.appendChild(option);
        });
        
        // Sélectionner le modèle actuel si disponible
        if (provider === currentProvider) {
            modelSelect.value = '{{ $currentConfig['model'] }}';
        }
    }
    
    // Mettre à jour le statut
    document.getElementById('status-model').textContent = modelSelect.value;
}

// Afficher/Masquer la clé API
function toggleApiKeyVisibility() {
    const apiKeyInput = document.getElementById('api_key');
    const toggleIcon = document.getElementById('toggle-icon');
    
    if (apiKeyInput.type === 'password') {
        apiKeyInput.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        apiKeyInput.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

// Soumission du formulaire
document.getElementById('aitext-config-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
    
    fetch('{{ route('admin.aitext.save') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Configuration enregistrée avec succès !', 'success');
            
            // Mettre à jour le statut
            const selectedProvider = document.querySelector('input[name="provider"]:checked').value;
            providersData[selectedProvider].has_api_key = true;
            providersData[selectedProvider].api_key = document.getElementById('api_key').value;
            
            // Mettre à jour l'icône de la carte
            const card = document.querySelector(`.provider-card[data-provider="${selectedProvider}"]`);
            const statusBadge = card.querySelector('.badge.bg-secondary-subtle');
            if (statusBadge) {
                statusBadge.className = 'badge bg-primary-subtle text-primary';
                statusBadge.innerHTML = '<i class="fas fa-check-circle"></i> Configuré';
            }
            
            // Mettre à jour le badge de statut
            document.getElementById('status-key-badge').innerHTML = '<span class="badge bg-success">Configurée</span>';
            document.getElementById('status-model').textContent = document.getElementById('model').value;
            
            // Activer le bouton de test
            document.getElementById('test-connection-btn').disabled = false;
        } else {
            let errorMsg = 'Erreur lors de l\'enregistrement';
            if (data.errors) {
                errorMsg = Object.values(data.errors).flat().join('<br>');
            }
            showNotification(errorMsg, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur de connexion au serveur', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Test de connexion
document.getElementById('test-connection-btn').addEventListener('click', function() {
    const testBtn = this;
    const testResult = document.getElementById('test-result');
    
    testBtn.disabled = true;
    testBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Test en cours...';
    testResult.classList.add('d-none');

    fetch('{{ route('admin.aitext.test') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        testResult.classList.remove('d-none');
        
        if (data.success) {
            testResult.innerHTML = `
                <div class="alert alert-success border-0 mb-0">
                    <h6 class="alert-heading">
                        <i class="fas fa-check-circle me-2"></i>Connexion réussie !
                    </h6>
                    <hr>
                    <p class="mb-2"><strong>Provider :</strong> ${data.provider}</p>
                    <p class="mb-2"><strong>Modèle :</strong> ${data.model}</p>
                    <p class="mb-0 small text-muted">${data.message}</p>
                </div>
            `;
        } else {
            testResult.innerHTML = `
                <div class="alert alert-danger border-0 mb-0">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-circle me-2"></i>Erreur de connexion
                    </h6>
                    <hr>
                    <p class="mb-0 small">${data.error || data.message}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        testResult.classList.remove('d-none');
        testResult.innerHTML = `
            <div class="alert alert-danger border-0 mb-0">
                <i class="fas fa-times-circle me-2"></i>
                Erreur réseau : ${error.message}
            </div>
        `;
    })
    .finally(() => {
        testBtn.disabled = false;
        testBtn.innerHTML = '<i class="fas fa-plug me-2"></i>Tester la connexion API';
    });
});

// Système de notifications
function showNotification(message, type = 'info') {
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#0ea5e9'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const notification = document.createElement('div');
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
    `;
    
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas ${icons[type]}" style="color: ${colors[type]}; font-size: 20px;"></i>
            <div style="flex: 1; color: #1f2937;">${message}</div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(400px)';
        notification.style.transition = 'all 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, type === 'error' ? 8000 : 4000);
}
</script>
@endpush
@endsection