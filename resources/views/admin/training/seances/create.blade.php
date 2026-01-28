@extends('layouts.admin')

@section('title', 'Créer une séance')
@section('page-title', 'Nouvelle séance')
@section('page-description', 'Création d\'une nouvelle séance d\'entraînement')

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.training.seances.store') }}">
        @include('admin.training.seances.partials.form', [
            'submitLabel' => 'Créer la séance',
            'series' => $series
        ])
    </form>
</div>
@endsection

@push('styles')

@endpush