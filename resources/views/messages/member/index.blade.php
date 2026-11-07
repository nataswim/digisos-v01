@extends('layouts.member')

@section('title', 'Mes messages')

@php use App\Services\MessageService as MS; $me = auth()->user(); @endphp

@section('content')
<x-member.header title="Mes messages" icon="fa-envelope" lead="Vos échanges privés avec les coachs et le club. Vous seul(e) et votre interlocuteur les voyez.">
    <a href="{{ route('user.messages.create') }}" class="btn btn-outline-light"><i class="fas fa-pen me-2" aria-hidden="true"></i>Écrire à un coach</a>
</x-member.header>
@include('messages._styles')

<div class="container-lg py-4 py-lg-5" style="max-width: 900px">
    @include('messages.member._flash')

    <div class="card border-0 shadow-sm">
        <div class="msg-list">
            @forelse ($conversations as $c)
                @php $nonLu = $c->isUnreadFor($me); $autre = $c->otherParty($me); @endphp
                <a href="{{ route('user.messages.show', $c) }}" class="msg-item {{ $nonLu ? 'is-unread' : '' }}">
                    <span class="msg-dot {{ $nonLu ? '' : 'is-read' }}" aria-hidden="true"></span>
                    <span class="msg-main">
                        <span class="msg-top">
                            <span class="msg-who">{{ MS::userLabel($autre) }} <span class="text-muted fw-normal small">{{ MS::roleLabel($autre) }}</span>@if ($nonLu)<span class="visually-hidden">, non lu</span>@endif</span>
                            <span class="msg-when">{{ $c->last_message_at?->locale('fr')->isoFormat('D MMM, H[h]mm') }}</span>
                        </span>
                        <span class="msg-subject">{{ $c->subject }}</span>
                    </span>
                </a>
            @empty
                <div class="p-4 text-center">
                    <p class="text-muted mb-3">Aucun message pour l'instant.</p>
                    <a href="{{ route('user.messages.create') }}" class="btn btn-primary"><i class="fas fa-pen me-1" aria-hidden="true"></i>Écrire à un coach</a>
                </div>
            @endforelse
        </div>
    </div>
    <div class="mt-3">{{ $conversations->links() }}</div>
</div>
@endsection
