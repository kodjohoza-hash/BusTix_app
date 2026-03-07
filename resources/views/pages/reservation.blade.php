@extends('layouts.app')

@section('title', 'Réservation')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Progress Bar -->
            <div class="mb-5">
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 66%;" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100">
                        <strong>Étape 2 sur 3</strong>
                    </div>
                </div>
            </div>

            <!-- Passenger Form -->
            <div class="card border-0 shadow-sm rounded-3xl mb-5">
                <div class="card-header bg-gradient text-white rounded-top-3xl p-4">
                    <h4 class="mb-0"><i class="fas fa-users me-2"></i>Informations des Passagers</h4>
                </div>
                <div class="card-body p-5">
                    <form>
                        @for($i = 1; $i <= 2; $i++)
                        <div class="passenger-form mb-5 pb-5 border-bottom">
                            <h5 class="fw-bold mb-4">Passager {{ $i }}</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Prénom</label>
                                    <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Jean" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nom</label>
                                    <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Dupont" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control form-control-lg rounded-pill" placeholder="jean@example.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Téléphone</label>
                                    <input type="tel" class="form-control form-control-lg rounded-pill" placeholder="+33 6 12 34 56 78" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Date de Naissance</label>
                                    <input type="date" class="form-control form-control-lg rounded-pill" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Genre</label>
                                    <select class="form-select form-select-lg rounded-pill" required>
                                        <option>Sélectionner...</option>
                                        <option>Homme</option>
                                        <option>Femme</option>
                                        <option>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endfor

                        <!-- Special Requests -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Demandes Spéciales (Optionnel)</h5>
                            <textarea class="form-control rounded-3xl" rows="4" placeholder="Des besoins particuliers? Handicap, allergies, etc..."></textarea>
                        </div>

                        <!-- Offers -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Offres et Assurances</h5>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="insurance">
                                <label class="form-check-label" for="insurance">
                                    Assurance voyage <strong>(+3€)</strong> - Protection contre les annulations
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="luggage">
                                <label class="form-check-label" for="luggage">
                                    Bagage extra <strong>(+5€)</strong> - Un bagage supplémentaire
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex gap-3 mb-5">
                <a href="{{ route('details', 1) }}" class="btn btn-outline-primary btn-lg rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <a href="{{ route('paiement') }}" class="btn btn-primary btn-lg rounded-pill ms-auto">
                    <i class="fas fa-arrow-right me-2"></i>Continuer au Paiement
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Reservation Summary -->
            <div class="card border-0 shadow-lg rounded-3xl sticky-top p-4" style="top: 20px;">
                <h5 class="fw-bold mb-4">Résumé de la Réservation</h5>
                
                <div class="summary-item mb-4 pb-4 border-bottom">
                    <p class="text-muted mb-2">Voyage</p>
                    <h6 class="fw-bold">Douala - Yaoundé</h6>
                    <small class="text-muted">5 Mars 2026 - 10:30</small>
                </div>

                <div class="summary-item mb-4 pb-4 border-bottom">
                    <p class="text-muted mb-2">Passagers</p>
                    <h6 class="fw-bold">2 passagers</h6>
                    <small class="text-muted">Places: 5, 6</small>
                </div>

                <div class="price-breakdown">
                    <div class="d-flex justify-content-between mb-2">
                        <span>2x Voyage</span>
                        <span>17 000 FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Assurance</span>
                        <span>+2 000 FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom fw-bold">
                        <span>Sous-total</span>
                        <span>19 000 FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold h5">
                        <span>Total:</span>
                        <span class="text-primary">19 000 FCFA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
