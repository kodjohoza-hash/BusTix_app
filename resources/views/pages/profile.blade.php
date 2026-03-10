@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')

<!-- ===== HEADER ===== -->
<section style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%); color: white; padding: 60px 0;">
    <div class="container text-center">
        <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center mb-3"
             style="width:80px;height:80px;">
            <span class="fw-bold text-primary" style="font-size:2rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
        </div>
        <h1 class="fw-bold mb-1">
            {{ auth()->user()->name }} {{ auth()->user()->user_surname }}
        </h1>
        <p class="text-light mb-0">
            <i class="fas fa-envelope me-2"></i>{{ auth()->user()->email }}
        </p>
    </div>
</section>

<!-- ===== CONTENU ===== -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center g-4">

            <!-- ===== INFOS PERSONNELLES ===== -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-user text-primary me-2"></i>
                            Informations Personnelles
                        </h5>
                    </div>
                    <div class="card-body px-4 pb-4">

                        @if(session('success'))
                            <div class="alert alert-success rounded-3 mb-3">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger rounded-3 mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-bold">Prénom</label>
                                <input type="text"
                                       name="name"
                                       class="form-control rounded-pill @error('name') is-invalid @enderror"
                                       value="{{ old('name', auth()->user()->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nom</label>
                                <input type="text"
                                       name="user_surname"
                                       class="form-control rounded-pill @error('user_surname') is-invalid @enderror"
                                       value="{{ old('user_surname', auth()->user()->user_surname) }}">
                                @error('user_surname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email"
                                       name="email"
                                       class="form-control rounded-pill @error('email') is-invalid @enderror"
                                       value="{{ old('email', auth()->user()->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Téléphone</label>
                                <input type="text"
                                       name="telephone"
                                       class="form-control rounded-pill @error('telephone') is-invalid @enderror"
                                       value="{{ old('telephone', auth()->user()->telephone) }}">
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ===== CHANGER MOT DE PASSE ===== -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">
                            <i class="fas fa-lock text-primary me-2"></i>
                            Changer le Mot de Passe
                        </h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mot de passe actuel</label>
                                <input type="password"
                                       name="current_password"
                                       class="form-control rounded-pill @error('current_password') is-invalid @enderror"
                                       placeholder="••••••••">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nouveau mot de passe</label>
                                <input type="password"
                                       name="password"
                                       class="form-control rounded-pill @error('password') is-invalid @enderror"
                                       placeholder="••••••••">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Confirmer le mot de passe</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control rounded-pill"
                                       placeholder="••••••••">
                            </div>

                            <button type="submit" class="btn btn-warning w-100 rounded-pill">
                                <i class="fas fa-key me-2"></i>Changer le mot de passe
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ===== STATS ===== -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            Mes Statistiques
                        </h5>
                        @php
                            $customer = auth()->user()->customer;
                            $total     = $customer ? $customer->ticketReservations()->count() : 0;
                            $confirmed = $customer ? $customer->ticketReservations()->where('status', 'confirmée')->count() : 0;
                            $pending   = $customer ? $customer->ticketReservations()->where('status', 'en_attente')->count() : 0;
                            $cancelled = $customer ? $customer->ticketReservations()->where('status', 'annulée')->count() : 0;
                        @endphp
                        <div class="row g-3 text-center">
                            <div class="col-6">
                                <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                    <div class="fw-bold text-primary fs-4">{{ $total }}</div>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                    <div class="fw-bold text-success fs-4">{{ $confirmed }}</div>
                                    <small class="text-muted">Confirmées</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                                    <div class="fw-bold text-warning fs-4">{{ $pending }}</div>
                                    <small class="text-muted">En attente</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-danger bg-opacity-10 rounded-3">
                                    <div class="fw-bold text-danger fs-4">{{ $cancelled }}</div>
                                    <small class="text-muted">Annulées</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection