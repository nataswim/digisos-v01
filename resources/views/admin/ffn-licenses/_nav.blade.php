{{-- En-tête commun du module Licences FFN. Variable : $active (index | import) --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-id-card text-primary me-2" aria-hidden="true"></i>Licences FFN</h1>
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link {{ $active === 'index' ? 'active' : '' }}" href="{{ route('admin.ffn-licenses.index') }}">
                <i class="fas fa-users me-1" aria-hidden="true"></i>Licenciés
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $active === 'import' ? 'active' : '' }}" href="{{ route('admin.ffn-licenses.import.create') }}">
                <i class="fas fa-file-import me-1" aria-hidden="true"></i>Importer
            </a>
        </li>
    </ul>
</div>
<p class="small text-muted mb-3">
    <i class="fas fa-user-shield me-1" aria-hidden="true"></i>
    Données personnelles des licenciés (adresses, téléphones, e-mails) : réservées aux administrateurs, à ne pas diffuser.
</p>
