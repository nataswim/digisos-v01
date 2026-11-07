@extends('layouts.member')

@section('title', $conversation->subject)

@php use App\Services\MessageService as MS; $me = auth()->user(); $autre = $conversation->otherParty($me); @endphp

@section('content')
<x-member.header title="Mes messages" icon="fa-envelope" />

<div class="container-lg py-4 py-lg-5" style="max-width: 900px">
    <a href="{{ route('user.messages.index') }}" class="small"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Mes messages</a>
    <h1 class="h4 fw-bold mt-2 mb-1">{{ $conversation->subject }}</h1>
    <p class="text-muted small mb-4">Avec <strong>{{ MS::userLabel($autre) }}</strong> ({{ MS::roleLabel($autre) }}) — conversation privée.</p>

    @include('messages.member._flash')
    @include('messages._thread', ['conversation' => $conversation, 'me' => $me])

    @if ($autre)
        @include('messages._reply', ['action' => route('user.messages.reply', $conversation)])
    @else
        <p class="text-muted mt-4">Ce compte n'existe plus : vous ne pouvez plus répondre. <a href="{{ route('user.messages.create') }}">Écrire à un autre coach</a>.</p>
    @endif
</div>
@endsection
