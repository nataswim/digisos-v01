@extends('layouts.admin')

@section('title', 'Nouvelle bannière')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="fas fa-plus me-2 text-primary"></i>Nouvelle bannière</h1>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Retour
        </a>
    </div>

    <form action="{{ route('admin.banners.store') }}" method="POST">
        @csrf
        @include('admin.banners.partials.form')
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Créer la bannière
            </button>
        </div>
    </form>

</div>
@endsection
