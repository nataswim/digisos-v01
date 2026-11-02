@extends('layouts.coach')

@section('title', 'Modifier une performance')

@section('content')
@php use App\Models\SwimPerformance as P; @endphp
<div class="container-lg" style="max-width: 860px">
    <a href="{{ route('coach.performances.swimmer', $performance->planning_swimmer_id) }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>{{ $performance->swimmer?->full_name }}</a>
    <h1 class="h3 fw-bold mb-4">Modifier : {{ $performance->swimmer?->full_name }}, {{ $performance->event }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('coach.performances.update', $performance) }}" class="card border-0 shadow-sm">
        @csrf @method('PUT')
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label for="competition_id" class="form-label fw-semibold">Compétition du calendrier</label>
                <select id="competition_id" name="competition_id" class="form-select">
                    <option value="">Aucune (meeting extérieur ou entraînement)</option>
                    @foreach ($competitions as $c)<option value="{{ $c->id }}" @selected((int) old('competition_id', $performance->competition_id) === $c->id)>{{ $c->starts_on?->format('d/m/Y') }} · {{ $c->title }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="meet_name" class="form-label fw-semibold">Nom de la réunion (sans compétition)</label>
                <input type="text" id="meet_name" name="meet_name" maxlength="255" class="form-control" value="{{ old('meet_name', $performance->meet_name) }}">
            </div>
            <div class="col-md-4">
                <label for="performed_on" class="form-label fw-semibold">Date</label>
                <input type="date" id="performed_on" name="performed_on" required class="form-control" max="{{ today()->toDateString() }}" value="{{ old('performed_on', $performance->performed_on->toDateString()) }}">
            </div>
            <div class="col-md-3">
                <label for="pool" class="form-label fw-semibold">Bassin</label>
                <select id="pool" name="pool" class="form-select"><option value="25" @selected((int) old('pool', $performance->pool) === 25)>25 m</option><option value="50" @selected((int) old('pool', $performance->pool) === 50)>50 m</option></select>
            </div>
            <div class="col-md-5">
                <label for="location" class="form-label fw-semibold">Lieu</label>
                <input type="text" id="location" name="location" maxlength="255" class="form-control" value="{{ old('location', $performance->location) }}">
            </div>
            <div class="col-md-3">
                <label for="event" class="form-label fw-semibold">Épreuve</label>
                <select id="event" name="event" class="form-select">@foreach (P::EVENTS as $e)<option value="{{ $e }}" @selected(old('event', $performance->event) === $e)>{{ $e }}</option>@endforeach</select>
            </div>
            <div class="col-md-3">
                <label for="round" class="form-label fw-semibold">Tour</label>
                <select id="round" name="round" class="form-select">@foreach (P::ROUNDS as $k => $l)<option value="{{ $k }}" @selected(old('round', $performance->round) === $k)>{{ $l }}</option>@endforeach</select>
            </div>
            <div class="col-md-3">
                <label for="time" class="form-label fw-semibold">Temps</label>
                <input type="text" id="time" name="time" maxlength="12" class="form-control font-monospace fw-bold" value="{{ old('time', $performance->time_cs ? \App\Models\SwimRecord::formatTime($performance->time_cs) : '') }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label fw-semibold">Statut</label>
                <select id="status" name="status" class="form-select">@foreach (P::STATUSES as $k => $l)<option value="{{ $k }}" @selected(old('status', $performance->status) === $k)>{{ $l }}</option>@endforeach</select>
            </div>
            <div class="col-12">
                <label for="note" class="form-label fw-semibold">Note</label>
                <input type="text" id="note" name="note" maxlength="255" class="form-control" value="{{ old('note', $performance->note) }}">
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between">
            <button form="delete-perf" class="btn btn-outline-danger"><i class="fas fa-trash me-1" aria-hidden="true"></i>Supprimer</button>
            <button class="btn btn-primary"><i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer</button>
        </div>
    </form>
    <form method="POST" action="{{ route('coach.performances.destroy', $performance) }}" id="delete-perf" onsubmit="return confirm('Supprimer ce temps ?')">@csrf @method('DELETE')</form>
</div>
@endsection
