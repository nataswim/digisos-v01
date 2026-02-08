@extends('layouts.admin')

@section('title', 'Modifier mon profil')
@section('page-title', 'Modifier mon profil')
@section('page-description', 'Gérez vos informations personnelles et votre sécurité')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-primary">
                        <i class="fas fa-user-cog me-2"></i>Paramètres du profil
                    </h1>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-eye me-2"></i>Voir mon profil
                </a>
            </div>

            <div class="row g-4">
                <!-- Informations du profil -->
                <div class="col-12">
                    @include('admin.profile.partials.update-profile-information-form', ['user' => $user])
                </div>

                <!-- Mot de passe -->
                <div class="col-12">
                    @include('admin.profile.partials.update-password-form')
                </div>

                <!-- Supprimer le compte -->
                <div class="col-12">
                    @include('admin.profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient {
    background: linear-gradient(135deg, #38859b 0%, #49aaca 100%) !important;
}
</style>
@endpush