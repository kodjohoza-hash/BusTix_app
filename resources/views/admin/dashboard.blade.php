@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('subtitle', 'Vue générale de BusTix')

@section('content')

<!-- ===== STATS CARDS ===== -->
<div class="row g-4 mb-4">

    <!-- Utilisateurs -->
    <div class="col-md-3">
        <div class="stat-card card shadow-sm p-4 text-center bg-gradient-blue text-white">
            <h2 class="fw-bold mb-2">{{ $totalCustomers }}</h2>
            <p class="mb-0"><i class="fas fa-users me-2"></i>Clients</p>
        </div>
    </div>

    <!-- Réservations -->
    <div class="col-md-3">
        <div class="stat-card card shadow-sm p-4 text-center bg-gradient-green text-white">
            <h2 class="fw-bold mb-2">{{ $totalReservations }}</h2>
            <p class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Réservations</p>
        </div>
    </div>

    <!-- Revenus -->
    <div class="col-md-3">
        <div class="stat-card card shadow-sm p-4 text-center bg-gradient-orange text-white">
            <h2 class="fw-bold mb-2">{{ number_format($totalRevenue, 0, ',', ' ') }} F</h2>
            <p class="mb-0"><i class="fas fa-money-bill me-2"></i>Revenus</p>
        </div>
    </div>

    <!-- Bus -->
    <div class="col-md-3">
        <div class="stat-card card shadow-sm p-4 text-center bg-gradient-purple text-white">
            <h2 class="fw-bold mb-2">{{ $totalBuses }}</h2>
            <p class="mb-0"><i class="fas fa-bus me-2"></i>Bus</p>
        </div>
    </div>

</div>

<!-- ===== RÉSERVATIONS + ACTIONS RAPIDES ===== -->
<div class="row g-4 mb-4">

    <!-- Réservations récentes -->
    <div class="col-lg-8">
        <div class="card table-card">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-list text-primary me-2"></i>Réservations Récentes
                </h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Code Billet</th>
                            <th>Client</th>
                            <th>Trajet</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestReservations as $reservation)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-primary">
                                    {{ $reservation->ticket_code }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10
                                                text-primary d-flex align-items-center
                                                justify-content-center fw-bold"
                                         style="width:35px;height:35px;font-size:0.8rem">
                                        {{ strtoupper(substr($reservation->customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold small">
                                            {{ $reservation->customer->name }}
                                            {{ $reservation->customer->surname }}
                                        </p>
                                        <small class="text-muted">
                                            {{ $reservation->customer->email }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small>
                                    {{ $reservation->trip->displacement->start_point }}
                                    <i class="fas fa-arrow-right mx-1 text-muted"></i>
                                    {{ $reservation->trip->displacement->destination_point }}
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $reservation->reservation_date->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $reservation->status }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill">
                                    Voir
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Aucune réservation pour le moment
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Actions rapides + Statut réservations -->
    <div class="col-lg-4">

        <!-- Actions rapides -->
        <div class="card table-card p-4 mb-4">
            <h5 class="fw-bold mb-4">
                <i class="fas fa-bolt text-warning me-2"></i>Actions Rapides
            </h5>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.bus') }}"
                   class="btn btn-outline-primary rounded-pill py-2">
                    <i class="fas fa-plus me-2"></i>Ajouter un Bus
                </a>
                <a href="{{ route('admin.voyages') }}"
                   class="btn btn-outline-primary rounded-pill py-2">
                    <i class="fas fa-plus me-2"></i>Ajouter un Voyage
                </a>
                <a href="{{ route('admin.reservations') }}"
                   class="btn btn-outline-success rounded-pill py-2">
                    <i class="fas fa-ticket-alt me-2"></i>Voir Réservations
                </a>
                <a href="{{ route('admin.users') }}"
                   class="btn btn-outline-info rounded-pill py-2">
                    <i class="fas fa-users me-2"></i>Voir Clients
                </a>
            </div>
        </div>

        <!-- Statut réservations -->
        <div class="card table-card p-4">
            <h5 class="fw-bold mb-4">
                <i class="fas fa-chart-pie text-primary me-2"></i>Statut Réservations
            </h5>

            <!-- En attente -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge badge-en_attente px-3 py-2 rounded-pill">En attente</span>
                <span class="fw-bold">{{ $pendingReservations }}</span>
            </div>
            <div class="progress mb-3" style="height:6px">
                <div class="progress-bar bg-warning"
                     style="width:{{ $totalReservations > 0 ? ($pendingReservations/$totalReservations)*100 : 0 }}%">
                </div>
            </div>

            <!-- Confirmées -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge badge-confirmée px-3 py-2 rounded-pill">Confirmées</span>
                <span class="fw-bold">{{ $confirmedReservations }}</span>
            </div>
            <div class="progress mb-3" style="height:6px">
                <div class="progress-bar bg-success"
                     style="width:{{ $totalReservations > 0 ? ($confirmedReservations/$totalReservations)*100 : 0 }}%">
                </div>
            </div>

            <!-- Annulées -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge badge-annulée px-3 py-2 rounded-pill">Annulées</span>
                <span class="fw-bold">{{ $cancelledReservations }}</span>
            </div>
            <div class="progress" style="height:6px">
                <div class="progress-bar bg-danger"
                     style="width:{{ $totalReservations > 0 ? ($cancelledReservations/$totalReservations)*100 : 0 }}%">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== PROCHAINS VOYAGES ===== -->
<div class="row">
    <div class="col-12">
        <div class="card table-card">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex
                        justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-route text-primary me-2"></i>Prochains Voyages
                </h5>
                <a href="{{ route('admin.voyages') }}"
                   class="btn btn-sm btn-outline-primary rounded-pill">
                    Voir tout
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Trajet</th>
                            <th>Bus</th>
                            <th>Date Départ</th>
                            <th>Prix</th>
                            <th>Sièges Dispo</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingTripsList as $trip)
                        <tr>
                            <td class="ps-4 fw-bold">
                                {{ $trip->displacement->start_point }}
                                <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                {{ $trip->displacement->destination_point }}
                            </td>
                            <td>
                                <small>{{ $trip->displacement->bus->mack }}</small>
                            </td>
                            <td>
                                <small>
                                    {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y H:i') }}
                                </small>
                            </td>
                            <td class="fw-bold text-success">
                                {{ number_format($trip->price, 0, ',', ' ') }} F
                            </td>
                            <td>
                                @php
                                    $reserved  = $trip->ticketReservations()
                                                      ->where('status', '!=', 'annulée')
                                                      ->count();
                                    $available = $trip->displacement->bus->capacity - $reserved;
                                @endphp
                                <span class="badge rounded-pill px-3
                                      {{ $available > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $available }} / {{ $trip->displacement->bus->capacity }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $trip->travel_status }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($trip->travel_status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Aucun voyage planifié
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- ===== GRAPHIQUES ===== -->
<div class="row g-4 mt-2 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3 p-4">
            <h6 class="fw-bold mb-3">
                <i class="fas fa-chart-bar text-primary me-2"></i>
                Réservations des 6 derniers mois
            </h6>
            <div style="height:200px"><canvas id="reservationsChart"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3 p-4">
            <h6 class="fw-bold mb-3">
                <i class="fas fa-chart-bar text-success me-2"></i>
                Revenus des 6 derniers mois (FCFA)
            </h6>
            <div style="height:200px"><canvas id="revenusChart"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3 p-4">
            <h6 class="fw-bold mb-3">
                <i class="fas fa-chart-pie text-warning me-2"></i>
                Réservations par Statut
            </h6>
            <div style="height:200px"><canvas id="statusChart"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3 p-4">
            <h6 class="fw-bold mb-3">
                <i class="fas fa-chart-pie text-info me-2"></i>
                Voyages par Trajet
            </h6>
           <div style="height:200px"><canvas id="trajetsChart"></canvas></div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('reservationsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthLabels) !!},
            datasets: [{
                label: 'Réservations',
                data: {!! json_encode($reservationsPerMonth) !!},
                backgroundColor: 'rgba(26, 35, 126, 0.7)',
                borderColor: '#1a237e',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' } }
}
    });

    new Chart(document.getElementById('revenusChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthLabels) !!},
            datasets: [{
                label: 'Revenus (FCFA)',
                data: {!! json_encode($revenusPerMonth) !!},
                backgroundColor: 'rgba(46, 125, 50, 0.7)',
                borderColor: '#2e7d32',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: ['En attente', 'Confirmées', 'Annulées'],
            datasets: [{
                data: {!! json_encode($statusData) !!},
                backgroundColor: [
                    'rgba(245, 124, 0, 0.8)',
                    'rgba(46, 125, 50, 0.8)',
                    'rgba(198, 40, 40, 0.8)',
                ],
                borderColor: ['#f57c00', '#2e7d32', '#c62828'],
                borderWidth: 2,
            }]
        },
   options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' } }
}
    });

    new Chart(document.getElementById('trajetsChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($displacementLabels) !!},
            datasets: [{
                data: {!! json_encode($displacementData) !!},
                backgroundColor: [
                    'rgba(26, 35, 126, 0.8)',
                    'rgba(13, 71, 161, 0.8)',
                    'rgba(21, 101, 192, 0.8)',
                    'rgba(25, 118, 210, 0.8)',
                    'rgba(30, 136, 229, 0.8)',
                ],
                borderWidth: 2,
            }]
        },
     options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' } }
}
    });
</script>
@endpush