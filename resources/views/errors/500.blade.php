@extends('errors.layout.error')

@section('title', '500 — Erreur serveur')

@section('content')

    <div class="error-icon">
        <i class="fas fa-exclamation-triangle"></i>
    </div>

    <p class="error-code">500</p>

    <div class="error-divider"></div>

    <h1 class="error-title">Erreur serveur</h1>

    <p class="error-message">
        Une erreur inattendue s'est produite sur le serveur.<br>
        Notre équipe a été notifiée. Veuillez réessayer dans quelques instants.
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
