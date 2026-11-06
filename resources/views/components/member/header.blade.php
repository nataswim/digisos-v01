{{--
    En-tête des pages de l'espace adhérent (remplace l'en-tête vidéo) :
    titre de la page + photo et nom du ou des nageurs rattachés au compte.

    <x-member.header title="Mes compétitions" icon="fa-trophy" lead="…">
        <a href="…" class="btn btn-outline-light">Bouton</a>
    </x-member.header>
--}}
@props(['title', 'icon' => null, 'lead' => null])
@php
    $user = auth()->user();
    $nageurs = \App\Models\PlanningSwimmer::ofUser($user?->id)->get();
@endphp
<x-member.styles />
<section class="member-head py-4 py-lg-5">
    <div class="container-lg">
        <p class="member-head-eyebrow text-uppercase fw-semibold mb-2">Espace adhérent</p>
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <h1 class="h2 fw-bold mb-2">
                    @if ($icon)<i class="fas {{ $icon }} me-2" aria-hidden="true"></i>@endif{{ $title }}
                </h1>
                @if ($lead)<p class="lead mb-3">{{ $lead }}</p>@endif
                @if ($slot->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2">{{ $slot }}</div>
                @endif
            </div>
            <div class="col-lg-6">
                <ul class="member-people list-unstyled mb-0 justify-content-lg-end" aria-label="{{ $nageurs->count() > 1 ? 'Nageurs rattachés à votre compte' : 'Adhérent' }}">
                    @forelse ($nageurs as $nageur)
                        <li class="member-person">
                            <x-member.avatar :swimmer="$nageur" />
                            <span>
                                <span class="member-person-name d-block">{{ $nageur->full_name }}</span>
                                @if ($nageur->groups->isNotEmpty())<span class="member-person-meta">{{ $nageur->groups->pluck('name')->implode(' · ') }}</span>@endif
                            </span>
                        </li>
                    @empty
                        <li class="member-person">
                            <x-member.avatar :user="$user" />
                            <span class="member-person-name">{{ $user?->first_name ? trim($user->first_name . ' ' . mb_strtoupper((string) $user->last_name)) : $user?->name }}</span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</section>
