@extends('layouts.guichet')

@section('title', 'Clients')
@section('subtitle', 'Liste des clients')

@section('content')

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0">
            <i class="fas fa-users text-primary me-2"></i>
            Tous les Clients
        </h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Client</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Réservations</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10
                                        text-primary d-flex align-items-center
                                        justify-content-center fw-bold"
                                 style="width:35px;height:35px;font-size:0.8rem">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="mb-0 fw-bold small">
                                    {{ $customer->name }} {{ $customer->surname }}
                                </p>
                                <small class="text-muted">
                                    Inscrit le {{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td><small>{{ $customer->email }}</small></td>
                    <td><small>{{ $customer->telephone }}</small></td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                            {{ $customer->ticketReservations->count() }} réservation(s)
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('guichet.reservations.create') }}?customer_id={{ $customer->id }}"
                           class="btn btn-sm btn-primary rounded-pill">
                            <i class="fas fa-plus me-1"></i>Réserver
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2 d-block opacity-25"></i>
                        Aucun client
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $customers->links() }}
</div>

@endsection