<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/test', [App\Http\Controllers\testController::class, 'primero'])->name('test.primero');
    Route::get('/cambiar-mensaje', [App\Http\Controllers\testController::class, 'cambiarMensaje'])->name('test.cambiarMensaje');
    Route::post('/cambiar-mensaje', [App\Http\Controllers\testController::class, 'registro'])->name('test.registro');
});
