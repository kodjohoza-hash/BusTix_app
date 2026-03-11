<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusTix - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { margin: 0; padding: 0; min-height: 100vh; display: flex; }

        .left-panel {
            background: linear-gradient(135deg, #1a237e 0%, #0d47a1 50%, #1565c0 100%);
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -100px; left: -100px;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            bottom: -50px; right: -50px;
        }

        .bus-illustration {
            font-size: 120px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 30px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .right-panel {
            width: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #f8f9fa;
        }

        .login-card {
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #1a237e;
            box-shadow: 0 0 0 0.2rem rgba(26,35,126,0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, #1a237e, #0d47a1);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(26,35,126,0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26,35,126,0.4);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 2px solid #e9ecef;
            border-right: none;
            background: #f8f9fa;
            color: #1a237e;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
            border-left: none;
        }

        .input-group .form-control:focus {
            border-color: #1a237e;
        }

        .input-group:focus-within .input-group-text {
            border-color: #1a237e;
        }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; }
        }
    </style>
</head>
<body>

<!-- Panneau gauche - Illustration -->
<div class="left-panel">
    <div class="text-center z-1 position-relative">
        <div class="bus-illustration">
            <i class="fas fa-bus"></i>
        </div>
        <h2 class="text-white fw-bold mb-3">BusTix</h2>
        <p class="text-white opacity-75 fs-5 mb-4">
            Votre plateforme de réservation<br>de billets de bus
        </p>
        <!-- Features -->
        <div class="d-flex flex-column gap-3 text-start">
            <div class="d-flex align-items-center gap-3 text-white">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;background:rgba(255,255,255,0.2)">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <span>Réservation rapide et simple</span>
            </div>
            <div class="d-flex align-items-center gap-3 text-white">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;background:rgba(255,255,255,0.2)">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span>Paiement sécurisé</span>
            </div>
            <div class="d-flex align-items-center gap-3 text-white">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;background:rgba(255,255,255,0.2)">
                    <i class="fas fa-headset"></i>
                </div>
                <span>Support 24/7</span>
            </div>
        </div>
    </div>
</div>

<!-- Panneau droit - Formulaire -->
<div class="right-panel">
    <div class="login-card">

        <!-- Header -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                 style="width:60px;height:60px;background:linear-gradient(135deg,#1a237e,#0d47a1)">
                <i class="fas fa-bus text-white fs-4"></i>
            </div>
            <h4 class="fw-bold mb-1">Bienvenue ! 👋</h4>
            <p class="text-muted small">Connectez-vous à votre compte</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success rounded-3 mb-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label fw-600 small">Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="votre@email.com"
                           value="{{ old('email') }}"
                           required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="mb-3">
                <label class="form-label fw-600 small">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="••••••••"
                           required>
                    <button type="button"
                            class="btn btn-outline-secondary"
                            style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none;"
                            onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Remember + Forgot -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="remember" id="remember"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="remember">
                        Se souvenir de moi
                    </label>
                </div>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="small text-primary text-decoration-none">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <!-- Bouton connexion -->
            <button type="submit" class="btn btn-login btn-primary w-100 text-white mb-3">
                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
            </button>

            <!-- Inscription -->
            <p class="text-center text-muted small mb-0">
                Pas encore de compte ?
                <a href="{{ route('register') }}"
                   class="text-primary fw-bold text-decoration-none">
                    S'inscrire
                </a>
            </p>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>