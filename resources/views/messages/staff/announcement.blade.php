@extends($layout)

@section('title', 'Envoi groupé — ' . $announcement->subject)

@php
    use App\Services\MessageService as MS;
    $r = fn ($n, $p = []) => route($area . '.messages.' . $n, $p);
    $total = $conversations->count();
@endphp

@section('content')
@include('messages._styles')
<div class="container-lg" style="max-width: 980px">
    <a href="{{ $r('index', ['vue' => 'annonces']) }}" class="small"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Envois groupés</a>
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mt-2 mb-3">
        <div>
            <h1 class="h4 fw-bold mb-1"><i class="fas fa-bullhorn text-primary me-2" aria-hidden="true"></i>{{ $announcement->subject }}</h1>
            <p class="text-muted small mb-0">
                Envoyé par {{ MS::userLabel($announcement->sender) }} le {{ $announcement->created_at->locale('fr')->isoFormat('D MMMM YYYY [à] H[h]mm') }}
                · {{ collect($announcement->targets)->implode(' · ') }}
            </p>
        </div>
        @if ($isAdmin)
            <form method="POST" action="{{ $r('announcement.destroy', $announcement) }}" onsubmit="return confirm('Supprimer cet envoi et les {{ $total }} conversations qu\'il a créées (réponses comprises) ?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1" aria-hidden="true"></i>Supprimer l'envoi</button>
            </form>
        @endif
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
            <div class="small text-muted">Lu par</div>
            <div class="h3 fw-bold mb-1">{{ $readCount }} / {{ $total }}</div>
            <div class="progress" role="progressbar" aria-label="Proportion de lecture" aria-valuenow="{{ $readCount }}" aria-valuemin="0" aria-valuemax="{{ $total }}" style="height: 6px">
                <div class="progress-bar" style="width: {{ $total ? round($readCount / $total * 100) : 0 }}%"></div>
            </div>
        </div></div></div>
        <div class="col-sm-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
            <div class="small text-muted">Pas encore lu</div><div class="h3 fw-bold mb-0 {{ $total - $readCount ? 'text-warning' : 'text-success' }}">{{ $total - $readCount }}</div>
        </div></div></div>
        <div class="col-sm-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
            <div class="small text-muted">Ont répondu</div><div class="h3 fw-bold mb-0">{{ $replyCount }}</div>
        </div></div></div>
    </div>

    <details class="card border-0 shadow-sm mb-4">
        <summary class="card-header bg-white fw-semibold">Message envoyé</summary>
        <div class="card-body"><div class="msg-body">{{ $announcement->body }}</div>
            @if ($announcement->hasAttachment())<p class="small text-muted mt-2 mb-0"><i class="fas fa-paperclip me-1" aria-hidden="true"></i>{{ $announcement->attachment_name }} ({{ $announcement->attachment_size_label }})</p>@endif
        </div>
    </details>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white"><h2 class="h6 fw-bold mb-0">Destinataires — les non-lus en premier</h2></div>
        <div class="table-responsive position-relative">
            <table class="table align-middle mb-0">
                <caption class="visually-hidden">Lecture par destinataire</caption>
                <thead class="table-light"><tr><th scope="col">Destinataire</th><th scope="col">Lecture</th><th scope="col">Réponse</th><th scope="col"><span class="visually-hidden">Ouvrir</span></th></tr></thead>
                <tbody>
                    @foreach ($conversations as $c)
                        <tr>
                            <th scope="row">{{ MS::userLabel($c->user) }}</th>
                            <td>
                                @if ($c->user_read_at)
                                    <span class="badge bg-success-subtle text-success-emphasis"><i class="fas fa-check me-1" aria-hidden="true"></i>Lu le {{ $c->user_read_at->locale('fr')->isoFormat('D MMM, H[h]mm') }}</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis">Pas encore lu</span>
                                @endif
                            </td>
                            <td>@if ($c->user_replies)<span class="badge bg-info-subtle text-info-emphasis"><i class="fas fa-reply me-1" aria-hidden="true"></i>A répondu</span>@else<span class="text-muted small">—</span>@endif</td>
                            <td class="text-end"><a href="{{ $r('show', $c) }}" class="btn btn-sm btn-outline-primary">Conversation</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
