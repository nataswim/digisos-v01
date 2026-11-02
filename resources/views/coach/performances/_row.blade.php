{{-- Ligne de la grille de saisie. Variables : $i, $row, $swimmers --}}
@php use App\Models\SwimPerformance as P; $n = "rows[$i]"; @endphp
<tr>
    <td>
        <label class="visually-hidden" for="r{{ $i }}-sw">Nageur</label>
        <select id="r{{ $i }}-sw" name="{{ $n }}[swimmer]" class="form-select form-select-sm perf-swimmer" data-f="swimmer">
            <option value="">—</option>
            @foreach ($swimmers as $s)<option value="{{ $s->id }}" @selected((int) ($row['swimmer'] ?? 0) === $s->id)>{{ $s->full_name }}</option>@endforeach
        </select>
    </td>
    <td>
        <label class="visually-hidden" for="r{{ $i }}-ev">Épreuve</label>
        <select id="r{{ $i }}-ev" name="{{ $n }}[event]" class="form-select form-select-sm" data-f="event">
            <option value="">—</option>
            @foreach (P::EVENTS as $e)<option value="{{ $e }}" @selected(($row['event'] ?? null) === $e)>{{ $e }}</option>@endforeach
        </select>
    </td>
    <td>
        <label class="visually-hidden" for="r{{ $i }}-rd">Tour</label>
        <select id="r{{ $i }}-rd" name="{{ $n }}[round]" class="form-select form-select-sm" data-f="round">
            @foreach (P::ROUNDS as $k => $l)<option value="{{ $k }}" @selected(($row['round'] ?? 'serie') === $k)>{{ $l }}</option>@endforeach
        </select>
    </td>
    <td>
        <label class="visually-hidden" for="r{{ $i }}-t">Temps</label>
        <input type="text" id="r{{ $i }}-t" name="{{ $n }}[time]" maxlength="12" inputmode="decimal" class="form-control form-control-sm perf-time-input" value="{{ $row['time'] ?? '' }}" placeholder="1:05.32" data-f="time">
    </td>
    <td>
        <label class="visually-hidden" for="r{{ $i }}-st">Statut</label>
        <select id="r{{ $i }}-st" name="{{ $n }}[status]" class="form-select form-select-sm" data-f="status">
            @foreach (P::STATUSES as $k => $l)<option value="{{ $k }}" @selected(($row['status'] ?? 'valid') === $k)>{{ $k === 'valid' ? 'Validé' : $l }}</option>@endforeach
        </select>
    </td>
    <td><span class="perf-live" data-live aria-live="polite"></span></td>
    <td>
        <label class="visually-hidden" for="r{{ $i }}-no">Note</label>
        <input type="text" id="r{{ $i }}-no" name="{{ $n }}[note]" maxlength="255" class="form-control form-control-sm" value="{{ $row['note'] ?? '' }}">
    </td>
    <td><button type="button" class="btn btn-sm btn-link text-danger" data-remove title="Retirer la ligne"><i class="fas fa-times" aria-hidden="true"></i><span class="visually-hidden">Retirer la ligne</span></button></td>
</tr>
