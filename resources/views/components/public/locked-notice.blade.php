{{--
    Message affiché à la place d'un contenu réservé (fiche ou page).

    Exemple :
        <x-public.locked-notice title="Fiche réservée aux adhérents"
                                :message="$fiche->getAccessMessage(auth()->user())" />

    Trois situations :
        - personne non connectée  → créer un compte ou se connecter
        - compte « visiteur »     → comment adhérer au club, ou faire relier son compte à son adhésion
        - autre compte            → contacter le club
--}}
@props([
    'title'   => 'Contenu réservé aux adhérents',
    'message' => null,
])

<div class="alert alert-warning border-0 mb-0" role="note">
    <div class="d-flex align-items-start gap-3">
        <i class="fas fa-lock text-warning fs-2" aria-hidden="true"></i>
        <div class="flex-grow-1">
            <h2 class="h5 alert-heading mb-2">{{ $title }}</h2>

            @if ($message)
                <p class="mb-3">{{ $message }}</p>
            @endif

            @guest
                <p class="mb-3">Connectez-vous, ou créez votre compte gratuit sur le site, pour y accéder.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('login') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-sign-in-alt me-2" aria-hidden="true"></i>Me connecter
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-user-plus me-2" aria-hidden="true"></i>Créer mon compte
                    </a>
                </div>
            @else
                @if (auth()->user()->hasRole('visitor'))
                    <p class="mb-3">
                        Ce contenu est réservé aux adhérents du club. Déjà adhérent ? Écrivez-nous pour que votre
                        compte soit relié à votre adhésion.
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('contact') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-envelope me-2" aria-hidden="true"></i>Contacter le club
                        </a>
                        <a href="{{ route('pricing') }}" class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-clipboard-check me-2" aria-hidden="true"></i>Adhérer au club
                        </a>
                    </div>
                @else
                    <a href="{{ route('contact') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-envelope me-2" aria-hidden="true"></i>Contacter le club
                    </a>
                @endif
            @endguest
        </div>
    </div>
</div>
