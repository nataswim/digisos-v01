<?php

namespace App\Http\Controllers\Admin\Members;

use App\Models\Membership;
use App\Models\MembershipPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/** Règlements d'une adhésion : ajout (un ou plusieurs chèques), encaissement, suppression. */
class MembershipPaymentController extends MembersArea
{
    public function store(Request $request, Membership $membership): RedirectResponse
    {
        $data = $request->validate([
            'method' => ['required', Rule::in(array_keys(Membership::PAYMENT_METHODS))],
            'amount' => ['required', 'string', 'max:20'],
            'count' => ['nullable', 'integer', 'min:1', 'max:4'],
            'cashed_on' => ['nullable', 'date'],
            'is_cashed' => ['nullable', 'boolean'],
            'reference' => ['nullable', 'string', 'max:120'],
        ]);
        $total = Membership::parseMoney($data['amount']);
        if (! $total) {
            throw ValidationException::withMessages(['amount' => 'Montant illisible (exemple : 190 ou 47,50).']);
        }

        // Paiement en plusieurs chèques : montant réparti, encaissements le 15 des mois suivants
        $n = (int) ($data['count'] ?? 1);
        $base = intdiv($total, $n);
        $date = isset($data['cashed_on']) ? \Illuminate\Support\Carbon::parse($data['cashed_on']) : null;
        for ($i = 0; $i < $n; $i++) {
            $membership->payments()->create([
                'method' => $data['method'],
                'amount_cents' => $i === $n - 1 ? $total - $base * ($n - 1) : $base,
                'cashed_on' => $date?->copy()->addMonthsNoOverflow($i)->toDateString(),
                'is_cashed' => $n === 1 && $request->boolean('is_cashed'),
                'reference' => $data['reference'] ?? null,
            ]);
        }

        return back()->with('success', $n > 1 ? "{$n} règlements ajoutés (" . Membership::money($total) . ').' : 'Règlement ajouté.');
    }

    public function update(Request $request, MembershipPayment $payment): RedirectResponse
    {
        $payment->update(['is_cashed' => ! $payment->is_cashed, 'cashed_on' => $payment->cashed_on ?? today()]);

        return back()->with('success', $payment->is_cashed ? 'Règlement marqué encaissé.' : 'Règlement marqué non encaissé.');
    }

    public function destroy(MembershipPayment $payment): RedirectResponse
    {
        $payment->delete();

        return back()->with('success', 'Règlement supprimé.');
    }
}
