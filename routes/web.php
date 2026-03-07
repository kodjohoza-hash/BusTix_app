<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BusController as AdminBusController;

/*
|--------------------------------------------------------------------------
| Web Routes - BusTix
|--------------------------------------------------------------------------
*/

// Redirection vers login
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Routes Admin - Protégées par auth + middleware admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
     ->middleware(['auth', 'admin'])
     ->name('admin.')
     ->group(function () {

    // Tableau de bord
    Route::get('dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    // Gestion des Bus
    Route::get('bus', [AdminBusController::class, 'index'])->name('bus');
    Route::post('bus', [AdminBusController::class, 'store'])->name('bus.store');
    Route::put('bus/{id}', [AdminBusController::class, 'update'])->name('bus.update');
    Route::delete('bus/{id}', [AdminBusController::class, 'destroy'])->name('bus.destroy');

    // Pages à développer
    Route::get('voyages', function () {
        return view('admin.voyages');
    })->name('voyages');

    Route::get('users', function () {
        return view('admin.users');
    })->name('users');

    Route::get('reservations', function () {
        return view('admin.reservations');
    })->name('reservations');
});

/*
|--------------------------------------------------------------------------
| Routes Client - Pages publiques
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return view('pages.home');
})->name('home');

Route::get('/voyages', function () {
    return view('pages.voyages');
})->name('voyages');

Route::get('/search', function () {
    return view('pages.search');
})->name('search');

Route::get('/reservations', function () {
    return view('pages.reservations');
})->middleware('auth')->name('reservations');

Route::get('/profile', function () {
    return view('pages.profil');
})->middleware('auth')->name('profile');

/*
|--------------------------------------------------------------------------
| Routes Auth - Générées par Laravel UI
|--------------------------------------------------------------------------
*/
Auth::routes();