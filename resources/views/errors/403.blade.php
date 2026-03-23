@extends('errors.layout.error')

@section('title', '403 — Accès interdit')

@section('content')

    <div class="error-icon">
        <i class="fas fa-lock"></i>
    </div>

    <p class="error-code">403</p>

    <div class="error-divider"></div>

    <h1 class="error-title">Accès interdit</h1>

    <p class="error-message">
        Vous n'avez pas les droits nécessaires pour accéder à cette page.<br>
        Si vous pensez qu'il s'agit d'une erreur, contactez un administrateur.
    </p>

    <div>
        <a href="{{ route('home') }}" class="btn-home">
            <i class="fas fa-home"></i>Retour à l'accueil
        </a>
        <a href="javascript:history.back()" class="btn-back">
            <i class="fas fa-arrow-left"></i>Page précédente
        </a>
    </div>

@endsection
