@php use App\Models\Membership as M; @endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Attestation de paiement — {{ $member->full_name }} — {{ $membership->season }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #1d293d; max-width: 760px; margin: 2rem auto; padding: 0 1.5rem; line-height: 1.5; }
        header { display: flex; align-items: center; gap: 1.25rem; border-bottom: 3px solid #0090D4; padding-bottom: 1rem; margin-bottom: 2rem; }
        header img { width: 130px; }
        h1 { font-size: 1.4rem; text-align: center; margin: 2rem 0; letter-spacing: .03em; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        th, td { border: 1px solid #cad5e2; padding: .45rem .6rem; text-align: left; font-size: .95rem; }
        th { background: #f1f5f9; }
        .total { font-weight: bold; }
        .signature { margin-top: 3rem; text-align: right; }
        .actions { margin-bottom: 1.5rem; }
        .actions button { padding: .5rem 1rem; font-size: 1rem; cursor: pointer; }
        @media print { .actions { display: none; } body { margin: 0 auto; } }
    </style>
</head>
<body>
    <div class="actions"><button type="button" onclick="window.print()">Imprimer</button></div>
    <header>
        <img src="{{ asset('assets/images/logo/cnbb-logo-800.png') }}" alt="Logo du CNBB">
        <div>
            <strong>Cercle des Nageurs du Bocage Bressuirais</strong><br>
            {{ config('club.dons.adresse') }}<br>
            Association affiliée à la Fédération Française de Natation
        </div>
    </header>

    <h1>ATTESTATION DE PAIEMENT</h1>

    <p>
        Le Cercle des Nageurs du Bocage Bressuirais atteste que
        <strong>{{ $member->full_name }}</strong>@if ($member->birth_date), né(e) le {{ $member->birth_date->format('d/m/Y') }}@endif,
        est adhérent(e) du club pour la saison <strong>{{ $membership->season }}</strong>@if ($membership->category) (catégorie {{ $membership->category->label }})@endif,
        et que la cotisation a été réglée comme suit :
    </p>

    <table>
        <caption style="text-align: left; font-weight: bold; padding-bottom: .4rem">Détail de la cotisation</caption>
        <tbody>
            <tr><th scope="row">Cotisation (licence FFN incluse)</th><td>{{ M::money($membership->price_cents) }}</td></tr>
            @if ($membership->discount_cents)<tr><th scope="row">Remise</th><td>− {{ M::money($membership->discount_cents) }}</td></tr>@endif
            <tr class="total"><th scope="row">Montant dû</th><td>{{ M::money($membership->amount_cents) }}</td></tr>
            @if ($membership->pass_sport && $membership->pass_sport_cents)<tr><th scope="row">Pass'Sport</th><td>{{ M::money($membership->pass_sport_cents) }}</td></tr>@endif
            @foreach ($membership->payments as $p)
                <tr><th scope="row">{{ $p->method_label }}@if ($p->cashed_on) du {{ $p->cashed_on->format('d/m/Y') }}@endif</th><td>{{ M::money($p->amount_cents) }}</td></tr>
            @endforeach
            <tr class="total"><th scope="row">Total réglé</th><td>{{ M::money($membership->paid_cents + ($membership->pass_sport ? $membership->pass_sport_cents : 0)) }}</td></tr>
            @if ($membership->due_cents)<tr><th scope="row">Reste à payer</th><td>{{ M::money($membership->due_cents) }}</td></tr>@endif
        </tbody>
    </table>

    <p>Attestation délivrée pour faire valoir ce que de droit.</p>

    <div class="signature">
        Fait à Bressuire, le {{ today()->translatedFormat('j F Y') }}<br><br>
        Pour le club,<br><br><br>
        ____________________
    </div>
</body>
</html>
