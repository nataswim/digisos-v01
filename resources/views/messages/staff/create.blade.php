@extends($layout)

@section('title', 'Nouveau message')

@php
    use App\Services\MessageService as MS;
    $r = fn ($n, $p = []) => route($area . '.messages.' . $n, $p);
    $anciens = collect(old('users', $preselection ? [$preselection->id] : []))->map(fn ($id) => (int) $id);
    $personnes = \App\Models\User::whereIn('id', $anciens)->get()->map(fn ($u) => ['id' => $u->id, 'label' => MS::userLabel($u), 'role' => MS::roleLabel($u)]);
@endphp

@section('content')
@include('messages._styles')
<div class="container-lg" style="max-width: 980px">
    <a href="{{ $r('index') }}" class="small"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Messagerie</a>
    <h1 class="h3 fw-bold mt-2 mb-1">Nouveau message</h1>
    <p class="text-muted">Chaque destinataire reçoit le message dans une conversation privée avec vous, et un e-mail l'avertit (sans le contenu). Les réponses ne sont visibles que par vous et lui.</p>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $r('store') }}" enctype="multipart/form-data" id="msg-form">
        @csrf
        <section class="card border-0 shadow-sm mb-4" aria-labelledby="dest-titre">
            <div class="card-header bg-white"><h2 id="dest-titre" class="h6 fw-bold mb-0">1. Destinataires <span class="text-muted fw-normal">(vous pouvez combiner)</span></h2></div>
            <div class="card-body">
                {{-- Personnes --}}
                <div class="mb-4">
                    <label for="msg-search" class="form-label fw-semibold"><i class="fas fa-user me-1 text-primary" aria-hidden="true"></i>Une ou plusieurs personnes</label>
                    <div class="position-relative" style="max-width: 460px">
                        <input type="search" id="msg-search" class="form-control" autocomplete="off" placeholder="Nom, prénom, e-mail ou prénom du nageur…"
                               role="combobox" aria-expanded="false" aria-controls="msg-results" aria-autocomplete="list">
                        <div id="msg-results" class="msg-results" role="listbox" hidden></div>
                    </div>
                    <div id="msg-chips" class="msg-chips mt-2" aria-live="polite"></div>
                </div>

                {{-- Groupes --}}
                @if ($groups->isNotEmpty())
                    <fieldset class="mb-4">
                        <legend class="form-label fw-semibold fs-6"><i class="fas fa-layer-group me-1 text-primary" aria-hidden="true"></i>Un ou plusieurs groupes du planning</legend>
                        <div class="msg-groups">
                            @foreach ($groups as $g)
                                <label class="msg-check">
                                    <input type="checkbox" name="groups[]" value="{{ $g->id }}" class="form-check-input mt-0" @checked(in_array($g->id, old('groups', [])))>
                                    <span class="flex-grow-1">{{ $g->name }}</span><span class="small text-muted">{{ $g->swimmers_count }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="form-text">Le message va aux comptes rattachés aux nageurs du groupe.</p>
                    </fieldset>
                @endif

                {{-- Compétition --}}
                <fieldset class="mb-4">
                    <legend class="form-label fw-semibold fs-6"><i class="fas fa-trophy me-1 text-primary" aria-hidden="true"></i>Les convoqués d'une compétition</legend>
                    <div class="row g-2">
                        <div class="col-md-7">
                            <label class="visually-hidden" for="competition_id">Compétition</label>
                            <select id="competition_id" name="competition_id" class="form-select">
                                <option value="">— aucune —</option>
                                @foreach ($competitions as $c)
                                    <option value="{{ $c->id }}" @selected((int) old('competition_id') === $c->id)>{{ $c->starts_on->format('d/m') }} · {{ $c->title }} ({{ $c->attendances_count }} convoqué{{ $c->attendances_count > 1 ? 's' : '' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="visually-hidden" for="competition_scope">Lesquels</label>
                            <select id="competition_scope" name="competition_scope" class="form-select">
                                @foreach ($scopes as $k => $libelle)<option value="{{ $k }}" @selected(old('competition_scope') === $k)>{{ ucfirst($libelle) }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                </fieldset>

                {{-- Tous --}}
                <fieldset>
                    <legend class="form-label fw-semibold fs-6"><i class="fas fa-users me-1 text-primary" aria-hidden="true"></i>Tout le monde</legend>
                    <div class="d-flex flex-wrap gap-2">
                        <label class="msg-check"><input type="checkbox" name="all_members" value="1" class="form-check-input mt-0" @checked(old('all_members'))> Tous les adhérents</label>
                        <label class="msg-check"><input type="checkbox" name="all_coaches" value="1" class="form-check-input mt-0" @checked(old('all_coaches'))> Tous les coachs</label>
                    </div>
                </fieldset>
            </div>
            <div class="card-footer bg-light" id="msg-preview" aria-live="polite">
                <span class="text-muted">Aucun destinataire pour l'instant.</span>
            </div>
        </section>

        <section class="card border-0 shadow-sm mb-4" aria-labelledby="msg-titre">
            <div class="card-header bg-white"><h2 id="msg-titre" class="h6 fw-bold mb-0">2. Message</h2></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="subject" class="form-label fw-semibold">Sujet</label>
                    <input type="text" id="subject" name="subject" maxlength="150" value="{{ old('subject') }}" class="form-control @error('subject') is-invalid @enderror" required>
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="body" class="form-label fw-semibold">Message</label>
                    <textarea id="body" name="body" rows="8" maxlength="10000" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="attachment" class="form-label fw-semibold">Pièce jointe <span class="text-muted fw-normal">(facultatif : PDF ou image, 5 Mo maximum)</span></label>
                    <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.webp" class="form-control @error('attachment') is-invalid @enderror" style="max-width: 460px">
                    @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="card-footer bg-white text-end">
                <button class="btn btn-primary" id="msg-send"><i class="fas fa-paper-plane me-1" aria-hidden="true"></i>Envoyer</button>
            </div>
        </section>
    </form>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('msg-form');
    const search = document.getElementById('msg-search');
    const results = document.getElementById('msg-results');
    const chips = document.getElementById('msg-chips');
    const preview = document.getElementById('msg-preview');
    const sendBtn = document.getElementById('msg-send');
    const token = form.querySelector('input[name=_token]').value;
    const choisis = new Map(@json($personnes).map(p => [p.id, p]));
    let compte = 0, minuterie, recherche;

    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]);

    function dessinerChips() {
        chips.innerHTML = '';
        choisis.forEach(p => {
            const el = document.createElement('span');
            el.className = 'msg-chip';
            el.innerHTML = `${esc(p.label)}${p.role ? ' <span class="small opacity-75">· ' + esc(p.role) + '</span>' : ''}
                <input type="hidden" name="users[]" value="${p.id}">
                <button type="button" aria-label="Retirer ${esc(p.label)}">&times;</button>`;
            el.querySelector('button').addEventListener('click', () => { choisis.delete(p.id); dessinerChips(); apercu(); });
            chips.appendChild(el);
        });
    }

    function fermer() { results.hidden = true; search.setAttribute('aria-expanded', 'false'); }

    search.addEventListener('input', () => {
        clearTimeout(recherche);
        const q = search.value.trim();
        if (q.length < 2) { fermer(); return; }
        recherche = setTimeout(async () => {
            const rep = await fetch(@json($r('search')) + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
            const liste = rep.ok ? await rep.json() : [];
            results.innerHTML = liste.length ? '' : '<p class="p-2 mb-0 text-muted small">Aucun compte trouvé.</p>';
            liste.forEach(p => {
                const b = document.createElement('button');
                b.type = 'button'; b.setAttribute('role', 'option');
                b.innerHTML = `<strong>${esc(p.label)}</strong> <span class="small text-muted">${esc(p.role)}${p.swimmers ? ' · nageur(s) : ' + esc(p.swimmers) : ''}</span>`;
                b.addEventListener('click', () => { choisis.set(p.id, p); dessinerChips(); search.value = ''; fermer(); search.focus(); apercu(); });
                results.appendChild(b);
            });
            results.hidden = false; search.setAttribute('aria-expanded', 'true');
        }, 250);
    });
    search.addEventListener('keydown', e => {
        if (e.key === 'Escape') fermer();
        if (e.key === 'ArrowDown' && !results.hidden) { e.preventDefault(); results.querySelector('button')?.focus(); }
        if (e.key === 'Enter') e.preventDefault();
    });
    results.addEventListener('keydown', e => {
        const b = document.activeElement;
        if (e.key === 'ArrowDown') { e.preventDefault(); b.nextElementSibling?.focus(); }
        if (e.key === 'ArrowUp') { e.preventDefault(); (b.previousElementSibling || search).focus(); }
        if (e.key === 'Escape') { fermer(); search.focus(); }
    });
    document.addEventListener('click', e => { if (!results.contains(e.target) && e.target !== search) fermer(); });

    async function apercu() {
        clearTimeout(minuterie);
        minuterie = setTimeout(async () => {
            const data = new FormData(form);
            data.delete('attachment'); data.delete('body');
            const rep = await fetch(@json($r('preview')), { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }, body: data });
            if (!rep.ok) return;
            const r = await rep.json();
            compte = r.count;
            let html = r.count
                ? `<strong>${r.count} destinataire${r.count > 1 ? 's' : ''}</strong>${r.count > 1 ? ' — une conversation privée chacun' : ''}
                   <details class="mt-1"><summary class="small">Voir la liste</summary><p class="small mb-0 mt-1">${r.names.map(esc).join(', ')}${r.count > r.names.length ? '…' : ''}</p></details>`
                : '<span class="text-muted">Aucun destinataire pour l\'instant.</span>';
            if (r.missing.length) {
                html += `<p class="small text-warning-emphasis mb-0 mt-2"><i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>${r.missing.length} nageur(s) sans compte rattaché ne recevront pas le message : ${r.missing.map(esc).join(', ')}.</p>`;
            }
            preview.innerHTML = html;
        }, 200);
    }

    form.addEventListener('change', e => { if (e.target.type !== 'file') apercu(); });
    form.addEventListener('submit', e => {
        if (compte > 1 && !confirm(`Envoyer ce message à ${compte} personnes ?`)) e.preventDefault();
        else sendBtn.disabled = true;
    });

    dessinerChips();
    apercu();
})();
</script>
@endpush
