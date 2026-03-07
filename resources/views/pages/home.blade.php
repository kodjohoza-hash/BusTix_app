@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 100px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 fadeInLeft">
                <h1 class="display-4 fw-bold mb-4">Voyagez en Toute Simplicité</h1>
                <p class="lead mb-4 text-light">Réservez votre billet de bus en quelques clics. Les meilleurs prix garantis pour vos destinations préférées.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('search') }}" class="btn btn-light btn-lg px-5 rounded-pill shadow hover-effect">
                        <i class="fas fa-search me-2"></i>Chercher un Voyage
                    </a>
                    <a href="{{ route('voyages') }}" class="btn btn-outline-light btn-lg px-5 rounded-pill hover-effect">
                        <i class="fas fa-road me-2"></i>Explorer
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://via.placeholder.com/500x400/667eea/ffffff?text=Bus+Travel" class="img-fluid rounded-3xl shadow-lg fadeInRight" alt="Bus Travel">
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Trouvez Votre Billet</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="{{ route('search') }}" method="GET" class="bg-white p-5 rounded-3xl shadow-lg">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Départ</label>
                            <input type="text" name="departure" class="form-control form-control-lg rounded-pill" placeholder="Ville de départ" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Destination</label>
                            <input type="text" name="destination" class="form-control form-control-lg rounded-pill" placeholder="Ville destination" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Featured Trips -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Voyages Populaires</h2>
        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="trip-card h-100 shadow-sm rounded-3xl overflow-hidden hover-effect transition-all">
                    <img src="https://via.placeholder.com/300x200/667eea/ffffff?text=Douala-Yaounde" class="card-img-top" alt="Douala-Yaounde">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary mb-2">Douala - Yaoundé</h5>
                        <p class="text-muted mb-3"><i class="fas fa-clock"></i> 3h 30m</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-success">8 places</span>
                            <span class="h5 mb-0 text-primary">8 500 FCFA</span>
                        </div>
                        <<a href="#" class="btn btn-primary btn-sm w-100 rounded-pill">Détails</a>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="trip-card h-100 shadow-sm rounded-3xl overflow-hidden hover-effect transition-all">
                    <img src="https://via.placeholder.com/300x200/764ba2/ffffff?text=Douala-Bamenda" class="card-img-top" alt="Douala-Bamenda">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary mb-2">Douala - Bamenda</h5>
                        <p class="text-muted mb-3"><i class="fas fa-clock"></i> 5h</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-success">12 places</span>
                            <span class="h5 mb-0 text-primary">10 500 FCFA</span>
                        </div>
                        <a href="{{ route('details', 2) }}" class="btn btn-primary btn-sm w-100 rounded-pill">Détails</a>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="trip-card h-100 shadow-sm rounded-3xl overflow-hidden hover-effect transition-all">
                    <img src="https://via.placeholder.com/300x200/667eea/ffffff?text=Bamenda-Yaounde" class="card-img-top" alt="Bamenda-Yaounde">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary mb-2">Bamenda - Yaoundé</h5>
                        <p class="text-muted mb-3"><i class="fas fa-clock"></i> 7h 30m</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-warning">4 places</span>
                            <span class="h5 mb-0 text-primary">12 000 FCFA</span>
                        </div>
                        <a href="{{ route('details', 3) }}" class="btn btn-primary btn-sm w-100 rounded-pill">Détails</a>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="trip-card h-100 shadow-sm rounded-3xl overflow-hidden hover-effect transition-all">
                    <img src="https://via.placeholder.com/300x200/764ba2/ffffff?text=Buea-Bamenda" class="card-img-top" alt="Buea-Bamenda">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary mb-2">Buea - Bamenda</h5>
                        <p class="text-muted mb-3"><i class="fas fa-clock"></i> 6h 30m</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-success">6 places</span>
                            <span class="h5 mb-0 text-primary">9 500 FCFA</span>
                        </div>
                        <a href="{{ route('details', 4) }}" class="btn btn-primary btn-sm w-100 rounded-pill">Détails</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Pourquoi Choisir BusTix?</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center feature-box">
                <div class="feature-icon mb-3">
                    <i class="fas fa-check-circle text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Meilleurs Prix</h5>
                <p class="text-muted">Les prix les plus compétitifs du marché, garantis.</p>
            </div>
            <div class="col-md-3 text-center feature-box">
                <div class="feature-icon mb-3">
                    <i class="fas fa-bolt text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Réservation Rapide</h5>
                <p class="text-muted">Réservez en moins de 2 minutes, facilement.</p>
            </div>
            <div class="col-md-3 text-center feature-box">
                <div class="feature-icon mb-3">
                    <i class="fas fa-lock text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Sécurisé</h5>
                <p class="text-muted">Paiement sécurisé avec SSL et protection de données.</p>
            </div>
            <div class="col-md-3 text-center feature-box">
                <div class="feature-icon mb-3">
                    <i class="fas fa-headset text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Support 24/7</h5>
                <p class="text-muted">Notre équipe est toujours disponible pour vous aider.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Avis de Nos Clients</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card p-4 bg-light rounded-3xl shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://via.placeholder.com/50/667eea/ffffff?text=J" class="rounded-circle me-3" alt="Avatar" width="50" height="50">
                        <div>
                            <h6 class="mb-0">Jean Dupont</h6>
                            <small class="text-muted">Vérifié</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <p class="text-muted">"Excellent service! Très facile à utiliser et les prix sont vraiment les meilleurs."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card p-4 bg-light rounded-3xl shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://via.placeholder.com/50/764ba2/ffffff?text=M" class="rounded-circle me-3" alt="Avatar" width="50" height="50">
                        <div>
                            <h6 class="mb-0">Marie Martin</h6>
                            <small class="text-muted">Vérifié</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <p class="text-muted">"Application super intuitive. J'ai réservé mon billet en 1 minute."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card p-4 bg-light rounded-3xl shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://via.placeholder.com/50/667eea/ffffff?text=P" class="rounded-circle me-3" alt="Avatar" width="50" height="50">
                        <div>
                            <h6 class="mb-0">Pierre Bernard</h6>
                            <small class="text-muted">Vérifié</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <p class="text-muted">"Meilleur service client. Ils m'ont aidé rapidement pour un changement de billet."</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
