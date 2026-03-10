@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

<!-- ===== HERO SECTION ===== -->
<section style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%); color: white; padding: 100px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Voyagez en Toute <span style="color:#64b5f6">Simplicité</span>
                </h1>
                <p class="lead mb-4 text-light">
                    Réservez votre billet de bus en quelques clics.
                    Les meilleurs prix garantis pour vos destinations préférées.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('search') }}"
                       class="btn btn-light btn-lg px-5 rounded-pill shadow">
                        <i class="fas fa-search me-2"></i>Chercher un Voyage
                    </a>
                    <a href="{{ route('voyages') }}"
                       class="btn btn-outline-light btn-lg px-5 rounded-pill">
                        <i class="fas fa-road me-2"></i>Explorer
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <i class="fas fa-bus" style="font-size:200px; opacity:0.2;"></i>
            </div>
        </div>
    </div>
</section>

<!-- ===== RECHERCHE RAPIDE ===== -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4 fw-bold">Trouvez Votre Billet</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="{{ route('search') }}" method="GET"
                      class="bg-white p-4 rounded-3 shadow">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-500">Départ</label>
                            <input type="text"
                                   name="departure"
                                   class="form-control form-control-lg rounded-pill"
                                   placeholder="Ville de départ">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-500">Destination</label>
                            <input type="text"
                                   name="destination"
                                   class="form-control form-control-lg rounded-pill"
                                   placeholder="Ville destination">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit"
                                    class="btn btn-primary btn-lg w-100 rounded-pill">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ===== VOYAGES POPULAIRES ===== -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4 fw-bold">Voyages Disponibles</h2>
        <div class="row g-4">
            @forelse($featuredTrips as $trip)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <!-- Header coloré -->
                    <div class="p-4 text-white text-center"
                         style="background: linear-gradient(135deg, #1a237e, #0d47a1);">
                        <i class="fas fa-bus fa-2x mb-2 opacity-75"></i>
                        <h6 class="fw-bold mb-0">
                            {{ $trip->displacement->start_point }}
                        </h6>
                        <i class="fas fa-arrow-down my-1"></i>
                        <h6 class="fw-bold mb-0">
                            {{ $trip->displacement->destination_point }}
                        </h6>
                    </div>

                    <div class="card-body">
                        <!-- Date -->
                        <p class="text-muted small mb-2">
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y à H:i') }}
                        </p>

                        <!-- Bus -->
                        <p class="text-muted small mb-2">
                            <i class="fas fa-bus me-1"></i>
                            {{ $trip->displacement->bus->mack ?? 'N/A' }}
                        </p>

                        <!-- Places disponibles -->
                        @php
                            $reserved  = $trip->ticketReservations()
                                              ->where('status', '!=', 'annulée')
                                              ->count();
                            $available = $trip->displacement->bus->capacity - $reserved;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge rounded-pill px-3
                                {{ $available > 5 ? 'bg-success' : ($available > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $available }} places
                            </span>
                            <span class="fw-bold text-primary">
                                {{ number_format($trip->price, 0, ',', ' ') }} FCFA
                            </span>
                        </div>

                        <a href="{{ route('details', $trip->id) }}"
                           class="btn btn-primary btn-sm w-100 rounded-pill">
                            <i class="fas fa-ticket-alt me-1"></i>Réserver
                        </a>
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

        <!-- Voir tous les voyages -->
        <div class="text-center mt-4">
            <a href="{{ route('voyages') }}"
               class="btn btn-outline-primary rounded-pill px-5">
                <i class="fas fa-road me-2"></i>Voir tous les voyages
            </a>
        </div>
    </div>
</section>

<!-- ===== POURQUOI BUSTIX ===== -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Pourquoi Choisir BusTix ?</h2>
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                    <i class="fas fa-check-circle text-primary fa-3x mb-3"></i>
                    <h5 class="fw-bold">Meilleurs Prix</h5>
                    <p class="text-muted small">Les prix les plus compétitifs du marché, garantis.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                    <i class="fas fa-bolt text-warning fa-3x mb-3"></i>
                    <h5 class="fw-bold">Réservation Rapide</h5>
                    <p class="text-muted small">Réservez en moins de 2 minutes, facilement.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                    <i class="fas fa-lock text-success fa-3x mb-3"></i>
                    <h5 class="fw-bold">Sécurisé</h5>
                    <p class="text-muted small">Paiement sécurisé et protection de vos données.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                    <i class="fas fa-headset text-danger fa-3x mb-3"></i>
                    <h5 class="fw-bold">Support 24/7</h5>
                    <p class="text-muted small">Notre équipe est toujours disponible pour vous.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== TÉMOIGNAGES ===== -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Avis de Nos Clients</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-3 shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 fw-bold"
                             style="width:50px;height:50px;">J</div>
                        <div>
                            <h6 class="mb-0 fw-bold">Jean Dupont</h6>
                            <small class="text-muted">Client vérifié</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        @for($i=0;$i<5;$i++)<i class="fas fa-star text-warning"></i>@endfor
                    </div>
                    <p class="text-muted small">"Excellent service! Très facile à utiliser et les prix sont vraiment les meilleurs."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-3 shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3 fw-bold"
                             style="width:50px;height:50px;">M</div>
                        <div>
                            <h6 class="mb-0 fw-bold">Marie Ngono</h6>
                            <small class="text-muted">Client vérifié</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        @for($i=0;$i<5;$i++)<i class="fas fa-star text-warning"></i>@endfor
                    </div>
                    <p class="text-muted small">"Application super intuitive. J'ai réservé mon billet en 1 minute."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-3 shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center me-3 fw-bold"
                             style="width:50px;height:50px;">P</div>
                        <div>
                            <h6 class="mb-0 fw-bold">Paul Mbarga</h6>
                            <small class="text-muted">Client vérifié</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        @for($i=0;$i<5;$i++)<i class="fas fa-star text-warning"></i>@endfor
                    </div>
                    <p class="text-muted small">"Meilleur service client. Ils m'ont aidé rapidement pour un changement de billet."</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection