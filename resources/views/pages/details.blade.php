@extends('layouts.app')

@section('title', 'Détail du Voyage')

@section('content')

<!-- ===== HEADER ===== -->
<section style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%); color: white; padding: 60px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('voyages') }}" class="btn btn-outline-light btn-sm rounded-pill mb-3">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
                <h1 class="fw-bold mb-1">
                    {{ $trip->displacement->start_point }}
                    <i class="fas fa-arrow-right mx-2"></i>
                    {{ $trip->displacement->destination_point }}
                </h1>
                <p class="text-light mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y à H:i') }}
                </p>
            </div>
            <div class="col-auto">
                <div class="text-center">
                    <div class="display-5 fw-bold">
                        {{ number_format($trip->price, 0, ',', ' ') }}
                    </div>
                    <small>FCFA / personne</small>
                </div>
            </div>
        </div>
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

        <div class="row g-4">

            <!-- ===== INFOS VOYAGE ===== -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Infos du Voyage
                        </h5>
                    </div>
                    <div class="card-body px-4">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <small class="text-muted d-block">Départ</small>
                                <span class="fw-bold">{{ $trip->displacement->start_point }}</span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Destination</small>
                                <span class="fw-bold">{{ $trip->displacement->destination_point }}</span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Date & Heure</small>
                                <span class="fw-bold">
                                    {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y à H:i') }}
                                </span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Bus</small>
                                <span class="fw-bold">
                                    {{ $trip->displacement->bus->mack }}
                                    ({{ $trip->displacement->bus->bus_number }})
                                </span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Distance</small>
                                <span class="fw-bold">{{ $trip->displacement->distance ?? '?' }} km</span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Prix</small>
                                <span class="fw-bold text-primary fs-5">
                                    {{ number_format($trip->price, 0, ',', ' ') }} FCFA
                                </span>
                            </li>
                            <li>
                                <small class="text-muted d-block">Places disponibles</small>
                                @php
                                    $available = $trip->displacement->bus->capacity - count($reservedSeats);
                                @endphp
                                <span class="badge rounded-pill px-3 py-2
                                    {{ $available > 5 ? 'bg-success' : ($available > 0 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $available }} / {{ $trip->displacement->bus->capacity }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ===== SIÈGES ===== -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-chair text-primary me-2"></i>
                            Choisissez votre Siège
                        </h5>
                        <!-- Légende -->
                        <div class="d-flex gap-3 mt-2">
                            <small>
                                <span class="badge bg-success me-1">■</span>Disponible
                            </small>
                            <small>
                                <span class="badge bg-danger me-1">■</span>Réservé
                            </small>
                            <small>
                                <span class="badge bg-primary me-1">■</span>Sélectionné
                            </small>
                        </div>
                    </div>
                    <div class="card-body px-4">

                        @auth
                        <form method="POST" action="{{ route('client.reservations.store') }}" id="reservationForm">
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                            <input type="hidden" name="seat_id" id="selected_seat_id" value="">

                            <!-- Grille des sièges -->
                            <div class="row g-2 mb-4">
                                @foreach($trip->displacement->bus->seats as $seat)
                                @php
                                    $isReserved = in_array($seat->id, $reservedSeats);
                                @endphp
                                <div class="col-2 col-md-1">
                                    <button type="button"
                                            class="btn w-100 rounded-2 seat-btn p-2
                                                {{ $isReserved ? 'btn-danger disabled' : 'btn-success' }}"
                                            data-seat-id="{{ $seat->id }}"
                                            data-seat-number="{{ $seat->seat_number }}"
                                            {{ $isReserved ? 'disabled' : '' }}
                                            title="{{ $seat->seat_number }}">
                                        <i class="fas fa-chair d-block"></i>
                                        <small style="font-size:10px">{{ $seat->seat_number }}</small>
                                    </button>
                                </div>
                                @endforeach
                            </div>

                            <!-- Siège sélectionné -->
                            <div id="selectedSeatInfo" class="alert alert-primary d-none mb-3">
                                <i class="fas fa-check-circle me-2"></i>
                                Siège sélectionné : <strong id="selectedSeatNumber"></strong>
                            </div>

                            <!-- Bouton réserver -->
                            <button type="submit"
                                    id="reserveBtn"
                                    class="btn btn-primary btn-lg w-100 rounded-pill"
                                    disabled>
                                <i class="fas fa-ticket-alt me-2"></i>
                                Réserver ce siège — {{ number_format($trip->price, 0, ',', ' ') }} FCFA
                            </button>
                        </form>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">Connectez-vous pour réserver un siège</p>
                            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-5">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </a>
                        </div>
                        @endauth

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Sélection de siège
    document.querySelectorAll('.seat-btn:not(.disabled)').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Désélectionne tous
            document.querySelectorAll('.seat-btn').forEach(function(b) {
                if (!b.classList.contains('btn-danger')) {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-success');
                }
            });

            // Sélectionne celui-ci
            this.classList.remove('btn-success');
            this.classList.add('btn-primary');

            // Met à jour le formulaire
            const seatId     = this.getAttribute('data-seat-id');
            const seatNumber = this.getAttribute('data-seat-number');

            document.getElementById('selected_seat_id').value = seatId;
            document.getElementById('selectedSeatNumber').textContent = seatNumber;
            document.getElementById('selectedSeatInfo').classList.remove('d-none');
            document.getElementById('reserveBtn').removeAttribute('disabled');
        });
    });
</script>
@endpush