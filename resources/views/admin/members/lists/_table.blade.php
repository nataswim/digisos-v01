{{-- Petit tableau d'adhérents. Paramètres : $rows, $r (fabrique de routes), $columns : ['year'] et/ou ['city'] en plus. --}}
<div class="table-responsive position-relative">
    <table class="table table-sm align-middle mb-0 mbr-list">
        <caption class="visually-hidden">{{ $caption }}</caption>
        <thead class="table-light">
            <tr>
                <th scope="col">Nom</th>
                <th scope="col" class="text-center">Sexe</th>
                @if (in_array('year', $columns))<th scope="col">Naissance</th>@endif
                @if (in_array('city', $columns))<th scope="col">Commune</th>@endif
                <th scope="col">Catégorie</th>
                <th scope="col">Groupe(s)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <th scope="row" class="fw-semibold"><a href="{{ $r('show', $row['member']) }}" class="text-decoration-none">{{ mb_strtoupper($row['last_name']) }} {{ $row['first_name'] }}</a></th>
                    <td class="text-center">{{ ['F' => 'F', 'M' => 'M'][$row['gender']] ?? '—' }}</td>
                    @if (in_array('year', $columns))<td>{{ $row['birth_date']?->format('d/m/Y') ?? '—' }}</td>@endif
                    @if (in_array('city', $columns))<td>{{ $row['city'] !== '' ? $row['city'] : '—' }}</td>@endif
                    <td class="small">{{ $row['category'] ?? '—' }}</td>
                    <td>
                        @forelse ($row['groups'] as $g)
                            <span class="badge rounded-pill mbr-group" style="background: {{ $g->background_color }}; color: {{ $g->text_color }}">{{ $g->name }}</span>
                        @empty
                            <span class="small text-muted">{{ $row['swimmer'] ? 'sans groupe' : 'pas de fiche nageur' }}</span>
                        @endforelse
                        @if ($row['swimmer_guessed'] && $row['groups']->isNotEmpty())
                            <span class="small text-muted" title="Fiche nageur trouvée par le nom et l'année de naissance (pas encore liée à l'adhérent)">*</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
