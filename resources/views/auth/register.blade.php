@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pt-4 text-center">
                    <h4 class="fw-bold">
                        <i class="fas fa-bus text-primary me-2"></i>
                        <span>Bus</span><span class="text-primary">Tix</span>
                    </h4>
                    <p class="text-muted">Créez votre compte</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Prénom -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-500">
                                <i class="fas fa-user me-1 text-muted"></i>Prénom
                            </label>
                            <input id="name"
                                   type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Votre prénom"
                                   required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nom de famille -->
                        <div class="mb-3">
                            <label for="user_surname" class="form-label fw-500">
                                <i class="fas fa-user me-1 text-muted"></i>Nom de famille
                            </label>
                            <input id="user_surname"
                                   type="text"
                                   class="form-control @error('user_surname') is-invalid @enderror"
                                   name="user_surname"
                                   value="{{ old('user_surname') }}"
                                   placeholder="Votre nom de famille"
                                   required>
                            @error('user_surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div class="mb-3">
                            <label for="telephone" class="form-label fw-500">
                                <i class="fas fa-phone me-1 text-muted"></i>Téléphone
                            </label>
                            <input id="telephone"
                                   type="text"
                                   class="form-control @error('telephone') is-invalid @enderror"
                                   name="telephone"
                                   value="{{ old('telephone') }}"
                                   placeholder="Ex: 6912345678"
                                   required>
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-500">
                                <i class="fas fa-envelope me-1 text-muted"></i>Email
                            </label>
                            <input id="email"
                                   type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="votre@email.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-500">
                                <i class="fas fa-lock me-1 text-muted"></i>Mot de passe
                            </label>
                            <input id="password"
                                   type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password"
                                   placeholder="Minimum 8 caractères"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-500">
                                <i class="fas fa-lock me-1 text-muted"></i>Confirmer le mot de passe
                            </label>
                            <input id="password-confirm"
                                   type="password"
                                   class="form-control"
                                   name="password_confirmation"
                                   placeholder="Répétez votre mot de passe"
                                   required>
                        </div>

                        <!-- Bouton inscription -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-500">
                                <i class="fas fa-user-plus me-2"></i>Créer mon compte
                            </button>
                        </div>

                        <!-- Lien connexion -->
                        <div class="text-center">
                            <small class="text-muted">
                                Déjà un compte ?
                                <a href="{{ route('login') }}" class="text-primary fw-500">
                                    Se connecter
                                </a>
                            </small>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection