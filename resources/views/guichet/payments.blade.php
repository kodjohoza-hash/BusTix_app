@extends('layouts.guichet')

@section('title', 'Paiements')
@section('subtitle', 'Historique des paiements')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0">
            <i class="fas fa-money-bill text-success me-2"></i>
            Tous les Paiements
        </h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Référence</th>
                    <th>Client</th>
                    <th>Trajet</th>
                    <th>Montant</th>
                    <th>Mode</th>
                    <th>Date</th>
                    <th>Billet</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="ps-4 font-monospace fw-bold text-success small">
                        {{ $payment->transaction_reference }}
                    </td>
                    <td>
                        <small>
                            {{ $payment->ticketReservation->customer->name }}
                            {{ $payment->ticketReservation->customer->surname }}
                        </small>
                    </td>
                    <td>
                        <small>
                            {{ $payment->ticketReservation->trip->displacement->start_point }}
                            <i class="fas fa-arrow-right mx-1 text-muted"></i>
                            {{ $payment->ticketReservation->trip->displacement->destination_point }}
                        </small>
                    </td>
                    <td class="fw-bold text-success">
                        {{ number_format($payment->amount, 0, ',', ' ') }} F
                    </td>
                    <td>
                        @if($payment->payment_mode === 'espèces')
                            <span class="badge bg-success rounded-pill px-3">
                                <i class="fas fa-money-bill me-1"></i>Espèces
                            </span>
                        @elseif($payment->payment_mode === 'mobile_money')
                            <span class="badge bg-warning rounded-pill px-3">
                                <i class="fas fa-mobile-alt me-1"></i>Mobile Money
                            </span>
                        @else
                            <span class="badge bg-info rounded-pill px-3">
                                <i class="fas fa-credit-card me-1"></i>Carte
                            </span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}
                        </small>
                    </td>
                    <td class="font-monospace text-primary small fw-bold">
                        {{ $payment->ticketReservation->ticket_code }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-money-bill fa-2x mb-2 d-block opacity-25"></i>
                        Aucun paiement enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $payments->links() }}
</div>

@endsection