@extends('layouts.app')

@section('title', 'Tous les Voyages')

@section('content')
<!-- Header -->
<div class="bg-gradient py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container">
        <h1 class="mb-2"><i class="fas fa-road me-2"></i>Tous les Voyages</h1>
        <p class="text-light">Découvrez notre catalogue complet de voyages en bus</p>
    </div>
</div>

<!-- Filter Section -->
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-4">
            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Départ...">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Destination...">
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary btn-lg w-100 rounded-pill">
                <i class="fas fa-search me-2"></i>Rechercher
            </button>
        </div>
    </div>

    <!-- Tabs for Categories -->
    <ul class="nav nav-pills justify-content-center mb-5" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill mx-2" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">Tous</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill mx-2" id="morning-tab" data-bs-toggle="tab" data-bs-target="#morning" type="button">Matin</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill mx-2" id="afternoon-tab" data-bs-toggle="tab" data-bs-target="#afternoon" type="button">Après-midi</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill mx-2" id="evening-tab" data-bs-toggle="tab" data-bs-target="#evening" type="button">Soirée</button>
        </li>
    </ul>

    <!-- Trips Grid -->
    <div class="tab-content">
        <div class="tab-pane fade show active" id="all" role="tabpanel">
            <div class="row g-4">
                @for($i = 1; $i <= 12; $i++)
                <div class="col-md-6 col-lg-4">
                    <div class="voyage-card card border-0 shadow-sm h-100 hover-effect transition-all rounded-3xl overflow-hidden">
                        <img src="https://via.placeholder.com/400x250/667eea/ffffff?text=Voyage {{ $i }}" class="card-img-top" alt="Voyage">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-primary">{{ ['Douala - Yaoundé', 'Douala - Bamenda', 'Bamenda - Yaoundé', 'Buea - Douala', 'Douala - Ouest', 'Kumba - Bamenda', 'Yaoundé - Bamenda', 'Buea - Yaoundé', 'Limbe - Douala', 'Garoua - Yaoundé', 'Bafoussam - Douala', 'Ngaoundéré - Yaoundé'][$i - 1] }}</h5>
                            <p class="text-muted mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>{{ now()->addDays($i)->format('d/m/Y') }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span><i class="fas fa-clock text-warning"></i> {{ (3 + ($i % 8)) }}h{{ (10 + ($i * 3)) % 60 }}m</span>
                                <span class="badge bg-success">{{ 15 - $i }} places</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="h5 text-primary mb-0">{{ (8000 + ($i * 300)) }} FCFA</strong>
                                <a href="{{ route('details', $i) }}" class="btn btn-primary btn-sm rounded-pill">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <div class="tab-pane fade" id="morning" role="tabpanel">
            <div class="row g-4">
                @for($i = 1; $i <= 4; $i++)
                <div class="col-md-6 col-lg-4">
                    <div class="voyage-card card border-0 shadow-sm h-100 hover-effect transition-all rounded-3xl overflow-hidden">
                        <img src="https://via.placeholder.com/400x250/667eea/ffffff?text=Matin {{ $i }}" class="card-img-top" alt="Voyage">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-primary">Paris - Marseille</h5>
                            <p class="text-muted mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>06:00 - 16:00
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span><i class="fas fa-clock text-warning"></i> 10h</span>
                                <span class="badge bg-success">{{ 10 + $i }} places</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="h5 text-primary mb-0">45€</strong>
                                <a href="{{ route('details', $i) }}" class="btn btn-primary btn-sm rounded-pill">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>

<!-- Promo Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold mb-4">Offre Spéciale</h2>
                <p class="lead text-muted mb-4">Obtenez jusqu'à 30% de réduction sur les voyages en groupe!</p>
                <a href="#" class="btn btn-primary btn-lg rounded-pill">
                    <i class="fas fa-gift me-2"></i>En Savoir Plus
                </a>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://via.placeholder.com/400x300/764ba2/ffffff?text=Special%20Offer" class="img-fluid rounded-3xl" alt="Offer">
            </div>
        </div>
    </div>
</section>
@endsection
