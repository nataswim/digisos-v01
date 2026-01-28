@extends('layouts.admin')

@section('title', 'Créer un plan')
@section('page-title', 'Nouveau plan d\'entraînement')
@section('page-description', 'Création d\'un nouveau plan')

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.training.plans.store') }}">
        @include('admin.training.plans.partials.form', [
            'submitLabel' => 'Créer le plan',
            'cycles' => $cycles
        ])
    </form>
</div>
@endsection

@push('styles')

@endpush