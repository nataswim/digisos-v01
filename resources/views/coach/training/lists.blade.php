@extends('layouts.coach')

@section('title', 'Listes des séances')

@section('content')
<div class="container-lg" style="max-width: 980px">
    <a href="{{ route('coach.training.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Séances</a>
    <h1 class="h3 fw-bold mb-1">Listes des séances</h1>
    <p class="text-muted mb-4">Une valeur par ligne, dans l'ordre d'affichage. Ces listes sont proposées dans les formulaires ; on peut toujours saisir une autre valeur.</p>

    <form method="POST" action="{{ route('coach.training-lists.update') }}">
        @csrf @method('PUT')
        <div class="row g-4">
            @foreach ($lists as $key => $list)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <label for="list-{{ $key }}" class="form-label fw-bold">{{ $list['title'] }}</label>
                            <textarea id="list-{{ $key }}" name="lists[{{ $key }}]" rows="10" class="form-control" aria-describedby="help-{{ $key }}">{{ old("lists.$key", $list['values']) }}</textarea>
                            <div id="help-{{ $key }}" class="form-text">{{ $list['help'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-end mt-4">
            <button class="btn btn-primary btn-lg"><i class="fas fa-save me-2" aria-hidden="true"></i>Enregistrer les listes</button>
        </div>
    </form>
</div>
@endsection
