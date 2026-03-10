@extends('layouts.guichet')

@section('title', 'Réservations')
@section('subtitle', 'Gestion des réservations')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Toutes les Réservations</h5>
    <a href="{{ route('guichet.reservations.create') }}"
       class="btn btn-primary rounded-pill">
        <i class="fas fa-plus me-2"></i>Nouvelle Réservation
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Code Billet</th>
                    <th>Client</th>
                    <th>Trajet</th>
                    <th>Siège</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Paiement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr>
                    <td class="ps-4 fw-bold text-primary font-monospace">
                        {{ $reservation->ticket_code }}
                    </td>
                    <td>
                        <small>{{ $reservation->customer->name }} {{ $reservation->customer->surname }}</small>
                    </td>
                    <td>
                        <small>
                            {{ $reservation->trip->displacement->start_point }}
                            <i class="fas fa-arrow-right mx-1 text-muted"></i>
                            {{ $reservation->trip->displacement->destination_point }}
                        </small>
                    </td>
                    <td>
                        <span class="badge bg-info bg-opacity-10 text-info px-2 rounded-pill">
                            {{ $reservation->seat->seat_number ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}
                        </small>
                    </td>
                    <td>
                        <span class="badge badge-{{ $reservation->status }} px-3 py-2 rounded-pill">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </td>
                    <td>
                        @if($reservation->payment)
                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                <i class="fas fa-check me-1"></i>Payé
                            </span>
                        @else
                            <span class="badge bg-warning px-3 py-2 rounded-pill">
                                <i class="fas fa-clock me-1"></i>Non payé
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            @if(!$reservation->payment && $reservation->status === 'en_attente')
                                <a href="{{ route('guichet.payment.create', $reservation->id) }}"
                                   class="btn btn-sm btn-success rounded-pill">
                                    <i class="fas fa-credit-card"></i>
                                </a>
                            @endif
                            <form method="POST"
                                  action="{{ route('guichet.reservations.destroy', $reservation->id) }}"
                                  onsubmit="return confirm('Supprimer cette réservation ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-ticket-alt fa-2x mb-2 d-block opacity-25"></i>
                        Aucune réservation
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $reservations->links() }}
</div>

@endsection