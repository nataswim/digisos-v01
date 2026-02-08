<section class="card shadow-aqua border-0 overflow-hidden">
    <div class="card-header bg-danger text-white py-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                <i class="fas fa-exclamation-triangle fa-lg"></i>
            </div>
            <div>
                <h2 class="h5 mb-1 fw-bold">
                    {{ __('Supprimer le compte') }}
                </h2>
                <p class="mb-0 small opacity-90">
                    {{ __('Action irréversible - Toutes vos données seront définitivement effacées') }}
                </p>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="alert alert-warning bg-warning-lighter border-warning d-flex align-items-start gap-3 mb-4">
            <i class="fas fa-info-circle text-warning mt-1"></i>
            <div class="flex-grow-1 small">
                {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.') }}
            </div>
        </div>

        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="w-100 w-md-auto">
            <i class="fas fa-trash-alt me-2"></i>
            {{ __('Supprimer le compte') }}
        </x-danger-button>

        <x-modal name="confirm-user-deletion" focusable>
            <form method="POST" action="{{ route('profile.destroy') }}" class="p-0">
                @csrf
                @method('DELETE')

                <div class="modal-header bg-danger text-white border-0 py-4">
                    <div class="d-flex align-items-center gap-3 w-100">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                        <div>
                            <h2 class="h5 mb-0 fw-bold">
                                {{ __('Êtes-vous sûr de vouloir supprimer votre compte ?') }}
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="modal-body p-4">
                    <div class="alert alert-danger bg-danger-lighter border-danger d-flex align-items-start gap-3 mb-4">
                        <i class="fas fa-exclamation-circle text-danger mt-1"></i>
                        <div class="flex-grow-1 small">
                            {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez entrer votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.') }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-input-label for="password" value="{{ __('Mot de passe') }}" />
                        <x-text-input
                            id="password"
                            name="password"
                            type="password"
                            class="w-100"
                            placeholder="{{ __('Entrez votre mot de passe pour confirmer') }}"
                        />
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light p-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        <i class="fas fa-times me-2"></i>
                        {{ __('Annuler') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        <i class="fas fa-trash-alt me-2"></i>
                        {{ __('Supprimer le compte') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
</section>