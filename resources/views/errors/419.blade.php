@extends('errors.layout.error')

@section('title', '419 — Session expirée')

@section('content')

    <div class="error-icon">
        <i class="fas fa-clock"></i>
    </div>

    <p class="error-code">419</p>

    <div class="error-divider"></div>

    <h1 class="error-title">Session expirée</h1>

    <p class="error-message">
        Votre session a expiré en raison d'une inactivité prolongée.<br>
        Rechargez la page et recommencez votre action.
    </p>

    <div>
        <a href="javascript:location.reload()" class="btn-home">
            <i class="fas fa-redo"></i>Recharger la page
        </a>
        <a href="{{ route('home') }}" class="btn-back">
            <i class="fas fa-home"></i>Accueil
        </a>
    </div>

@endsection
