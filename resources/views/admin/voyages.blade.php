@extends('layouts.app')

@section('title', 'Gérer les Voyages')

@section('content')
<!-- Header -->
<div class="bg-gradient py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2"><i class="fas fa-road me-2"></i>Gestion des Voyages</h1>
                <p class="text-light">Gérez tous les voyages disponibles</p>
            </div>
            <a href="#" class="btn btn-light btn-lg rounded-pill">
                <i class="fas fa-plus me-2"></i>Ajouter Voyage
            </a>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Filters -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Départ...">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Destination...">
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-lg rounded-pill">
                <option>Actif</option>
                <option>Inactif</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary btn-lg w-100 rounded-pill">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <!-- Voyages Table -->
    <div class="card border-0 shadow-sm rounded-3xl overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Itinéraire</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Bus</th>
                        <th>Places</th>
                        <th>Prix</th>
                        <th>Réservations</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= 10; $i++)
                    <tr>
                        <td class="fw-bold">Paris - {{ ['Lyon', 'Marseille', 'Toulouse', 'Bordeaux'][$i % 4] }}</td>
                        <td>{{ now()->addDays($i)->format('d/m/Y') }}</td>
                        <td>{{ 8 + ($i % 12) }}:30</td>
                        <td>75-AB-{{ 100 + $i }}</td>
                        <td>
                            <span class="badge bg-info">{{ 60 - ($i * 3) }}/{{ 60 + ($i * 2) }}</span>
                        </td>
                        <td class="fw-bold text-primary">{{ 25 + ($i * 2) }}€</td>
                        <td>{{ $i * 4 }}</td>
                        <td>
                            <span class="badge bg-success">Actif</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-start-pill">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-end-pill">
                                    <i class="fas fa-trash"></i>
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
                <li class="page-item"><a class="page-link rounded-pill" href="#">Suivant</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
