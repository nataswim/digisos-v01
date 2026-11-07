{{-- Zone de réponse. Paramètres : $action (URL). --}}
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="card border-0 shadow-sm mt-4">
    @csrf
    <div class="card-body">
        <label for="body" class="form-label fw-semibold">Votre réponse</label>
        <textarea id="body" name="body" rows="4" maxlength="10000" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
        @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mt-3">
            <div>
                <label for="attachment" class="form-label small mb-1">Pièce jointe <span class="text-muted">(facultatif : PDF ou image, 5 Mo maximum)</span></label>
                <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.webp" class="form-control form-control-sm @error('attachment') is-invalid @enderror">
                @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-primary"><i class="fas fa-paper-plane me-1" aria-hidden="true"></i>Envoyer</button>
        </div>
    </div>
</form>
