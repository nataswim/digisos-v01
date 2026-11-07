@extends($layout)

@section('title', 'Messagerie')

@php
    use App\Services\MessageService as MS;
    $me = auth()->user();
    $r = fn ($n, $p = []) => route($area . '.messages.' . $n, $p);
    $nonLus = \App\Models\Conversation::unreadCountFor($me);
@endphp

@section('content')
@include('messages._styles')
<div class="container-lg">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h1 class="h3 fw-bold mb-1"><i class="fas fa-envelope text-primary me-2" aria-hidden="true"></i>Messagerie</h1>
            <p class="text-muted mb-0">Conversations privées avec un utilisateur. Un envoi à plusieurs personnes crée une conversation séparée pour chacune : personne ne voit les réponses des autres.</p>
        </div>
        <a href="{{ $r('create') }}" class="btn btn-primary"><i class="fas fa-pen me-1" aria-hidden="true"></i>Nouveau message</a>
    </div>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $vue === 'boite' ? 'active' : '' }}" href="{{ $r('index') }}" @if ($vue === 'boite') aria-current="page" @endif>
                Mes conversations @if ($nonLus)<span class="msg-unread-badge ms-1">{{ $nonLus }}<span class="visually-hidden"> non lues</span></span>@endif
            </a>
        </li>
        <li class="nav-item"><a class="nav-link {{ $vue === 'annonces' ? 'active' : '' }}" href="{{ $r('index', ['vue' => 'annonces']) }}" @if ($vue === 'annonces') aria-current="page" @endif>{{ $isAdmin ? 'Envois groupés' : 'Mes envois groupés' }}</a></li>
        @if ($isAdmin)
            <li class="nav-item"><a class="nav-link {{ $vue === 'toutes' ? 'active' : '' }}" href="{{ $r('index', ['vue' => 'toutes']) }}" @if ($vue === 'toutes') aria-current="page" @endif>Toutes les conversations</a></li>
        @endif
    </ul>

    @if ($conversations !== null)
        <form method="GET" class="d-flex flex-wrap gap-2 mb-3" role="search">
            <input type="hidden" name="vue" value="{{ $vue }}">
            <label class="visually-hidden" for="q">Rechercher</label>
            <input type="search" id="q" name="q" value="{{ $q }}" class="form-control" style="max-width: 320px" placeholder="Sujet ou nom…">
            <div class="form-check align-self-center ms-1">
                <input class="form-check-input" type="checkbox" name="non-lus" value="1" id="non-lus" @checked(request()->boolean('non-lus')) onchange="this.form.submit()">
                <label class="form-check-label" for="non-lus">Non lus seulement</label>
            </div>
            <button class="btn btn-outline-secondary">Rechercher</button>
        </form>

        <div class="card border-0 shadow-sm">
            <div class="msg-list">
                @forelse ($conversations as $c)
                    @php
                        $nonLu = $c->isUnreadFor($me);
                        $mienne = $c->isParticipant($me);
                        $autre = $mienne ? $c->otherParty($me) : null;
                    @endphp
                    <a href="{{ $r('show', $c) }}" class="msg-item {{ $nonLu ? 'is-unread' : '' }}">
                        <span class="msg-dot {{ $nonLu ? '' : 'is-read' }}" aria-hidden="true"></span>
                        <span class="msg-main">
                            <span class="msg-top">
                                <span class="msg-who">
                                    @if ($mienne)
                                        {{ MS::userLabel($autre) }} <span class="text-muted fw-normal small">{{ MS::roleLabel($autre) }}</span>
                                    @else
                                        {{ MS::userLabel($c->staff) }} ↔ {{ MS::userLabel($c->user) }}
                                    @endif
                                    @if ($nonLu)<span class="visually-hidden">, non lu</span>@endif
                                </span>
                                <span class="msg-when">{{ $c->last_message_at?->locale('fr')->isoFormat('D MMM, H[h]mm') }}</span>
                            </span>
                            <span class="msg-subject">
                                @if ($c->announcement_id)<i class="fas fa-bullhorn text-muted me-1" title="Envoi groupé" aria-label="Envoi groupé"></i>@endif{{ $c->subject }}
                            </span>
                        </span>
                    </a>
                @empty
                    <p class="text-muted p-4 mb-0">Aucune conversation{{ $q || request()->boolean('non-lus') ? ' pour cette recherche' : '' }}.</p>
                @endforelse
            </div>
        </div>
        <div class="mt-3">{{ $conversations->links() }}</div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive position-relative">
                <table class="table align-middle mb-0">
                    <caption class="visually-hidden">Envois groupés</caption>
                    <thead class="table-light"><tr><th scope="col">Date</th><th scope="col">Sujet</th>@if ($isAdmin)<th scope="col">Expéditeur</th>@endif<th scope="col">Destinataires</th><th scope="col">Lu par</th></tr></thead>
                    <tbody>
                        @forelse ($annonces as $a)
                            <tr>
                                <td class="text-nowrap small">{{ $a->created_at->locale('fr')->isoFormat('D MMM YYYY') }}</td>
                                <th scope="row"><a href="{{ $r('announcement', $a) }}">{{ $a->subject }}</a></th>
                                @if ($isAdmin)<td>{{ MS::userLabel($a->sender) }}</td>@endif
                                <td class="small">{{ collect($a->targets)->implode(' · ') }}</td>
                                <td class="text-nowrap"><strong>{{ $a->read_count }}</strong> / {{ $a->recipients_count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-muted p-4">Aucun envoi groupé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $annonces->links() }}</div>
    @endif
</div>
@endsection
