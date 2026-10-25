{{-- Barre de taux de présence. Variable : $rate (int|null) --}}
@if ($rate === null)
    <span class="text-muted small">—</span>
@else
    @php $couleur = $rate >= 75 ? 'success' : ($rate >= 50 ? 'warning' : 'danger'); @endphp
    <div class="d-flex align-items-center gap-2" style="min-width: 120px">
        <div class="progress flex-grow-1" style="height: 8px" role="progressbar" aria-label="Taux de présence" aria-valuenow="{{ $rate }}" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar bg-{{ $couleur }}" style="width: {{ $rate }}%"></div>
        </div>
        <span class="fw-semibold small">{{ $rate }} %</span>
    </div>
@endif
