@extends('layouts.admin')

@section('title', 'Formulaire profil')
@section('page-title', 'Formulaire profil')
@section('page-description', 'Modification des informations du profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <form action="{{ route('profile.update') }}" method="POST" class="row g-4">
                @csrf
                @method('PATCH')

                <!-- Informations du profil -->
                <div class="col-12">
                    @include('admin.profile.partials.update-profile-information-form', ['user' => $user])
                </div>

                <!-- Mot de passe -->
                <div class="col-12">
                    @include('admin.profile.partials.update-password-form')
                </div>

                <!-- Actions -->
                <div class="col-12">
                    <div class="card shadow-aqua border-0">
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-end">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection