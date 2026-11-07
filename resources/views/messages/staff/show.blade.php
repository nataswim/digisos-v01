@extends($layout)

@section('title', $conversation->subject)

@php
    use App\Services\MessageService as MS;
    $me = auth()->user();
    $r = fn ($n, $p = []) => route($area . '.messages.' . $n, $p);
    $utilisateur = $conversation->user;
@endphp

@section('content')
<div class="container-lg" style="max-width: 900px">
    <a href="{{ $r('index', $participant ? [] : ['vue' => 'toutes']) }}" class="small"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Messagerie</a>
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mt-2 mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1">{{ $conversation->subject }}</h1>
            <p class="text-muted small mb-0">
                Conversation privée entre <strong>{{ MS::userLabel($conversation->staff) }}</strong> ({{ MS::roleLabel($conversation->staff) }})
                et <strong>{{ MS::userLabel($utilisateur) }}</strong>@if ($utilisateur) ({{ MS::roleLabel($utilisateur) }}@if ($utilisateur->planningSwimmers->isNotEmpty()) — {{ $utilisateur->planningSwimmers->pluck('first_name')->implode(', ') }}@endif)@endif.
                @if ($conversation->announcement && ($isAdmin || $conversation->announcement->sender_id === $me->id))
                    <br>Issue de l'envoi groupé <a href="{{ $r('announcement', $conversation->announcement) }}">« {{ $conversation->announcement->subject }} »</a>.
                @endif
            </p>
        </div>
        @if ($isAdmin)
            <form method="POST" action="{{ $r('destroy', $conversation) }}" onsubmit="return confirm('Supprimer définitivement cette conversation et ses pièces jointes ?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1" aria-hidden="true"></i>Supprimer</button>
            </form>
        @endif
    </div>

    @if (! $participant)
        <div class="alert alert-info small">Vous consultez cette conversation en tant qu'administrateur. Une réponse de votre part sera visible par les deux participants.</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    @include('messages._thread', ['conversation' => $conversation, 'me' => $me])

    @if ($conversation->user && $conversation->staff)
        @include('messages._reply', ['action' => $r('reply', $conversation)])
    @else
        <p class="text-muted mt-4">Un des comptes a été supprimé : la conversation ne peut plus recevoir de réponse.</p>
    @endif
</div>
@endsection
