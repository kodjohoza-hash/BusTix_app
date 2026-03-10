@extends('layouts.app')

@section('title', 'Mes Réservations')

@section('content')

<!-- ===== HEADER ===== -->
<section style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%); color: white; padding: 60px 0;">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">
            <i class="fas fa-ticket-alt me-2"></i>Mes Réservations
        </h1>
        <p class="text-light mb-0">Gérez vos billets de voyage</p>
    </div>
</section>

<!-- ===== CONTENU ===== -->
<section class="py-5">
    <div class="container">

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

        @forelse($reservations as $reservation)
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">

                    <!-- Code billet -->
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="p-3 rounded-3"
                             style="background: linear-gradient(135deg, #1a237e, #0d47a1);">
                            <i class="fas fa-ticket-alt text-white fa-2x mb-1"></i>
                            <div class="text-white fw-bold font-monospace small">
                                {{ $reservation->ticket_code }}
                            </div>
                        </div>
                    </div>

                    <!-- Infos trajet -->
                    <div class="col-md-4 mb-3 mb-md-0">
                        @if($reservation->trip && $reservation->trip->displacement)
                            <h6 class="fw-bold mb-1">
                                {{ $reservation->trip->displacement->start_point }}
                                <i class="fas fa-arrow-right mx-1 text-primary small"></i>
                                {{ $reservation->trip->displacement->destination_point }}
                            </h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($reservation->trip->living_date_time)->format('d/m/Y à H:i') }}
                            </small>
                        @endif
                    </div>

                    <!-- Siège + Prix -->
                    <div class="col-md-3 mb-3 mb-md-0">
                        @if($reservation->seat)
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill me-2">
                                <i class="fas fa-chair me-1"></i>
                                {{ $reservation->seat->seat_number }}
                            </span>
                        @endif
                        @if($reservation->trip)
                            <span class="fw-bold text-primary">
                                {{ number_format($reservation->trip->price, 0, ',', ' ') }} FCFA
                            </span>
                        @endif
                    </div>

                    <!-- Statut + Actions -->
                    <div class="col-md-3 text-md-end">
                        <!-- Statut -->
                        <div class="mb-2">
                            @if($reservation->status === 'confirmée')
                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i>Confirmée
                                </span>
                            @elseif($reservation->status === 'en_attente')
                                <span class="badge bg-warning px-3 py-2 rounded-pill">
                                    <i class="fas fa-clock me-1"></i>En attente
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill">
                                    <i class="fas fa-times me-1"></i>Annulée
                                </span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2 justify-content-md-end">
                            @if(!$reservation->payment && $reservation->status === 'en_attente')
                                <a href="{{ route('client.payment.create', $reservation->id) }}"
                                   class="btn btn-sm btn-primary rounded-pill">
                                    <i class="fas fa-credit-card me-1"></i>Payer
                                </a>
                            @endif

                            @if($reservation->payment)
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                    <i class="fas fa-check me-1"></i>Payé
                                </span>
                            @endif

                            @if($reservation->status !== 'annulée' && !$reservation->payment)
                                <form method="POST"
                                      action="{{ route('client.reservations.destroy', $reservation->id) }}"
                                      onsubmit="return confirm('Annuler cette réservation ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger rounded-pill">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-ticket-alt fa-4x text-muted mb-4 d-block opacity-25"></i>
            <h5 class="text-muted mb-3">Vous n'avez aucune réservation</h5>
            <a href="{{ route('voyages') }}" class="btn btn-primary rounded-pill px-5">
                <i class="fas fa-road me-2"></i>Voir les voyages
            </a>
        </div>
        @endforelse

    </div>
</section>

@endsection