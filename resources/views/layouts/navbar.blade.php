<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm navbar-gradient">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fas fa-bus text-primary me-2"></i><span class="text-gradient">BusTix</span>
        </a>
        
        <!-- Toggler Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-3">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('voyages') }}">
                        <i class="fas fa-road me-1"></i>Voyages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('search') }}">
                        <i class="fas fa-search me-1"></i>Rechercher
                    </a>
                </li>
                
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-id-card"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('reservations') }}"><i class="fas fa-ticket"></i> Mes Réservations</a></li>
                            @if(Auth::user()->role == 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Admin</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>Inscription
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
