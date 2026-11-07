{{-- Bloc « Messages » des tableaux de bord. Paramètres : $index (route de la boîte), $show (nom de la route d'une conversation). --}}
@php
    $me = auth()->user();
    $nonLus = \App\Models\Conversation::with(['staff', 'user'])->unreadFor($me)->orderByDesc('last_message_at')->limit(3)->get();
    $total = $nonLus->count() < 3 ? $nonLus->count() : \App\Models\Conversation::unreadCountFor($me);
@endphp
<section class="card border-0 shadow-sm mb-4" aria-labelledby="bloc-messages">
    <div class="card-body p-3 p-md-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h2 id="bloc-messages" class="h6 fw-bold mb-0">
            <i class="fas fa-envelope text-primary me-2" aria-hidden="true"></i>
            @if ($total)
                {{ $total }} message{{ $total > 1 ? 's' : '' }} non lu{{ $total > 1 ? 's' : '' }}
            @else
                Messages <span class="text-muted fw-normal">— aucun nouveau message</span>
            @endif
        </h2>
        <a href="{{ route($index) }}" class="btn btn-sm {{ $total ? 'btn-primary' : 'btn-outline-primary' }}">{{ $total ? 'Lire' : 'Ouvrir la messagerie' }}</a>
    </div>
    @if ($total)
        <ul class="list-group list-group-flush">
            @foreach ($nonLus as $c)
                <li class="list-group-item px-3 px-md-4">
                    <a href="{{ route($show, $c) }}" class="d-flex flex-wrap justify-content-between gap-2 text-decoration-none">
                        <span><strong>{{ \App\Services\MessageService::userLabel($c->otherParty($me)) }}</strong> — {{ $c->subject }}</span>
                        <span class="small text-muted">{{ $c->last_message_at->locale('fr')->isoFormat('D MMM, H[h]mm') }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</section>
