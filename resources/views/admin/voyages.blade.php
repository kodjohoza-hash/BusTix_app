@extends('layouts.admin')

@section('title', 'Gestion des Voyages')
@section('subtitle', 'Gérez tous les voyages BusTix')

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
            <div class="stat-number">{{ $totalTrips }}</div>
            <div class="stat-label"><i class="fas fa-route me-1"></i>Total Voyages</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card green">
            <div class="stat-number">{{ $plannedTrips }}</div>
            <div class="stat-label"><i class="fas fa-calendar me-1"></i>Planifiés</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card orange">
            <div class="stat-number">{{ $completedTrips }}</div>
            <div class="stat-label"><i class="fas fa-check me-1"></i>Terminés</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card purple">
            <div class="stat-number">{{ $cancelledTrips }}</div>
            <div class="stat-label"><i class="fas fa-times me-1"></i>Annulés</div>
        </div>
    </div>
</div>

<!-- ===== HEADER ===== -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">
            <i class="fas fa-route text-primary me-2"></i>Tous les Voyages
        </h5>
        <small class="text-muted">{{ $totalTrips }} voyage(s) enregistré(s)</small>
    </div>
    <button class="btn btn-primary rounded-pill px-4"
            data-bs-toggle="modal" data-bs-target="#addVoyageModal">
        <i class="fas fa-plus me-2"></i>Ajouter un Voyage
    </button>
</div>

<!-- ===== LISTE DES VOYAGES ===== -->
<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Trajet</th>
                        <th>Bus</th>
                        <th>Date & Heure</th>
                        <th>Prix</th>
                        <th>Distance</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trips as $trip)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>

                        <!-- Trajet -->
                        <td>
                            @if($trip->displacement)
                                <div class="fw-bold">
                                    {{ $trip->displacement->start_point }}
                                    <i class="fas fa-arrow-right text-primary mx-1 small"></i>
                                    {{ $trip->displacement->destination_point }}
                                </div>
                            @else
                                <span class="text-muted">Trajet supprimé</span>
                            @endif
                        </td>

                        <!-- Bus -->
                        <td>
                            @if($trip->displacement && $trip->displacement->bus)
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                    <i class="fas fa-bus me-1"></i>
                                    {{ $trip->displacement->bus->bus_number }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Date & Heure -->
                        <td>
                            <div class="fw-500">
                                {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y') }}
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($trip->living_date_time)->format('H:i') }}
                            </small>
                        </td>

                        <!-- Prix -->
                        <td>
                            <span class="fw-bold text-success">
                                {{ number_format($trip->price, 0, ',', ' ') }} FCFA
                            </span>
                        </td>

                        <!-- Distance -->
                        <td>
                            @if($trip->displacement)
                                <span class="text-muted">
                                    {{ $trip->displacement->distance }} km
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Statut -->
                        <td>
                            @if($trip->travel_status === 'planifié')
                                <span class="badge badge-planifie px-3 py-2 rounded-pill">
                                    <i class="fas fa-calendar me-1"></i>Planifié
                                </span>
                            @elseif($trip->travel_status === 'en cours')
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    <i class="fas fa-road me-1"></i>En cours
                                </span>
                            @elseif($trip->travel_status === 'terminé')
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                    <i class="fas fa-check me-1"></i>Terminé
                                </span>
                            @else
                                <span class="badge badge-annulee px-3 py-2 rounded-pill">
                                    <i class="fas fa-times me-1"></i>Annulé
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary rounded-pill me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editVoyageModal"
                                    data-id="{{ $trip->id }}"
                                    data-displacement="{{ $trip->displacement_id }}"
                                    data-date="{{ \Carbon\Carbon::parse($trip->living_date_time)->format('Y-m-d\TH:i') }}"
                                    data-price="{{ $trip->price }}"
                                    data-status="{{ $trip->travel_status }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteVoyageModal"
                                    data-id="{{ $trip->id }}"
                                    data-trajet="{{ $trip->displacement ? $trip->displacement->start_point.' → '.$trip->displacement->destination_point : 'N/A' }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-route fa-3x mb-3 d-block opacity-25"></i>
                            Aucun voyage enregistré pour le moment
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($trips->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $trips->links() }}
        </div>
        @endif
    </div>
</div>

<!-- ===== MODAL AJOUTER VOYAGE ===== -->
<div class="modal fade" id="addVoyageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus text-primary me-2"></i>Ajouter un Voyage
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.voyages.store') }}">
                @csrf
                <div class="modal-body">

                    <!-- Trajet -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Trajet</label>
                        <select name="displacement_id" class="form-select" required>
                            <option value="">Choisir un trajet...</option>
                            @foreach($displacements as $displacement)
                                <option value="{{ $displacement->id }}">
                                    {{ $displacement->start_point }} → {{ $displacement->destination_point }}
                                    ({{ $displacement->bus->bus_number ?? 'Bus N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date & Heure -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Date & Heure de départ</label>
                        <input type="datetime-local"
                               name="living_date_time"
                               class="form-control"
                               required>
                    </div>

                    <!-- Prix -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Prix (FCFA)</label>
                        <input type="number"
                               name="price"
                               class="form-control"
                               placeholder="Ex: 5000"
                               min="0"
                               required>
                    </div>

                    <!-- Statut -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Statut</label>
                        <select name="travel_status" class="form-select" required>
                            <option value="planifié">📅 Planifié</option>
                            <option value="en cours">🚌 En cours</option>
                            <option value="terminé">✅ Terminé</option>
                            <option value="annulé">❌ Annulé</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-plus me-2"></i>Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL MODIFIER VOYAGE ===== -->
<div class="modal fade" id="editVoyageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit text-primary me-2"></i>Modifier le Voyage
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editVoyageForm">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <!-- Trajet -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Trajet</label>
                        <select name="displacement_id" id="edit_displacement" class="form-select" required>
                            @foreach($displacements as $displacement)
                                <option value="{{ $displacement->id }}">
                                    {{ $displacement->start_point }} → {{ $displacement->destination_point }}
                                    ({{ $displacement->bus->bus_number ?? 'Bus N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date & Heure -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Date & Heure de départ</label>
                        <input type="datetime-local"
                               name="living_date_time"
                               id="edit_date"
                               class="form-control"
                               required>
                    </div>

                    <!-- Prix -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Prix (FCFA)</label>
                        <input type="number"
                               name="price"
                               id="edit_price"
                               class="form-control"
                               min="0"
                               required>
                    </div>

                    <!-- Statut -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Statut</label>
                        <select name="travel_status" id="edit_status" class="form-select" required>
                            <option value="planifié">📅 Planifié</option>
                            <option value="en cours">🚌 En cours</option>
                            <option value="terminé">✅ Terminé</option>
                            <option value="annulé">❌ Annulé</option>
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

<!-- ===== MODAL SUPPRIMER VOYAGE ===== -->
<div class="modal fade" id="deleteVoyageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="fas fa-trash me-2"></i>Supprimer le Voyage
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <p class="mb-1">Voulez-vous vraiment supprimer le voyage</p>
                <p class="fw-bold text-primary fs-5" id="delete_trajet"></p>
                <small class="text-muted">Cette action est irréversible !</small>
            </div>
            <form method="POST" id="deleteVoyageForm">
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
    // ===== MODAL MODIFIER =====
    document.getElementById('editVoyageModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id           = button.getAttribute('data-id');
        const displacement = button.getAttribute('data-displacement');
        const date         = button.getAttribute('data-date');
        const price        = button.getAttribute('data-price');
        const status       = button.getAttribute('data-status');

        document.getElementById('editVoyageForm').action = '/admin/voyages/' + id;
        document.getElementById('edit_displacement').value = displacement;
        document.getElementById('edit_date').value         = date;
        document.getElementById('edit_price').value        = price;
        document.getElementById('edit_status').value       = status;
    });

    // ===== MODAL SUPPRIMER =====
    document.getElementById('deleteVoyageModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id     = button.getAttribute('data-id');
        const trajet = button.getAttribute('data-trajet');

        document.getElementById('deleteVoyageForm').action = '/admin/voyages/' + id;
        document.getElementById('delete_trajet').textContent = trajet;
    });
</script>
@endpush