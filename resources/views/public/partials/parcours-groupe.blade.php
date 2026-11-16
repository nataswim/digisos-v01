{{-- Carte d'un groupe sur la page « Le parcours du nageur ». Reçoit $g (voir PublicParcoursController). --}}
<div class="parcours-groupe" style="--c: {{ $g['couleur'] }}">
    <div class="parcours-groupe-tete">
        <span class="parcours-groupe-section">{{ $g['section'] }}</span>
        @if ($g['competition'] === true)
            <span class="parcours-badge"><i class="fas fa-trophy" aria-hidden="true"></i> Compétition</span>
        @elseif ($g['competition'] === false)
            <span class="parcours-badge parcours-badge--loisir">Hors compétition officielle</span>
        @endif
    </div>
    <h5 class="parcours-groupe-nom"><a href="{{ $g['lien'] }}">{{ $g['nom'] }}</a></h5>
    <p class="parcours-groupe-resume">{{ $g['resume'] }}</p>
    <dl class="parcours-groupe-infos">
        <div><dt>Âge</dt><dd>{{ $g['age'] }}</dd></div>
        <div><dt>Prérequis</dt><dd>{{ $g['prerequis'] }}</dd></div>
        <div><dt>Séances</dt><dd>{{ $g['seances'] }} par semaine</dd></div>
    </dl>
    @if ($g['creneaux'])
        <p class="parcours-groupe-creneaux">
            <i class="far fa-clock" aria-hidden="true"></i>
            <a href="{{ $g['ancre'] }}">{{ implode(' · ', $g['creneaux']) }}</a>
        </p>
    @else
        <p class="parcours-groupe-creneaux"><i class="far fa-clock" aria-hidden="true"></i> <a href="{{ $g['ancre'] }}">Voir les créneaux</a></p>
    @endif
</div>
