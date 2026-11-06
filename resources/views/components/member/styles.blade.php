{{-- Styles communs de l'en-tête adhérent et des fiches nageur (chargés une seule fois). --}}
@once
@push('styles')
<style>
    .member-head { background: linear-gradient(135deg, #0b3a5b 0%, #0090D4 100%); color: #fff; }
    .member-head .member-head-eyebrow { letter-spacing: .08em; font-size: .8rem; opacity: .85; }
    .member-head h1 { color: #fff; }
    .member-head .lead { color: rgba(255, 255, 255, .9); font-size: 1.05rem; }
    .member-head .btn-outline-light { border: 1px solid rgba(255, 255, 255, .75) !important; color: #fff !important; background: transparent !important; border-radius: 999px; padding: .5rem 1.1rem; }
    .member-head .btn-outline-light:hover { background: rgba(255, 255, 255, .15) !important; }
    .member-people { display: flex; flex-wrap: wrap; gap: .75rem; }
    .member-person { display: flex; align-items: center; gap: .75rem; background: rgba(255, 255, 255, .12); border: 1px solid rgba(255, 255, 255, .25); border-radius: 999px; padding: .35rem 1rem .35rem .35rem; }
    .member-person-name { font-weight: 700; line-height: 1.2; }
    .member-person-meta { font-size: .8rem; opacity: .9; }
    .member-avatar { flex: 0 0 auto; width: 64px; height: 64px; border-radius: 50%; overflow: hidden; display: inline-flex; align-items: center; justify-content: center; background: #e0f2fe; color: #0369a1; font-weight: 700; font-size: 1.25rem; }
    .member-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .member-head .member-avatar { border: 2px solid #fff; }
    .member-avatar--lg { width: 104px; height: 104px; font-size: 1.75rem; }
    .member-card { display: flex; gap: 1.25rem; align-items: center; }
    .member-card dl { display: grid; grid-template-columns: auto 1fr; gap: .25rem .75rem; margin: 0; font-size: .95rem; }
    .member-card dt { color: #62748e; font-weight: 500; }
    .member-card dd { margin: 0; }
    .member-group { display: inline-block; padding: .15rem .6rem; border-radius: 999px; font-size: .8rem; font-weight: 600; margin: 0 .25rem .25rem 0; }
    .member-convoc { display: flex; flex-wrap: wrap; gap: .75rem 1.25rem; align-items: center; justify-content: space-between; }
    .member-convoc-date { flex: 0 0 auto; width: 64px; text-align: center; border-radius: .75rem; background: #e0f2fe; color: #075985; padding: .35rem 0; line-height: 1.1; }
    .member-convoc-date strong { display: block; font-size: 1.4rem; }
    .member-convoc-main { display: flex; gap: 1rem; align-items: center; flex: 1 1 280px; min-width: 0; }
    @media (max-width: 575.98px) {
        .member-person { border-radius: 1rem; }
        .member-card { flex-direction: column; text-align: center; }
        .member-card dl { text-align: left; }
    }
</style>
@endpush
@endonce
