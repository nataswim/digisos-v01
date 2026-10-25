{{-- Choix niveau / bassin / catégorie. Variables : $level, $pool, $category, $route, $levels --}}
@php use App\Models\SwimRecord as R; @endphp
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="mb-2">
            @foreach ($levels as $key)
                <a href="{{ route($route, ['niveau' => $key, 'bassin' => $pool, 'categorie' => $category]) }}"
                   class="btn btn-sm mb-1 {{ $level === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $key === 'club' ? 'Club' : \Illuminate\Support\Str::after(R::levelLabel($key), 'Records ') }}
                    @if (! empty($counts[$key]))<span class="badge bg-light text-dark ms-1">{{ $counts[$key] }}</span>@endif
                </a>
            @endforeach
        </div>
        <div class="mb-2">
            @foreach (R::POOLS as $p => $label)
                <a href="{{ route($route, ['niveau' => $level, 'bassin' => $p, 'categorie' => $category]) }}"
                   class="btn btn-sm mb-1 {{ $pool === $p ? 'btn-dark' : 'btn-outline-secondary' }}">{{ $p }} m</a>
            @endforeach
            <span class="mx-2 text-muted">|</span>
            @foreach (R::CATEGORIES as $key => $label)
                <a href="{{ route($route, ['niveau' => $level, 'bassin' => $pool, 'categorie' => $key]) }}"
                   class="btn btn-sm mb-1 {{ $category === (string) $key ? 'btn-dark' : 'btn-outline-secondary' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
</div>
