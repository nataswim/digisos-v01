{{-- Photo ronde d'un nageur (ou initiales). Paramètres : swimmer (PlanningSwimmer) ou user (User), size (''|'lg'). --}}
@props(['swimmer' => null, 'user' => null, 'size' => ''])
@php
    if ($swimmer) {
        $src = $swimmer->photo_url;
        $initiales = $swimmer->initials;
    } else {
        $src = $user?->avatar ?: null;
        $nom = trim(($user?->first_name ?? '') . ' ' . ($user?->last_name ?? '')) ?: ($user?->name ?? '');
        $initiales = collect(preg_split('/\s+/', $nom))->filter()->take(2)->map(fn ($m) => mb_strtoupper(mb_substr($m, 0, 1)))->implode('');
    }
@endphp
<span {{ $attributes->class(['member-avatar', 'member-avatar--' . $size => $size]) }} aria-hidden="true">
    @if ($src)
        <img src="{{ $src }}" alt="" loading="lazy">
    @else
        {{ $initiales ?: '?' }}
    @endif
</span>
