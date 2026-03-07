@extends('layouts.app')

@section('title', 'Rechercher')

@section('content')
<!-- Header -->
<div class="bg-gradient py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container">
        <h1 class="mb-2"><i class="fas fa-search me-2"></i>Rechercher un Voyage</h1>
        <p class="text-light">Trouvez le voyage parfait selon vos critères</p>
    </div>
</div>

<!-- Search Section -->
<div class="container py-5">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="bg-light p-4 rounded-3xl sticky-top">
                <h5 class="fw-bold mb-4">Filtres</h5>
                
                <!-- Date Filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Date de Départ</label>
                    <input type="date" class="form-control rounded-pill" name="date">
                </div>
                
                <!-- Price Filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Budget (FCFA)</label>
                    <input type="range" class="form-range" min="0" max="50000" value="25000">
                    <small class="text-muted">0 FCFA - 50 000 FCFA</small>
                </div>
                
                <!-- Duration Filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Durée Maximale</label>
                    <select class="form-select rounded-pill">
                        <option>Toutes les durées</option>
                        <option>Moins de 4h</option>
                        <option>4h - 8h</option>
                        <option>Plus de 8h</option>
                    </select>
                </div>
                
                <!-- Departure Time -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Heure de Départ</label>
                    <select class="form-select rounded-pill">
                        <option>Toutes les heures</option>
                        <option>Matin (6h - 12h)</option>
                        <option>Après-midi (12h - 18h)</option>
                        <option>Soirée (18h - 00h)</option>
                    </select>
                </div>
                
                <button class="btn btn-primary w-100 rounded-pill">Appliquer Filtres</button>
            </div>
        </div>
        
        <!-- Results Section -->
        <div class="col-lg-9">
            <!-- Search Bar -->
            <form action="{{ route('search') }}" method="GET" class="mb-4">
                <div class="input-group input-group-lg mb-4">
                    <input type="text" name="query" class="form-control rounded-start-pill" placeholder="Chercher un voyage...">
                    <button class="btn btn-primary rounded-end-pill" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <!-- Results Count -->
            <div class="mb-4">
                <p class="text-muted">Affichage de <strong>12 résultats</strong></p>
            </div>
            
                    @for($i = 1; $i <= 6; $i++)
                    <div class="card mb-3 shadow-sm hover-effect transition-all border-0 rounded-3xl">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h5 class="card-title fw-bold mb-2">{{ ['Douala - Yaoundé', 'Douala - Bamenda', 'Bamenda - Yaoundé', 'Buea - Douala', 'Douala - Ouest', 'Kumba - Bamenda'][$i - 1] }}</h5>
                                    <p class="text-muted mb-0"><i class="fas fa-calendar"></i> 5 Mars 2026</p>
                                    <p class="text-muted"><i class="fas fa-clock"></i> {{ (8 + $i) }}:30 - {{ (13 + $i) }}:00</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-2"><strong class="text-primary">Durée:</strong> {{ (3 + ($i % 8)) }}h{{ (10 + ($i * 3)) % 60 }}m</p>
                                    <p class="mb-0"><strong class="text-primary">Bus:</strong> Cameroun Transit</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-muted mb-2">Places disponibles: <span class="badge bg-success">{{ 8 + $i }}</span></p>
                                    <p class="text-muted mb-0">Confort: <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></p>
                                </div>
                                <div class="col-md-3 text-md-end">
                                    <h4 class="text-primary fw-bold mb-3">{{ (8000 + ($i * 1000)) }} FCFA</h4>
                                    <a href="{{ route('details', $i) }}" class="btn btn-primary rounded-pill">
                                        Sélectionner
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
            
            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link rounded-pill" href="#">Précédent</a>
                    </li>
                    <li class="page-item active"><a class="page-link rounded-pill" href="#">1</a></li>
                    <li class="page-item"><a class="page-link rounded-pill" href="#">2</a></li>
                    <li class="page-item"><a class="page-link rounded-pill" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link rounded-pill" href="#">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
