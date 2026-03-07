<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers Admin
use App\Http\Controllers\Api\Admin\BusController;
use App\Http\Controllers\Api\Admin\DisplacementController;
use App\Http\Controllers\Api\Admin\TripController as AdminTripController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Api\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Api\Admin\SeatController;

// Controllers Client
use App\Http\Controllers\Api\Client\TripController as ClientTripController;
use App\Http\Controllers\Api\Client\ReservationController as ClientReservationController;
use App\Http\Controllers\Api\Client\PaymentController as ClientPaymentController;

/*
|--------------------------------------------------------------------------
| API Routes - BusTix
|--------------------------------------------------------------------------
|
| Toutes les routes API de l'application BusTix.
| Les routes Admin sont protégées par le middleware 'admin'
| Les routes Client sont protégées par le middleware 'client'
|
*/

/*
|--------------------------------------------------------------------------
| Routes publiques - Accessibles sans authentification
|--------------------------------------------------------------------------
*/
Route::prefix('public')->group(function () {
    // Recherche de voyages disponibles (accessible à tous)
    Route::get('trips', [ClientTripController::class, 'index']);
    Route::get('trips/search', [ClientTripController::class, 'search']);
    Route::get('trips/{id}', [ClientTripController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| Routes Admin - Protégées par auth + middleware admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {

    // Gestion des Bus
    Route::apiResource('buses', BusController::class);

    // Gestion des Trajets
    Route::apiResource('displacements', DisplacementController::class);

    // Gestion des Voyages
    Route::apiResource('trips', AdminTripController::class);

    // Gestion des Clients
    Route::apiResource('customers', CustomerController::class);

    // Gestion des Réservations
    Route::apiResource('reservations', AdminReservationController::class);

    // Gestion des Paiements
    Route::apiResource('payments', AdminPaymentController::class);


    Route::apiResource('buses', BusController::class);
    Route::apiResource('seats', SeatController::class); // ← Ajoute cette ligne
    Route::apiResource('displacements', DisplacementController::class);
    // ... reste des routes
});


/*
|--------------------------------------------------------------------------
| Routes Client - Protégées par auth + middleware client
|--------------------------------------------------------------------------
*/
Route::prefix('client')
    ->middleware(['auth', 'client'])
    ->group(function () {

    // Consultation des voyages disponibles
    Route::get('trips', [ClientTripController::class, 'index']);
    Route::get('trips/search', [ClientTripController::class, 'search']);
    Route::get('trips/{id}', [ClientTripController::class, 'show']);

    // Gestion des réservations du client
    Route::apiResource('reservations', ClientReservationController::class);

    // Gestion des paiements du client
    Route::apiResource('payments', ClientPaymentController::class);
    // Ajoute cette ligne avec les autres routes admin
    Route::apiResource('seats', SeatController::class);
});
