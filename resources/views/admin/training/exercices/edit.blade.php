@extends('layouts.admin')

@section('title', 'Modifier un exercice')
@section('page-title', 'Modifier l\'exercice')
@section('page-description', 'Modification de l\'exercice : ' . $exercice->titre)

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.training.exercices.update', $exercice) }}">
        @method('PUT')
        @include('admin.training.exercices.partials.form', [
            'submitLabel' => 'Mettre à jour l\'exercice',
            'exercice' => $exercice
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