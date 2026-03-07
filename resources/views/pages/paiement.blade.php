@extends('layouts.app')

@section('title', 'Paiement')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Progress Bar -->
            <div class="mb-5">
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                        <strong>Étape 3 sur 3 - Paiement</strong>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="card border-0 shadow-sm rounded-3xl mb-5">
                <div class="card-header bg-gradient text-white rounded-top-3xl p-4">
                    <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Méthode de Paiement</h4>
                </div>
                <div class="card-body p-5">
                    <div class="payment-methods">
                        <!-- Card Payment -->
                        <div class="form-check mb-4 p-4 border-2 rounded-3xl payment-option selected">
                            <input class="form-check-input" type="radio" name="payment" id="card" checked>
                            <label class="form-check-label w-100" for="card">
                                <i class="fas fa-credit-card me-2 text-primary"></i><strong>Carte Bancaire</strong>
                            </label>
                        </div>

                        <!-- Card Form (shown when card is selected) -->
                        <div id="card-form" class="mb-5 p-4 bg-light rounded-3xl">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Titulaire de la Carte</label>
                                    <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Jean Dupont">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control form-control-lg rounded-pill" placeholder="jean@example.com">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Numéro de Carte</label>
                                    <input type="text" class="form-control form-control-lg rounded-pill" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">CVV</label>
                                    <input type="text" class="form-control form-control-lg rounded-pill" placeholder="123" maxlength="3">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Date d'Expiration</label>
                                    <input type="text" class="form-control form-control-lg rounded-pill" placeholder="MM/AA">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Pays</label>
                                    <select class="form-select form-select-lg rounded-pill">
                                        <option>Sélectionner un pays...</option>
                                        <option>France</option>
                                        <option>Belgique</option>
                                        <option>Suisse</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div class="form-check mb-4 p-4 border-2 rounded-3xl payment-option">
                            <input class="form-check-input" type="radio" name="payment" id="paypal">
                            <label class="form-check-label w-100" for="paypal">
                                <i class="fab fa-paypal me-2 text-primary"></i><strong>PayPal</strong>
                            </label>
                        </div>

                        <!-- Apple Pay -->
                        <div class="form-check mb-4 p-4 border-2 rounded-3xl payment-option">
                            <input class="form-check-input" type="radio" name="payment" id="applepay">
                            <label class="form-check-label w-100" for="applepay">
                                <i class="fab fa-apple me-2 text-primary"></i><strong>Apple Pay</strong>
                            </label>
                        </div>

                        <!-- Google Pay -->
                        <div class="form-check p-4 border-2 rounded-3xl payment-option">
                            <input class="form-check-input" type="radio" name="payment" id="googlepay">
                            <label class="form-check-label w-100" for="googlepay">
                                <i class="fab fa-google me-2 text-primary"></i><strong>Google Pay</strong>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Address -->
            <div class="card border-0 shadow-sm rounded-3xl mb-5">
                <div class="card-header bg-gradient text-white rounded-top-3xl p-4">
                    <h4 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Adresse de Facturation</h4>
                </div>
                <div class="card-body p-5">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Adresse</label>
                            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="123 Rue de la Gare">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Code Postal</label>
                            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="75001">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ville</label>
                            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Paris">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pays</label>
                            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="France">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms -->
            <div class="form-check mb-5 p-4 bg-light rounded-3xl">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                    Je accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a>
                </label>
            </div>

            <!-- Buttons -->
            <div class="d-flex gap-3">
                <a href="{{ route('reservation') }}" class="btn btn-outline-primary btn-lg rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <button type="submit" class="btn btn-primary btn-lg rounded-pill ms-auto">
                    <i class="fas fa-check me-2"></i>Confirmer le Paiement
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Payment Summary -->
            <div class="card border-0 shadow-lg rounded-3xl sticky-top p-4" style="top: 20px;">
                <h5 class="fw-bold mb-4">Résumé du Paiement</h5>
                
                <div class="summary-item mb-4 pb-4 border-bottom">
                    <p class="text-muted mb-2">Voyage</p>
                    <h6 class="fw-bold">Douala - Yaoundé</h6>
                    <small class="text-muted">5 Mars 2026 - 10:30</small>
                </div>

                <div class="price-breakdown mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>2x Voyage</span>
                        <span>17 000 FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Assurance</span>
                        <span>+2 000 FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-3 border-bottom">
                        <span>Taxes</span>
                        <span>0 FCFA</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between fw-bold h5 mb-4">
                    <span>Montant Total:</span>
                    <span class="text-primary">19 000 FCFA</span>
                </div>

                <!-- Security Badge -->
                <div class="text-center p-3 bg-light rounded-3xl">
                    <i class="fas fa-lock text-success me-2"></i>
                    <small class="text-muted"><strong>Paiement 100% Sécurisé</strong></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
