@extends('layouts.guichet')

@section('title', 'Dashboard Guichet')
@section('subtitle', 'Activité du jour')

@section('content')

<!-- ===== STATS DU JOUR ===== -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card card shadow-sm p-4 text-center bg-gradient-orange text-white">
            <h2 class="fw-bold mb-2">{{ $totalTodayTrips }}</h2>
            <p class="mb-0"><i class="fas fa-bus me-2"></i>Voyages Aujourd'hui</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card shadow-sm p-4 text-center bg-gradient-blue text-white">
            <h2 class="fw-bold mb-2">{{ $todayConfirmed + $todayPending }}</h2>
            <p class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Réservations du Jour</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card shadow-sm p-4 text-center bg-gradient-green text-white">
            <h2 class="fw-bold mb-2">{{ number_format($todayRevenue, 0, ',', ' ') }} F</h2>
            <p class="mb-0"><i class="fas fa-money-bill me-2"></i>Revenus du Jour</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card shadow-sm p-4 text-center bg-gradient-purple text-white">
            <h2 class="fw-bold mb-2">{{ $todayPending }}</h2>
            <p class="mb-0"><i class="fas fa-clock me-2"></i>En Attente</p>
        </div>
    </div>
</div>

<!-- ===== ACTIONS RAPIDES ===== -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3 p-4">
            <h5 class="fw-bold mb-3">
                <i class="fas fa-bolt text-warning me-2"></i>Actions Rapides
            </h5>
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('guichet.reservations.create') }}"
                   class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i>Nouvelle Réservation
                </a>
                <a href="{{ route('guichet.reservations') }}"
                   class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-ticket-alt me-2"></i>Voir Réservations
                </a>
                <a href="{{ route('guichet.payments') }}"
                   class="btn btn-outline-success rounded-pill px-4">
                    <i class="fas fa-money-bill me-2"></i>Voir Paiements
                </a>
                <a href="{{ route('guichet.voyages') }}"
                   class="btn btn-outline-warning rounded-pill px-4">
                    <i class="fas fa-bus me-2"></i>Voyages du Jour
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ===== VOYAGES DU JOUR ===== -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-bus text-warning me-2"></i>
                    Voyages Aujourd'hui — {{ now()->format('d/m/Y') }}
                </h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Trajet</th>
                            <th>Bus</th>
                            <th>Heure</th>
                            <th>Prix</th>
                            <th>Places Dispo</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayTrips as $trip)
                        <tr>
                            <td class="ps-4 fw-bold">
                                {{ $trip->displacement->start_point }}
                                <i class="fas fa-arrow-right mx-1 text-muted"></i>
                                {{ $trip->displacement->destination_point }}
                            </td>
                            <td><small>{{ $trip->displacement->bus->mack }}</small></td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($trip->living_date_time)->format('H:i') }}</small>
                            </td>
                            <td class="text-success fw-bold">
                                {{ number_format($trip->price, 0, ',', ' ') }} F
                            </td>
                            <td>
                                @php
                                    $reserved  = $trip->ticketReservations()->where('status', '!=', 'annulée')->count();
                                    $available = $trip->displacement->bus->capacity - $reserved;
                                @endphp
                                <span class="badge rounded-pill
                                    {{ $available > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $available }} / {{ $trip->displacement->bus->capacity }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $trip->travel_status }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($trip->travel_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('guichet.reservations.create') }}?trip_id={{ $trip->id }}"
                                   class="btn btn-sm btn-primary rounded-pill">
                                    <i class="fas fa-plus me-1"></i>Réserver
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-bus fa-2x mb-2 d-block opacity-25"></i>
                                Aucun voyage prévu aujourd'hui
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ===== RÉSERVATIONS DU JOUR ===== -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-ticket-alt text-primary me-2"></i>
                    Réservations du Jour
                </h5>
                <a href="{{ route('guichet.reservations') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                    Voir tout
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Code Billet</th>
                            <th>Client</th>
                            <th>Trajet</th>
                            <th>Siège</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayReservations as $reservation)
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
                                <span class="badge badge-{{ $reservation->status }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td>
                                @if(!$reservation->payment && $reservation->status === 'en_attente')
                                    <a href="{{ route('guichet.payment.create', $reservation->id) }}"
                                       class="btn btn-sm btn-success rounded-pill">
                                        <i class="fas fa-credit-card me-1"></i>Encaisser
                                    </a>
                                @else
                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-check me-1"></i>Payé
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-ticket-alt fa-2x mb-2 d-block opacity-25"></i>
                                Aucune réservation aujourd'hui
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection