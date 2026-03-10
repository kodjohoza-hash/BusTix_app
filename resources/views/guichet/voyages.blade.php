@extends('layouts.guichet')

@section('title', 'Voyages')
@section('subtitle', 'Liste des voyages')

@section('content')

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0">
            <i class="fas fa-bus text-warning me-2"></i>
            Tous les Voyages
        </h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Trajet</th>
                    <th>Bus</th>
                    <th>Date Départ</th>
                    <th>Prix</th>
                    <th>Places Dispo</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trips as $trip)
                <tr>
                    <td class="ps-4 fw-bold">
                        {{ $trip->displacement->start_point }}
                        <i class="fas fa-arrow-right mx-2 text-muted"></i>
                        {{ $trip->displacement->destination_point }}
                    </td>
                    <td><small>{{ $trip->displacement->bus->mack }}</small></td>
                    <td>
                        <small>
                            {{ \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y H:i') }}
                        </small>
                    </td>
                    <td class="fw-bold text-success">
                        {{ number_format($trip->price, 0, ',', ' ') }} F
                    </td>
                    <td>
                        @php
                            $reserved  = $trip->ticketReservations()->where('status', '!=', 'annulée')->count();
                            $available = $trip->displacement->bus->capacity - $reserved;
                        @endphp
                        <span class="badge rounded-pill px-3
                            {{ $available > 5 ? 'bg-success' : ($available > 0 ? 'bg-warning' : 'bg-danger') }}">
                            {{ $available }} / {{ $trip->displacement->bus->capacity }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $trip->travel_status }} px-3 py-2 rounded-pill">
                            {{ ucfirst($trip->travel_status) }}
                        </span>
                    </td>
                    <td>
                        @if($available > 0)
                            <a href="{{ route('guichet.reservations.create') }}?trip_id={{ $trip->id }}"
                               class="btn btn-sm btn-primary rounded-pill">
                                <i class="fas fa-plus me-1"></i>Réserver
                            </a>
                        @else
                            <span class="badge bg-danger rounded-pill px-3">Complet</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-bus fa-2x mb-2 d-block opacity-25"></i>
                        Aucun voyage disponible
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $trips->links() }}
</div>

@endsection