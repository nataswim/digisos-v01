@extends('layouts.admin')

@section('title', 'Mon profil')
@section('page-title', 'Mon profil')
@section('page-description', 'Informations de votre compte')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Card profil principal -->
            <div class="card shadow-aqua border-0 overflow-hidden mb-4">
                <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #38859b 0%, #49aaca 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-20 rounded-circle p-4">
                            <i class="fas fa-user-circle fa-3x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h1 class="h4 mb-1 fw-bold">{{ $user->name }}</h1>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 p-3 bg-primary-lighter rounded-lg">
                                <div class="bg-primary text-white rounded-circle p-3">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Date de création</p>
                                    <p class="mb-0 fw-semibold">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3 p-3 bg-info-lighter rounded-lg">
                                <div class="bg-info text-white rounded-circle p-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Dernière mise à jour</p>
                                    <p class="mb-0 fw-semibold">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($user->email_verified_at)
                        <div class="col-12">
                            <div class="alert alert-success bg-success-lighter border-success d-flex align-items-center gap-3 mb-0">
                                <i class="fas fa-check-circle text-success fa-lg"></i>
                                <div class="flex-grow-1">
                                    <strong>Email vérifié</strong> le {{ $user->email_verified_at->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-12">
                            <div class="alert alert-warning bg-warning-lighter border-warning d-flex align-items-center gap-3 mb-0">
                                <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                                <div class="flex-grow-1">
                                    <strong>Email non vérifié</strong> - Vérifiez votre boîte de réception
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card-footer bg-light border-0 p-4">
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-end">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection