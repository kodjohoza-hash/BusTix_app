<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BusTix Admin - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #1a237e 0%, #0d47a1 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.4rem;
        }

        .sidebar-brand span {
            color: #64b5f6;
        }

        .nav-section-title {
            color: rgba(255,255,255,0.4);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px 5px;
        }

        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 10px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        /* ===== CONTENU PRINCIPAL ===== */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* ===== TOP NAVBAR ===== */
        .top-navbar {
            background: white;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border: none !important;
            border-radius: 15px !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* ===== TABLES ===== */
        .table-card {
            border: none !important;
            border-radius: 15px !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05) !important;
        }

        .table thead th {
            background-color: #f8f9fa;
            border: none;
            font-weight: 600;
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
        }

        /* ===== BADGES STATUT ===== */
        .badge-planifié   { background-color: #e3f2fd; color: #1565c0; }
        .badge-confirmée  { background-color: #e8f5e9; color: #2e7d32; }
        .badge-annulée    { background-color: #ffebee; color: #c62828; }
        .badge-en_attente { background-color: #fff3e0; color: #e65100; }
        .badge-disponible { background-color: #e8f5e9; color: #2e7d32; }

        /* ===== GRADIENTS ===== */
        .bg-gradient-blue   { background: linear-gradient(135deg, #667eea, #764ba2); }
        .bg-gradient-green  { background: linear-gradient(135deg, #11998e, #38ef7d); }
        .bg-gradient-orange { background: linear-gradient(135deg, #f7971e, #ffd200); }
        .bg-gradient-purple { background: linear-gradient(135deg, #c471ed, #f64f59); }
    </style>

    @stack('styles')
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar">
        <!-- Logo -->
        <div class="sidebar-brand d-flex align-items-center gap-2">
            <i class="fas fa-bus-alt text-white fs-4"></i>
            <h4>Bus<span>Tix</span></h4>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav mt-2">
            <div class="nav-section-title">Principal</div>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>

            <div class="nav-section-title">Gestion</div>
            <a href="{{ route('admin.bus') }}"
               class="nav-link {{ request()->routeIs('admin.bus') ? 'active' : '' }}">
                <i class="fas fa-bus"></i> Bus
            </a>
           <!-- Par -->
<a href="{{ route('admin.voyages') }}"
   class="nav-link {{ request()->routeIs('admin.voyages') ? 'active' : '' }}">
    <i class="fas fa-route"></i> Displacements
</a>
            <a href="{{ route('admin.users') }}"
               class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Clients
            </a>
            <a href="{{ route('admin.reservations') }}"
               class="nav-link {{ request()->routeIs('admin.reservations') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> Réservations
            </a>
            <a href="{{ route('admin.payments') }}"
               class="nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i> Paiements
            </a>

            <div class="nav-section-title">Compte</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="nav-link border-0 w-100 text-start"
                        style="background:none; cursor:pointer;">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>
        </nav>
    </div>

    <!-- ===== CONTENU PRINCIPAL ===== -->
    <div class="main-content">

      <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <!-- Bouton Retour -->
                <!-- Bouton Retour - caché sur le dashboard -->
@if(!request()->routeIs('admin.dashboard'))
<button onclick="goBack('{{ route('admin.dashboard') }}')"
        class="btn btn-sm rounded-pill px-3 py-2 me-2"
        style="background: linear-gradient(135deg, #1a237e, #0d47a1);
               color: white; border: none;
               box-shadow: 0 3px 10px rgba(26,35,126,0.3);">
    <i class="fas fa-arrow-left me-1"></i>Retour
</button>
@endif
                <div>
                    <h5 class="mb-0 fw-bold">@yield('title')</h5>
                    <small class="text-muted">@yield('subtitle', 'BusTix Administration')</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Profil Admin -->
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center
                                justify-content-center fw-bold"
                         style="width:38px;height:38px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold small">{{ auth()->user()->name }}</div>
                        <div class="text-muted" style="font-size:0.75rem">Super Admin</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu -->
        <div class="p-4">
            {{-- Message succès --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Message erreur --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        function goBack(fallback) {
        if (document.referrer && document.referrer.includes('/admin')) {
        history.back();
          } else {
             window.location.href = fallback;
          }
    }
    </script>

    @stack('scripts')
</body>
</html>