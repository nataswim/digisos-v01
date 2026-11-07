@once
@push('styles')
<style>
    .msg-list .msg-item { display: flex; gap: .9rem; align-items: flex-start; padding: .9rem 1rem; text-decoration: none; color: inherit; border-bottom: 1px solid #e2e8f0; }
    .msg-list .msg-item:last-child { border-bottom: 0; }
    .msg-list .msg-item:hover, .msg-list .msg-item:focus-visible { background: #f8fafc; }
    .msg-list .msg-item.is-unread { background: #eff8ff; }
    .msg-list .msg-item.is-unread .msg-subject { font-weight: 700; }
    .msg-dot { width: .6rem; height: .6rem; border-radius: 50%; background: #0090D4; flex: 0 0 auto; margin-top: .45rem; }
    .msg-dot.is-read { background: transparent; }
    .msg-main { flex: 1 1 auto; min-width: 0; }
    .msg-top { display: flex; flex-wrap: wrap; gap: .25rem .75rem; justify-content: space-between; }
    .msg-who { font-weight: 600; }
    .msg-when { color: #62748e; font-size: .85rem; white-space: nowrap; }
    .msg-subject { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .msg-thread { display: flex; flex-direction: column; gap: 1rem; }
    .msg-bubble { max-width: 85%; border-radius: 1rem; padding: .8rem 1rem; background: #fff; border: 1px solid #e2e8f0; }
    .msg-bubble.is-mine { align-self: flex-end; background: #e0f2fe; border-color: #bae6fd; }
    .msg-bubble-head { display: flex; flex-wrap: wrap; gap: .25rem .75rem; justify-content: space-between; font-size: .85rem; color: #45556c; margin-bottom: .35rem; }
    .msg-body { white-space: pre-line; overflow-wrap: anywhere; }
    .msg-attachment { display: inline-flex; align-items: center; gap: .5rem; margin-top: .6rem; padding: .4rem .75rem; border-radius: .5rem; background: #f1f5f9; text-decoration: none; font-size: .9rem; }
    .msg-attachment img { max-height: 160px; max-width: 100%; border-radius: .4rem; display: block; }
    .msg-chips { display: flex; flex-wrap: wrap; gap: .4rem; }
    .msg-chip { display: inline-flex; align-items: center; gap: .4rem; background: #e0f2fe; color: #075985; border-radius: 999px; padding: .25rem .4rem .25rem .75rem; font-size: .9rem; }
    .msg-chip button { border: 0; background: transparent; color: inherit; line-height: 1; padding: .1rem .35rem; border-radius: 50%; }
    .msg-chip button:hover { background: rgba(0, 0, 0, .08); }
    .msg-results { position: absolute; z-index: 20; left: 0; right: 0; max-height: 300px; overflow-y: auto; background: #fff; border: 1px solid #cad5e2; border-radius: .5rem; box-shadow: 0 8px 24px rgba(15, 23, 42, .12); }
    .msg-results button { display: block; width: 100%; text-align: left; border: 0; background: transparent; padding: .5rem .75rem; }
    .msg-results button:hover, .msg-results button:focus { background: #eff8ff; outline: none; }
    .msg-check { display: flex; align-items: center; gap: .5rem; padding: .35rem .6rem; border: 1px solid #e2e8f0; border-radius: .5rem; cursor: pointer; margin: 0; }
    .msg-check:has(input:checked) { background: #e0f2fe; border-color: #7dd3fc; }
    .msg-groups { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: .5rem; }
    .msg-unread-badge { display: inline-block; min-width: 1.35rem; padding: .1rem .4rem; border-radius: 999px; background: #dc2626; color: #fff; font-size: .72rem; font-weight: 700; text-align: center; line-height: 1.2; }
    @media (max-width: 575.98px) { .msg-bubble { max-width: 100%; } }
</style>
@endpush
@endonce
