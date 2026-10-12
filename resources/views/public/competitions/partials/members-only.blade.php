{{-- Encadré « réservé aux adhérents ». Variable : $quoi (ce qui est masqué) --}}
<div class="alert alert-info d-flex align-items-start gap-2 mb-0" role="note">
    <i class="fas fa-lock mt-1" aria-hidden="true"></i>
    <div>
        {{ $quoi }} : informations réservées aux adhérents du club.
        @guest
            <a href="{{ route('login') }}" class="alert-link">Connectez-vous</a> pour les consulter.
        @else
            Votre compte n'est pas encore validé comme adhérent.
        @endguest
    </div>
</div>
