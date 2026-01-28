@extends('layouts.admin')

@section('title', 'Créer un exercice')
@section('page-title', 'Nouvel exercice')
@section('page-description', 'Création d\'un nouvel exercice d\'entraînement')

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.training.exercices.store') }}">
        @include('admin.training.exercices.partials.form', [
            'submitLabel' => 'Créer l\'exercice'
        ])
    </form>
</div>
@endsection

@push('styles')
<style>






.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}
</style>
@endpush