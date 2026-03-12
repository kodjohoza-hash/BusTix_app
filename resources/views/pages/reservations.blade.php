@extends('layouts.app')

@section('title', 'Mes Réservations')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .hero-reservations {
        background: linear-gradient(135deg, #0a0e27 0%, #1a237e 50%, #0d47a1 100%);
        padding: 70px 0 100px;
        position: relative;
        overflow: hidden;
    }

    .hero-reservations::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .stats-bar {
        background: #f0f4ff;
        padding-bottom: 50px;
    }

    .stat-card-res {
        border: none;
        border-radius: 20px;
        padding: 25px;
        margin-top: -50px;
        position: relative;
        z-index: 10;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        transition: all 0.3s;
        animation: fadeInUp 0.6s ease both;
        background: white;
    }

    .stat-card-res:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(26,35,126,0.15);
    }

    .reservation-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        animation: fadeInUp 0.5s ease both;
        margin-bottom: 20px;
    }

    .reservation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(26,35,126,0.12);
    }

    .ticket-code-box {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        border-radius: 15px;
        padding: 20px 15px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .ticket-code-box::before {
        content: '\f3ff';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        font-size: 80px;
        opacity: 0.08;
        color: white;
        right: -15px;
        bottom: -15px;
    }

    .btn-payer {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s;
    }

    .btn-payer:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(26,35,126,0.4);
        color: white;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
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

    /* Ticket perforé */
    .ticket-separator {
        position: relative;
        border-top: 2px dashed #e9ecef;
        margin: 15px 0;
    }

    .ticket-separator::before,
    .ticket-separator::after {
        content: '';
        position: absolute;
        width: 20px; height: 20px;
        background: #f8faff;
        border-radius: 50%;
        top: -11px;
    }

    .ticket-separator::before { left: -30px; }
    .ticket-separator::after  { right: -30px; }
</style>
@endpush

@section('content')

<!-- ===== HERO ===== -->
<section class="hero-reservations">
    <div class="container text-center position-relative" style="z-index:2">
        <div style="animation: fadeInUp 0.8s ease both">
            <div class="d-inline-flex align-items-center gap-2 mb-4 px-4 py-2 rounded-pill"
                 style="background:rgba(255,255,255,0.1);backdrop-filter:blur(10px)">
                <i class="fas fa-ticket-alt text-white"></i>
                <span class="text-white small fw-500">Mes Billets</span>
            </div>
            <h1 class="fw-bold text-white mb-2" style="font-size:2.5rem">
                Mes <span style="color:#64b5f6">Réservations</span>
            </h1>
            <p class="text-light opacity-75">Gérez tous vos billets de voyage</p>
        </div>
    </div>
</section>

<!-- ===== STATS ===== -->
<section class="stats-bar">
    <div class="container">
        @php
            $total     = $reservations->count();
            $confirmee = $reservations->where('status', 'confirmée')->count();
            $attente   = $reservations->where('status', 'en_attente')->count();
            $annulee   = $reservations->where('status', 'annulée')->count();
        @endphp
        <div class="row g-4">
            <div class="col-6 col-md-3">
                <div class="stat-card-res" style="animation-delay:0s">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                             style="width:50px;height:50px;min-width:50px;
                                    background:linear-gradient(135deg,#667eea,#764ba2)">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total</div>
                            <div class="fw-bold fs-5">{{ $total }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-res" style="animation-delay:0.1s">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                             style="width:50px;height:50px;min-width:50px;
                                    background:linear-gradient(135deg,#11998e,#38ef7d)">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Confirmées</div>
                            <div class="fw-bold fs-5 text-success">{{ $confirmee }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-res" style="animation-delay:0.2s">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                             style="width:50px;height:50px;min-width:50px;
                                    background:linear-gradient(135deg,#f7971e,#ffd200)">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="text-muted small">En attente</div>
                            <div class="fw-bold fs-5 text-warning">{{ $attente }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-res" style="animation-delay:0.3s">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                             style="width:50px;height:50px;min-width:50px;
                                    background:linear-gradient(135deg,#f64f59,#c471ed)">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Annulées</div>
                            <div class="fw-bold fs-5 text-danger">{{ $annulee }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== LISTE ===== -->
<section class="py-5" style="background:#f8faff">
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 reveal">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 reveal">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show rounded-3 mb-4 reveal">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @forelse($reservations as $i => $reservation)
        <div class="reservation-card card" style="animation-delay:{{ $i * 0.08 }}s">
            <div class="card-body p-4">
                <div class="row align-items-center g-3">

                    <!-- Code billet -->
                    <div class="col-md-2">
                        <div class="ticket-code-box">
                            <i class="fas fa-ticket-alt text-white fa-lg mb-2 d-block"></i>
                            <div class="text-white fw-bold font-monospace"
                                 style="font-size:0.75rem;letter-spacing:1px">
                                {{ $reservation->ticket_code }}
                            </div>
                        </div>
                    </div>

                    <!-- Trajet -->
                    <div class="col-md-3">
                        @if($reservation->trip && $reservation->trip->displacement)
                            <div class="fw-bold mb-1">
                                {{ $reservation->trip->displacement->start_point }}
                                <i class="fas fa-arrow-right mx-2 text-primary small"></i>
                                {{ $reservation->trip->displacement->destination_point }}
                            </div>
                            <small class="text-muted d-block">
                                <i class="fas fa-calendar me-1 text-primary"></i>
                                {{ \Carbon\Carbon::parse($reservation->trip->living_date_time)->format('d/m/Y à H:i') }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-bus me-1 text-primary"></i>
                                {{ $reservation->trip->displacement->bus->mack ?? 'N/A' }}
                            </small>
                        @endif
                    </div>

                    <!-- Siège + Prix -->
                    <div class="col-md-2 text-center">
                        @if($reservation->seat)
                            <div class="mb-2">
                                <span class="badge rounded-pill px-3 py-2"
                                      style="background:#e8f0fe;color:#1a237e">
                                    <i class="fas fa-chair me-1"></i>
                                    Siège {{ $reservation->seat->seat_number }}
                                </span>
                            </div>
                        @endif
                        @if($reservation->trip)
                            <div class="fw-bold text-primary">
                                {{ number_format($reservation->trip->price, 0, ',', ' ') }} FCFA
                            </div>
                        @endif
                    </div>

                    <!-- Statut -->
                    <div class="col-md-2 text-center">
                        @if($reservation->status === 'confirmée')
                            <span class="status-badge"
                                  style="background:#e8f5e9;color:#2e7d32">
                                <i class="fas fa-check-circle me-1"></i>Confirmée
                            </span>
                        @elseif($reservation->status === 'en_attente')
                            <span class="status-badge"
                                  style="background:#fff3e0;color:#e65100">
                                <i class="fas fa-clock me-1"></i>En attente
                            </span>
                        @else
                            <span class="status-badge"
                                  style="background:#ffebee;color:#c62828">
                                <i class="fas fa-times-circle me-1"></i>Annulée
                            </span>
                        @endif
                    </div>

                    <!-- Paiement -->
                    <div class="col-md-1 text-center">
                        @if($reservation->payment)
                            <span class="status-badge"
                                  style="background:#e8f5e9;color:#2e7d32">
                                <i class="fas fa-check me-1"></i>Payé
                            </span>
                        @else
                            <span class="status-badge"
                                  style="background:#fff3e0;color:#e65100">
                                <i class="fas fa-hourglass me-1"></i>Impayé
                            </span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="col-md-2 text-md-end">
                        <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                            @if(!$reservation->payment && $reservation->status === 'en_attente')
                                <a href="{{ route('client.payment.create', $reservation->id) }}"
                                   class="btn-payer">
                                    <i class="fas fa-credit-card me-1"></i>Payer
                                </a>
                            @endif

                            @if($reservation->status !== 'annulée' && !$reservation->payment)
                                <form method="POST"
                                      action="{{ route('client.reservations.destroy', $reservation->id) }}"
                                      onsubmit="return confirm('Annuler cette réservation ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 reveal">
            <i class="fas fa-ticket-alt fa-4x text-muted mb-4 d-block opacity-25"></i>
            <h5 class="text-muted mb-2">Vous n'avez aucune réservation</h5>
            <p class="text-muted small mb-4">Explorez nos displacements et réservez votre billet !</p>
            <a href="{{ route('voyages') }}" class="btn btn-primary rounded-pill px-5">
                <i class="fas fa-route me-2"></i>Voir les displacements
            </a>
        </div>
        @endforelse

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