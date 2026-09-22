<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\UnavailabilityController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/disponibilites', [AvailabilityController::class, 'index'])->name('availabilities.index');
    Route::post('/disponibilites', [AvailabilityController::class, 'update'])->name('availabilities.update');
    Route::resource('unavailabilities', UnavailabilityController::class)->except(['show', 'edit', 'update']);
    Route::get('/rendez-vous', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/rendez-vous/{appointment}/annuler', [AdminAppointmentController::class, 'cancel'])->name('appointments.cancel');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/rendez-vous/prendre', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::get('/rendez-vous/creneaux', [AppointmentController::class, 'getSlots'])->name('appointments.slots');
    Route::post('/rendez-vous', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/rendez-vous/{appointment}/confirmation', [AppointmentController::class, 'confirmation'])->name('appointments.confirmation');
    Route::get('/mes-rendez-vous', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/rendez-vous/{appointment}/annuler', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
});

Route::get('/', function () {
    return redirect()->route('appointments.create');
})->name('home');

require __DIR__.'/auth.php';
