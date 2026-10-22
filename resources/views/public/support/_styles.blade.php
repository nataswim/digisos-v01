{{-- Styles communs aux pages « Devenir mécène » et « Faire un don » --}}
@push('styles')
<style>
    .support-title {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        border-radius: 15px 0 15px 0;
        background-image: linear-gradient(129deg, #f9be38 85%, #2f80b8 0);
        color: #1c2111;
        box-shadow: 0 5px 6px 4px rgba(0, 0, 0, 0.05);
    }

    .support-lead {
        max-width: 760px;
        font-size: 1.1rem;
        line-height: 1.7;
    }

    /* Chiffres clés */
    .support-key {
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        height: 100%;
        padding: 1.5rem;
        text-align: center;
    }

    .support-key-value {
        display: block;
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1.1;
        color: #2f80b8;
    }

    .support-key-label {
        display: block;
        margin-top: 0.5rem;
        color: #1c2111;
        font-weight: 600;
    }

    .support-key-note {
        display: block;
        margin-top: 0.25rem;
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Cartes avec icône */
    .support-card {
        border-radius: 15px;
        height: 100%;
    }

    .support-icon {
        display: inline-flex;
        width: 56px;
        height: 56px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e8f2f9;
        color: #2f80b8;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .support-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .support-list li {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 0.75rem;
        line-height: 1.6;
    }

    .support-list li::before {
        content: "\f00c";
        font-family: "Font Awesome 6 Free", "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 0.05rem;
        color: #2f80b8;
    }

    /* Simulateur */
    .support-simulator {
        border-radius: 15px;
        background: #1c2111;
        color: #fff;
    }

    .support-simulator label,
    .support-simulator .form-text {
        color: rgba(255, 255, 255, 0.85);
    }

    .support-result {
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.08);
        padding: 1rem;
        height: 100%;
    }

    .support-result-value {
        display: block;
        font-size: 1.75rem;
        font-weight: 800;
        color: #f9be38;
    }

    .support-result-label {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.85);
    }

    .sim-preset {
        border: 1px solid rgba(255, 255, 255, 0.6);
        color: #fff;
        background: transparent;
        border-radius: 999px;
        padding: 0.25rem 0.9rem;
    }

    .sim-preset:hover,
    .sim-preset:focus,
    .sim-preset.is-active {
        background: #f9be38;
        border-color: #f9be38;
        color: #1c2111;
    }

    /* Étapes */
    .support-step-number {
        display: inline-flex;
        width: 44px;
        height: 44px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f9be38;
        color: #1c2111;
        font-weight: 800;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .support-payment {
        border-radius: 15px;
        border: 2px dashed #2f80b8;
        background: #fff;
    }

    .support-iban {
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 1rem;
        word-break: break-all;
        background: #f6f8fa;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        display: inline-block;
    }

    .support-compare th,
    .support-compare td {
        padding: 0.9rem 1rem;
        vertical-align: top;
    }

    .support-compare thead th {
        background: #2f80b8;
        color: #fff;
        border: 0;
    }

    .support-compare tbody th {
        background: #f6f8fa;
        width: 28%;
    }

    /* Sur mobile, le tableau devient une liste lisible */
    @media (max-width: 767.98px) {
        .support-compare thead { display: none; }
        .support-compare tr,
        .support-compare th,
        .support-compare td { display: block; width: 100% !important; }
        .support-compare tbody th { border-bottom: 0; }
        .support-compare td::before {
            content: attr(data-label);
            display: block;
            font-weight: 700;
            color: #2f80b8;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
    }

    .support-note {
        font-size: 0.9rem;
        color: #6c757d;
    }
</style>
@endpush
