{{--
    En-tête compact des outils (sans vidéo) : fil d'Ariane, titre, une ligne d'introduction.
    Paramètres : title, lead (facultatif), icon (Font Awesome), home (true sur la page d'accueil des outils)
--}}
@php($home = $home ?? false)
<section class="tools-header py-3 border-bottom">
    <div class="container">
        <nav aria-label="Fil d'Ariane" class="small mb-1">
            <a href="{{ route('home') }}">Accueil</a>
            <span class="mx-1" aria-hidden="true">›</span>
            @if ($home)
                <span aria-current="page">Outils &amp; calculateurs</span>
            @else
                <a href="{{ route('tools.index') }}">Outils &amp; calculateurs</a>
            @endif
        </nav>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <h1 class="h3 fw-bold mb-0">
                <i class="fas {{ $icon ?? 'fa-calculator' }} me-2" aria-hidden="true"></i>{{ $title }}
            </h1>
            @unless ($home)
                <a href="{{ route('tools.index') }}" class="btn btn-sm btn-outline-primary ms-auto">
                    <i class="fas fa-th me-1" aria-hidden="true"></i>Tous les outils
                </a>
            @endunless
        </div>
        @if (! empty($lead))
            <p class="text-muted mb-0 mt-1 small">{{ $lead }}</p>
        @endif
        <div class="tools-share d-flex flex-wrap align-items-center gap-1 mt-2">
            <span class="small text-muted me-1"><i class="fas fa-share-alt me-1" aria-hidden="true"></i>Partager :</span>
            <a href="#" data-network="facebook" class="btn btn-sm tools-share-btn" style="--c:#1877f2" target="_blank" rel="noopener noreferrer" title="Partager sur Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i><span class="visually-hidden">Facebook</span></a>
            <a href="#" data-network="whatsapp" class="btn btn-sm tools-share-btn" style="--c:#25d366" target="_blank" rel="noopener noreferrer" title="Partager sur WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i><span class="visually-hidden">WhatsApp</span></a>
            <a href="#" data-network="linkedin" class="btn btn-sm tools-share-btn" style="--c:#0a66c2" target="_blank" rel="noopener noreferrer" title="Partager sur LinkedIn"><i class="fab fa-linkedin-in" aria-hidden="true"></i><span class="visually-hidden">LinkedIn</span></a>
            <a href="#" data-network="twitter" class="btn btn-sm tools-share-btn" style="--c:#111" target="_blank" rel="noopener noreferrer" title="Partager sur X"><i class="fab fa-twitter" aria-hidden="true"></i><span class="visually-hidden">X (Twitter)</span></a>
            <a href="#" data-network="email" class="btn btn-sm tools-share-btn" style="--c:#6c757d" title="Partager par e-mail"><i class="fas fa-envelope" aria-hidden="true"></i><span class="visually-hidden">E-mail</span></a>
            <button type="button" class="btn btn-sm tools-share-btn tools-copy" style="--c:#2f80b8" title="Copier le lien"><i class="fas fa-link" aria-hidden="true"></i><span class="ms-1 small copy-label">Copier le lien</span></button>
        </div>
    </div>
</section>

@once
    @push('styles')
        <style>
            .tools-header { background: #f4f9fb; }
            .tools-header nav a { text-decoration: none; }
            .tools-share-btn { color: #fff; background: var(--c); border: 0; min-width: 2rem; }
            .tools-share-btn:hover, .tools-share-btn:focus { color: #fff; filter: brightness(1.1); }
        </style>
    @endpush
    @push('scripts')
        <script>
            document.addEventListener('click', async e => {
                const btn = e.target.closest('.tools-copy');
                if (!btn) return;
                const label = btn.querySelector('.copy-label');
                try { await navigator.clipboard.writeText(location.href); label.textContent = 'Lien copié !'; }
                catch (err) { window.prompt('Copiez le lien :', location.href); }
                setTimeout(() => { label.textContent = 'Copier le lien'; }, 2000);
            });
        </script>
    @endpush
@endonce
