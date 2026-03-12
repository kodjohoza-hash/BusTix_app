<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BusController as AdminBusController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\VoyageController as AdminVoyageController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Client\TripController as ClientTripController;
use App\Http\Controllers\Client\ReservationController as ClientReservationController;
use App\Http\Controllers\Client\PaymentController as ClientPaymentController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;
use App\Http\Controllers\Guichet\DashboardController as GuichetDashboardController;
use App\Http\Controllers\Guichet\ReservationController as GuichetReservationController;
use App\Http\Controllers\Guichet\PaymentController as GuichetPaymentController;
use App\Http\Controllers\Guichet\ClientController as GuichetClientController;
use App\Http\Controllers\Guichet\VoyageController as GuichetVoyageController;

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Auth - EN PREMIER pour que nos routes écrasent celles de Laravel
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Routes Super Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
     ->middleware(['auth', 'super_admin'])
     ->name('admin.')
     ->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Bus
    Route::get('bus', [AdminBusController::class, 'index'])->name('bus');
    Route::post('bus', [AdminBusController::class, 'store'])->name('bus.store');
    Route::put('bus/{id}', [AdminBusController::class, 'update'])->name('bus.update');
    Route::delete('bus/{id}', [AdminBusController::class, 'destroy'])->name('bus.destroy');

    // Voyages
    Route::get('voyages', [AdminVoyageController::class, 'index'])->name('voyages');
    Route::post('voyages', [AdminVoyageController::class, 'store'])->name('voyages.store');
    Route::put('voyages/{id}', [AdminVoyageController::class, 'update'])->name('voyages.update');
    Route::delete('voyages/{id}', [AdminVoyageController::class, 'destroy'])->name('voyages.destroy');

    // Réservations
    Route::get('reservations', [AdminReservationController::class, 'index'])->name('reservations');
    Route::put('reservations/{id}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.status');
    Route::delete('reservations/{id}', [AdminReservationController::class, 'destroy'])->name('reservations.destroy');

    // Clients
    Route::get('users', [AdminClientController::class, 'index'])->name('users');
    Route::put('users/{id}/toggle', [AdminClientController::class, 'toggleStatus'])->name('users.toggle');
    Route::delete('users/{id}', [AdminClientController::class, 'destroy'])->name('users.destroy');

    // Paiements
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments');
});

/*
|--------------------------------------------------------------------------
| Routes Admin Guichet
|--------------------------------------------------------------------------
*/
Route::prefix('guichet')
     ->middleware(['auth', 'guichet'])
     ->name('guichet.')
     ->group(function () {

    Route::get('dashboard', [GuichetDashboardController::class, 'index'])->name('dashboard');
    Route::get('seats/{tripId}', [GuichetReservationController::class, 'getSeats'])->name('seats');

    // Réservations
    Route::get('reservations', [GuichetReservationController::class, 'index'])->name('reservations');
    Route::get('reservations/create', [GuichetReservationController::class, 'create'])->name('reservations.create');
    Route::post('reservations', [GuichetReservationController::class, 'store'])->name('reservations.store');
    Route::put('reservations/{id}/status', [GuichetReservationController::class, 'updateStatus'])->name('reservations.status');
    Route::delete('reservations/{id}', [GuichetReservationController::class, 'destroy'])->name('reservations.destroy');

    // Paiements
    Route::get('payments', [GuichetPaymentController::class, 'index'])->name('payments');
    Route::get('payment/{id}/create', [GuichetPaymentController::class, 'create'])->name('payment.create');
    Route::post('payment/{id}', [GuichetPaymentController::class, 'store'])->name('payment.store');

    // Clients
    Route::get('clients', [GuichetClientController::class, 'index'])->name('clients');

    // Voyages
    Route::get('voyages', [GuichetVoyageController::class, 'index'])->name('voyages');
});

/*
|--------------------------------------------------------------------------
| Routes Client - PUBLIQUES (après Auth::routes pour écraser /home)
|--------------------------------------------------------------------------
*/

// Pages publiques - accessibles sans connexion
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/voyages', [ClientTripController::class, 'index'])->name('voyages');
Route::get('/search', [ClientTripController::class, 'search'])->name('search');
Route::get('/details/{id}', [ClientTripController::class, 'show'])->name('details');

// Pages privées - connexion requise
Route::middleware('auth')->group(function () {
    Route::get('/reservations', [ClientReservationController::class, 'index'])->name('reservations');
    Route::post('/reservations', [ClientReservationController::class, 'store'])->name('client.reservations.store');
    Route::delete('/reservations/{id}', [ClientReservationController::class, 'destroy'])->name('client.reservations.destroy');

    Route::get('/paiement/{id}', [ClientPaymentController::class, 'create'])->name('client.payment.create');
    Route::post('/paiement/{id}', [ClientPaymentController::class, 'store'])->name('client.payment.store');

    Route::get('/mes-paiements', [ClientPaymentController::class, 'history'])->name('client.payments.history');

    Route::get('/profile', [ClientProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ClientProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ClientProfileController::class, 'password'])->name('profile.password');

    Route::get('/refresh-csrf', function() {
        return response()->json(['token' => csrf_token()]);
    });
});