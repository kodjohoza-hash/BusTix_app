@extends('layouts.app')

@section('title', 'Détail du Voyage')

@section('content')

<!-- ===== HEADER ===== -->
<section style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%); color: white; padding: 60px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('voyages') }}" class="btn btn-outline-light btn-sm rounded-pill mb-3">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
                <h1 class="fw-bold mb-1">
                    {{ $trip->displacement->start_point }}
                    <i class="fas fa-arrow-right mx-2"></i>
                    {{ $trip->displacement->destination_point }}
                </h1>
                <p class="text-light mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y à H:i') }}
                </p>
            </div>
            <div class="col-auto">
                <div class="text-center">
                    <div class="display-5 fw-bold">
                        {{ number_format($trip->price, 0, ',', ' ') }}
                    </div>
                    <small>FCFA / personne</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CONTENU ===== -->
<section class="py-5">
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            <!-- ===== INFOS VOYAGE ===== -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Infos du Voyage
                        </h5>
                    </div>
                    <div class="card-body px-4">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <small class="text-muted d-block">Départ</small>
                                <span class="fw-bold">{{ $trip->displacement->start_point }}</span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Destination</small>
                                <span class="fw-bold">{{ $trip->displacement->destination_point }}</span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Date & Heure</small>
                                <span class="fw-bold">
                                    {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y à H:i') }}
                                </span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Bus</small>
                                <span class="fw-bold">
                                    {{ $trip->displacement->bus->mack }}
                                    ({{ $trip->displacement->bus->bus_number }})
                                </span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Distance</small>
                                <span class="fw-bold">{{ $trip->displacement->distance ?? '?' }} km</span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Prix</small>
                                <span class="fw-bold text-primary fs-5">
                                    {{ number_format($trip->price, 0, ',', ' ') }} FCFA
                                </span>
                            </li>
                            <li>
                                <small class="text-muted d-block">Places disponibles</small>
                                @php
                                    $available = $trip->displacement->bus->capacity - count($reservedSeats);
                                @endphp
                                <span class="badge rounded-pill px-3 py-2
                                    {{ $available > 5 ? 'bg-success' : ($available > 0 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $available }} / {{ $trip->displacement->bus->capacity }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ===== SIÈGES ===== -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-chair text-primary me-2"></i>
                            Choisissez votre Siège
                        </h5>
                        <!-- Légende -->
                        <div class="d-flex gap-3 mt-2">
                            <small>
                                <span class="badge bg-success me-1">■</span>Disponible
                            </small>
                            <small>
                                <span class="badge bg-danger me-1">■</span>Réservé
                            </small>
                            <small>
                                <span class="badge bg-primary me-1">■</span>Sélectionné
                            </small>
                        </div>
                    </div>
                    <div class="card-body px-4">

                        @auth
                        <form method="POST" action="{{ route('client.reservations.store') }}" id="reservationForm">
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                            <input type="hidden" name="seat_id" id="selected_seat_id" value="">

                            <!-- Grille des sièges -->
                            <div class="row g-2 mb-4">
                                @foreach($trip->displacement->bus->seats as $seat)
                                @php
                                    $isReserved = in_array($seat->id, $reservedSeats);
                                @endphp
                                <div class="col-2 col-md-1">
                                    <button type="button"
                                            class="btn w-100 rounded-2 seat-btn p-2
                                                {{ $isReserved ? 'btn-danger disabled' : 'btn-success' }}"
                                            data-seat-id="{{ $seat->id }}"
                                            data-seat-number="{{ $seat->seat_number }}"
                                            {{ $isReserved ? 'disabled' : '' }}
                                            title="{{ $seat->seat_number }}">
                                        <i class="fas fa-chair d-block"></i>
                                        <small style="font-size:10px">{{ $seat->seat_number }}</small>
                                    </button>
                                </div>
                                @endforeach
                            </div>

                            <!-- Siège sélectionné -->
                            <div id="selectedSeatInfo" class="alert alert-primary d-none mb-3">
                                <i class="fas fa-check-circle me-2"></i>
                                Siège sélectionné : <strong id="selectedSeatNumber"></strong>
                            </div>

                            <!-- Bouton réserver -->
                            <button type="submit"
                                    id="reserveBtn"
                                    class="btn btn-primary btn-lg w-100 rounded-pill"
                                    disabled>
                                <i class="fas fa-ticket-alt me-2"></i>
                                Réserver ce siège — {{ number_format($trip->price, 0, ',', ' ') }} FCFA
                            </button>
                        </form>
                        @else
                        <!-- Grille sièges visible mais non cliquable -->
                        <div class="row g-2 mb-4">
                            @foreach($trip->displacement->bus->seats as $seat)
                            @php $isReserved = in_array($seat->id, $reservedSeats); @endphp
                            <div class="col-2 col-md-1">
                                <button type="button"
                                        class="btn w-100 rounded-2 p-2
                                            {{ $isReserved ? 'btn-danger' : 'btn-success' }}"
                                        disabled
                                        title="{{ $seat->seat_number }}">
                                    <i class="fas fa-chair d-block"></i>
                                    <small style="font-size:10px">{{ $seat->seat_number }}</small>
                                </button>
                            </div>
                            @endforeach
                        </div>

                        <!-- Bouton qui ouvre le modal -->
                        <button type="button"
                                class="btn btn-primary btn-lg w-100 rounded-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#authModal">
                            <i class="fas fa-ticket-alt me-2"></i>
                            Réserver ce siège — {{ number_format($trip->price, 0, ',', ' ') }} FCFA
                        </button>
                        @endauth

<!-- ===== MODAL AUTH ===== -->
@guest
<div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">

            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div class="d-flex gap-2">
                    <button type="button"
                            class="btn rounded-pill px-4 fw-bold tab-auth-btn active"
                            id="btnShowLogin"
                            style="background:linear-gradient(135deg,#1a237e,#0d47a1);color:white">
                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                    </button>
                    <button type="button"
                            class="btn btn-outline-primary rounded-pill px-4 fw-bold tab-auth-btn"
                            id="btnShowRegister">
                        <i class="fas fa-user-plus me-2"></i>S'inscrire
                    </button>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <!-- ===== FORM CONNEXION ===== -->
                <div id="formLogin">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2"
                             style="width:55px;height:55px;background:linear-gradient(135deg,#1a237e,#0d47a1)">
                            <i class="fas fa-bus text-white fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Bienvenue ! 👋</h5>
                        <p class="text-muted small">Connectez-vous pour finaliser votre réservation</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success rounded-3">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ url()->current() }}">

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="border-radius:10px 0 0 10px;border:2px solid #e9ecef;
                                             border-right:none;background:#f8f9fa;color:#1a237e">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none;padding:12px 15px"
                                       placeholder="votre@email.com"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="border-radius:10px 0 0 10px;border:2px solid #e9ecef;
                                             border-right:none;background:#f8f9fa;color:#1a237e">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" id="loginPwd"
                                       class="form-control @error('password') is-invalid @enderror"
                                       style="border:2px solid #e9ecef;border-left:none;border-right:none;padding:12px 15px"
                                       placeholder="••••••••" required>
                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none"
                                        onclick="togglePwd('loginPwd','eyeLogin')">
                                    <i class="fas fa-eye" id="eyeLogin"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit"
                                class="btn w-100 text-white fw-bold rounded-pill py-3 mb-3"
                                style="background:linear-gradient(135deg,#1a237e,#0d47a1);
                                       box-shadow:0 5px 15px rgba(26,35,126,0.3)">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </button>
                    </form>
                </div>

                <!-- ===== FORM INSCRIPTION ===== -->
                <div id="formRegister" style="display:none">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2"
                             style="width:55px;height:55px;background:linear-gradient(135deg,#1a237e,#0d47a1)">
                            <i class="fas fa-user-plus text-white fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Créer un compte 🚀</h5>
                        <p class="text-muted small">Inscrivez-vous pour réserver votre billet</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ url()->current() }}">

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Prénom</label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="border-radius:10px 0 0 10px;border:2px solid #e9ecef;
                                                 border-right:none;background:#f8f9fa;color:#1a237e">
                                        <i class="fas fa-user small"></i>
                                    </span>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none;padding:10px 15px"
                                           placeholder="Prénom"
                                           value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Nom</label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="border-radius:10px 0 0 10px;border:2px solid #e9ecef;
                                                 border-right:none;background:#f8f9fa;color:#1a237e">
                                        <i class="fas fa-user small"></i>
                                    </span>
                                    <input type="text" name="user_surname"
                                           class="form-control @error('user_surname') is-invalid @enderror"
                                           style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none;padding:10px 15px"
                                           placeholder="Nom"
                                           value="{{ old('user_surname') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="border-radius:10px 0 0 10px;border:2px solid #e9ecef;
                                             border-right:none;background:#f8f9fa;color:#1a237e">
                                    <i class="fas fa-phone small"></i>
                                </span>
                                <input type="text" name="telephone"
                                       class="form-control @error('telephone') is-invalid @enderror"
                                       style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none;padding:10px 15px"
                                       placeholder="Ex: 6912345678"
                                       value="{{ old('telephone') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="border-radius:10px 0 0 10px;border:2px solid #e9ecef;
                                             border-right:none;background:#f8f9fa;color:#1a237e">
                                    <i class="fas fa-envelope small"></i>
                                </span>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none;padding:10px 15px"
                                       placeholder="votre@email.com"
                                       value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="border-radius:10px 0 0 10px;border:2px solid #e9ecef;
                                             border-right:none;background:#f8f9fa;color:#1a237e">
                                    <i class="fas fa-lock small"></i>
                                </span>
                                <input type="password" name="password" id="regPwd"
                                       class="form-control @error('password') is-invalid @enderror"
                                       style="border:2px solid #e9ecef;border-left:none;border-right:none;padding:10px 15px"
                                       placeholder="Minimum 8 caractères" required>
                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none"
                                        onclick="togglePwd('regPwd','eyeReg1')">
                                    <i class="fas fa-eye" id="eyeReg1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Confirmer le mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="border-radius:10px 0 0 10px;border:2px solid #e9ecef;
                                             border-right:none;background:#f8f9fa;color:#1a237e">
                                    <i class="fas fa-lock small"></i>
                                </span>
                                <input type="password" name="password_confirmation" id="regPwdConfirm"
                                       class="form-control"
                                       style="border:2px solid #e9ecef;border-left:none;border-right:none;padding:10px 15px"
                                       placeholder="Répétez votre mot de passe" required>
                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none"
                                        onclick="togglePwd('regPwdConfirm','eyeReg2')">
                                    <i class="fas fa-eye" id="eyeReg2"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                                class="btn w-100 text-white fw-bold rounded-pill py-3"
                                style="background:linear-gradient(135deg,#1a237e,#0d47a1);
                                       box-shadow:0 5px 15px rgba(26,35,126,0.3)">
                            <i class="fas fa-user-plus me-2"></i>Créer mon compte
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle entre Connexion et Inscription
document.getElementById('btnShowLogin').addEventListener('click', function() {
    document.getElementById('formLogin').style.display = 'block';
    document.getElementById('formRegister').style.display = 'none';
    this.style.background = 'linear-gradient(135deg,#1a237e,#0d47a1)';
    this.style.color = 'white';
    this.classList.remove('btn-outline-primary');
    document.getElementById('btnShowRegister').style.background = '';
    document.getElementById('btnShowRegister').style.color = '';
    document.getElementById('btnShowRegister').classList.add('btn-outline-primary');
});

document.getElementById('btnShowRegister').addEventListener('click', function() {
    document.getElementById('formRegister').style.display = 'block';
    document.getElementById('formLogin').style.display = 'none';
    this.style.background = 'linear-gradient(135deg,#1a237e,#0d47a1)';
    this.style.color = 'white';
    this.classList.remove('btn-outline-primary');
    document.getElementById('btnShowLogin').style.background = '';
    document.getElementById('btnShowLogin').style.color = '';
    document.getElementById('btnShowLogin').classList.add('btn-outline-primary');
});

// Toggle password
function togglePwd(fieldId, iconId) {
    const pwd  = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Ouvre modal sur erreur de validation
@if($errors->any())
    var authModal = new bootstrap.Modal(document.getElementById('authModal'));
    authModal.show();
    @if($errors->has('name') || $errors->has('user_surname') || $errors->has('telephone'))
        document.getElementById('btnShowRegister').click();
    @endif
@endif
</script>
@endpush
@endguest

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Sélection de siège
    document.querySelectorAll('.seat-btn:not(.disabled)').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Désélectionne tous
            document.querySelectorAll('.seat-btn').forEach(function(b) {
                if (!b.classList.contains('btn-danger')) {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-success');
                }
            });

            // Sélectionne celui-ci
            this.classList.remove('btn-success');
            this.classList.add('btn-primary');

            // Met à jour le formulaire
            const seatId     = this.getAttribute('data-seat-id');
            const seatNumber = this.getAttribute('data-seat-number');

            document.getElementById('selected_seat_id').value = seatId;
            document.getElementById('selectedSeatNumber').textContent = seatNumber;
            document.getElementById('selectedSeatInfo').classList.remove('d-none');
            document.getElementById('reserveBtn').removeAttribute('disabled');
            // Empêcher la re-soumission du formulaire après retour
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// Désactiver le bouton après soumission
document.querySelector('form')?.addEventListener('submit', function() {
    const btn = this.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Réservation en cours...';
    }
});
        });
    });
</script>
@endpush