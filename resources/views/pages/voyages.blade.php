@extends('layouts.app')

@section('title', 'Displacements')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-10px); }
    }

    .hero-displacements {
        background: linear-gradient(135deg, #0a0e27 0%, #1a237e 50%, #0d47a1 100%);
        padding: 80px 0 120px;
        position: relative;
        overflow: hidden;
    }

    .hero-displacements::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .floating-bus {
        position: absolute;
        color: rgba(255,255,255,0.05);
        animation: float 4s ease-in-out infinite;
    }

    .search-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        margin-top: -60px;
        position: relative;
        z-index: 10;
    }

    .search-input {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 12px 20px;
        transition: all 0.3s;
    }

    .search-input:focus {
        border-color: #1a237e;
        box-shadow: 0 0 0 4px rgba(26,35,126,0.1);
    }

    .btn-search {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 5px 20px rgba(26,35,126,0.3);
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(26,35,126,0.4);
        color: white;
    }

    .displacement-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        animation: fadeInUp 0.6s ease both;
    }

    .displacement-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 50px rgba(26,35,126,0.2);
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        padding: 25px;
        position: relative;
        overflow: hidden;
    }

    .card-header-gradient::after {
        content: '\f207';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: -15px;
        bottom: -15px;
        font-size: 90px;
        opacity: 0.08;
        color: white;
    }

    .route-arrow {
        width: 40px; height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .btn-reserve {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: 600;
        transition: all 0.3s;
        width: 100%;
    }

    .btn-reserve:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(26,35,126,0.4);
        color: white;
    }

    .info-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f0f4ff;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.82rem;
        color: #555;
    }

    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease;
    }

    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('content')

<!-- ===== HERO ===== -->
<section class="hero-displacements">
    <i class="fas fa-bus floating-bus" style="font-size:200px;top:-30px;right:5%"></i>
    <i class="fas fa-shuttle-van floating-bus" style="font-size:100px;bottom:20px;left:3%;animation-delay:1s"></i>

    <div class="container text-center position-relative" style="z-index:2">
        <div style="animation: fadeInUp 0.8s ease both">
            <div class="d-inline-flex align-items-center gap-2 mb-4 px-4 py-2 rounded-pill"
                 style="background:rgba(255,255,255,0.1);backdrop-filter:blur(10px)">
                <i class="fas fa-route text-white"></i>
                <span class="text-white small fw-500">Tous les Displacements</span>
            </div>
            <h1 class="fw-bold text-white mb-3" style="font-size:3rem">
                Nos <span style="color:#64b5f6">Displacements</span>
            </h1>
            <p class="text-light opacity-75 fs-5">
                Trouvez le displacement qui vous convient parmi nos offres
            </p>
        </div>
    </div>
</section>

<!-- ===== RECHERCHE ===== -->
<section style="background:#f0f4ff;padding-bottom:50px">
    <div class="container">
        <div class="search-card reveal">
            <h5 class="fw-bold mb-4">
                <i class="fas fa-search text-primary me-2"></i>
                Rechercher un Displacement
            </h5>
            <form action="{{ route('search') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-bold small">
                            <i class="fas fa-map-marker-alt text-success me-1"></i>Départ
                        </label>
                        <input type="text" name="departure"
                               class="form-control search-input"
                               placeholder="Ville de départ"
                               value="{{ request('departure') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold small">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i>Destination
                        </label>
                        <input type="text" name="destination"
                               class="form-control search-input"
                               placeholder="Ville de destination"
                               value="{{ request('destination') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-search w-100">
                            <i class="fas fa-search me-1"></i>Chercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- ===== LISTE ===== -->
<section class="py-5" style="background:#f8faff">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-5 reveal">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="fas fa-route text-primary me-2"></i>
                    {{ $trips->total() }} Displacement(s) disponible(s)
                </h4>
                <p class="text-muted mb-0 small">Choisissez votre trajet et réservez en quelques clics</p>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-home me-2"></i>Accueil
            </a>
        </div>

        <div class="row g-4">
            @forelse($trips as $i => $trip)
            <div class="col-md-6 col-lg-4 reveal" style="animation-delay:{{ ($i % 6) * 0.1 }}s">
                <div class="displacement-card card h-100">

                    <!-- Header -->
                    <div class="card-header-gradient text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-center flex-grow-1">
                                <div class="fw-bold fs-6 mb-1">
                                    {{ $trip->displacement->start_point }}
                                </div>
                                <small class="opacity-75">Départ</small>
                            </div>
                            <div class="route-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="text-center flex-grow-1">
                                <div class="fw-bold fs-6 mb-1">
                                    {{ $trip->displacement->destination_point }}
                                </div>
                                <small class="opacity-75">Arrivée</small>
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="text-center mt-3">
                            <span class="badge px-4 py-2 rounded-pill fw-bold fs-6"
                                  style="background:rgba(255,255,255,0.2);backdrop-filter:blur(10px)">
                                {{ number_format($trip->price, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <div class="info-chip">
                                <i class="fas fa-calendar text-primary"></i>
                                {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y') }}
                            </div>
                            <div class="info-chip">
                                <i class="fas fa-clock text-warning"></i>
                                {{ \Carbon\Carbon::parse($trip->living_date_time)->format('H:i') }}
                            </div>
                            <div class="info-chip">
                                <i class="fas fa-bus text-primary"></i>
                                {{ $trip->displacement->bus->mack ?? 'N/A' }}
                            </div>
                            <div class="info-chip">
                                <i class="fas fa-road text-success"></i>
                                {{ $trip->displacement->distance ?? '?' }} km
                            </div>
                        </div>

                        @php
                            $reserved  = $trip->ticketReservations()
                                              ->where('status', '!=', 'annulée')
                                              ->count();
                            $available = $trip->displacement->bus->capacity - $reserved;
                            $percent   = $trip->displacement->bus->capacity > 0
                                ? ($reserved / $trip->displacement->bus->capacity) * 100
                                : 0;
                        @endphp

                        <!-- Progress places -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Places disponibles</small>
                                <small class="fw-bold
                                    {{ $available > 5 ? 'text-success' : ($available > 0 ? 'text-warning' : 'text-danger') }}">
                                    {{ $available }} / {{ $trip->displacement->bus->capacity }}
                                </small>
                            </div>
                            <div class="progress rounded-pill" style="height:6px">
                                <div class="progress-bar
                                    {{ $available > 5 ? 'bg-success' : ($available > 0 ? 'bg-warning' : 'bg-danger') }}"
                                    style="width:{{ $percent }}%">
                                </div>
                            </div>
                        </div>

                        @if($available > 0)
                            <a href="{{ route('details', $trip->id) }}" class="btn-reserve">
                                <i class="fas fa-ticket-alt me-2"></i>Réserver maintenant
                            </a>
                        @else
                            <button class="btn btn-secondary w-100 rounded-3" disabled>
                                <i class="fas fa-times me-2"></i>Complet
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 reveal">
                <i class="fas fa-route fa-4x text-muted mb-4 d-block opacity-25"></i>
                <h5 class="text-muted">Aucun displacement disponible</h5>
                <p class="text-muted small">Revenez plus tard pour découvrir nos offres</p>
                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-5 mt-2">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
            </div>
            @endforelse
        </div>

        @if($trips->hasPages())
        <div class="d-flex justify-content-center mt-5 reveal">
            {{ $trips->links() }}
        </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) e.target.classList.add('visible');
    });
}, { threshold: 0.1 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@endpush