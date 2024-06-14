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

    // Area
    Route::get('/area', [App\Http\Controllers\areaController::class, 'index'])->name('area.index');
    Route::get('/area/create', [App\Http\Controllers\areaController::class, 'create'])->name('area.create');
    Route::post('/area/store', [App\Http\Controllers\areaController::class, 'store'])->name('area.store');
    // Route::get('/area/{id}', [App\Http\Controllers\areaController::class, 'show'])->name('area.show');
    Route::get('/area/{id}/edit', [App\Http\Controllers\areaController::class, 'edit'])->name('area.edit');
    Route::patch('/area/{id}/update', [App\Http\Controllers\areaController::class, 'update'])->name('area.update');
    Route::delete('/area/{id}/destroy', [App\Http\Controllers\areaController::class, 'destroy'])->name('area.destroy');
});
