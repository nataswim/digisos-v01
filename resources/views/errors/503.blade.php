@extends('errors.layout.error')

@section('title', 'Maintenance en cours')

@section('content')

    <div class="error-icon">
        <i class="fas fa-tools"></i>
    </div>

    <p class="error-code">503</p>

    <div class="error-divider"></div>

    <h1 class="error-title">Maintenance en cours</h1>

    <p class="error-message">
        {{ config('app.name') }} est temporairement indisponible pour maintenance.<br>
        Nous serons de retour très prochainement. Merci pour votre patience.
    </p>

    {{-- Affiche le message personnalisé si défini via `php artisan down --message="..."` --}}
    @if(isset($exception) && $exception->getMessage())
        <div class="alert alert-info d-inline-block mb-3 text-start" style="border-radius:12px;font-size:.9rem;">
            <i class="fas fa-info-circle me-2"></i>{{ $exception->getMessage() }}
        </div>
    @endif

    <div>
        <a href="javascript:location.reload()" class="btn-home">
            <i class="fas fa-redo"></i>Réessayer
        </a>
    </div>

@endsection
