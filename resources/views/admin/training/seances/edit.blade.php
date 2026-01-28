@extends('layouts.admin')

@section('title', 'Modifier une séance')
@section('page-title', 'Modifier la séance')
@section('page-description', 'Modification de la séance : ' . $seance->titre)

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.training.seances.update', $seance) }}">
        @method('PUT')
        @include('admin.training.seances.partials.form', [
            'submitLabel' => 'Mettre à jour la séance',
            'seance' => $seance,
            'series' => $series
        ])
    </form>
</div>
@endsection

@push('styles')

@endpush