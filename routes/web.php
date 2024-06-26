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
    Route::get('/areas', [App\Http\Controllers\areaController::class, 'index'])->name('area.index');
    Route::get('/area/create', [App\Http\Controllers\areaController::class, 'create'])->name('area.create');
    Route::post('/area/store', [App\Http\Controllers\areaController::class, 'store'])->name('area.store');
    // Route::get('/area/{id}', [App\Http\Controllers\areaController::class, 'show'])->name('area.show');
    Route::get('/area/{id}/edit', [App\Http\Controllers\areaController::class, 'edit'])->name('area.edit');
    Route::patch('/area/{id}/update', [App\Http\Controllers\areaController::class, 'update'])->name('area.update');
    Route::delete('/area/{id}/destroy', [App\Http\Controllers\areaController::class, 'destroy'])->name('area.destroy');

    // Departamento
    Route::get('/departamentos', [App\Http\Controllers\departamentoController::class, 'index'])->name('departamento.index');
    Route::get('/departamento/create', [App\Http\Controllers\departamentoController::class, 'create'])->name('departamento.create');
    Route::post('/departamento/store', [App\Http\Controllers\departamentoController::class, 'store'])->name('departamento.store');
    // Route::get('/departamento/{id}', [App\Http\Controllers\departamentoController::class, 'show'])->name('departamento.show');
    Route::get('/departamento/{id}/edit', [App\Http\Controllers\departamentoController::class, 'edit'])->name('departamento.edit');
    Route::patch('/departamento/{id}/update', [App\Http\Controllers\departamentoController::class, 'update'])->name('departamento.update');
    Route::delete('/departamento/{id}/destroy', [App\Http\Controllers\departamentoController::class, 'destroy'])->name('departamento.destroy');
});
