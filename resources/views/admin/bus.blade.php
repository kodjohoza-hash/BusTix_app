@extends('layouts.admin')

@section('title', 'Gestion des Bus')
@section('subtitle', 'Gérez la flotte de bus BusTix')

@section('content')

<!-- ===== PAGE HEADER ===== -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">
            <i class="fas fa-bus text-primary me-2"></i>Flotte de Bus
        </h5>
        <small class="text-muted">{{ $buses->count() }} bus enregistrés</small>
    </div>
    <button class="btn btn-primary rounded-pill px-4"
            data-bs-toggle="modal" data-bs-target="#addBusModal">
        <i class="fas fa-plus me-2"></i>Ajouter un Bus
    </button>
</div>

<!-- ===== LISTE DES BUS ===== -->
<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Numéro Bus</th>
                        <th>Marque</th>
                        <th>Capacité</th>
                        <th>Sièges</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buses as $bus)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <span class="fw-bold text-primary">
                                {{ $bus->bus_number }}
                            </span>
                        </td>
                        <td>
                            <i class="fas fa-bus text-muted me-2"></i>
                            {{ $bus->mack }}
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                {{ $bus->capacity }} places
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                <i class="fas fa-chair me-1"></i>
                                {{ $bus->seats_count }} sièges
                            </span>
                        </td>
                        <td>
                            @if($bus->bus_status === 'disponible')
                                <span class="badge badge-disponible px-3 py-2 rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i>Disponible
                                </span>
                            @elseif($bus->bus_status === 'en service')
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    <i class="fas fa-road me-1"></i>En service
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                    <i class="fas fa-tools me-1"></i>En maintenance
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <!-- Bouton Modifier -->
                            <button class="btn btn-sm btn-outline-primary rounded-pill me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editBusModal"
                                    data-id="{{ $bus->id }}"
                                    data-bus_number="{{ $bus->bus_number }}"
                                    data-mack="{{ $bus->mack }}"
                                    data-bus_status="{{ $bus->bus_status }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <!-- Bouton Supprimer -->
                            <button class="btn btn-sm btn-outline-danger rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteBusModal"
                                    data-id="{{ $bus->id }}"
                                    data-bus_number="{{ $bus->bus_number }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-bus fa-3x mb-3 d-block opacity-25"></i>
                            Aucun bus enregistré pour le moment
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== MODAL AJOUTER BUS ===== -->
<div class="modal fade" id="addBusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus text-primary me-2"></i>Ajouter un Bus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.bus.store') }}">
                @csrf
                <div class="modal-body">

                    <!-- Numéro Bus -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Numéro d'immatriculation</label>
                        <input type="text"
                               name="bus_number"
                               class="form-control @error('bus_number') is-invalid @enderror"
                               placeholder="Ex: LT-001-YDE"
                               value="{{ old('bus_number') }}"
                               required>
                        @error('bus_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Marque -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Marque</label>
                        <input type="text"
                               name="mack"
                               class="form-control @error('mack') is-invalid @enderror"
                               placeholder="Ex: Toyota Coaster"
                               value="{{ old('mack') }}"
                               required>
                        @error('mack')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Capacité -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Capacité (nombre de sièges)</label>
                        <input type="number"
                               name="capacity"
                               class="form-control @error('capacity') is-invalid @enderror"
                               placeholder="Ex: 30"
                               min="1" max="100"
                               value="{{ old('capacity') }}"
                               required>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Les sièges seront générés automatiquement
                        </small>
                    </div>

                    <!-- Statut -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Statut</label>
                        <select name="bus_status"
                                class="form-select @error('bus_status') is-invalid @enderror"
                                required>
                            <option value="">Choisir un statut...</option>
                            <option value="disponible" {{ old('bus_status') == 'disponible' ? 'selected' : '' }}>
                                ✅ Disponible
                            </option>
                            <option value="en service" {{ old('bus_status') == 'en service' ? 'selected' : '' }}>
                                🚌 En service
                            </option>
                            <option value="en maintenance" {{ old('bus_status') == 'en maintenance' ? 'selected' : '' }}>
                                🔧 En maintenance
                            </option>
                        </select>
                        @error('bus_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">
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

<!-- ===== MODAL MODIFIER BUS ===== -->
<div class="modal fade" id="editBusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit text-primary me-2"></i>Modifier le Bus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editBusForm">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <!-- Numéro Bus -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Numéro d'immatriculation</label>
                        <input type="text"
                               name="bus_number"
                               id="edit_bus_number"
                               class="form-control"
                               required>
                    </div>

                    <!-- Marque -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Marque</label>
                        <input type="text"
                               name="mack"
                               id="edit_mack"
                               class="form-control"
                               required>
                    </div>

                    <!-- Statut -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Statut</label>
                        <select name="bus_status" id="edit_bus_status" class="form-select">
                            <option value="disponible">✅ Disponible</option>
                            <option value="en service">🚌 En service</option>
                            <option value="en maintenance">🔧 En maintenance</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">
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

<!-- ===== MODAL SUPPRIMER BUS ===== -->
<div class="modal fade" id="deleteBusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="fas fa-trash me-2"></i>Supprimer le Bus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <p class="mb-1">Voulez-vous vraiment supprimer le bus</p>
                <p class="fw-bold fs-5" id="delete_bus_number"></p>
                <small class="text-muted">
                    Cette action supprimera aussi tous les sièges associés !
                </small>
            </div>
            <form method="POST" id="deleteBusForm">
                @csrf
                @method('DELETE')
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">
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
    // Remplit automatiquement le formulaire avec les données du bus
    document.getElementById('editBusModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        // Récupère les données du bus depuis les attributs data-*
        const id         = button.getAttribute('data-id');
        const busNumber  = button.getAttribute('data-bus_number');
        const mack       = button.getAttribute('data-mack');
        const busStatus  = button.getAttribute('data-bus_status');

        // Met à jour l'action du formulaire avec l'ID du bus
        document.getElementById('editBusForm').action = '/admin/bus/' + id;

        // Remplit les champs du formulaire
        document.getElementById('edit_bus_number').value = busNumber;
        document.getElementById('edit_mack').value       = mack;
        document.getElementById('edit_bus_status').value = busStatus;
    });

    // ===== MODAL SUPPRIMER =====
    // Remplit le modal avec le numéro du bus à supprimer
    document.getElementById('deleteBusModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        const id        = button.getAttribute('data-id');
        const busNumber = button.getAttribute('data-bus_number');

        // Met à jour l'action du formulaire et affiche le numéro du bus
        document.getElementById('deleteBusForm').action   = '/admin/bus/' + id;
        document.getElementById('delete_bus_number').textContent = busNumber;
    });
</script>
@endpush