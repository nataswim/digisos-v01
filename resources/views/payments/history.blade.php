@extends('layouts.user')

@section('title', 'Historique des paiements')

@section('content')
<div class="container-lg py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 card-header bg-white p-4">
                        <div>
                            <h4 class="mb-1"><i class="fas fa-history me-2"></i>Historique des paiements</h4>
                            <p class="text-muted mb-0">Consultez tous vos paiements et leur statut</p>
                        </div>
                        <div>
                            <a href="{{ route('payments.index') }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-2"></i>Nouveau paiement
                            </a>
                            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 p-4">Plan</th>
                                        <th class="border-0 p-4">Montant</th>
                                        <th class="border-0 p-4">Date</th>
                                        <th class="border-0 p-4">Statut paiement</th>
                                        <th class="border-0 p-4">Statut validation</th>
                                        <th class="border-0 p-4">Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td class="p-4">
                                            <div>
                                                <strong>{{ $payment->plan_name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ config("stripe.plans.{$payment->plan_type}.duration_months") }} mois
                                                </small>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="badge bg-success fs-6 px-3 py-2">
                                                {{ $payment->formatted_price }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div>
                                                <strong>{{ $payment->created_at->format('d/m/Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            @if($payment->status === 'completed')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Paye
                                                </span>
                                            @elseif($payment->status === 'pending')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>En attente
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>{{ ucfirst($payment->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if($payment->admin_status === 'pending')
                                                <span class="badge bg-info">
                                                    <i class="fas fa-hourglass-half me-1"></i>En validation
                                                </span>
                                            @elseif($payment->admin_status === 'approved')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Approuve
                                                </span>
                                                @if($payment->processed_at)
                                                    <br>
                                                    <small class="text-muted">
                                                        Le {{ $payment->processed_at->format('d/m/Y A H:i') }}
                                                    </small>
                                                @endif
                                            @elseif($payment->admin_status === 'rejected')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Rejete
                                                </span>
                                                @if($payment->processed_at)
                                                    <br>
                                                    <small class="text-muted">
                                                        Le {{ $payment->processed_at->format('d/m/Y A H:i') }}
                                                    </small>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if($payment->admin_notes)
                                                <div class="small">
                                                    <i class="fas fa-sticky-note text-muted me-1"></i>
                                                    {!! Str::limit($payment->admin_notes, 50) !!}
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                            
                                            @if($payment->processedBy)
                                                <div class="small text-muted mt-1">
                                                    Traite par {{ $payment->processedBy->name }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else
                        <!-- etat vide -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-receipt fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">Aucun paiement effectue</h5>
                            <p class="text-muted mb-4">Vous n'avez encore effectue aucun paiement pour un acces premium.</p>
                            <a href="{{ route('payments.index') }}" class="btn btn-primary">
                                <i class="fas fa-crown me-2"></i>Passer Premium maintenant
                            </a>
                        </div>
                    @endif

                </div>
                
                @if($payments->count() > 0)
                    <!-- Resume en bas -->
                    <div class="card-footer bg-light border-top p-4">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="small text-muted">Total des paiements</div>
                                <strong>{{ $payments->count() }}</strong>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Payes</div>
                                <strong class="text-success">{{ $payments->where('status', 'completed')->count() }}</strong>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Approuves</div>
                                <strong class="text-success">{{ $payments->where('admin_status', 'approved')->count() }}</strong>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">En attente</div>
                                <strong class="text-warning">{{ $payments->where('admin_status', 'pending')->count() }}</strong>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection