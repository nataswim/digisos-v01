@extends('layouts.public')

@section('title', 'Écrire à un coach')

@php use App\Services\MessageService as MS; @endphp

@section('content')
<x-member.header title="Écrire à un coach" icon="fa-pen" lead="Votre message n'est visible que par la personne choisie (et l'administrateur du club)." />

<div class="container-lg py-4 py-lg-5" style="max-width: 820px">
    <a href="{{ route('user.messages.index') }}" class="small"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Mes messages</a>
    <div class="mt-3">@include('messages.member._flash')</div>

    @if ($staff->isEmpty())
        <p class="alert alert-info mt-3">Aucun coach n'est disponible pour le moment. Vous pouvez utiliser la <a href="{{ route('contact') }}">page contact</a>.</p>
    @else
        <form method="POST" action="{{ route('user.messages.store') }}" enctype="multipart/form-data" class="card border-0 shadow-sm mt-3">
            @csrf
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="staff_id" class="form-label fw-semibold">Destinataire</label>
                    <select id="staff_id" name="staff_id" class="form-select @error('staff_id') is-invalid @enderror" required>
                        <option value="">— choisir —</option>
                        @foreach ($staff->groupBy(fn ($u) => MS::roleLabel($u)) as $role => $personnes)
                            <optgroup label="{{ $role === 'Administrateur' ? 'Administration du club' : $role . 's' }}">
                                @foreach ($personnes as $p)
                                    <option value="{{ $p->id }}" @selected((int) old('staff_id', $selected) === $p->id)>{{ MS::userLabel($p) }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('staff_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label fw-semibold">Sujet</label>
                    <input type="text" id="subject" name="subject" maxlength="150" value="{{ old('subject') }}" class="form-control @error('subject') is-invalid @enderror" required>
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="body" class="form-label fw-semibold">Message</label>
                    <textarea id="body" name="body" rows="7" maxlength="10000" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="attachment" class="form-label fw-semibold">Pièce jointe <span class="text-muted fw-normal">(facultatif : PDF ou image, 5 Mo maximum)</span></label>
                    <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.webp" class="form-control @error('attachment') is-invalid @enderror">
                    @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="card-footer bg-white text-end p-3">
                <button class="btn btn-primary"><i class="fas fa-paper-plane me-1" aria-hidden="true"></i>Envoyer</button>
            </div>
        </form>
    @endif
</div>
@endsection
