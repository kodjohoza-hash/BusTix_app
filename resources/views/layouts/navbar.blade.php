<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fas fa-bus text-primary me-2"></i>
            <span class="text-gradient">BusTix</span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-2 align-items-center">

                <!-- Bouton Retour - visible seulement si pas sur home -->
                @if(!request()->routeIs('home'))
                <li class="nav-item">
                    <button onclick="goBack('{{ route('home') }}')"
                            class="btn btn-sm rounded-pill px-3 py-2"
                            style="background: linear-gradient(135deg, #1a237e, #0d47a1);
                                   color: white; border: none;
                                   box-shadow: 0 3px 10px rgba(26,35,126,0.3);">
                        <i class="fas fa-arrow-left me-1"></i>Retour
                    </button>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'fw-bold text-primary' : '' }}"
                       href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('voyages') ? 'fw-bold text-primary' : '' }}"
                       href="{{ route('voyages') }}">
                        <i class="fas fa-route me-1"></i>Displacements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('search') ? 'fw-bold text-primary' : '' }}"
                       href="{{ route('search') }}">
                        <i class="fas fa-search me-1"></i>Rechercher
                    </a>
                </li>

                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                       id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="d-inline-flex align-items-center justify-content-center
                                    rounded-circle text-white fw-bold me-1"
                             style="width:28px;height:28px;font-size:0.75rem;
                                    background: linear-gradient(135deg, #1a237e, #0d47a1);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile') }}">
                                <i class="fas fa-id-card me-2 text-primary"></i>Profil
                            </a>
                        </li>
                        <li>
                            <li>
                            <a class="dropdown-item py-2" href="{{ route('reservations') }}">
                                <i class="fas fa-ticket-alt me-2 text-primary"></i>Mes Réservations
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('client.payments.history') }}">
                                <i class="fas fa-receipt me-2 text-primary"></i>Mes Paiements
                            </a>
                        </li>
                        </li>
                        @if(auth()->user()->isSuperAdmin())
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2 text-primary"></i>Super Admin
                            </a>
                        </li>
                        @elseif(auth()->user()->isGuichet())
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('guichet.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2 text-warning"></i>Guichet
                            </a>
                        </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="redirect" value="{{ route('home') }}">
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="btn btn-outline-primary btn-sm rounded-pill px-3"
                       href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Connexion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary btn-sm rounded-pill px-3"
                       href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-1"></i>Inscription
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>