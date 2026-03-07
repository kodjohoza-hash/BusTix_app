@extends('layouts.app')

@section('title', 'Détails du Voyage')

@section('content')
<!-- Breadcrumb -->
<div class="container py-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('voyages') }}">Voyages</a></li>
            <li class="breadcrumb-item active">Détails</li>
        </ol>
    </nav>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Trip Header -->
            <div class="card border-0 shadow-sm rounded-3xl overflow-hidden mb-4">
                <img src="https://via.placeholder.com/800x400/667eea/ffffff?text=Trip+Details" class="card-img-top" alt="Trip">
                <div class="card-body p-5">
                    <h1 class="fw-bold mb-4 text-primary">Douala - Yaoundé</h1>
                    
                    <!-- Trip Info Grid -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-3">
                            <div class="info-box p-4 bg-light rounded-3xl text-center">
                                <p class="text-muted mb-2">Départ</p>
                                <h5 class="fw-bold">10:30</h5>
                                <small class="text-muted">Paris (Bercy)</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box p-4 bg-light rounded-3xl text-center">
                                <p class="text-muted mb-2">Durée</p>
                                <h5 class="fw-bold">4h 30m</h5>
                                <small class="text-muted"></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box p-4 bg-light rounded-3xl text-center">
                                <p class="text-muted mb-2">Arrivée</p>
                                <h5 class="fw-bold">14:00</h5>
                                <small class="text-muted">Yaoundé (Pk10)</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box p-4 bg-light rounded-3xl text-center">
                                <p class="text-muted mb-2">Places</p>
                                <h5 class="fw-bold text-success">8</h5>
                                <small class="text-muted">Disponibles</small>
                            </div>
                        </div>
                    </div>

                    <!-- Bus Details -->
                    <h4 class="fw-bold mb-4">Détails du Bus</h4>
                    <div class="bus-details bg-light p-4 rounded-3xl mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="https://via.placeholder.com/200/764ba2/ffffff?text=Cameroun+Transit" class="img-fluid rounded-2xl" alt="Bus">
                            </div>
                            <div class="col-md-9">
                                <h5 class="fw-bold mb-3">Cameroun Transit - Confort</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><i class="fas fa-check-circle text-success me-2"></i>WiFi Gratuit</p>
                                        <p><i class="fas fa-check-circle text-success me-2"></i>Toilettes</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><i class="fas fa-check-circle text-success me-2"></i>Climatisation</p>
                                        <p><i class="fas fa-check-circle text-success me-2"></i>Prises USB</p>
                                    </div>
                                </div>
                                <div class="rating mt-3">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                    <span class="ms-2 fw-bold">4.5/5</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seat Selection -->
                    <h4 class="fw-bold mb-4">Sélectionner vos Places</h4>
                    <div class="seat-selector bg-light p-5 rounded-3xl">
                        <div class="text-center mb-4">
                            <small class="text-muted">Conducateur</small>
                            <div class="bg-white border py-1 mt-2" style="height: 40px;"></div>
                        </div>
                        
                        <div class="row g-2 justify-content-center">
                            @for($i = 1; $i <= 32; $i++)
                            <div class="col-3 col-md-2">
                                <input type="checkbox" id="seat{{ $i }}" class="btn-check" value="seat{{ $i }}">
                                <label for="seat{{ $i }}" class="btn btn-outline-primary w-100 rounded-pill py-2" style="font-size: 0.8rem;">
                                    {{ $i }}
                                </label>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Price Box -->
            <div class="card border-0 shadow-lg rounded-3xl sticky-top p-4 mb-4" style="top: 20px;">
                <h4 class="fw-bold mb-4">Résumé du Voyage</h4>
                
                <div class="mb-4">
                    <p class="text-muted mb-2">Douala - Yaoundé</p>
                    <h5 class="fw-bold">5 Mars 2026</h5>
                </div>

                <div class="mb-4 pb-4 border-bottom">
                    <p class="text-muted mb-2">Places sélectionnées</p>
                    <div id="selected-seats" class="mb-2">
                        <span class="badge bg-primary">Aucune place</span>
                    </div>
                </div>

                <div class="price-breakdown mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>1x Voyage</span>
                        <span>8 500 FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Assurance (optionnel)</span>
                        <input type="checkbox" class="form-check-input" value="1000">
                        <span>+1 000 FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold h5 mb-4">
                        <span>Total:</span>
                        <span class="text-primary" id="total-price">8 500 FCFA</span>
                    </div>
                </div>

                <a href="{{ route('reservation', ['trip' => 1]) }}" class="btn btn-primary btn-lg w-100 rounded-pill mb-3">
                    <i class="fas fa-arrow-right me-2"></i>Continuer
                </a>
                <button class="btn btn-outline-primary btn-lg w-100 rounded-pill">
                    <i class="fas fa-heart me-2"></i>Ajouter aux favoris
                </button>
            </div>

            <!-- Info Box -->
            <div class="card border-0 shadow-sm rounded-3xl p-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Informations Importants</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2">Arrivée à l'heure ou remboursement</li>
                    <li class="mb-2">Annulation gratuite jusqu'à 24h avant</li>
                    <li class="mb-2">Politique bagage flexible</li>
                    <li>Support client 24/7</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
