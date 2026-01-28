@extends('layouts.admin')

@section('title', 'Gestion des Paiements')
@section('page-title', 'Paiements')
@section('page-description', 'Gestion des paiements et validations')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-4">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Gestion des paiements</h5>
                <div class="d-flex gap-2">
                    <a href="?status=pending" class="btn btn-sm btn-{{ $status === 'pending' ? 'primary' : 'outline-primary' }}">
                        <i class="fas fa-clock me-2"></i>En attente
                    </a>
                    <a href="?status=all" class="btn btn-sm btn-{{ $status === 'all' ? 'primary' : 'outline-primary' }}">
                        <i class="fas fa-list me-2"></i>Tous
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="card-body border-bottom p-4">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               class="form-control border-start-0"
                               placeholder="Rechercher un utilisateur...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="payment_status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Paye</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>echoue</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="admin_status" class="form-select">
                        <option value="">Toutes les validations</option>
                        <option value="pending" {{ request('admin_status') == 'pending' ? 'selected' : '' }}>En validation</option>
                        <option value="approved" {{ request('admin_status') == 'approved' ? 'selected' : '' }}>Approuve</option>
                        <option value="rejected" {{ request('admin_status') == 'rejected' ? 'selected' : '' }}>Rejete</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary flex-fill">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                        @if(request('search') || request('payment_status') || request('admin_status'))
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Tableau -->
        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-4 py-3">Utilisateur</th>
                            <th class="border-0 py-3">Plan</th>
                            <th class="border-0 py-3">Montant</th>
                            <th class="border-0 py-3">Date</th>
                            <th class="border-0 py-3">Statut paiement</th>
                            <th class="border-0 py-3">Statut validation</th>
                            <th class="border-0 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 45px; height: 45px; font-size: 16px;">
                                        {{ substr($payment->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $payment->user->name }}</h6>
                                        <small class="text-muted">{{ $payment->user->email }}</small>
                                        <br>
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            {{ $payment->user->role->display_name }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div>
                                    <strong>{{ $payment->plan_name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ config("stripe.plans.{$payment->plan_type}.duration_months") }} mois
                                    </small>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                    {{ $payment->formatted_price }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="text-muted">
                                    {{ $payment->created_at->format('d/m/Y') }}
                                    <br><small>{{ $payment->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($payment->status === 'completed')
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="fas fa-check-circle me-1"></i>Paye
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning">
                                        <i class="fas fa-clock me-1"></i>En attente
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="fas fa-times-circle me-1"></i>{{ ucfirst($payment->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($payment->admin_status === 'pending')
                                    <span class="badge bg-info-subtle text-info">
                                        <i class="fas fa-hourglass-half me-1"></i>En validation
                                    </span>
                                @elseif($payment->admin_status === 'approved')
                                    <div>
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="fas fa-check-circle me-1"></i>Approuve
                                        </span>
                                        @if($payment->processed_at)
                                            <br>
                                            <small class="text-muted">
                                                {{ $payment->processed_at->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                    </div>
                                @elseif($payment->admin_status === 'rejected')
                                    <div>
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="fas fa-times-circle me-1"></i>Rejete
                                        </span>
                                        @if($payment->processed_at)
                                            <br>
                                            <small class="text-muted">
                                                {{ $payment->processed_at->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 text-end">
                                @if($payment->status === 'completed' && $payment->admin_status === 'pending')
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <form method="POST" action="{{ route('admin.payments.approve', $payment) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="dropdown-item text-success"
                                                            onclick="return confirm('Approuver ce paiement et changer le rôle ?')">
                                                        <i class="fas fa-check me-2"></i>Approuver
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button class="dropdown-item text-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $payment->id }}">
                                                    <i class="fas fa-times me-2"></i>Rejeter
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                @elseif($payment->admin_status === 'approved')
                                    <small class="text-success">
                                        <i class="fas fa-user-check me-1"></i>
                                        Traite par {{ $payment->processedBy->name }}
                                    </small>
                                @elseif($payment->admin_status === 'rejected')
                                    <small class="text-danger">
                                        <i class="fas fa-user-times me-1"></i>
                                        Rejete par {{ $payment->processedBy->name ?? 'Admin' }}
                                    </small>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal de rejet -->
                        @if($payment->admin_status === 'pending')
                        <div class="modal fade" id="rejectModal{{ $payment->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-times-circle text-danger me-2"></i>
                                                Rejeter le paiement
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Cette action changera le statut du paiement en "rejete".
                                            </div>
                                            
                                            <div class="mb-3">
                                                <strong>Utilisateur :</strong> {{ $payment->user->name }}<br>
                                                <strong>Plan :</strong> {{ $payment->plan_name }}<br>
                                                <strong>Montant :</strong> {{ $payment->formatted_price }}
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Motif du rejet <span class="text-danger">*</span></label>
                                                <textarea name="reason" 
                                                          class="form-control" 
                                                          rows="4" 
                                                          placeholder="Expliquez pourquoi ce paiement est rejete..."
                                                          required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                Annuler
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times me-2"></i>Rejeter le paiement
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
@if($payments->hasPages())
    <div class="card-footer bg-white border-top p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted">
                Affichage de {{ $payments->firstItem() }} à {{ $payments->lastItem() }} 
                sur {{ $payments->total() }} résultat(s)
            </div>
            {{ $payments->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif

        @else
            <!-- etat vide -->
            <div class="card-body">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-credit-card fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">Aucun paiement trouve</h5>
                    <p class="text-muted">
                        @if($status === 'pending')
                            Il n'y a actuellement aucun paiement en attente de validation.
                        @else
                            Aucun paiement ne correspond aux criteres selectionnes.
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- Statistiques en bas -->
    @if($payments->count() > 0)
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-credit-card fa-2x"></i>
                    </div>
                    <h4 class="mb-1">{{ $payments->count() }}</h4>
                    <small class="text-muted">Total paiements</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h4 class="mb-1">{{ $payments->where('admin_status', 'approved')->count() }}</h4>
                    <small class="text-muted">Approuves</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                    <h4 class="mb-1">{{ $payments->where('admin_status', 'pending')->count() }}</h4>
                    <small class="text-muted">En attente</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-euro-sign fa-2x"></i>
                    </div>
                    <h4 class="mb-1">{{ number_format($payments->where('status', 'completed')->sum('amount_paid') / 100, 0) }}€</h4>
                    <small class="text-muted">Total encaisse</small>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection