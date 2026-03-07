@extends('layouts.app')

@section('title', 'Gérer les Utilisateurs')

@section('content')
<!-- Header -->
<div class="bg-gradient py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2"><i class="fas fa-users me-2"></i>Gestion des Utilisateurs</h1>
                <p class="text-light">Gérez les comptes utilisateurs</p>
            </div>
            <a href="#" class="btn btn-light btn-lg rounded-pill">
                <i class="fas fa-plus me-2"></i>Ajouter Utilisateur
            </a>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Filters -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Nom...">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Email...">
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-lg rounded-pill">
                <option>Tous les rôles</option>
                <option>Admin</option>
                <option>Utilisateur</option>
                <option>Support</option>
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
        <div class="col-md-4">
            <div class="stat-box p-3 bg-light rounded-3xl text-center">
                <h5 class="fw-bold mb-1">{{ 1234 }}</h5>
                <small class="text-muted">Utilisateurs totaux</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box p-3 bg-light rounded-3xl text-center">
                <h5 class="fw-bold mb-1 text-success">{{ 1150 }}</h5>
                <small class="text-muted">Actifs</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box p-3 bg-light rounded-3xl text-center">
                <h5 class="fw-bold mb-1 text-warning">{{ 84 }}</h5>
                <small class="text-muted">Inactifs</small>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm rounded-3xl overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Rôle</th>
                        <th>Réservations</th>
                        <th>Inscription</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= 10; $i++)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/40/667eea/ffffff?text={{ $i }}" class="rounded-circle me-2" alt="Avatar" width="40" height="40">
                                <div>
                                    <p class="mb-0 fw-bold">Utilisateur {{ $i }}</p>
                                    <small class="text-muted">ID: #{{ 1000 + $i }}</small>
                                </div>
                            </div>
                        </td>
                        <td>user{{ $i }}@email.com</td>
                        <td>+33 6 {{ str_pad(rand(0, 999999), 8, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            @if($i == 1)
                                <span class="badge bg-danger">Admin</span>
                            @elseif($i % 5 == 0)
                                <span class="badge bg-info">Support</span>
                            @else
                                <span class="badge bg-primary">Utilisateur</span>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $i * 3 }}</td>
                        <td>{{ now()->subDays(rand(1, 365))->format('d/m/Y') }}</td>
                        <td>
                            @if($i % 10 != 0)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-start-pill">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill mx-1">
                                    <i class="fas fa-eye"></i>
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
                <li class="page-item"><a class="page-link rounded-pill" href="#">4</a></li>
                <li class="page-item"><a class="page-link rounded-pill" href="#">5</a></li>
                <li class="page-item"><a class="page-link rounded-pill" href="#">Suivant</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
