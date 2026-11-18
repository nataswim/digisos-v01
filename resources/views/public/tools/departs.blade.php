@extends('layouts.tools')

@section('title', 'Départs avec bip - Séries chronométrées natation')
@section('meta_description', 'Programmez vos séries de natation : départ toutes les X secondes, nombre de répétitions et de séries, récupération. Bips 3-2-1 et bip de départ, chrono par nageur et export des temps.')

@section('content')
@include('public.tools.partials.header', ['title' => 'Départs avec bip', 'lead' => 'Séries à intervalle fixe : bips 3-2-1, bip de départ et temps de chaque répétition pour plusieurs nageurs.', 'icon' => 'fa-bell'])

<section class="py-4">
    <div class="container">

        {{-- ===================== Réglages ===================== --}}
        <div class="card shadow-sm border-0 mb-4" id="setupCard">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0"><i class="fas fa-sliders-h me-2" aria-hidden="true"></i>Programmer la série</h2>
            </div>
            <div class="card-body">
                <div id="setupError" class="alert alert-danger d-none" role="alert"></div>

                <div class="row g-3">
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label fw-bold" for="intMin">Départ toutes les</label>
                        <div class="input-group">
                            <input type="number" id="intMin" class="form-control" min="0" max="20" value="1" inputmode="numeric" aria-label="Minutes">
                            <span class="input-group-text">min</span>
                            <input type="number" id="intSec" class="form-control" min="0" max="59" value="40" inputmode="numeric" aria-label="Secondes">
                            <span class="input-group-text">s</span>
                        </div>
                    </div>
                    <div class="col-6 col-lg-2">
                        <label class="form-label fw-bold" for="reps">Répétitions</label>
                        <input type="number" id="reps" class="form-control" min="1" max="50" value="6" inputmode="numeric">
                    </div>
                    <div class="col-6 col-lg-2">
                        <label class="form-label fw-bold" for="series">Séries</label>
                        <input type="number" id="series" class="form-control" min="1" max="10" value="1" inputmode="numeric">
                    </div>
                    <div class="col-sm-6 col-lg-3" id="recoveryField">
                        <label class="form-label fw-bold" for="recMin">Récupération entre séries</label>
                        <div class="input-group">
                            <input type="number" id="recMin" class="form-control" min="0" max="20" value="2" inputmode="numeric" aria-label="Minutes de récupération">
                            <span class="input-group-text">min</span>
                            <input type="number" id="recSec" class="form-control" min="0" max="59" value="0" inputmode="numeric" aria-label="Secondes de récupération">
                            <span class="input-group-text">s</span>
                        </div>
                    </div>
                    <div class="col-6 col-lg-2">
                        <label class="form-label fw-bold" for="leadIn">Premier départ dans</label>
                        <select id="leadIn" class="form-select">
                            <option value="5">5 s</option>
                            <option value="10" selected>10 s</option>
                            <option value="15">15 s</option>
                            <option value="30">30 s</option>
                        </select>
                    </div>

                    <div class="col-6 col-lg-3">
                        <label class="form-label fw-bold" for="swimmers">Nageurs / lignes</label>
                        <select id="swimmers" class="form-select">
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" @selected($i === 2)>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label fw-bold" for="stagger">Décalage entre nageurs</label>
                        <select id="stagger" class="form-select">
                            <option value="0">Aucun (départ ensemble)</option>
                            <option value="5">5 s</option>
                            <option value="10">10 s</option>
                            <option value="15">15 s</option>
                            <option value="20">20 s</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-6">
                        <span class="form-label fw-bold d-block mb-2">Noms</span>
                        <div id="namesRow" class="row g-2"></div>
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-3 py-2" id="summary" aria-live="polite"></div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="button" id="startBtn" class="btn btn-primary btn-lg fw-bold">
                        <i class="fas fa-play me-2" aria-hidden="true"></i>Lancer la série
                    </button>
                    <button type="button" class="btn btn-outline-secondary preset" data-preset="1,40,6,1,0,0">6 × départ 1'40</button>
                    <button type="button" class="btn btn-outline-secondary preset" data-preset="0,50,10,1,0,0">10 × départ 50"</button>
                    <button type="button" class="btn btn-outline-secondary preset" data-preset="1,30,4,3,2,0">3 × (4 × 1'30), récup 2'</button>
                </div>
            </div>
        </div>

        {{-- ===================== Séance en cours ===================== --}}
        <div id="runPanel" class="d-none">
            <div class="card shadow border-0 mb-3 text-white" id="bigPanel">
                <div class="card-body text-center py-4">
                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-2">
                        <span class="badge bg-light text-dark fs-6" id="seriesBadge">Série 1/1</span>
                        <span class="badge bg-light text-dark fs-6" id="repBadge">Rép. 1/6</span>
                        <span class="badge bg-warning text-dark fs-6" id="stateBadge">Prêt</span>
                    </div>
                    <div class="small text-uppercase opacity-75" id="nextLabel">Prochain départ</div>
                    <div class="dep-countdown fw-bold" id="countdown" aria-hidden="true">0:10.0</div>
                    <div class="small opacity-75">Chrono de la séance : <strong id="globalClock">0:00.0</strong></div>
                    <div class="visually-hidden" aria-live="assertive" id="srAnnounce"></div>
                    <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                        <button type="button" id="pauseBtn" class="btn btn-warning btn-lg fw-bold">
                            <i class="fas fa-pause me-2" aria-hidden="true"></i>Pause
                        </button>
                        <button type="button" id="muteBtn" class="btn btn-light btn-lg" aria-pressed="false">
                            <i class="fas fa-volume-up me-1" aria-hidden="true"></i><span>Son</span>
                        </button>
                        <button type="button" id="stopBtn" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-stop me-2" aria-hidden="true"></i>Terminer
                        </button>
                    </div>
                </div>
            </div>

            <p class="small text-muted mb-2">
                <i class="fas fa-keyboard me-1" aria-hidden="true"></i>
                Cliquez sur « Arrivée » quand le nageur touche le mur (touches <kbd>1</kbd> à <kbd>8</kbd> au clavier, <kbd>Espace</kbd> pour la pause).
            </p>
            <div class="row g-3 mb-4" id="swimmerCards"></div>
        </div>

        {{-- ===================== Résultats ===================== --}}
        <div class="card shadow-sm border-0 mb-4 d-none" id="resultsCard">
            <div class="card-header bg-success text-white d-flex flex-wrap align-items-center gap-2">
                <h2 class="h5 mb-0 me-auto"><i class="fas fa-list-ol me-2" aria-hidden="true"></i>Temps par répétition</h2>
                <button type="button" id="csvBtn" class="btn btn-light btn-sm"><i class="fas fa-file-csv me-1" aria-hidden="true"></i>Export CSV</button>
                <button type="button" id="newBtn" class="btn btn-outline-light btn-sm"><i class="fas fa-redo me-1" aria-hidden="true"></i>Nouvelle série</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle mb-0 text-center" id="resultsTable"></table>
                </div>
            </div>
        </div>

        {{-- ===================== Mode d'emploi ===================== --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h2 class="h5 mb-0"><i class="fas fa-question-circle me-2" aria-hidden="true"></i>Comment ça marche ?</h2>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h3 class="h6 fw-bold">Les signaux</h3>
                        <ul class="small mb-0">
                            <li>3 bips courts à 3, 2 et 1 seconde du départ ;</li>
                            <li>un bip long et plus aigu au départ, avec un flash de l'écran ;</li>
                            <li>avec un décalage, chaque nageur a son propre compte à rebours.</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h3 class="h6 fw-bold">Les temps</h3>
                        <ul class="small mb-0">
                            <li>le chrono de chaque nageur part à son bip de départ ;</li>
                            <li>« Arrivée » enregistre le temps de la répétition ;</li>
                            <li>le temps restant avant le départ suivant s'affiche : c'est la récupération.</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h3 class="h6 fw-bold">Conseils au bord du bassin</h3>
                        <ul class="small mb-0">
                            <li>montez le volume du téléphone ou branchez une enceinte ;</li>
                            <li>l'écran reste allumé pendant la série (selon le navigateur) ;</li>
                            <li>la pause arrête les bips et tous les chronos en même temps.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('public.tools.partials.credit')
@endsection

@push('styles')
<style>
    #bigPanel { background: linear-gradient(135deg, #006170 0%, #004d5a 100%); transition: background-color .15s; }
    #bigPanel.flash { background: #f0a830 !important; }
    #bigPanel.flash .dep-countdown { color: #1d1d1b; }
    .dep-countdown { font-family: 'Courier New', monospace; font-size: clamp(3.5rem, 14vw, 7rem); line-height: 1.1; }
    .dep-countdown.soon { color: #ffd166; }
    .swimmer-card .card-header { color: #fff; }
    .swimmer-card .run-time { font-family: 'Courier New', monospace; font-size: 2rem; font-weight: 700; }
    .swimmer-card .arrive-btn { min-height: 4rem; font-size: 1.25rem; }
    .swimmer-card.waiting { opacity: .75; }
    #resultsTable th { white-space: nowrap; }
</style>
@endpush

@push('scripts')
<script>
(() => {
    const COLORS = ['#0d6efd', '#dc3545', '#198754', '#6f42c1', '#fd7e14', '#20c997', '#6c757d', '#d63384'];
    const STORE = 'cnbb_departs_config';
    const $ = id => document.getElementById(id);

    // ---------- Utilitaires ----------
    const int = (id, min, max) => Math.min(max, Math.max(min, parseInt($(id).value, 10) || 0));
    function fmt(sec, decimals = 1) {
        const neg = sec < 0; sec = Math.abs(sec);
        const m = Math.floor(sec / 60);
        const s = (sec - m * 60).toFixed(decimals).padStart(decimals ? 3 + decimals : 2, '0');
        return (neg ? '-' : '') + m + ':' + s;
    }
    const fmtInterval = s => (Math.floor(s / 60) ? Math.floor(s / 60) + "'" : '') + String(s % 60).padStart(Math.floor(s / 60) ? 2 : 1, '0') + '"';

    // ---------- Réglages ----------
    function readConfig() {
        const n = int('swimmers', 1, 8);
        const names = [];
        for (let k = 0; k < n; k++) {
            const el = $('name' + k);
            names.push((el && el.value.trim()) || 'Nageur ' + (k + 1));
        }
        return {
            interval: int('intMin', 0, 20) * 60 + int('intSec', 0, 59),
            reps: int('reps', 1, 50),
            series: int('series', 1, 10),
            recovery: int('recMin', 0, 20) * 60 + int('recSec', 0, 59),
            leadIn: parseInt($('leadIn').value, 10),
            swimmers: n,
            stagger: parseInt($('stagger').value, 10),
            names,
        };
    }

    function renderNames(saved = []) {
        const n = int('swimmers', 1, 8);
        const current = [];
        $('namesRow').querySelectorAll('input').forEach(i => current.push(i.value));
        $('namesRow').innerHTML = '';
        for (let k = 0; k < n; k++) {
            const col = document.createElement('div');
            col.className = 'col-6 col-md-3';
            const input = document.createElement('input');
            input.type = 'text'; input.maxLength = 20; input.className = 'form-control form-control-sm';
            input.id = 'name' + k; input.placeholder = 'Nageur ' + (k + 1);
            input.setAttribute('aria-label', 'Nom du nageur ' + (k + 1));
            input.value = current[k] ?? saved[k] ?? '';
            input.style.borderLeft = '4px solid ' + COLORS[k];
            col.appendChild(input);
            $('namesRow').appendChild(col);
        }
    }

    function validate(c) {
        if (c.interval < 10) return 'L\'intervalle de départ doit être d\'au moins 10 secondes.';
        const needed = (c.swimmers - 1) * c.stagger + 5;
        if (c.interval < needed) return 'Avec ' + c.swimmers + ' nageurs décalés de ' + c.stagger + ' s, le départ doit être d\'au moins ' + fmtInterval(needed) + '.';
        return null;
    }

    function updateSummary() {
        const c = readConfig();
        $('recoveryField').style.opacity = c.series > 1 ? 1 : .5;
        const err = validate(c);
        if (err) { $('summary').className = 'alert alert-warning mt-3 mb-3 py-2'; $('summary').textContent = err; return; }
        const total = c.series * c.reps * c.interval + (c.series - 1) * c.recovery + (c.swimmers - 1) * c.stagger;
        const serie = c.reps + ' × départ ' + fmtInterval(c.interval);
        const txt = (c.series > 1 ? c.series + ' × (' + serie + '), récupération ' + fmtInterval(c.recovery) : serie)
            + ' · ' + c.swimmers + (c.swimmers > 1 ? ' nageurs' : ' nageur')
            + (c.swimmers > 1 && c.stagger ? ' décalés de ' + c.stagger + ' s' : '')
            + ' · durée ≈ ' + Math.floor(total / 60) + ' min ' + String(total % 60).padStart(2, '0') + ' s';
        $('summary').className = 'alert alert-info mt-3 mb-3 py-2';
        $('summary').textContent = txt;
    }

    function saveConfig(c) { try { localStorage.setItem(STORE, JSON.stringify(c)); } catch (e) {} }
    function loadConfig() {
        try {
            const c = JSON.parse(localStorage.getItem(STORE) || 'null');
            if (!c) return [];
            $('intMin').value = Math.floor(c.interval / 60); $('intSec').value = c.interval % 60;
            $('reps').value = c.reps; $('series').value = c.series;
            $('recMin').value = Math.floor(c.recovery / 60); $('recSec').value = c.recovery % 60;
            $('leadIn').value = c.leadIn; $('swimmers').value = c.swimmers; $('stagger').value = c.stagger;
            return c.names || [];
        } catch (e) { return []; }
    }

    // ---------- Séance ----------
    let ctx = null, master = null, t0 = 0, cfg = null, plan = [], departures = [], ended = false;
    let results = [], muted = false, lastFlash = -1, wakeLock = null, raf = null, timer = null;

    // Planning : un départ par nageur, répétition et série (temps en secondes depuis le lancement)
    function buildPlan(c) {
        const list = [];
        for (let s = 0; s < c.series; s++) {
            const start = c.leadIn + s * (c.reps * c.interval + c.recovery);
            for (let r = 0; r < c.reps; r++) {
                for (let k = 0; k < c.swimmers; k++) {
                    list.push({ t: start + r * c.interval + k * c.stagger, s, r, k, idx: s * c.reps + r });
                }
            }
        }
        return list;
    }

    function beep(at, freq, dur, type, vol) {
        const o = ctx.createOscillator(), g = ctx.createGain();
        o.type = type; o.frequency.value = freq;
        g.gain.setValueAtTime(0.0001, at);
        g.gain.exponentialRampToValueAtTime(vol, at + 0.01);
        g.gain.setValueAtTime(vol, at + dur - 0.05);
        g.gain.exponentialRampToValueAtTime(0.0001, at + dur);
        o.connect(g); g.connect(master);
        o.start(at); o.stop(at + dur + 0.02);
    }

    // Tous les bips sont programmés d'avance sur l'horloge audio : précis, et suspendus par la pause
    function scheduleBeeps() {
        departures = [...new Set(plan.map(p => p.t))].sort((a, b) => a - b);
        departures.forEach(d => {
            [3, 2, 1].forEach(n => { if (d - n >= 0) beep(t0 + d - n, 880, 0.15, 'sine', 0.6); });
            beep(t0 + d, 1320, 0.7, 'square', 0.9);
        });
    }

    const now = () => ctx ? ctx.currentTime - t0 : 0;

    async function start() {
        cfg = readConfig();
        const err = validate(cfg);
        if (err) { $('setupError').textContent = err; $('setupError').classList.remove('d-none'); return; }
        $('setupError').classList.add('d-none');
        saveConfig(cfg);

        const AC = window.AudioContext || window.webkitAudioContext;
        if (!AC) { $('setupError').textContent = 'Ce navigateur ne permet pas de jouer les bips.'; $('setupError').classList.remove('d-none'); return; }
        ctx = new AC();
        if (ctx.state === 'suspended') await ctx.resume();
        master = ctx.createGain(); master.gain.value = muted ? 0 : 1; master.connect(ctx.destination);
        t0 = ctx.currentTime + 0.1;

        plan = buildPlan(cfg);
        results = Array.from({ length: cfg.swimmers }, () => Array(cfg.series * cfg.reps).fill(null));
        ended = false; lastFlash = -1;
        scheduleBeeps();
        renderSwimmers();

        $('setupCard').classList.add('d-none');
        $('resultsCard').classList.add('d-none');
        $('runPanel').classList.remove('d-none');
        setPauseLabel(false);
        requestWakeLock();
        loop();
        timer = setInterval(tick, 200); // relais quand l'onglet n'anime plus
        window.scrollTo({ top: $('runPanel').offsetTop - 10, behavior: 'smooth' });
    }

    function renderSwimmers() {
        const wrap = $('swimmerCards'); wrap.innerHTML = '';
        const col = cfg.swimmers === 1 ? 'col-12' : (cfg.swimmers === 2 ? 'col-md-6' : 'col-md-6 col-xl-3');
        cfg.names.forEach((name, k) => {
            const div = document.createElement('div');
            div.className = col;
            div.innerHTML = `
                <div class="card swimmer-card shadow-sm h-100" id="sw${k}">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background:${COLORS[k]}">
                        <span class="fw-bold"></span><span class="badge bg-light text-dark rep-label"></span>
                    </div>
                    <div class="card-body text-center">
                        <div class="run-time">0:00.0</div>
                        <div class="small text-muted mb-2 info">&nbsp;</div>
                        <button type="button" class="btn btn-success w-100 fw-bold arrive-btn" data-k="${k}">
                            <i class="fas fa-flag-checkered me-2" aria-hidden="true"></i>Arrivée <kbd class="ms-1">${k + 1}</kbd>
                        </button>
                        <div class="small mt-2 last">&nbsp;</div>
                    </div>
                </div>`;
            div.querySelector('.card-header .fw-bold').textContent = name; // nom saisi : texte seulement
            wrap.appendChild(div);
        });
        wrap.querySelectorAll('.arrive-btn').forEach(b => b.addEventListener('click', () => arrive(+b.dataset.k)));
    }

    // Départ en cours d'un nageur : le dernier départ déjà donné
    function currentOf(k, t) {
        let cur = null;
        for (const p of plan) { if (p.k === k && p.t <= t) cur = p; }
        return cur;
    }
    const nextOf = (k, t) => plan.find(p => p.k === k && p.t > t) || null;

    function arrive(k) {
        if (!ctx || ended || ctx.state !== 'running') return;
        const t = now(), cur = currentOf(k, t);
        if (!cur || results[k][cur.idx] !== null) return;
        results[k][cur.idx] = t - cur.t;
        if (results.every(r => r[r.length - 1] !== null)) finish();
    }

    function tick() {
        if (!ctx) return;
        const t = now();
        const next = plan.find(p => p.t > t);
        const lastDep = departures[departures.length - 1];

        // Grand affichage
        if (next) {
            const wait = next.t - t;
            $('countdown').textContent = wait < 10 ? wait.toFixed(1) : fmt(wait);
            $('countdown').classList.toggle('soon', wait <= 3);
            const who = cfg.stagger && cfg.swimmers > 1 ? ' — ' + cfg.names[next.k] : '';
            $('nextLabel').textContent = 'Prochain départ' + who;
            $('seriesBadge').textContent = 'Série ' + (next.s + 1) + '/' + cfg.series;
            $('repBadge').textContent = 'Rép. ' + (next.r + 1) + '/' + cfg.reps;
            const between = next.r === 0 && next.s > 0 && plan.filter(p => p.t <= t).every(p => p.s < next.s);
            setState(t < cfg.leadIn ? 'Préparez-vous' : (between ? 'Récupération entre séries' : 'En cours'), between ? 'bg-info' : 'bg-warning');
        } else {
            $('countdown').textContent = '—';
            $('countdown').classList.remove('soon');
            $('nextLabel').textContent = 'Dernière répétition : cliquez « Arrivée » ou « Terminer »';
            setState('Dernière répétition', 'bg-warning');
        }
        $('globalClock').textContent = fmt(Math.max(0, t - cfg.leadIn));

        // Flash et annonce au départ
        const passed = departures.filter(d => d <= t).length - 1;
        if (passed > lastFlash) {
            lastFlash = passed;
            const p = $('bigPanel'); p.classList.add('flash'); setTimeout(() => p.classList.remove('flash'), 350);
            if (navigator.vibrate) navigator.vibrate(200);
            $('srAnnounce').textContent = 'Départ';
        }

        // Cartes nageurs
        cfg.names.forEach((_, k) => {
            const card = $('sw' + k); if (!card) return;
            const cur = currentOf(k, t), nxt = nextOf(k, t);
            const btn = card.querySelector('.arrive-btn');
            if (!cur) {
                card.classList.add('waiting');
                card.querySelector('.run-time').textContent = fmt(-(nxt.t - t));
                card.querySelector('.rep-label').textContent = 'Attente';
                card.querySelector('.info').textContent = 'Départ dans ' + Math.ceil(nxt.t - t) + ' s';
                btn.disabled = true;
                return;
            }
            card.classList.remove('waiting');
            const done = results[k][cur.idx];
            card.querySelector('.rep-label').textContent = (cfg.series > 1 ? 'S' + (cur.s + 1) + ' · ' : '') + 'Rép. ' + (cur.r + 1);
            card.querySelector('.run-time').textContent = fmt(done !== null ? done : t - cur.t);
            card.querySelector('.run-time').classList.toggle('text-success', done !== null);
            card.querySelector('.info').textContent = done !== null
                ? (nxt ? 'Récupération : ' + Math.max(0, Math.ceil(nxt.t - t)) + ' s avant le départ' : 'Terminé')
                : (nxt ? 'Départ suivant dans ' + Math.ceil(nxt.t - t) + ' s' : 'Dernière répétition');
            btn.disabled = done !== null || ended;
            const prev = results[k].slice(0, cur.idx + 1).filter(v => v !== null);
            card.querySelector('.last').textContent = prev.length ? 'Dernier temps : ' + fmt(prev[prev.length - 1], 2) : ' ';
        });

        // Après la dernière répétition, on s'arrête dès que tout le monde est arrivé
        if (!ended && t > lastDep + cfg.interval * 2) finish();
    }

    function loop() { tick(); raf = requestAnimationFrame(loop); }

    function setState(txt, cls) {
        const b = $('stateBadge'); b.textContent = txt; b.className = 'badge fs-6 ' + cls + (cls === 'bg-info' ? '' : ' text-dark');
    }

    function setPauseLabel(paused) {
        $('pauseBtn').innerHTML = paused
            ? '<i class="fas fa-play me-2" aria-hidden="true"></i>Reprendre'
            : '<i class="fas fa-pause me-2" aria-hidden="true"></i>Pause';
    }

    async function togglePause() {
        if (!ctx || ended) return;
        if (ctx.state === 'running') { await ctx.suspend(); setPauseLabel(true); setState('Pause', 'bg-secondary'); }
        else { await ctx.resume(); setPauseLabel(false); }
    }

    function finish() {
        if (ended) return;
        ended = true;
        cancelAnimationFrame(raf); clearInterval(timer);
        if (ctx) { ctx.close(); ctx = null; }
        releaseWakeLock();
        $('runPanel').classList.add('d-none');
        renderResults();
        $('resultsCard').classList.remove('d-none');
        $('setupCard').classList.remove('d-none');
        $('resultsCard').scrollIntoView({ behavior: 'smooth' });
    }

    function columns() {
        const cols = [];
        for (let s = 0; s < cfg.series; s++) for (let r = 0; r < cfg.reps; r++)
            cols.push((cfg.series > 1 ? 'S' + (s + 1) + ' ' : '') + 'R' + (r + 1));
        return cols;
    }

    function stats(row) {
        const v = row.filter(x => x !== null);
        return v.length ? { avg: v.reduce((a, b) => a + b, 0) / v.length, best: Math.min(...v) } : null;
    }

    function renderResults() {
        const table = $('resultsTable'); table.innerHTML = '';
        const head = table.createTHead().insertRow();
        ['Nageur', ...columns(), 'Moyenne', 'Meilleur'].forEach(h => { const th = document.createElement('th'); th.textContent = h; head.appendChild(th); });
        const body = table.createTBody();
        cfg.names.forEach((name, k) => {
            const tr = body.insertRow();
            const th = document.createElement('th'); th.textContent = name; th.style.borderLeft = '4px solid ' + COLORS[k]; tr.appendChild(th);
            const st = stats(results[k]);
            results[k].forEach(v => { const td = tr.insertCell(); td.textContent = v === null ? '—' : fmt(v, 2); if (st && v === st.best) td.className = 'fw-bold text-success'; });
            tr.insertCell().textContent = st ? fmt(st.avg, 2) : '—';
            tr.insertCell().textContent = st ? fmt(st.best, 2) : '—';
        });
    }

    function exportCsv() {
        const cfgLine = 'Départ ' + fmtInterval(cfg.interval) + ' ; ' + cfg.reps + ' rép. ; ' + cfg.series + ' série(s)';
        const rows = [[cfgLine], ['Nageur', ...columns(), 'Moyenne', 'Meilleur']];
        cfg.names.forEach((name, k) => {
            const st = stats(results[k]);
            rows.push([name, ...results[k].map(v => v === null ? '' : fmt(v, 2)), st ? fmt(st.avg, 2) : '', st ? fmt(st.best, 2) : '']);
        });
        const csv = '﻿' + rows.map(r => r.map(c => '"' + String(c).replace(/"/g, '""') + '"').join(';')).join('\r\n');
        const a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv;charset=utf-8' }));
        a.download = 'departs-' + new Date().toISOString().slice(0, 10) + '.csv';
        document.body.appendChild(a); a.click(); a.remove();
        setTimeout(() => URL.revokeObjectURL(a.href), 1000);
    }

    async function requestWakeLock() {
        try { if ('wakeLock' in navigator) wakeLock = await navigator.wakeLock.request('screen'); } catch (e) { wakeLock = null; }
    }
    function releaseWakeLock() { try { if (wakeLock) wakeLock.release(); } catch (e) {} wakeLock = null; }
    document.addEventListener('visibilitychange', () => { if (document.visibilityState === 'visible' && ctx && !ended) requestWakeLock(); });

    // ---------- Événements ----------
    document.addEventListener('DOMContentLoaded', () => {
        const savedNames = loadConfig();
        renderNames(savedNames);
        updateSummary();

        ['intMin', 'intSec', 'reps', 'series', 'recMin', 'recSec', 'leadIn', 'stagger'].forEach(id => $(id).addEventListener('input', updateSummary));
        $('swimmers').addEventListener('change', () => { renderNames(); updateSummary(); });
        $('stagger').addEventListener('change', updateSummary);

        document.querySelectorAll('.preset').forEach(b => b.addEventListener('click', () => {
            const [m, s, reps, series, rm, rs] = b.dataset.preset.split(',');
            $('intMin').value = m; $('intSec').value = s; $('reps').value = reps; $('series').value = series;
            $('recMin').value = rm; $('recSec').value = rs;
            updateSummary();
        }));

        $('startBtn').addEventListener('click', start);
        $('pauseBtn').addEventListener('click', togglePause);
        $('stopBtn').addEventListener('click', finish);
        $('muteBtn').addEventListener('click', () => {
            muted = !muted;
            if (master) master.gain.value = muted ? 0 : 1;
            $('muteBtn').setAttribute('aria-pressed', String(muted));
            $('muteBtn').innerHTML = muted
                ? '<i class="fas fa-volume-mute me-1" aria-hidden="true"></i><span>Muet</span>'
                : '<i class="fas fa-volume-up me-1" aria-hidden="true"></i><span>Son</span>';
        });
        $('csvBtn').addEventListener('click', exportCsv);
        $('newBtn').addEventListener('click', () => { $('resultsCard').classList.add('d-none'); $('setupCard').scrollIntoView({ behavior: 'smooth' }); });

        document.addEventListener('keydown', e => {
            if (!ctx || ended || ['INPUT', 'SELECT', 'TEXTAREA'].includes(e.target.tagName)) return;
            if (e.code === 'Space') { e.preventDefault(); togglePause(); return; }
            const m = e.code.match(/^(?:Digit|Numpad)([1-8])$/);
            if (m) { e.preventDefault(); arrive(parseInt(m[1], 10) - 1); }
        });
    });
})();
</script>
@endpush
