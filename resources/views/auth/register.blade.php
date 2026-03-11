<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusTix - Inscription</title>
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
            font-size: 100px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .right-panel {
            width: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            background: #f8f9fa;
            overflow-y: auto;
        }

        .register-card {
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #1a237e;
            box-shadow: 0 0 0 0.2rem rgba(26,35,126,0.15);
        }

        .btn-register {
            background: linear-gradient(135deg, #1a237e, #0d47a1);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(26,35,126,0.3);
        }

        .btn-register:hover {
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

        .input-group:focus-within .input-group-text {
            border-color: #1a237e;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .step {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #e9ecef;
        }

        .step.active {
            background: #1a237e;
            width: 24px;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; }
        }
    </style>
</head>
<body>

<!-- Panneau gauche -->
<div class="left-panel">
    <div class="text-center z-1 position-relative">
        <div class="bus-illustration">
            <i class="fas fa-bus"></i>
        </div>
        <h2 class="text-white fw-bold mb-3">BusTix</h2>
        <p class="text-white opacity-75 fs-5 mb-4">
            Rejoignez des milliers de<br>voyageurs satisfaits
        </p>
        <div class="d-flex flex-column gap-3 text-start">
            <div class="d-flex align-items-center gap-3 text-white">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;background:rgba(255,255,255,0.2)">
                    <i class="fas fa-bolt"></i>
                </div>
                <span>Inscription en 1 minute</span>
            </div>
            <div class="d-flex align-items-center gap-3 text-white">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;background:rgba(255,255,255,0.2)">
                    <i class="fas fa-tag"></i>
                </div>
                <span>Meilleurs prix garantis</span>
            </div>
            <div class="d-flex align-items-center gap-3 text-white">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;background:rgba(255,255,255,0.2)">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <span>Destinations partout au Cameroun</span>
            </div>
        </div>
    </div>
</div>

<!-- Panneau droit -->
<div class="right-panel">
    <div class="register-card">

        <!-- Header -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                 style="width:60px;height:60px;background:linear-gradient(135deg,#1a237e,#0d47a1)">
                <i class="fas fa-user-plus text-white fs-5"></i>
            </div>
            <h4 class="fw-bold mb-1">Créer un compte 🚀</h4>
            <p class="text-muted small">Remplissez le formulaire ci-dessous</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Prénom + Nom -->
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label small fw-bold">Prénom</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user small"></i>
                        </span>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Prénom"
                               value="{{ old('name') }}"
                               required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label small fw-bold">Nom</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user small"></i>
                        </span>
                        <input type="text"
                               name="user_surname"
                               class="form-control @error('user_surname') is-invalid @enderror"
                               placeholder="Nom"
                               value="{{ old('user_surname') }}"
                               required>
                        @error('user_surname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Téléphone -->
            <div class="mb-3">
                <label class="form-label small fw-bold">Téléphone</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-phone small"></i>
                    </span>
                    <input type="text"
                           name="telephone"
                           class="form-control @error('telephone') is-invalid @enderror"
                           placeholder="Ex: 6912345678"
                           value="{{ old('telephone') }}"
                           required>
                    @error('telephone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label small fw-bold">Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope small"></i>
                    </span>
                    <input type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="votre@email.com"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="mb-3">
                <label class="form-label small fw-bold">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock small"></i>
                    </span>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimum 8 caractères"
                           required>
                    <button type="button"
                            class="btn btn-outline-secondary"
                            style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none;"
                            onclick="togglePassword('password', 'eyeIcon1')">
                        <i class="fas fa-eye" id="eyeIcon1"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Confirmer mot de passe -->
            <div class="mb-4">
                <label class="form-label small fw-bold">Confirmer le mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock small"></i>
                    </span>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirm"
                           class="form-control"
                           placeholder="Répétez votre mot de passe"
                           required>
                    <button type="button"
                            class="btn btn-outline-secondary"
                            style="border-radius:0 10px 10px 0;border:2px solid #e9ecef;border-left:none;"
                            onclick="togglePassword('password_confirm', 'eyeIcon2')">
                        <i class="fas fa-eye" id="eyeIcon2"></i>
                    </button>
                </div>
            </div>

            <!-- Bouton inscription -->
            <button type="submit"
                    class="btn btn-register btn-primary w-100 text-white mb-3">
                <i class="fas fa-user-plus me-2"></i>Créer mon compte
            </button>

            <!-- Lien connexion -->
            <p class="text-center text-muted small mb-0">
                Déjà un compte ?
                <a href="{{ route('login') }}"
                   class="text-primary fw-bold text-decoration-none">
                    Se connecter
                </a>
            </p>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword(fieldId, iconId) {
    const pwd  = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
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