{{-- Fil d'une conversation. Paramètres : $conversation (messages chargés), $me (utilisateur connecté). --}}
@include('messages._styles')
<div class="msg-thread" aria-label="Messages de la conversation">
    @foreach ($conversation->messages as $message)
        @php $mien = $message->author_id === $me->id; @endphp
        <article class="msg-bubble {{ $mien ? 'is-mine' : '' }}" @if ($loop->last) id="dernier" @endif>
            <header class="msg-bubble-head">
                <span><strong>{{ $mien ? 'Vous' : \App\Services\MessageService::userLabel($message->author) }}</strong>@if (! $mien && ($r = \App\Services\MessageService::roleLabel($message->author))) · {{ $r }}@endif</span>
                <time datetime="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->locale('fr')->isoFormat('D MMM YYYY [à] H[h]mm') }}</time>
            </header>
            <div class="msg-body">{{ $message->body }}</div>
            @if ($message->hasAttachment())
                <a class="msg-attachment" href="{{ route('messages.attachment', $message) }}" target="_blank" rel="noopener">
                    @if ($message->attachment_is_image)
                        <img src="{{ route('messages.attachment', $message) }}" alt="Pièce jointe : {{ $message->attachment_name }}" loading="lazy">
                    @else
                        <i class="fas fa-file-pdf text-danger" aria-hidden="true"></i>
                        <span>{{ $message->attachment_name }} <span class="text-muted">({{ $message->attachment_size_label }})</span></span>
                    @endif
                </a>
            @endif
        </article>
    @endforeach
</div>
