<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BusTix') }} - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .text-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hover-effect { transition: all 0.3s ease; text-decoration: none; }
        .hover-effect:hover { opacity: 0.8; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Contenu principal --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
function goBack(fallback) {
    if (document.referrer && (
        document.referrer.includes('/home') ||
        document.referrer.includes('/voyages') ||
        document.referrer.includes('/search') ||
        document.referrer.includes('/details') ||
        document.referrer.includes('/reservations') ||
        document.referrer.includes('/profile') ||
        document.referrer.includes('/paiement')
    )) {
        history.back();
    } else {
        window.location.href = fallback;
    }
}
</script>
<script>
// Renouvellement automatique CSRF toutes les 30 minutes
setInterval(function() {
    fetch('/refresh-csrf')
        .then(r => r.json())
        .then(data => {
            document.querySelector('meta[name="csrf-token"]')
                    .setAttribute('content', data.token);
        });
}, 1800000);
</script>

    @stack('scripts')
    
</body>
</html>