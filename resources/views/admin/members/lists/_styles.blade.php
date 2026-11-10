@push('styles')
<style>
    .mbr-jump { display: flex; flex-wrap: wrap; gap: .35rem; }
    .mbr-jump a { padding: .2rem .6rem; border-radius: 999px; border: 1px solid #cfe0f5; font-size: .82rem; text-decoration: none; background: #fff; }
    .mbr-jump a:hover { background: #e8f1fb; }
    .mbr-list th, .mbr-list td { font-size: .88rem; }
    .mbr-list .mbr-group { font-size: .72rem; margin: 0 .2rem .15rem 0; }
    .mbr-block { break-inside: avoid; scroll-margin-top: 80px; }
    @media print {
        .esp-sidebar, .esp-topbar, .esp-footer, .mbr-noprint, nav[aria-label="Adhérents"] { display: none !important; }
        .esp-content { padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #ccc !important; }
        .mbr-list a { color: inherit !important; }
        body { background: #fff !important; }
    }
</style>
@endpush
