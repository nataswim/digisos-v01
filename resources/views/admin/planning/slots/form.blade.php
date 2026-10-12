@extends($layout)

@section('title', $slot->exists ? 'Modifier un créneau' : 'Ajouter un créneau')

@section('content')
<div class="planning-admin">
    @include('admin.planning._nav', ['active' => 'slots'])

    <div class="card border-0 shadow-sm" style="max-width: 760px;">
        <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-4">{{ $slot->exists ? 'Modifier le créneau' : 'Nouveau créneau' }}</h2>

            @if ($groups->isEmpty())
                <div class="alert alert-warning">
                    Créez d'abord un groupe :
                    <a href="{{ route($area . '.planning.groups.create') }}">ajouter un groupe</a>.
                </div>
            @endif

            <form method="POST"
                  action="{{ $slot->exists ? route($area . '.planning.slots.update', $slot) : route($area . '.planning.slots.store') }}">
                @csrf
                @if ($slot->exists) @method('PUT') @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="period" class="form-label fw-semibold">Planning</label>
                        <select id="period" name="period" class="form-select @error('period') is-invalid @enderror" required>
                            @foreach (\App\Models\PlanningSlot::PERIODS as $cle => $libelle)
                                <option value="{{ $cle }}" @selected(old('period', $slot->period) === $cle)>{{ $libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="planning_group_id" class="form-label fw-semibold">Groupe</label>
                        <select id="planning_group_id" name="planning_group_id" class="form-select @error('planning_group_id') is-invalid @enderror" required>
                            <option value="">— Choisir —</option>
                            @foreach ($groups->groupBy('section') as $section => $groupesSection)
                                <optgroup label="{{ $section }}">
                                    @foreach ($groupesSection as $groupe)
                                        <option value="{{ $groupe->id }}" @selected((int) old('planning_group_id', $slot->planning_group_id) === $groupe->id)>
                                            {{ $groupe->name }}{{ $groupe->is_active ? '' : ' (masqué)' }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="day_of_week" class="form-label fw-semibold">Jour</label>
                        <select id="day_of_week" name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                            @foreach (\App\Models\PlanningSlot::DAYS as $numero => $jour)
                                <option value="{{ $numero }}" @selected((int) old('day_of_week', $slot->day_of_week) === $numero)>{{ $jour }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-4">
                        <label for="starts_at" class="form-label fw-semibold">Début</label>
                        <input type="time" id="starts_at" name="starts_at" step="300" required
                               class="form-control @error('starts_at') is-invalid @enderror"
                               value="{{ old('starts_at', \App\Models\PlanningSlot::inputTime($slot->starts_at)) }}">
                    </div>

                    <div class="col-6 col-md-4">
                        <label for="ends_at" class="form-label fw-semibold">Fin</label>
                        <input type="time" id="ends_at" name="ends_at" step="300" required
                               class="form-control @error('ends_at') is-invalid @enderror"
                               value="{{ old('ends_at', \App\Models\PlanningSlot::inputTime($slot->ends_at)) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="location" class="form-label fw-semibold">Lieu</label>
                        <input type="text" id="location" name="location" maxlength="100" required list="lieux"
                               class="form-control @error('location') is-invalid @enderror"
                               value="{{ old('location', $slot->location) }}">
                        <datalist id="lieux">
                            <option value="Centre aquatique Cœur d'O">
                            <option value="Piscine Aquadel (Cerizay)">
                        </datalist>
                    </div>

                    <div class="col-md-6">
                        <label for="lanes" class="form-label fw-semibold">Espace</label>
                        <input type="text" id="lanes" name="lanes" maxlength="50" placeholder="ex. 2 couloirs, couloirs 4 et 5"
                               class="form-control @error('lanes') is-invalid @enderror"
                               value="{{ old('lanes', $slot->lanes) }}">
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label fw-semibold">Note affichée sur le planning <span class="text-muted fw-normal">(facultatif)</span></label>
                        <input type="text" id="notes" name="notes" maxlength="255" placeholder="ex. Stage sur convocation"
                               class="form-control @error('notes') is-invalid @enderror"
                               value="{{ old('notes', $slot->notes) }}">
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $slot->is_active))>
                            <label class="form-check-label" for="is_active">Afficher ce créneau sur le site</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
                    </button>
                    <a href="{{ route($area . '.planning.slots.index', ['periode' => $slot->period]) }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
