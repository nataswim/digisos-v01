@extends('layouts.coach')

@section('title', 'Modifier une note')

@section('content')
<div class="container-lg" style="max-width: 720px">
    <a href="{{ route('coach.notes.show', $swimmer) }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>{{ $swimmer->full_name }}
    </a>
    <h1 class="h3 fw-bold mb-4">Modifier la note</h1>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('coach.notes.update', $note) }}" class="card border-0 shadow-sm">
        @csrf @method('PUT')
        <div class="card-body">@include('coach.notes._form')</div>
        <div class="card-footer bg-white d-flex gap-2 justify-content-end">
            <a href="{{ route('coach.notes.show', $swimmer) }}" class="btn btn-outline-secondary">Annuler</a>
            <button class="btn btn-primary"><i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer</button>
        </div>
    </form>
</div>
@endsection
