@extends('layouts.app')

@section('title', 'Gérer les Réservations')

@section('content')
<!-- Header -->
<div class="bg-gradient py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container">
        <h1 class="mb-2"><i class="fas fa-ticket-alt me-2"></i>Gestion des Réservations</h1>
        <p class="text-light">Consultez et gérez toutes les réservations</p>
    </div>
</div>

<div class="container py-5">
    <!-- Filters -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="N° Réservation...">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Email client...">
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-lg rounded-pill">
                <option>Tous les statuts</option>
                <option>Confirmée</option>
                <option>Annulée</option>
                <option>En attente</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary btn-lg w-100 rounded-pill">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="stat-box p-3 bg-light rounded-3xl text-center">
                <h5 class="fw-bold mb-1">{{ 456 }}</h5>
                <small class="text-muted">Réservations totales</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box p-3 bg-light rounded-3xl text-center">
                <h5 class="fw-bold mb-1 text-success">{{ 423 }}</h5>
                <small class="text-muted">Confirmées</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box p-3 bg-light rounded-3xl text-center">
                <h5 class="fw-bold mb-1 text-warning">{{ 20 }}</h5>
                <small class="text-muted">En attente</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box p-3 bg-light rounded-3xl text-center">
                <h5 class="fw-bold mb-1 text-danger">{{ 13 }}</h5>
                <small class="text-muted">Annulées</small>
            </div>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="card border-0 shadow-sm rounded-3xl overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N° Réservation</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Voyage</th>
                        <th>Date</th>
                        <th>Places</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= 10; $i++)
                    <tr>
                        <td class="fw-bold">#RES0{{ 1000 + $i }}</td>
                        <td>Client {{ $i }}</td>
                        <td>client{{ $i }}@email.com</td>
                        <td>Paris - {{ ['Lyon', 'Marseille', 'Toulouse', 'Bordeaux'][$i % 4] }}</td>
                        <td>{{ now()->addDays($i)->format('d/m/Y') }}</td>
                        <td><span class="badge bg-info">{{ $i + 1 }} places</span></td>
                        <td class="fw-bold text-primary">{{ (25 + ($i * 2)) * ($i + 1) }}€</td>
                        <td>
                            @if($i % 3 == 0)
                                <span class="badge bg-danger">Annulée</span>
                            @elseif($i % 3 == 1)
                                <span class="badge bg-warning">En attente</span>
                            @else
                                <span class="badge bg-success">Confirmée</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-start-pill">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill mx-1">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-end-pill">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item disabled"><a class="page-link rounded-pill" href="#">Précédent</a></li>
                <li class="page-item active"><a class="page-link rounded-pill" href="#">1</a></li>
                <li class="page-item"><a class="page-link rounded-pill" href="#">2</a></li>
                <li class="page-item"><a class="page-link rounded-pill" href="#">3</a></li>
                <li class="page-item"><a class="page-link rounded-pill" href="#">4</a></li>
                <li class="page-item"><a class="page-link rounded-pill" href="#">Suivant</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
