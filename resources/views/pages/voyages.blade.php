@extends('layouts.app')

@section('title', 'Tous les Voyages')

@section('content')

<!-- ===== HEADER ===== -->
<section style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%); color: white; padding: 60px 0;">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">
            <i class="fas fa-road me-2"></i>Tous les Voyages
        </h1>
        <p class="text-light mb-0">Trouvez le voyage qui vous convient</p>
    </div>
</section>

<!-- ===== RECHERCHE ===== -->
<section class="py-4 bg-light">
    <div class="container">
        <form action="{{ route('search') }}" method="GET" class="bg-white p-3 rounded-3 shadow-sm">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-500">Départ</label>
                    <input type="text" name="departure"
                           class="form-control rounded-pill"
                           placeholder="Ville de départ">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-500">Destination</label>
                    <input type="text" name="destination"
                           class="form-control rounded-pill"
                           placeholder="Ville destination">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        <i class="fas fa-search me-1"></i>Chercher
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- ===== LISTE VOYAGES ===== -->
<section class="py-5">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">
                {{ $trips->total() }} voyage(s) disponible(s)
            </h5>
        </div>

        <div class="row g-4">
            @forelse($trips as $trip)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">

                    <!-- Header -->
                    <div class="p-4 text-white"
                         style="background: linear-gradient(135deg, #1a237e, #0d47a1);">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-1">
                                    {{ $trip->displacement->start_point }}
                                </h6>
                                <i class="fas fa-arrow-down my-1 small"></i>
                                <h6 class="fw-bold mb-0">
                                    {{ $trip->displacement->destination_point }}
                                </h6>
                            </div>
                            <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold">
                                {{ number_format($trip->price, 0, ',', ' ') }} F
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Infos -->
                        <div class="mb-3">
                            <p class="text-muted small mb-1">
                                <i class="fas fa-calendar me-2 text-primary"></i>
                                {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y à H:i') }}
                            </p>
                            <p class="text-muted small mb-1">
                                <i class="fas fa-bus me-2 text-primary"></i>
                                {{ $trip->displacement->bus->mack ?? 'N/A' }}
                                ({{ $trip->displacement->bus->bus_number ?? '' }})
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                {{ $trip->displacement->distance ?? '?' }} km
                            </p>
                        </div>

                        <!-- Places disponibles -->
                        @php
                            $reserved  = $trip->ticketReservations()
                                              ->where('status', '!=', 'annulée')
                                              ->count();
                            $available = $trip->displacement->bus->capacity - $reserved;
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge rounded-pill px-3 py-2
                                {{ $available > 5 ? 'bg-success' : ($available > 0 ? 'bg-warning' : 'bg-danger') }}
                                bg-opacity-10
                                {{ $available > 5 ? 'text-success' : ($available > 0 ? 'text-warning' : 'text-danger') }}">
                                <i class="fas fa-chair me-1"></i>
                                {{ $available }} / {{ $trip->displacement->bus->capacity }} places
                            </span>
                        </div>

                        @if($available > 0)
                            <a href="{{ route('details', $trip->id) }}"
                               class="btn btn-primary w-100 rounded-pill">
                                <i class="fas fa-ticket-alt me-2"></i>Réserver
                            </a>
                        @else
                            <button class="btn btn-secondary w-100 rounded-pill" disabled>
                                <i class="fas fa-times me-2"></i>Complet
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="fas fa-bus fa-3x mb-3 d-block opacity-25"></i>
                Aucun voyage disponible pour le moment
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($trips->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $trips->links() }}
        </div>
        @endif

    </div>
</section>

@endsection