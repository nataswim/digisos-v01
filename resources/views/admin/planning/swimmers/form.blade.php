@extends($layout)

@section('title', $swimmer->exists ? 'Modifier un nageur' : 'Ajouter un nageur')

@section('content')
<div class="planning-admin">
    @include('admin.planning._nav', ['active' => 'swimmers'])

    <div class="card border-0 shadow-sm" style="max-width: 760px;">
        <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-4">{{ $swimmer->exists ? 'Modifier le nageur' : 'Nouveau nageur' }}</h2>

            <form method="POST"
                  action="{{ $swimmer->exists ? route($area . '.planning.swimmers.update', $swimmer) : route($area . '.planning.swimmers.store') }}">
                @csrf
                @if ($swimmer->exists) @method('PUT') @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label fw-semibold">Prénom</label>
                        <input type="text" id="first_name" name="first_name" maxlength="100" required autocomplete="off"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $swimmer->first_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label fw-semibold">Nom</label>
                        <input type="text" id="last_name" name="last_name" maxlength="100" required autocomplete="off"
                               class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name', $swimmer->last_name) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="birth_year" class="form-label fw-semibold">Année de naissance <span class="text-muted fw-normal">(facultatif)</span></label>
                        <input type="number" id="birth_year" name="birth_year" min="1920" max="{{ now()->year }}"
                               class="form-control @error('birth_year') is-invalid @enderror"
                               value="{{ old('birth_year', $swimmer->birth_year) }}">
                    </div>

                    <div class="col-md-8">
                        <label for="user_id" class="form-label fw-semibold">Compte du site <span class="text-muted fw-normal">(facultatif)</span></label>
                        <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                            <option value="">— Aucun compte lié —</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected((int) old('user_id', $swimmer->user_id) === $user->id)>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <span class="form-label fw-semibold d-block mb-2">Groupe(s)</span>
                        <div class="row row-cols-1 row-cols-sm-2 g-1">
                            @foreach ($groups as $group)
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="groups[]" value="{{ $group->id }}"
                                               id="groupe-{{ $group->id }}"
                                               @checked(in_array($group->id, array_map('intval', old('groups', $selected)), true))>
                                        <label class="form-check-label" for="groupe-{{ $group->id }}">
                                            {{ $group->name }} <small class="text-muted">— {{ $group->section }}</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label fw-semibold">Note interne <span class="text-muted fw-normal">(jamais affichée sur le site)</span></label>
                        <textarea id="notes" name="notes" rows="3" maxlength="2000"
                                  class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $swimmer->notes) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $swimmer->is_active))>
                            <label class="form-check-label" for="is_active">Nageur actif cette saison</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
                    </button>
                    @if (! ($swimmer->exists))
                        <button type="submit" name="add_another" value="1" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-1" aria-hidden="true"></i>Enregistrer et ajouter un autre
                        </button>
                    @endif
                    <a href="{{ route($area . '.planning.swimmers.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
