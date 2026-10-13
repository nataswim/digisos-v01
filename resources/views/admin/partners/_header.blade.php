{{--
    En-tête commun du module Partenaires (titre + erreurs de formulaire).
    Les messages de succès sont affichés par layouts.admin / layouts.editor.
    Variables : $area ('admin' ou 'editor')
--}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-handshake text-primary me-2" aria-hidden="true"></i>Partenaires du club</h1>
    <a href="{{ route('partners.index') }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
        <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Voir la page publique
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <strong>Le formulaire contient des erreurs :</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $erreur)
                <li>{{ $erreur }}</li>
            @endforeach
        </ul>
    </div>
@endif
