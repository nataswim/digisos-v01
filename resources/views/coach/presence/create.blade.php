@extends('layouts.coach')

@section('title', 'Séance supplémentaire')

@section('content')
<div class="container-lg" style="max-width: 720px">
    <a href="{{ route('coach.presence.index', ['date' => $date->toDateString()]) }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Émargement
    </a>
    <h1 class="h3 fw-bold mb-1">Séance supplémentaire</h1>
    <p class="text-muted mb-4">Stage, rattrapage, séance hors planning…</p>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('coach.presence.store') }}" class="card border-0 shadow-sm">
        @csrf
        <div class="card-body row g-3">
            <div class="col-12">
                <label for="title" class="form-label fw-semibold">Titre <span class="text-danger" aria-hidden="true">*</span></label>
                <input type="text" id="title" name="title" required maxlength="255" class="form-control" value="{{ old('title') }}" placeholder="Stage de Toussaint">
            </div>
            <div class="col-sm-6">
                <label for="date" class="form-label fw-semibold">Date</label>
                <input type="date" id="date" name="date" required class="form-control" value="{{ old('date', $date->toDateString()) }}" max="{{ today()->addDays(7)->toDateString() }}">
            </div>
            <div class="col-sm-6">
                <label for="planning_group_id" class="form-label fw-semibold">Groupe</label>
                <select id="planning_group_id" name="planning_group_id" class="form-select" aria-describedby="groupe-aide">
                    <option value="">Aucun (nageurs ajoutés un par un)</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" @selected((int) old('planning_group_id') === $group->id)>{{ $group->name }}</option>
                    @endforeach
                </select>
                <div id="groupe-aide" class="form-text">Les nageurs du groupe seront proposés à l'appel.</div>
            </div>
            <div class="col-sm-6">
                <label for="starts_at" class="form-label fw-semibold">Début</label>
                <input type="time" id="starts_at" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
            </div>
            <div class="col-sm-6">
                <label for="ends_at" class="form-label fw-semibold">Fin</label>
                <input type="time" id="ends_at" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
            </div>
            <div class="col-12">
                <label for="location" class="form-label fw-semibold">Lieu</label>
                <input type="text" id="location" name="location" maxlength="255" class="form-control" value="{{ old('location') }}">
            </div>
        </div>
        <div class="card-footer bg-white text-end">
            <button class="btn btn-primary"><i class="fas fa-clipboard-check me-2" aria-hidden="true"></i>Créer et faire l'appel</button>
        </div>
    </form>
</div>
@endsection
