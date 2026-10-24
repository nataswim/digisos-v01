{{--
    Encadré de statut en haut de l'accueil d'un espace (visiteur, adhérent).
    Paramètres : icon (Font Awesome), titre, texte, variante ('info' bleu | 'membre' vert | 'editeur' doré)
--}}
<div class="espace-statut espace-statut--{{ $variante ?? 'info' }} d-flex gap-3 align-items-start mb-5" role="note">
    <span class="espace-statut-icon" aria-hidden="true"><i class="fas {{ $icon }}"></i></span>
    <div>
        <p class="fw-bold mb-1">{{ $titre }}</p>
        <p class="mb-0">{{ $texte }}</p>
    </div>
</div>

@once
@push('styles')
<style>
    .espace-statut { padding: 1.25rem 1.5rem; border-radius: 14px; color: #1d293d; }
    .espace-statut--info { background: #F0F9FF; border-left: 5px solid #0090D4; }
    .espace-statut--membre { background: #ECFDF5; border-left: 5px solid #059669; }
    .espace-statut--editeur { background: #FBF6EC; border-left: 5px solid #D49F45; }
    .espace-statut-icon {
        display: inline-flex; flex-shrink: 0; width: 44px; height: 44px; border-radius: 50%;
        align-items: center; justify-content: center; color: #fff; font-size: 1.1rem;
    }
    .espace-statut--info .espace-statut-icon { background: #0090D4; }
    .espace-statut--membre .espace-statut-icon { background: #059669; }
    .espace-statut--editeur .espace-statut-icon { background: #D49F45; color: #020618; }
</style>
@endpush
@endonce
