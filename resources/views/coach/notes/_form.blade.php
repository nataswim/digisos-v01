{{-- Champs d'une note de suivi (ajout et modification) --}}
<div class="row g-3">
    <div class="col-sm-6">
        <label for="noted_on" class="form-label fw-semibold">Date</label>
        <input type="date" id="noted_on" name="noted_on" required class="form-control @error('noted_on') is-invalid @enderror"
               value="{{ old('noted_on', $note->noted_on?->format('Y-m-d')) }}">
    </div>
    <div class="col-sm-6">
        <label for="category" class="form-label fw-semibold">Type</label>
        <select id="category" name="category" class="form-select">
            @foreach (\App\Models\SwimmerNote::CATEGORIES as $key => $label)
                <option value="{{ $key }}" @selected(old('category', $note->category) === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label for="content" class="form-label fw-semibold">Note</label>
        <textarea id="content" name="content" rows="4" required maxlength="5000" class="form-control @error('content') is-invalid @enderror"
                  aria-describedby="content-help">{{ old('content', $note->content) }}</textarea>
        <div id="content-help" class="form-text">
            Progrès, points techniques, objectifs… N'indiquez pas d'information médicale.
        </div>
    </div>
</div>
