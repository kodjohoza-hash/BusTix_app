@extends('layouts.admin')

@section('title', 'Paiements')

@section('content')

<!-- ===== HEADER ===== -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="fas fa-credit-card text-primary me-2"></i>Paiements
        </h4>
        <p class="text-muted mb-0">Historique de tous les paiements</p>
    </div>
</div>

<!-- ===== STATS ===== -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                     style="width:50px;height:50px;min-width:50px;background:linear-gradient(135deg,#1a237e,#0d47a1)">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Paiements</div>
                    <div class="fw-bold fs-5">{{ $totalPayments }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                     style="width:50px;height:50px;min-width:50px;background:linear-gradient(135deg,#11998e,#38ef7d)">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <div class="text-muted small">Revenu Total</div>
                    <div class="fw-bold fs-5 text-success">
                        {{ number_format($totalRevenue, 0, ',', ' ') }} FCFA
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                     style="width:50px;height:50px;min-width:50px;background:linear-gradient(135deg,#f7971e,#ffd200)">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div>
                    <div class="text-muted small">Aujourd'hui</div>
                    <div class="fw-bold fs-5 text-warning">
                        {{ number_format($todayRevenue, 0, ',', ' ') }} FCFA
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                     style="width:50px;height:50px;min-width:50px;background:linear-gradient(135deg,#f64f59,#c471ed)">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div>
                    <div class="text-muted small">Mobile Money</div>
                    <div class="fw-bold fs-5">
                        {{ $payments->where('payment_mode','mobile_money')->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== TABLE ===== -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8faff">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-600 small">Code Billet</th>
                        <th class="py-3 text-muted fw-600 small">Client</th>
                        <th class="py-3 text-muted fw-600 small">Trajet</th>
                        <th class="py-3 text-muted fw-600 small">Mode</th>
                        <th class="py-3 text-muted fw-600 small">Montant</th>
                        <th class="py-3 text-muted fw-600 small">Date</th>
                        <th class="py-3 text-muted fw-600 small">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="font-monospace text-primary fw-bold small">
                                {{ $payment->ticketReservation->ticket_code ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="py-3">
                            @if($payment->ticketReservation->customer)
                                <div class="fw-bold small">
                                    {{ $payment->ticketReservation->customer->name }}
                                    {{ $payment->ticketReservation->customer->surname }}
                                </div>
                            @else
                                <span class="text-muted small">N/A</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($payment->ticketReservation->trip->displacement ?? false)
                                <small>
                                    {{ $payment->ticketReservation->trip->displacement->start_point }}
                                    <i class="fas fa-arrow-right mx-1 text-primary"></i>
                                    {{ $payment->ticketReservation->trip->displacement->destination_point }}
                                </small>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($payment->payment_mode === 'espèces')
                                <span class="badge rounded-pill px-3"
                                      style="background:#e8f5e9;color:#2e7d32">
                                    <i class="fas fa-money-bill-wave me-1"></i>Espèces
                                </span>
                            @elseif($payment->payment_mode === 'mobile_money')
                                <span class="badge rounded-pill px-3"
                                      style="background:#fff3e0;color:#e65100">
                                    <i class="fas fa-mobile-alt me-1"></i>Mobile Money
                                </span>
                            @else
                                <span class="badge rounded-pill px-3"
                                      style="background:#e3f2fd;color:#1565c0">
                                    <i class="fas fa-credit-card me-1"></i>Carte
                                </span>
                            @endif
                        </td>
                        <td class="py-3">
                            <span class="fw-bold text-success">
                                {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                            </span>
                        </td>
                        <td class="py-3">
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}
                            </small>
                        </td>
                        <td class="py-3">
                            <span class="badge rounded-pill px-3"
                                  style="background:#e8f5e9;color:#2e7d32">
                                <i class="fas fa-check me-1"></i>Payé
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-credit-card fa-3x mb-3 d-block opacity-25"></i>
                            Aucun paiement enregistré
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($payments->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $payments->links() }}
</div>
@endif

@endsection