@extends('layouts.app')

@section('title', 'Accueil')

@push('styles')
<style>
    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInLeft {
        from { opacity: 0; transform: translateX(-40px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(40px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(-5deg); }
        50%       { transform: translateY(-20px) rotate(-5deg); }
    }
    @keyframes floatSlow {
        0%, 100% { transform: translateY(0) rotate(5deg); }
        50%       { transform: translateY(-15px) rotate(5deg); }
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50%       { transform: scale(1.05); }
    }
    @keyframes road {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    @keyframes busMove {
        from { transform: translateX(-150px); }
        to   { transform: translateX(110vw); }
    }
    @keyframes shimmer {
        0%   { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    @keyframes countUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes starPop {
        0%   { transform: scale(0) rotate(0deg); }
        60%  { transform: scale(1.3) rotate(15deg); }
        100% { transform: scale(1) rotate(0deg); }
    }

    /* ===== HERO ===== */
    .hero-section {
        background: linear-gradient(135deg, #0a0e27 0%, #1a237e 40%, #0d47a1 70%, #1565c0 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
    }

    .hero-particles {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .particle {
        position: absolute;
        width: 4px; height: 4px;
        background: rgba(255,255,255,0.4);
        border-radius: 50%;
        animation: fadeInUp 3s infinite;
    }

    .hero-text { animation: fadeInLeft 1s ease both; }
    .hero-image { animation: fadeInRight 1s ease 0.3s both; }

    .hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 800;
        line-height: 1.1;
    }

    .gradient-text {
        background: linear-gradient(90deg, #64b5f6, #e3f2fd, #64b5f6);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmer 3s linear infinite;
    }

    .hero-bus {
        font-size: 180px;
        color: rgba(255,255,255,0.15);
        animation: float 4s ease-in-out infinite;
        filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));
    }

    .hero-bus-small {
        font-size: 80px;
        color: rgba(255,255,255,0.08);
        animation: floatSlow 5s ease-in-out infinite;
        position: absolute;
    }

    .btn-hero-primary {
        background: white;
        color: #1a237e;
        border: none;
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        color: #1a237e;
    }

    .btn-hero-secondary {
        background: rgba(255,255,255,0.15);
        color: white;
        border: 2px solid rgba(255,255,255,0.5);
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        backdrop-filter: blur(10px);
    }

    .btn-hero-secondary:hover {
        background: rgba(255,255,255,0.25);
        color: white;
        transform: translateY(-3px);
    }

    /* ===== ROAD ANIMATION ===== */
    .road-section {
        background: #1a237e;
        padding: 15px 0;
        overflow: hidden;
        position: relative;
    }

    .road-line {
        display: flex;
        gap: 30px;
        animation: road 15s linear infinite;
        width: 200%;
    }

    .road-dash {
        width: 60px; height: 4px;
        background: rgba(255,255,255,0.4);
        border-radius: 2px;
        flex-shrink: 0;
    }

    .moving-bus {
        position: absolute;
        top: -15px;
        font-size: 40px;
        animation: busMove 8s linear infinite;
        filter: drop-shadow(0 5px 10px rgba(0,0,0,0.3));
    }

    /* ===== SEARCH ===== */
    .search-section {
        background: #f0f4ff;
        padding: 60px 0;
    }

    .search-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(26,35,126,0.1);
        animation: fadeInUp 0.8s ease both;
    }

    .search-input {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 15px 20px;
        font-size: 1rem;
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
        padding: 15px 30px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 5px 20px rgba(26,35,126,0.3);
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(26,35,126,0.4);
        color: white;
    }

    /* ===== STATS ===== */
    .stats-section {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        padding: 60px 0;
    }

    .stat-item {
        text-align: center;
        color: white;
        animation: countUp 0.8s ease both;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
    }

    /* ===== TRIPS CARDS ===== */
    .trip-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        animation: fadeInUp 0.6s ease both;
    }

    .trip-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 50px rgba(26,35,126,0.2);
    }

    .trip-card-header {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        padding: 25px;
        position: relative;
        overflow: hidden;
    }

    .trip-card-header::before {
        content: '\f207';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 80px;
        opacity: 0.1;
        color: white;
    }

    .btn-reserve {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px;
        font-weight: 600;
        transition: all 0.3s;
        width: 100%;
    }

    .btn-reserve:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(26,35,126,0.4);
        color: white;
    }

    /* ===== FEATURES ===== */
    .feature-card {
        background: white;
        border-radius: 20px;
        padding: 35px 25px;
        text-align: center;
        transition: all 0.4s;
        border: 2px solid transparent;
        animation: fadeInUp 0.6s ease both;
    }

    .feature-card:hover {
        border-color: #1a237e;
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(26,35,126,0.15);
    }

    .feature-icon {
        width: 80px; height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 20px;
        transition: all 0.3s;
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.1) rotate(5deg);
    }

    /* ===== TESTIMONIALS ===== */
    .testimonial-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        transition: all 0.3s;
        animation: fadeInUp 0.6s ease both;
        position: relative;
    }

    .testimonial-card::before {
        content: '"';
        position: absolute;
        top: 15px; right: 25px;
        font-size: 5rem;
        color: #e3f2fd;
        font-family: Georgia, serif;
        line-height: 1;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(26,35,126,0.12);
    }

    .star { animation: starPop 0.3s ease both; }

    /* ===== BUS BANNER ===== */
    .bus-banner {
        background: linear-gradient(135deg, #0a0e27, #1a237e);
        padding: 80px 0;
        position: relative;
        overflow: hidden;
    }

    .bus-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .floating-bus-1 {
        position: absolute;
        font-size: 120px;
        color: rgba(255,255,255,0.05);
        top: -20px; left: 5%;
        animation: float 4s ease-in-out infinite;
    }

    .floating-bus-2 {
        position: absolute;
        font-size: 80px;
        color: rgba(255,255,255,0.05);
        bottom: -10px; right: 8%;
        animation: floatSlow 5s ease-in-out infinite;
    }

    /* ===== SCROLL ANIMATIONS ===== */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.7s ease;
    }

    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('content')

<!-- ===== HERO ===== -->
<section class="hero-section">

    <!-- Particles -->
    <div class="hero-particles" id="particles"></div>

    <!-- Bus décoratifs -->
    <i class="fas fa-bus hero-bus-small" style="top:10%;right:5%"></i>
    <i class="fas fa-shuttle-van hero-bus-small" style="bottom:15%;left:3%;font-size:50px;animation-delay:1s"></i>

    <div class="container position-relative" style="z-index:2">
        <div class="row align-items-center min-vh-100 py-5">

            <!-- Texte -->
            <div class="col-lg-6 hero-text">
                <div class="d-inline-flex align-items-center gap-2 mb-4 px-4 py-2 rounded-pill"
                     style="background:rgba(255,255,255,0.1);backdrop-filter:blur(10px)">
                    <span class="rounded-circle bg-success"
                          style="width:8px;height:8px;animation:pulse 1.5s infinite"></span>
                    <span class="text-white small fw-500">Disponible maintenant</span>
                </div>

                <h1 class="hero-title text-white mb-4">
                    Voyagez en Toute<br>
                    <span class="gradient-text">Simplicité</span>
                </h1>

                <p class="text-light mb-5 fs-5" style="opacity:0.85;line-height:1.8">
                    Réservez votre billet de bus en quelques clics.<br>
                    Les meilleurs prix garantis pour vos destinations préférées.
                </p>

                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('search') }}" class="btn-hero-primary">
                        <i class="fas fa-search me-2"></i>Chercher un Voyage
                    </a>
                    <a href="{{ route('voyages') }}" class="btn-hero-secondary">
                        <i class="fas fa-route me-2"></i>Displacements
                    </a>
                </div>

                <!-- Mini stats -->
                <div class="d-flex gap-4 mt-5">
                    <div class="text-white">
                        <div class="fw-bold fs-4">500+</div>
                        <small class="opacity-75">Voyages/mois</small>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,0.2)"></div>
                    <div class="text-white">
                        <div class="fw-bold fs-4">10K+</div>
                        <small class="opacity-75">Clients satisfaits</small>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,0.2)"></div>
                    <div class="text-white">
                        <div class="fw-bold fs-4">50+</div>
                        <small class="opacity-75">Destinations</small>
                    </div>
                </div>
            </div>

            <!-- Bus animé -->
            <div class="col-lg-6 hero-image text-center d-none d-lg-block">
                <div style="position:relative;display:inline-block">
                    <i class="fas fa-bus hero-bus"></i>
                    <!-- Cercles décoratifs -->
                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                                width:300px;height:300px;border-radius:50%;
                                border:2px solid rgba(255,255,255,0.05);"></div>
                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                                width:400px;height:400px;border-radius:50%;
                                border:2px solid rgba(255,255,255,0.03);"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vague bas -->
    <div style="position:absolute;bottom:0;left:0;right:0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#f0f4ff"/>
        </svg>
    </div>
</section>

<!-- ===== ROUTE ANIMÉE ===== -->
<div class="road-section">
    <i class="fas fa-bus moving-bus">🚌</i>
    <div class="road-line">
        @for($i=0; $i<40; $i++)
            <div class="road-dash"></div>
        @endfor
    </div>
</div>

<!-- ===== RECHERCHE ===== -->
<section class="search-section">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <h2 class="fw-bold fs-1 mb-2">Trouvez Votre Billet</h2>
            <p class="text-muted">Entrez votre destination et trouvez les meilleurs voyages</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="search-card reveal">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-bold small mb-2">
                                    <i class="fas fa-map-marker-alt text-success me-1"></i>Départ
                                </label>
                                <input type="text" name="departure"
                                       class="form-control search-input"
                                       placeholder="Ville de départ">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold small mb-2">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>Destination
                                </label>
                                <input type="text" name="destination"
                                       class="form-control search-input"
                                       placeholder="Ville de destination">
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
        </div>
    </div>
</section>

<!-- ===== STATS ===== -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-md-3 reveal">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="opacity-75 mt-1">Voyages par mois</div>
                </div>
            </div>
            <div class="col-6 col-md-3 reveal" style="animation-delay:0.1s">
                <div class="stat-item">
                    <div class="stat-number">10K+</div>
                    <div class="opacity-75 mt-1">Clients satisfaits</div>
                </div>
            </div>
            <div class="col-6 col-md-3 reveal" style="animation-delay:0.2s">
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="opacity-75 mt-1">Destinations</div>
                </div>
            </div>
            <div class="col-6 col-md-3 reveal" style="animation-delay:0.3s">
                <div class="stat-item">
                    <div class="stat-number">99%</div>
                    <div class="opacity-75 mt-1">Satisfaction client</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== VOYAGES ===== -->
<section class="py-5" style="background:#f8faff">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <h2 class="fw-bold fs-1 mb-2">Voyages Disponibles</h2>
            <p class="text-muted">Découvrez nos meilleures offres du moment</p>
        </div>
        <div class="row g-4">
            @forelse($featuredTrips as $i => $trip)
            <div class="col-md-6 col-lg-3 reveal" style="animation-delay:{{ $i * 0.1 }}s">
                <div class="trip-card card h-100">
                    <div class="trip-card-header text-white text-center">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <div>
                                <div class="fw-bold fs-6">{{ $trip->displacement->start_point }}</div>
                                <small class="opacity-75">Départ</small>
                            </div>
                            <div>
                                <i class="fas fa-arrow-right fa-lg mx-2"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-6">{{ $trip->displacement->destination_point }}</div>
                                <small class="opacity-75">Arrivée</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-calendar text-primary"></i>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="fas fa-bus text-primary"></i>
                            <small class="text-muted">{{ $trip->displacement->bus->mack ?? 'N/A' }}</small>
                        </div>
                        @php
                            $reserved  = $trip->ticketReservations()->where('status','!=','annulée')->count();
                            $available = $trip->displacement->bus->capacity - $reserved;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="badge rounded-pill px-3 py-2
                                {{ $available > 5 ? 'bg-success' : ($available > 0 ? 'bg-warning' : 'bg-danger') }}">
                                <i class="fas fa-chair me-1"></i>{{ $available }} places
                            </span>
                            <span class="fw-bold text-primary fs-5">
                                {{ number_format($trip->price, 0, ',', ' ') }} F
                            </span>
                        </div>
                        <a href="{{ route('details', $trip->id) }}" class="btn-reserve">
                            <i class="fas fa-ticket-alt me-2"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-bus fa-4x text-muted mb-3 d-block opacity-25"></i>
                <p class="text-muted">Aucun voyage disponible pour le moment</p>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-5 reveal">
            <a href="{{ route('voyages') }}"
               class="btn btn-outline-primary btn-lg rounded-pill px-5">
                <i class="fas fa-route me-2"></i>Voir tous les displacements
            </a>
        </div>
    </div>
</section>

<!-- ===== BUS BANNER ===== -->
<section class="bus-banner">
    <i class="fas fa-bus floating-bus-1"></i>
    <i class="fas fa-shuttle-van floating-bus-2"></i>
    <div class="container text-center position-relative" style="z-index:2">
        <div class="reveal">
            <i class="fas fa-bus text-white mb-4 d-block" style="font-size:5rem;opacity:0.9"></i>
            <h2 class="text-white fw-bold fs-1 mb-3">
                Prêt à Voyager ?
            </h2>
            <p class="text-light mb-5 fs-5 opacity-75">
                Des centaines de destinations vous attendent.<br>
                Réservez maintenant et profitez des meilleurs tarifs !
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('register') }}"
                   class="btn-hero-primary">
                    <i class="fas fa-user-plus me-2"></i>Créer un compte gratuit
                </a>
                <a href="{{ route('voyages') }}"
                   class="btn-hero-secondary">
                    <i class="fas fa-route me-2"></i>Voir les displacements
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ===== FEATURES ===== -->
<section class="py-5" style="background:#f0f4ff">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <h2 class="fw-bold fs-1 mb-2">Pourquoi Choisir BusTix ?</h2>
            <p class="text-muted">La meilleure expérience de réservation de bus au Cameroun</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3 reveal">
                <div class="feature-card h-100">
                    <div class="feature-icon"
                         style="background:linear-gradient(135deg,#e3f2fd,#bbdefb)">
                        <i class="fas fa-tag text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Meilleurs Prix</h5>
                    <p class="text-muted small">Les prix les plus compétitifs du marché, garantis.</p>
                </div>
            </div>
            <div class="col-md-3 reveal" style="animation-delay:0.1s">
                <div class="feature-card h-100">
                    <div class="feature-icon"
                         style="background:linear-gradient(135deg,#fff8e1,#ffecb3)">
                        <i class="fas fa-bolt text-warning"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Réservation Rapide</h5>
                    <p class="text-muted small">Réservez en moins de 2 minutes, facilement.</p>
                </div>
            </div>
            <div class="col-md-3 reveal" style="animation-delay:0.2s">
                <div class="feature-card h-100">
                    <div class="feature-icon"
                         style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9)">
                        <i class="fas fa-shield-alt text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-2">100% Sécurisé</h5>
                    <p class="text-muted small">Paiement sécurisé et protection de vos données.</p>
                </div>
            </div>
            <div class="col-md-3 reveal" style="animation-delay:0.3s">
                <div class="feature-card h-100">
                    <div class="feature-icon"
                         style="background:linear-gradient(135deg,#fce4ec,#f8bbd0)">
                        <i class="fas fa-headset text-danger"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Support 24/7</h5>
                    <p class="text-muted small">Notre équipe est toujours disponible pour vous.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== TÉMOIGNAGES ===== -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <h2 class="fw-bold fs-1 mb-2">Avis de Nos Clients</h2>
            <p class="text-muted">Ce que nos voyageurs disent de nous</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['J','Jean Dupont','bg-primary',"Excellent service! Très facile à utiliser et les prix sont vraiment les meilleurs."],
                ['M','Marie Ngono','bg-success',"Application super intuitive. J'ai réservé mon billet en 1 minute chrono !"],
                ['P','Paul Mbarga','bg-warning',"Meilleur service client. Ils m'ont aidé rapidement pour un changement de billet."]
            ] as $i => $t)
            <div class="col-md-4 reveal" style="animation-delay:{{ $i * 0.15 }}s">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle {{ $t[2] }} text-white d-flex align-items-center
                                    justify-content-center fw-bold me-3 fs-5"
                             style="width:55px;height:55px;min-width:55px">
                            {{ $t[0] }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $t[1] }}</h6>
                            <div class="d-flex gap-1 mt-1">
                                @for($s=0;$s<5;$s++)
                                    <i class="fas fa-star text-warning star"
                                       style="animation-delay:{{ $s * 0.1 }}s;font-size:0.8rem"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mb-0">{{ $t[3] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ===== PARTICLES =====
const container = document.getElementById('particles');
for (let i = 0; i < 50; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.cssText = `
        left: ${Math.random() * 100}%;
        top: ${Math.random() * 100}%;
        animation-delay: ${Math.random() * 3}s;
        animation-duration: ${2 + Math.random() * 3}s;
        width: ${2 + Math.random() * 4}px;
        height: ${2 + Math.random() * 4}px;
        opacity: ${0.2 + Math.random() * 0.5};
    `;
    container.appendChild(p);
}

// ===== SCROLL REVEAL =====
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@endpush