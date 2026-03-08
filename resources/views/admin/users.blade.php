@extends('layouts.admin')

@section('title', 'Gestion des Clients')
@section('subtitle', 'Gérez tous les clients BusTix')

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
    <div class="col-md-4">
        <div class="stat-card blue">
            <div class="stat-number">{{ $totalCustomers }}</div>
            <div class="stat-label"><i class="fas fa-users me-1"></i>Total Clients</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card green">
            <div class="stat-number">{{ $activeCustomers }}</div>
            <div class="stat-label"><i class="fas fa-user-check me-1"></i>Comptes Actifs</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card orange">
            <div class="stat-number">{{ $totalCustomers - $activeCustomers }}</div>
            <div class="stat-label"><i class="fas fa-user-times me-1"></i>Comptes Désactivés</div>
        </div>
    </div>
</div>

<!-- ===== HEADER ===== -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">
            <i class="fas fa-users text-primary me-2"></i>Tous les Clients
        </h5>
        <small class="text-muted">{{ $totalCustomers }} client(s) enregistré(s)</small>
    </div>
</div>

<!-- ===== LISTE DES CLIENTS ===== -->
<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Client</th>
                        <th>Téléphone</th>
                        <th>Réservations</th>
                        <th>Membre depuis</th>
                        <th>Statut Compte</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>

                        <!-- Client -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3
                                            {{ $customer->user && $customer->user->actif ? 'bg-primary' : 'bg-secondary' }}
                                            bg-opacity-10 text-primary fw-bold"
                                     style="width:40px;height:40px;font-size:16px;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold">
                                        {{ $customer->name }} {{ $customer->surname }}
                                    </div>
                                    <small class="text-muted">{{ $customer->email }}</small>
                                </div>
                            </div>
                        </td>

                        <!-- Téléphone -->
                        <td>
                            <i class="fas fa-phone text-muted me-1 small"></i>
                            {{ $customer->telephone ?? '-' }}
                        </td>

                        <!-- Réservations -->
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <i class="fas fa-ticket-alt me-1"></i>
                                {{ $customer->ticket_reservations_count }} réservation(s)
                            </span>
                        </td>

                        <!-- Membre depuis -->
                        <td>
                            @if($customer->user)
                                <div>{{ \Carbon\Carbon::parse($customer->user->created_at)->format('d/m/Y') }}</div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($customer->user->created_at)->diffForHumans() }}
                                </small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Statut -->
                        <td>
                            @if($customer->user && $customer->user->actif)
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i>Actif
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                    <i class="fas fa-times-circle me-1"></i>Désactivé
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <!-- Activer/Désactiver -->
                            <form method="POST"
                                  action="{{ route('admin.users.toggle', $customer->id) }}"
                                  class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                        class="btn btn-sm rounded-pill me-1
                                               {{ $customer->user && $customer->user->actif
                                                  ? 'btn-outline-warning'
                                                  : 'btn-outline-success' }}"
                                        title="{{ $customer->user && $customer->user->actif ? 'Désactiver' : 'Activer' }}">
                                    <i class="fas {{ $customer->user && $customer->user->actif ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </form>

                            <!-- Supprimer -->
                            <button class="btn btn-sm btn-outline-danger rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteClientModal"
                                    data-id="{{ $customer->id }}"
                                    data-name="{{ $customer->name }} {{ $customer->surname }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-users fa-3x mb-3 d-block opacity-25"></i>
                            Aucun client enregistré pour le moment
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($customers->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
</div>

<!-- ===== MODAL SUPPRIMER CLIENT ===== -->
<div class="modal fade" id="deleteClientModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="fas fa-trash me-2"></i>Supprimer le Client
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <p class="mb-1">Voulez-vous vraiment supprimer le client</p>
                <p class="fw-bold text-primary fs-5" id="delete_client_name"></p>
                <small class="text-muted">
                    Son compte utilisateur sera aussi supprimé !
                </small>
            </div>
            <form method="POST" id="deleteClientForm">
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
    // ===== MODAL SUPPRIMER =====
    document.getElementById('deleteClientModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id     = button.getAttribute('data-id');
        const name   = button.getAttribute('data-name');

        document.getElementById('deleteClientForm').action = '/admin/users/' + id;
        document.getElementById('delete_client_name').textContent = name;
    });
</script>
@endpush