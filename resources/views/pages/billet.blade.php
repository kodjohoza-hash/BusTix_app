@extends('layouts.app')

@section('title', 'Mon Billet')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes checkPop {
        0%   { transform: scale(0); }
        70%  { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .hero-billet {
        background: linear-gradient(135deg, #0a0e27 0%, #1a237e 50%, #0d47a1 100%);
        padding: 60px 0 80px;
    }

    .ticket-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(26,35,126,0.15);
        overflow: hidden;
        animation: fadeInUp 0.8s ease both;
        max-width: 650px;
        margin: -50px auto 0;
        position: relative;
        z-index: 10;
    }

    .ticket-header {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        padding: 30px;
        color: white;
        text-align: center;
        position: relative;
    }

    .check-icon {
        width: 70px; height: 70px;
        background: #4caf50;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        animation: checkPop 0.5s ease both;
        animation-delay: 0.3s;
    }

    .ticket-body {
        padding: 30px;
    }

    .ticket-separator {
        position: relative;
        border-top: 2px dashed #e0e0e0;
        margin: 25px -30px;
    }

    .ticket-separator::before,
    .ticket-separator::after {
        content: '';
        position: absolute;
        width: 24px; height: 24px;
        background: #f8faff;
        border-radius: 50%;
        top: -13px;
    }
    .ticket-separator::before { left: -12px; }
    .ticket-separator::after  { right: -12px; }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .info-row:last-child { border-bottom: none; }

    .qr-container {
        text-align: center;
        padding: 20px;
        background: #f8faff;
        border-radius: 16px;
        margin-top: 20px;
    }

    .btn-download {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 14px 40px;
        font-weight: 600;
        transition: all 0.3s;
        width: 100%;
        margin-top: 15px;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(26,35,126,0.4);
        color: white;
    }

    .code-billet {
        font-family: monospace;
        font-size: 1.4rem;
        font-weight: 800;
        color: #1a237e;
        letter-spacing: 2px;
    }

   @media print {
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        .no-print { display: none !important; }
        body { background: white !important; margin: 0 !important; padding: 0 !important; }
        .hero-billet { padding: 20px 0 40px !important; }
        section.py-5 { padding: 0 !important; background: white !important; }
        .container { padding: 0 !important; }
        .ticket-card {
            box-shadow: none !important;
            margin: 0 auto !important;
            max-width: 100% !important;
            border-radius: 0 !important;
        }
        .ticket-body { padding: 15px !important; }
        .ticket-header { padding: 15px !important; }
        .check-icon { width: 45px !important; height: 45px !important; }
        .qr-container { padding: 10px !important; }
        .info-row { padding: 6px 0 !important; }
        h1 { font-size: 1.2rem !important; }
        .code-billet { font-size: 1rem !important; }
        .fw-bold.fs-5 { font-size: 0.9rem !important; }
        @page {
            size: A4;
            margin: 10mm;
        }
    }
</style>
@endpush

@section('content')

<!-- HERO -->
<section class="hero-billet">
    <div class="container text-center">
        <div style="animation: fadeInUp 0.6s ease both">
            <div class="d-inline-flex align-items-center gap-2 mb-3 px-4 py-2 rounded-pill"
                 style="background:rgba(255,255,255,0.1)">
                <i class="fas fa-check-circle text-success"></i>
                <span class="text-white small">Paiement confirmé</span>
            </div>
            <h1 class="fw-bold text-white mb-2">Votre Billet</h1>
            <p class="text-light opacity-75">Conservez ce billet pour votre voyage</p>
        </div>
    </div>
</section>

<!-- TICKET -->
<section class="py-5" style="background:#f0f4ff">
    <div class="container">

        @if(session('success'))
        <div class="alert alert-success rounded-3 mb-4 text-center no-print"
             style="max-width:650px;margin:0 auto 20px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
        @endif

        <div class="ticket-card">

            <!-- Header -->
            <div class="ticket-header">
                <div class="check-icon">
                    <i class="fas fa-check text-white fa-2x"></i>
                </div>
                <h4 class="fw-bold mb-1">Billet Confirmé</h4>
                <div class="code-billet text-white mt-2">
                    {{ $payment->ticketReservation->ticket_code }}
                </div>
            </div>

            <!-- Body -->
            <div class="ticket-body">

                <!-- Trajet -->
                <div class="text-center mb-4">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <div>
                            <div class="fw-bold fs-5">
                                {{ $payment->ticketReservation->trip->displacement->start_point }}
                            </div>
                            <small class="text-muted">Départ</small>
                        </div>
                        <div style="flex:1;border-top:2px dashed #1a237e;position:relative">
                            <i class="fas fa-bus text-primary"
                               style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:white;padding:0 5px"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5">
                                {{ $payment->ticketReservation->trip->displacement->destination_point }}
                            </div>
                            <small class="text-muted">Arrivée</small>
                        </div>
                    </div>
                </div>

                <!-- Séparateur perforé -->
                <div class="ticket-separator"></div>

                <!-- Infos -->
                <div class="info-row">
                    <span class="text-muted small"><i class="fas fa-calendar me-2 text-primary"></i>Date</span>
                    <span class="fw-bold">
                        {{ \Carbon\Carbon::parse($payment->ticketReservation->trip->living_date_time)->format('d/m/Y à H:i') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="text-muted small"><i class="fas fa-chair me-2 text-primary"></i>Siège</span>
                    <span class="fw-bold">N° {{ $payment->ticketReservation->seat->seat_number }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted small"><i class="fas fa-user me-2 text-primary"></i>Passager</span>
                    <span class="fw-bold">
                        {{ $payment->ticketReservation->customer->name }}
                        {{ $payment->ticketReservation->customer->surname }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="text-muted small"><i class="fas fa-credit-card me-2 text-primary"></i>Mode paiement</span>
                    <span class="fw-bold text-capitalize">{{ $payment->payment_mode }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted small"><i class="fas fa-coins me-2 text-success"></i>Montant payé</span>
                    <span class="fw-bold text-success fs-5">
                        {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                    </span>
                </div>

                <!-- Séparateur perforé -->
                <div class="ticket-separator"></div>

                <!-- QR Code -->
                <div class="qr-container">
                    <p class="fw-bold mb-3 text-muted small">
                        <i class="fas fa-qrcode me-2"></i>QR Code du billet
                    </p>
                    <div id="qrcode"></div>
                    <small class="text-muted mt-2 d-block">
                        Présentez ce QR code au guichet
                    </small>
                </div>

                <!-- Boutons -->
                <div class="mt-4 no-print">
                    <button onclick="window.print()" class="btn-download">
                        <i class="fas fa-print me-2"></i>Imprimer / Télécharger le billet
                    </button>
                    <a href="{{ route('reservations') }}"
                       class="btn btn-outline-primary rounded-pill w-100 mt-3">
                        <i class="fas fa-ticket-alt me-2"></i>Voir mes réservations
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function generateQR() {
    const qrData = "{{ $payment->ticketReservation->ticket_code }}|{{ $payment->ticketReservation->trip->displacement->start_point }}-{{ $payment->ticketReservation->trip->displacement->destination_point }}|Siege:{{ $payment->ticketReservation->seat->seat_number }}|{{ \Carbon\Carbon::parse($payment->ticketReservation->trip->living_date_time)->format('d/m/Y H:i') }}|{{ $payment->amount }}FCFA";

    const container = document.getElementById('qrcode');
    container.innerHTML = '';

    const img = document.createElement('img');
    img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + encodeURIComponent(qrData) + '&color=1a237e&bgcolor=ffffff';
    img.width = 180;
    img.height = 180;
    img.style.borderRadius = '10px';
    img.alt = 'QR Code du billet';
    container.appendChild(img);
}

generateQR();
</script>
@endpush