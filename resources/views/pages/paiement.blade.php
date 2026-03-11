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
                        <form method="POST" action="{{ route('client.payment.store', $reservation->id) }}">
                            @csrf

                            <!-- ===== ÉTAPE 1 : Choix du mode ===== -->
                            <div class="row g-3 mb-4" id="step1">
                                <!-- Espèces -->
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="payment_mode"
                                           id="cash" value="espèces" required>
                                    <label class="btn btn-outline-success w-100 rounded-3 py-3 mode-btn"
                                           for="cash">
                                        <i class="fas fa-money-bill-wave fa-2x d-block mb-2"></i>
                                        <span class="fw-bold">Espèces</span>
                                        <small class="d-block text-muted">Paiement en cash</small>
                                    </label>
                                </div>
                                <!-- Mobile Money -->
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="payment_mode"
                                           id="mobile_money" value="mobile_money">
                                    <label class="btn btn-outline-warning w-100 rounded-3 py-3 mode-btn"
                                           for="mobile_money">
                                        <i class="fas fa-mobile-alt fa-2x d-block mb-2"></i>
                                        <span class="fw-bold">Mobile Money</span>
                                        <small class="d-block text-muted">MTN / Orange</small>
                                    </label>
                                </div>
                                <!-- Carte -->
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="payment_mode"
                                           id="card" value="carte_bancaire">
                                    <label class="btn btn-outline-info w-100 rounded-3 py-3 mode-btn"
                                           for="card">
                                        <i class="fas fa-credit-card fa-2x d-block mb-2"></i>
                                        <span class="fw-bold">Carte Bancaire</span>
                                        <small class="d-block text-muted">Visa / Mastercard</small>
                                    </label>
                                </div>
                            </div>

                            <!-- ===== ÉTAPE 2 : Espèces ===== -->
                            <div id="section_especes" class="payment-section d-none mb-4">
                                <div class="alert rounded-3 border-0"
                                     style="background:#e8f5e9;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width:50px;height:50px;background:#4caf50;min-width:50px">
                                            <i class="fas fa-money-bill-wave text-white fs-5"></i>
                                        </div>
                                        <div>
                                            <p class="fw-bold mb-1 text-success">Paiement en Espèces</p>
                                            <p class="mb-0 small text-muted">
                                                Présentez-vous au guichet avec le montant exact de
                                                <strong class="text-success">
                                                    {{ number_format($reservation->trip->price, 0, ',', ' ') }} FCFA
                                                </strong>
                                                et votre code billet
                                                <strong class="text-primary font-monospace">
                                                    {{ $reservation->ticket_code }}
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== ÉTAPE 2 : Mobile Money ===== -->
                            <div id="section_mobile" class="payment-section d-none mb-4">

                                <!-- Choix opérateur -->
                                <p class="fw-bold mb-3">
                                    <i class="fas fa-sim-card text-warning me-2"></i>
                                    Choisissez votre opérateur
                                </p>
                                <div class="row g-3 mb-4">
                                    <!-- MTN -->
                                    <div class="col-6">
                                        <input type="radio" class="btn-check"
                                               name="operateur" id="mtn" value="MTN">
                                        <label class="btn w-100 rounded-3 py-3 border-2"
                                               for="mtn"
                                               style="border:2px solid #e9ecef;">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center
                                                            justify-content-center fw-bold text-white"
                                                     style="width:35px;height:35px;background:#ffcc00;color:#000!important">
                                                    <span style="color:#000;font-size:0.7rem">MTN</span>
                                                </div>
                                                <span class="fw-bold">MTN Mobile Money</span>
                                            </div>
                                        </label>
                                    </div>
                                    <!-- Orange -->
                                    <div class="col-6">
                                        <input type="radio" class="btn-check"
                                               name="operateur" id="orange" value="Orange">
                                        <label class="btn w-100 rounded-3 py-3 border-2"
                                               for="orange"
                                               style="border:2px solid #e9ecef;">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center
                                                            justify-content-center fw-bold text-white"
                                                     style="width:35px;height:35px;background:#ff6600;">
                                                    <span style="font-size:0.7rem">OM</span>
                                                </div>
                                                <span class="fw-bold">Orange Money</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Champs Mobile Money -->
                                <div id="mobile_fields" class="d-none">
                                    <div class="p-4 rounded-3 mb-3"
                                         style="background:#fff8e1;border:2px solid #ffe082;">
                                        <p class="fw-bold mb-3" id="operateur_label">
                                            <i class="fas fa-mobile-alt me-2"></i>
                                            Informations de paiement
                                        </p>
                                        <!-- Numéro -->
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">
                                                Numéro de téléphone <span id="op_label"></span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="fas fa-phone text-warning"></i>
                                                </span>
                                                <input type="text" name="mobile_numero"
                                                       class="form-control"
                                                       placeholder="Ex: 6XXXXXXXX"
                                                       maxlength="9">
                                            </div>
                                        </div>
                                        <!-- Nom du compte -->
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">
                                                Nom du titulaire du compte
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="fas fa-user text-warning"></i>
                                                </span>
                                                <input type="text" name="mobile_nom"
                                                       class="form-control"
                                                       placeholder="Nom complet">
                                            </div>
                                        </div>
                                        <!-- Code PIN -->
                                        <div class="mb-0">
                                            <label class="form-label small fw-bold">
                                                Code PIN Mobile Money
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="fas fa-lock text-warning"></i>
                                                </span>
                                                <input type="password" name="mobile_pin"
                                                       class="form-control"
                                                       placeholder="••••"
                                                       maxlength="4">
                                                <button type="button"
                                                        class="btn btn-outline-secondary"
                                                        onclick="togglePin()">
                                                    <i class="fas fa-eye" id="pinEye"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Votre PIN est chiffré et sécurisé
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== ÉTAPE 2 : Carte Bancaire ===== -->
                            <div id="section_carte" class="payment-section d-none mb-4">
                                <div class="p-4 rounded-3"
                                     style="background:#e3f2fd;border:2px solid #90caf9;">

                                    <p class="fw-bold mb-3">
                                        <i class="fas fa-credit-card text-info me-2"></i>
                                        Informations de la carte
                                    </p>

                                    <!-- Numéro de carte -->
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">
                                            Numéro de carte
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-credit-card text-info"></i>
                                            </span>
                                            <input type="text" name="card_number"
                                                   class="form-control"
                                                   placeholder="XXXX XXXX XXXX XXXX"
                                                   maxlength="19"
                                                   id="cardNumber">
                                            <span class="input-group-text bg-white" id="cardType">
                                                <i class="fas fa-question text-muted"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Nom du titulaire -->
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">
                                            Nom du titulaire
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-user text-info"></i>
                                            </span>
                                            <input type="text" name="card_name"
                                                   class="form-control"
                                                   placeholder="NOM PRENOM"
                                                   style="text-transform:uppercase">
                                        </div>
                                    </div>

                                    <!-- Expiration + CVV -->
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">
                                                Date d'expiration
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="fas fa-calendar text-info"></i>
                                                </span>
                                                <input type="text" name="card_expiry"
                                                       class="form-control"
                                                       placeholder="MM/AA"
                                                       maxlength="5"
                                                       id="cardExpiry">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">
                                                CVV
                                                <i class="fas fa-question-circle text-muted ms-1"
                                                   title="3 chiffres au dos de la carte"></i>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white">
                                                    <i class="fas fa-lock text-info"></i>
                                                </span>
                                                <input type="password" name="card_cvv"
                                                       class="form-control"
                                                       placeholder="•••"
                                                       maxlength="3"
                                                       id="cardCvv">
                                                <button type="button"
                                                        class="btn btn-outline-secondary"
                                                        onclick="toggleCvv()">
                                                    <i class="fas fa-eye" id="cvvEye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <small class="text-muted mt-2 d-block">
                                        <i class="fas fa-lock me-1"></i>
                                        Paiement sécurisé SSL 256-bit
                                    </small>
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
                                <button type="submit" id="btnPayer"
                                        class="btn btn-primary rounded-pill px-4 flex-grow-1">
                                    <i class="fas fa-check me-2"></i>
                                    Confirmer — {{ number_format($reservation->trip->price, 0, ',', ' ') }} FCFA
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

@push('scripts')
<script>
// ===== Affichage sections selon mode =====
document.querySelectorAll('input[name="payment_mode"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Cacher toutes les sections
        document.querySelectorAll('.payment-section').forEach(s => s.classList.add('d-none'));

        if (this.value === 'espèces') {
            document.getElementById('section_especes').classList.remove('d-none');
        } else if (this.value === 'mobile_money') {
            document.getElementById('section_mobile').classList.remove('d-none');
        } else if (this.value === 'carte_bancaire') {
            document.getElementById('section_carte').classList.remove('d-none');
        }
    });
});

// ===== Affichage champs selon opérateur =====
document.querySelectorAll('input[name="operateur"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const fields = document.getElementById('mobile_fields');
        const label  = document.getElementById('op_label');
        fields.classList.remove('d-none');

        if (this.value === 'MTN') {
            label.textContent = 'MTN Mobile Money';
            label.style.color = '#f57f17';
        } else {
            label.textContent = 'Orange Money';
            label.style.color = '#e65100';
        }
    });
});

// ===== Format numéro de carte =====
document.getElementById('cardNumber').addEventListener('input', function() {
    let val = this.value.replace(/\D/g, '').substring(0, 16);
    this.value = val.replace(/(.{4})/g, '$1 ').trim();

    // Détecter type de carte
    const icon = document.getElementById('cardType');
    if (val.startsWith('4')) {
        icon.innerHTML = '<i class="fab fa-cc-visa text-primary fs-5"></i>';
    } else if (val.startsWith('5')) {
        icon.innerHTML = '<i class="fab fa-cc-mastercard text-danger fs-5"></i>';
    } else {
        icon.innerHTML = '<i class="fas fa-credit-card text-muted"></i>';
    }
});

// ===== Format date expiration =====
document.getElementById('cardExpiry').addEventListener('input', function() {
    let val = this.value.replace(/\D/g, '').substring(0, 4);
    if (val.length >= 2) val = val.substring(0,2) + '/' + val.substring(2);
    this.value = val;
});

// ===== Toggle PIN =====
function togglePin() {
    const pin  = document.querySelector('input[name="mobile_pin"]');
    const icon = document.getElementById('pinEye');
    if (pin.type === 'password') {
        pin.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        pin.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// ===== Toggle CVV =====
function toggleCvv() {
    const cvv  = document.getElementById('cardCvv');
    const icon = document.getElementById('cvvEye');
    if (cvv.type === 'password') {
        cvv.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        cvv.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush