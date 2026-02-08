<section class="card shadow-aqua border-0 overflow-hidden">
    <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #38859b 0%, #49aaca 100%);">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                <i class="fas fa-lock fa-lg"></i>
            </div>
            <div>
                <h2 class="h5 mb-1 fw-bold">
                    {{ __('Mettre à jour le mot de passe') }}
                </h2>
                <p class="mb-0 small opacity-90">
                    {{ __('Renforcez la sécurité de votre compte') }}
                </p>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="alert alert-info bg-info-lighter border-info d-flex align-items-start gap-3 mb-4">
            <i class="fas fa-info-circle text-info mt-1"></i>
            <div class="flex-grow-1 small">
                {{ __('Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.') }}
            </div>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="row g-4">
            @csrf
            @method('PUT')

            <div class="col-12">
                <x-input-label for="current_password" :value="__('Mot de passe actuel')" />
                <div class="input-group">
                    <span class="input-group-text bg-primary-lighter border-primary text-primary">
                        <i class="fas fa-key"></i>
                    </span>
                    <x-text-input id="current_password" 
                                  name="current_password" 
                                  type="password" 
                                  class="form-control" 
                                  autocomplete="current-password"
                                  placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-label for="password" :value="__('Nouveau mot de passe')" />
                <div class="input-group">
                    <span class="input-group-text bg-success-lighter border-success text-success">
                        <i class="fas fa-lock"></i>
                    </span>
                    <x-text-input id="password" 
                                  name="password" 
                                  type="password" 
                                  class="form-control" 
                                  autocomplete="new-password"
                                  placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                <div class="input-group">
                    <span class="input-group-text bg-success-lighter border-success text-success">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <x-text-input id="password_confirmation" 
                                  name="password_confirmation" 
                                  type="password" 
                                  class="form-control" 
                                  autocomplete="new-password"
                                  placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-end">
                    <x-primary-button>
                        <i class="fas fa-save me-2"></i>
                        {{ __('Enregistrer') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</section>