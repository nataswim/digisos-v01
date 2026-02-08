<section class="card shadow-aqua border-0 overflow-hidden">
    <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #38859b 0%, #49aaca 100%);">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                <i class="fas fa-user-circle fa-lg"></i>
            </div>
            <div>
                <h2 class="h5 mb-1 fw-bold">
                    {{ __('Informations du profil') }}
                </h2>
                <p class="mb-0 small opacity-90">
                    {{ __('Gérez vos informations personnelles') }}
                </p>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <form method="POST" action="{{ route('profile.update') }}" class="row g-4">
            @csrf
            @method('PATCH')

            <div class="col-12 col-md-6">
                <x-input-label for="name" :value="__('Nom')" />
                <div class="input-group">
                    <span class="input-group-text bg-primary-lighter border-primary text-primary">
                        <i class="fas fa-user"></i>
                    </span>
                    <x-text-input id="name" 
                                  name="name" 
                                  type="text" 
                                  class="form-control"
                                  :value="old('name', $user->name)" 
                                  required 
                                  autofocus 
                                  autocomplete="name"
                                  placeholder="Votre nom complet" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-label for="email" :value="__('Email')" />
                <div class="input-group">
                    <span class="input-group-text bg-info-lighter border-info text-info">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <x-text-input id="email" 
                                  name="email" 
                                  type="email" 
                                  class="form-control"
                                  :value="old('email', $user->email)" 
                                  required 
                                  autocomplete="username"
                                  placeholder="votre@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert alert-warning bg-warning-lighter border-warning d-flex align-items-start gap-3 mt-3">
                        <i class="fas fa-exclamation-triangle text-warning mt-1"></i>
                        <div class="flex-grow-1 small">
                            <p class="mb-2">
                                {{ __('Votre adresse e-mail n\'est pas vérifiée.') }}
                            </p>
                            <button form="send-verification" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-paper-plane me-1"></i>
                                {{ __('Cliquez ici pour renvoyer l\'e-mail de vérification.') }}
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success bg-success-lighter border-success d-flex align-items-center gap-3 mt-3">
                            <i class="fas fa-check-circle text-success"></i>
                            <div class="flex-grow-1 small">
                                {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                            </div>
                        </div>
                    @endif
                @endif
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