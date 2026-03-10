@extends('layouts.guichet')

@section('title', 'Nouvelle Réservation')
@section('subtitle', 'Créer une réservation au guichet')

@section('content')

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold">
                    <i class="fas fa-plus text-primary me-2"></i>
                    Nouvelle Réservation
                </h5>
            </div>
            <div class="card-body px-4 pb-4">
                <form method="POST" action="{{ route('guichet.reservations.store') }}" id="reservationForm">
                    @csrf

                    <!-- Client -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Client</label>
                        <select name="customer_id" class="form-select rounded-pill @error('customer_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner un client --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }} {{ $customer->surname }} — {{ $customer->telephone }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Voyage -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Voyage</label>
                        <select name="trip_id" class="form-select rounded-pill @error('trip_id') is-invalid @enderror"
                                id="tripSelect" required>
                            <option value="">-- Sélectionner un voyage --</option>
                            @foreach($trips as $trip)
                                <option value="{{ $trip->id }}"
                                    data-bus-id="{{ $trip->displacement->bus->id }}"
                                    {{ request('trip_id') == $trip->id ? 'selected' : '' }}>
                                    {{ $trip->displacement->start_point }} →
                                    {{ $trip->displacement->destination_point }} |
                                    {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y H:i') }} |
                                    {{ number_format($trip->price, 0, ',', ' ') }} FCFA
                                </option>
                            @endforeach
                        </select>
                        @error('trip_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sièges -->
                    <div class="mb-4" id="seatsSection" style="display:none;">
                        <label class="form-label fw-bold">Siège</label>
                        <input type="hidden" name="seat_id" id="seat_id">
                        <div id="seatsGrid" class="row g-2 mb-3"></div>
                        <div id="selectedSeatInfo" class="alert alert-primary d-none">
                            <i class="fas fa-check-circle me-2"></i>
                            Siège sélectionné : <strong id="selectedSeatNumber"></strong>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('guichet.reservations') }}"
                           class="btn btn-light rounded-pill px-4 flex-grow-1">
                            <i class="fas fa-arrow-left me-2"></i>Annuler
                        </a>
                        <button type="submit" id="submitBtn"
                                class="btn btn-primary rounded-pill px-4 flex-grow-1" disabled>
                            <i class="fas fa-save me-2"></i>Créer la Réservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Quand on change le voyage, charger les sièges via AJAX
document.getElementById('tripSelect').addEventListener('change', function() {
    const tripId = this.value;
    if (!tripId) {
        document.getElementById('seatsSection').style.display = 'none';
        return;
    }

    fetch(`/guichet/seats/${tripId}`)
        .then(res => res.json())
        .then(data => {
            const grid = document.getElementById('seatsGrid');
            grid.innerHTML = '';

            data.seats.forEach(seat => {
                const col = document.createElement('div');
                col.className = 'col-2 col-md-1';
                col.innerHTML = `
                    <button type="button"
                            class="btn w-100 rounded-2 seat-btn p-2 ${seat.reserved ? 'btn-danger disabled' : 'btn-success'}"
                            data-seat-id="${seat.id}"
                            data-seat-number="${seat.seat_number}"
                            ${seat.reserved ? 'disabled' : ''}>
                        <i class="fas fa-chair d-block"></i>
                        <small style="font-size:10px">${seat.seat_number}</small>
                    </button>`;
                grid.appendChild(col);
            });

            // Événements sur les sièges
            document.querySelectorAll('.seat-btn:not(.disabled)').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.seat-btn').forEach(b => {
                        if (!b.classList.contains('btn-danger')) {
                            b.classList.remove('btn-primary');
                            b.classList.add('btn-success');
                        }
                    });
                    this.classList.remove('btn-success');
                    this.classList.add('btn-primary');
                    document.getElementById('seat_id').value = this.getAttribute('data-seat-id');
                    document.getElementById('selectedSeatNumber').textContent = this.getAttribute('data-seat-number');
                    document.getElementById('selectedSeatInfo').classList.remove('d-none');
                    document.getElementById('submitBtn').removeAttribute('disabled');
                });
            });

            document.getElementById('seatsSection').style.display = 'block';
        });
});

// Déclencher si trip_id déjà sélectionné
const tripSelect = document.getElementById('tripSelect');
if (tripSelect.value) {
    tripSelect.dispatchEvent(new Event('change'));
}
</script>
@endpush