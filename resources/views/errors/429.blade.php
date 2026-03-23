@extends('errors.layout.error')

@section('title', '429 — Trop de requêtes')

@section('content')

    <div class="error-icon">
        <i class="fas fa-tachometer-alt"></i>
    </div>

    <p class="error-code">429</p>

    <div class="error-divider"></div>

    <h1 class="error-title">Trop de requêtes</h1>

    <p class="error-message">
        Vous avez effectué trop de tentatives en peu de temps.<br>
        Veuillez patienter quelques instants avant de réessayer.
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
