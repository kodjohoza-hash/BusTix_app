<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BusController as AdminBusController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\VoyageController as AdminVoyageController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;

/*
|--------------------------------------------------------------------------
| Web Routes - BusTix
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Routes Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
     ->middleware(['auth', 'admin'])
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
});

/*
|--------------------------------------------------------------------------
| Routes Client
|--------------------------------------------------------------------------
*/
Route::get('/home', function () { return view('pages.home'); })->name('home');
Route::get('/voyages', function () { return view('pages.voyages'); })->name('voyages');
Route::get('/search', function () { return view('pages.search'); })->name('search');
Route::get('/details/{id}', function ($id) { return view('pages.detail'); })->name('details');
Route::get('/reservations', function () { return view('pages.reservations'); })->middleware('auth')->name('reservations');
Route::get('/profile', function () { return view('pages.profil'); })->middleware('auth')->name('profile');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Auth::routes();