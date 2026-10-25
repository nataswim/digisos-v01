@extends('layouts.coach')

@section('title', $record->exists ? 'Modifier un record' : 'Nouveau record')

@section('content')
@php use App\Models\SwimRecord as R; @endphp
<div class="container-lg" style="max-width: 860px">
    <a href="{{ route('coach.records.index', ['niveau' => $record->level, 'bassin' => $record->pool, 'categorie' => $record->category]) }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Records
    </a>
    <h1 class="h3 fw-bold mb-4">{{ $record->exists ? 'Modifier le record' : 'Nouveau record' }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $record->exists ? route('coach.records.update', $record) : route('coach.records.store') }}" class="card border-0 shadow-sm">
        @csrf
        @if ($record->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            <div class="col-md-4">
                <label for="level" class="form-label fw-semibold">Niveau</label>
                <select id="level" name="level" class="form-select">
                    @foreach (R::LEVELS as $key => $label)<option value="{{ $key }}" @selected(old('level', $record->level) === $key)>{{ R::levelLabel($key) }}</option>@endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label for="pool" class="form-label fw-semibold">Bassin</label>
                <select id="pool" name="pool" class="form-select">
                    @foreach (R::POOLS as $p => $label)<option value="{{ $p }}" @selected((int) old('pool', $record->pool) === $p)>{{ $p }} m</option>@endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label for="category" class="form-label fw-semibold">Catégorie</label>
                <select id="category" name="category" class="form-select">
                    @foreach (R::CATEGORIES as $key => $label)<option value="{{ $key }}" @selected((string) old('category', $record->category) === (string) $key)>{{ $label }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="sex" class="form-label fw-semibold">Sexe</label>
                <select id="sex" name="sex" class="form-select">
                    @foreach (R::SEXES as $key => $label)<option value="{{ $key }}" @selected(old('sex', $record->sex) === $key)>{{ $label }}</option>@endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="event" class="form-label fw-semibold">Épreuve <span class="text-danger" aria-hidden="true">*</span></label>
                <select id="event" name="event" class="form-select" required>
                    <option value="">Choisir…</option>
                    @foreach (R::EVENTS as $event)<option value="{{ $event }}" @selected(old('event', $record->event) === $event)>{{ $event }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="time" class="form-label fw-semibold">Temps <span class="text-danger" aria-hidden="true">*</span></label>
                <input type="text" id="time" name="time" required maxlength="12" class="form-control font-monospace fw-bold" inputmode="decimal"
                       value="{{ old('time', $record->time_cs ? $record->time_label : '') }}" placeholder="1:05.32" aria-describedby="time-help">
                <div id="time-help" class="form-text">Minutes:secondes.centièmes — ex. 28.45 ou 1:05.32</div>
            </div>
            <div class="col-md-4">
                <label for="record_date" class="form-label fw-semibold">Date</label>
                <input type="date" id="record_date" name="record_date" class="form-control" value="{{ old('record_date', $record->record_date?->toDateString()) }}" max="{{ today()->toDateString() }}">
            </div>

            <div class="col-md-8">
                <label for="swimmers" class="form-label fw-semibold">Nageur(s) <span class="text-danger" aria-hidden="true">*</span></label>
                <textarea id="swimmers" name="swimmers" rows="2" required maxlength="1000" class="form-control" aria-describedby="sw-help">{{ old('swimmers', $record->swimmers) }}</textarea>
                <div id="sw-help" class="form-text">NOM Prénom. Pour un relais : un relayeur par ligne.</div>
            </div>
            <div class="col-md-4">
                <label for="birth_year" class="form-label fw-semibold">Année de naissance</label>
                <input type="number" id="birth_year" name="birth_year" min="1900" max="{{ now()->year }}" class="form-control" value="{{ old('birth_year', $record->birth_year) }}">
            </div>
            <div class="col-md-6">
                <label for="club" class="form-label fw-semibold">Club</label>
                <input type="text" id="club" name="club" maxlength="255" class="form-control" value="{{ old('club', $record->club ?? ($record->level === 'club' ? 'CNBB' : '')) }}">
            </div>
            <div class="col-md-6">
                <label for="location" class="form-label fw-semibold">Lieu</label>
                <input type="text" id="location" name="location" maxlength="255" class="form-control" value="{{ old('location', $record->location) }}" placeholder="POITIERS (FRA)">
            </div>
            <div class="col-md-6">
                <label for="competition" class="form-label fw-semibold">Compétition</label>
                <input type="text" id="competition" name="competition" maxlength="255" class="form-control" value="{{ old('competition', $record->competition) }}">
            </div>
            <div class="col-md-6">
                <label for="source_url" class="form-label fw-semibold">Lien vers le résultat (facultatif)</label>
                <input type="url" id="source_url" name="source_url" maxlength="500" class="form-control" value="{{ old('source_url', $record->source_url) }}" placeholder="https://">
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('coach.records.index', ['niveau' => $record->level, 'bassin' => $record->pool, 'categorie' => $record->category]) }}" class="btn btn-outline-secondary">Annuler</a>
            <button class="btn btn-primary"><i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer</button>
        </div>
    </form>
</div>
@endsection
