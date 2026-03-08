@extends('layouts.admin')

@section('title', 'Gestion des Réservations')
@section('subtitle', 'Gérez toutes les réservations BusTix')

@section('content')

<!-- ===== ALERTES ===== -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- ===== STATISTIQUES ===== -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card blue">
            <div class="stat-number">{{ $totalReservations }}</div>
            <div class="stat-label"><i class="fas fa-ticket-alt me-1"></i>Total</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card orange">
            <div class="stat-number">{{ $pendingReservations }}</div>
            <div class="stat-label"><i class="fas fa-clock me-1"></i>En attente</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card green">
            <div class="stat-number">{{ $confirmedReservations }}</div>
            <div class="stat-label"><i class="fas fa-check me-1"></i>Confirmées</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card purple">
            <div class="stat-number">{{ $cancelledReservations }}</div>
            <div class="stat-label"><i class="fas fa-times me-1"></i>Annulées</div>
        </div>
    </div>
</div>

<!-- ===== LISTE DES RÉSERVATIONS ===== -->
<div class="card table-card">
    <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
        <h6 class="fw-bold mb-0">
            <i class="fas fa-list text-primary me-2"></i>
            Toutes les Réservations
            <span class="badge bg-primary bg-opacity-10 text-primary ms-2">
                {{ $totalReservations }}
            </span>
        </h6>
    </div>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Code Billet</th>
                        <th>Client</th>
                        <th>Trajet</th>
                        <th>Siège</th>
                        <th>Date Voyage</th>
                        <th>Paiement</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <!-- Code Billet -->
                        <td class="ps-4">
                            <span class="fw-bold text-primary font-monospace">
                                {{ $reservation->ticket_code ?? 'N/A' }}
                            </span>
                        </td>

                        <!-- Client -->
                        <td>
                            @if($reservation->customer)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                         style="width:35px;height:35px;font-size:14px;">
                                        {{ strtoupper(substr($reservation->customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-500">{{ $reservation->customer->name }}</div>
                                        <small class="text-muted">{{ $reservation->customer->email }}</small>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">Client supprimé</span>
                            @endif
                        </td>

                        <!-- Trajet -->
                        <td>
                            @if($reservation->trip && $reservation->trip->displacement)
                                <div class="fw-500">
                                    {{ $reservation->trip->displacement->start_point }}
                                    <i class="fas fa-arrow-right text-muted mx-1 small"></i>
                                    {{ $reservation->trip->displacement->destination_point }}
                                </div>
                                <small class="text-muted">
                                    {{ number_format($reservation->trip->price, 0, ',', ' ') }} FCFA
                                </small>
                            @else
                                <span class="text-muted">Voyage supprimé</span>
                            @endif
                        </td>

                        <!-- Siège -->
                        <td>
                            @if($reservation->seat)
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                    <i class="fas fa-chair me-1"></i>
                                    {{ $reservation->seat->seat_number }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Date Voyage -->
                        <td>
                            @if($reservation->trip)
                                <div>{{ \Carbon\Carbon::parse($reservation->trip->living_date_time)->format('d/m/Y') }}</div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($reservation->trip->living_date_time)->format('H:i') }}
                                </small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Paiement -->
                        <td>
                            @if($reservation->payment)
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                    <i class="fas fa-check me-1"></i>Payé
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                    <i class="fas fa-clock me-1"></i>Non payé
                                </span>
                            @endif
                        </td>

                        <!-- Statut -->
                        <td>
                            @if($reservation->status === 'confirmée')
                                <span class="badge badge-confirmee px-3 py-2 rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i>Confirmée
                                </span>
                            @elseif($reservation->status === 'en_attente')
                                <span class="badge badge-en-attente px-3 py-2 rounded-pill">
                                    <i class="fas fa-clock me-1"></i>En attente
                                </span>
                            @else
                                <span class="badge badge-annulee px-3 py-2 rounded-pill">
                                    <i class="fas fa-times-circle me-1"></i>Annulée
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <!-- Changer statut -->
                            <button class="btn btn-sm btn-outline-primary rounded-pill me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#statusModal"
                                    data-id="{{ $reservation->id }}"
                                    data-code="{{ $reservation->ticket_code }}"
                                    data-status="{{ $reservation->status }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <!-- Supprimer -->
                            <button class="btn btn-sm btn-outline-danger rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-id="{{ $reservation->id }}"
                                    data-code="{{ $reservation->ticket_code }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-ticket-alt fa-3x mb-3 d-block opacity-25"></i>
                            Aucune réservation pour le moment
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reservations->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $reservations->links() }}
        </div>
        @endif

    </div>
</div>

<!-- ===== MODAL CHANGER STATUT ===== -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit text-primary me-2"></i>Changer le Statut
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="statusForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Réservation : <span class="fw-bold text-primary font-monospace" id="status_code"></span>
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-500">Nouveau statut</label>
                        <select name="status" id="status_select" class="form-select" required>
                            <option value="en_attente">⏳ En attente</option>
                            <option value="confirmée">✅ Confirmée</option>
                            <option value="annulée">❌ Annulée</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL SUPPRIMER ===== -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="fas fa-trash me-2"></i>Supprimer la Réservation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <p class="mb-1">Voulez-vous vraiment supprimer la réservation</p>
                <p class="fw-bold text-primary font-monospace fs-5" id="delete_code"></p>
                <small class="text-muted">Cette action est irréversible !</small>
            </div>
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ===== MODAL STATUT =====
    document.getElementById('statusModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id     = button.getAttribute('data-id');
        const code   = button.getAttribute('data-code');
        const status = button.getAttribute('data-status');

        document.getElementById('statusForm').action = '/admin/reservations/' + id + '/status';
        document.getElementById('status_code').textContent = code;
        document.getElementById('status_select').value = status;
    });

    // ===== MODAL SUPPRIMER =====
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id     = button.getAttribute('data-id');
        const code   = button.getAttribute('data-code');

        document.getElementById('deleteForm').action = '/admin/reservations/' + id;
        document.getElementById('delete_code').textContent = code;
    });
</script>
@endpush