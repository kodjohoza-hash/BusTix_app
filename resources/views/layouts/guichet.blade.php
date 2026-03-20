<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusTix Guichet - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, #e65100 0%, #bf360c 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }
        .sidebar .brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .topbar {
            background: white;
            padding: 15px 30px;
            margin-left: 250px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .stat-card { border-radius: 15px; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .bg-gradient-orange { background: linear-gradient(135deg, #e65100, #bf360c) !important; }
        .bg-gradient-blue   { background: linear-gradient(135deg, #1a237e, #0d47a1) !important; }
        .bg-gradient-green  { background: linear-gradient(135deg, #1b5e20, #2e7d32) !important; }
        .bg-gradient-purple { background: linear-gradient(135deg, #4a148c, #6a1b9a) !important; }
        .badge-en_attente { background:#fff3e0; color:#e65100; }
        .badge-confirmée  { background:#e8f5e9; color:#2e7d32; }
        .badge-annulée    { background:#ffebee; color:#c62828; }
    </style>
    @stack('styles')
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar">
    <div class="brand text-white text-center">
        <i class="fas fa-ticket-alt fa-2x mb-2"></i>
        <h5 class="fw-bold mb-0">BusTix</h5>
        <small class="opacity-75">Guichet</small>
    </div>
    <nav class="mt-3">
        <a href="{{ route('guichet.dashboard') }}"
           class="nav-link {{ request()->routeIs('guichet.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </a>
        <a href="{{ route('guichet.reservations') }}"
           class="nav-link {{ request()->routeIs('guichet.reservations*') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt me-2"></i>Réservations
        </a>
        <a href="{{ route('guichet.payments') }}"
           class="nav-link {{ request()->routeIs('guichet.payments*') ? 'active' : '' }}">
            <i class="fas fa-money-bill me-2"></i>Paiements
        </a>
        <!-- Par -->
<a href="{{ route('guichet.voyages') }}"
           class="nav-link {{ request()->routeIs('guichet.voyages*') ? 'active' : '' }}">
            <i class="fas fa-route me-2"></i>Displacements
        </a>
        <a href="{{ route('guichet.clients') }}"
           class="nav-link {{ request()->routeIs('guichet.clients*') ? 'active' : '' }}">
            <i class="fas fa-users me-2"></i>Clients
        </a>
        <a href="{{ route('messages') }}"
           class="nav-link {{ request()->routeIs('messages') ? 'active' : '' }}"
           style="display:flex;align-items:center;">
            <i class="fas fa-comments me-2"></i>Messagerie
            <span class="notification-badge ms-auto"
                  style="background:#f44336;color:white;border-radius:50%;width:20px;height:20px;
                         font-size:0.7rem;display:none;align-items:center;justify-content:center;">0</span>
        </a>
    </nav>
</div>

<!-- ===== TOPBAR ===== -->
<div class="topbar">
    <div class="d-flex align-items-center gap-3">
     <!-- Bouton Retour - caché sur le dashboard -->
@if(!request()->routeIs('guichet.dashboard'))
<button onclick="goBack('{{ route('guichet.dashboard') }}')"
        class="btn btn-sm rounded-pill px-3 py-2 me-2"
        style="background: linear-gradient(135deg, #e65100, #bf360c);
               color: white; border: none;
               box-shadow: 0 3px 10px rgba(230,81,0,0.3);">
    <i class="fas fa-arrow-left me-1"></i>Retour
</button>
@endif
        <div>
            <h6 class="mb-0 fw-bold">@yield('title')</h6>
            <small class="text-muted">@yield('subtitle')</small>
        </div>
    </div>
    <div class="dropdown">
        <button class="btn btn-light rounded-pill dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle me-2"></i>
            {{ auth()->user()->name }} {{ auth()->user()->user_surname }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<!-- ===== CONTENU ===== -->
<div class="main-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
function goBack(fallback) {
    if (document.referrer && document.referrer.includes('/guichet')) {
        history.back();
    } else {
        window.location.href = fallback;
    }
}
</script>
<script>
function checkNotifications() {
    fetch('{{ route("messages.unread") }}')
    .then(r => r.json())
    .then(data => {
        document.querySelectorAll('.notification-badge').forEach(b => {
            b.textContent = data.count;
            b.style.display = data.count > 0 ? 'flex' : 'none';
        });
    });
}
@auth
setInterval(checkNotifications, 3000);
checkNotifications();
@endauth
</script>
@stack('scripts')
</body>
</html>