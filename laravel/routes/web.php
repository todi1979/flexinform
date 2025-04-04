<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarserviceController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/carservice', [CarserviceController::class, 'indexView'])->name('carservice.index');
    Route::get('/clients', [CarserviceController::class, 'index'])->name('clients.index');
    Route::get('/clients/{id}/cars', [CarserviceController::class, 'getClientCars'])->name('clients.cars');
    Route::get('/cars/{clientId}/{carId}/service-log', [CarserviceController::class, 'getServiceLog'])->name('cars.service-log');

});

require __DIR__.'/auth.php';

