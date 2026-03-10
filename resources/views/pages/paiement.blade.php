@extends('layouts.app')

@section('title', 'Paiement')

@section('content')

<!-- ===== HEADER ===== -->
<section style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%); color: white; padding: 60px 0;">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">
            <i class="fas fa-credit-card me-2"></i>Paiement
        </h1>
        <p class="text-light mb-0">Finalisez votre réservation</p>
    </div>
</section>

<!-- ===== CONTENU ===== -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- ===== RÉCAPITULATIF ===== -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-receipt text-primary me-2"></i>
                            Récapitulatif de la Réservation
                        </h5>
                    </div>
                    <div class="card-body px-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Code Billet</small>
                                <span class="fw-bold text-primary font-monospace fs-5">
                                    {{ $reservation->ticket_code }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Trajet</small>
                                <span class="fw-bold">
                                    {{ $reservation->trip->displacement->start_point }}
                                    <i class="fas fa-arrow-right mx-1 text-primary"></i>
                                    {{ $reservation->trip->displacement->destination_point }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Date & Heure</small>
                                <span class="fw-bold">
                                    {{ \Carbon\Carbon::parse($reservation->trip->living_date_time)->format('d/m/Y à H:i') }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Siège</small>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    <i class="fas fa-chair me-1"></i>
                                    {{ $reservation->seat->seat_number }}
                                </span>
                            </div>
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5">Total à payer</span>
                                    <span class="fw-bold text-primary fs-3">
                                        {{ number_format($reservation->trip->price, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== FORMULAIRE PAIEMENT ===== -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-credit-card text-primary me-2"></i>
                            Mode de Paiement
                        </h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <form method="POST"
                              action="{{ route('client.payment.store', $reservation->id) }}">
                            @csrf

                            <!-- Modes de paiement -->
                            <div class="row g-3 mb-4">

                                <!-- Cash -->
                                <div class="col-md-4">
                                    <input type="radio" name="payment_mode" id="cash" value="espèces" required>
                                    <label class="btn btn-outline-primary w-100 rounded-3 py-3"
                                           for="cash">
                                        <i class="fas fa-money-bill-wave fa-2x d-block mb-2"></i>
                                        <span class="fw-bold">Espèces</span>
                                        <small class="d-block text-muted">Paiement en cash</small>
                                    </label>
                                </div>

                                <!-- Mobile Money -->
                                <div class="col-md-4">
                                    <input type="radio" name="payment_mode" id="mobile_money" value="mobile_money">
                                    <label class="btn btn-outline-primary w-100 rounded-3 py-3"
                                           for="mobile_money">
                                        <i class="fas fa-mobile-alt fa-2x d-block mb-2"></i>
                                        <span class="fw-bold">Mobile Money</span>
                                        <small class="d-block text-muted">MTN / Orange</small>
                                    </label>
                                </div>

                                <!-- Carte -->
                                <div class="col-md-4">
                                    <input type="radio" name="payment_mode" id="card" value="carte_bancaire">
                                    <label class="btn btn-outline-primary w-100 rounded-3 py-3"
                                           for="card">
                                        <i class="fas fa-credit-card fa-2x d-block mb-2"></i>
                                        <span class="fw-bold">Carte Bancaire</span>
                                        <small class="d-block text-muted">Visa / Mastercard</small>
                                    </label>
                                </div>

                            </div>

                            @error('payment_mode')
                                <div class="alert alert-danger rounded-3 mb-3">
                                    {{ $message }}
                                </div>
                            @enderror

                            <!-- Boutons -->
                            <div class="d-flex gap-3">
                                <a href="{{ route('reservations') }}"
                                   class="btn btn-light rounded-pill px-4 flex-grow-1">
                                    <i class="fas fa-arrow-left me-2"></i>Annuler
                                </a>
                                <button type="submit"
                                        class="btn btn-primary rounded-pill px-4 flex-grow-1">
                                    <i class="fas fa-check me-2"></i>
                                    Confirmer le Paiement —
                                    {{ number_format($reservation->trip->price, 0, ',', ' ') }} FCFA
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection