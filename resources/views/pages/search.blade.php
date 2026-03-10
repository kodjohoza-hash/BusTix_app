@extends('layouts.app')

@section('title', 'Recherche')

@section('content')

<!-- ===== HEADER ===== -->
<section style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%); color: white; padding: 60px 0;">
    <div class="container">
        <h1 class="fw-bold text-center mb-4">
            <i class="fas fa-search me-2"></i>Rechercher un Voyage
        </h1>
        <!-- Formulaire de recherche -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="{{ route('search') }}" method="GET"
                      class="bg-white p-4 rounded-3 shadow">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold text-dark">Départ</label>
                            <input type="text"
                                   name="departure"
                                   class="form-control rounded-pill"
                                   placeholder="Ville de départ"
                                   value="{{ $departure ?? '' }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold text-dark">Destination</label>
                            <input type="text"
                                   name="destination"
                                   class="form-control rounded-pill"
                                   placeholder="Ville destination"
                                   value="{{ $destination ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit"
                                    class="btn btn-primary w-100 rounded-pill">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ===== RÉSULTATS ===== -->
<section class="py-5">
    <div class="container">

        <!-- Titre résultats -->
        @if(isset($departure) || isset($destination))
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">
                @if(isset($trips))
                    {{ $trips->total() }} résultat(s) trouvé(s)
                    @if($departure)
                        pour <span class="text-primary">{{ $departure }}</span>
                    @endif
                    @if($destination)
                        → <span class="text-primary">{{ $destination }}</span>
                    @endif
                @endif
            </h5>
            <a href="{{ route('search') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                <i class="fas fa-times me-1"></i>Effacer
            </a>
        </div>
        @else
        <h5 class="fw-bold mb-4 text-muted text-center">
            <i class="fas fa-info-circle me-2"></i>
            Entrez une ville de départ ou de destination pour rechercher
        </h5>
        @endif

        <!-- Résultats -->
        @isset($trips)
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
                        <p class="text-muted small mb-1">
                            <i class="fas fa-calendar me-2 text-primary"></i>
                            {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y à H:i') }}
                        </p>
                        <p class="text-muted small mb-1">
                            <i class="fas fa-bus me-2 text-primary"></i>
                            {{ $trip->displacement->bus->mack ?? 'N/A' }}
                        </p>

                        @php
                            $reserved  = $trip->ticketReservations()
                                              ->where('status', '!=', 'annulée')
                                              ->count();
                            $available = $trip->displacement->bus->capacity - $reserved;
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                            <span class="badge rounded-pill px-3 py-2
                                {{ $available > 5 ? 'bg-success' : ($available > 0 ? 'bg-warning' : 'bg-danger') }}">
                                <i class="fas fa-chair me-1"></i>
                                {{ $available }} places
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
            <div class="col-12 text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3 d-block opacity-25"></i>
                <h5 class="text-muted mb-2">Aucun voyage trouvé</h5>
                <p class="text-muted small">Essayez avec d'autres villes ou dates</p>
                <a href="{{ route('voyages') }}" class="btn btn-primary rounded-pill px-5 mt-2">
                    <i class="fas fa-road me-2"></i>Voir tous les voyages
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($trips->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $trips->links() }}
        </div>
        @endif
        @endisset

    </div>
</section>

@endsection