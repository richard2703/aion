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

    // User
    Route::get('/users', [App\Http\Controllers\userController::class, 'index'])->name('user.index');
    Route::get('/user/create', [App\Http\Controllers\userController::class, 'create'])->name('user.create');
    Route::post('/user/store', [App\Http\Controllers\userController::class, 'store'])->name('user.store');
    // Route::get('/user/{id}', [App\Http\Controllers\userController::class, 'show'])->name('user.show');
    Route::get('/user/{user}/edit', [App\Http\Controllers\userController::class, 'edit'])->name('user.edit');
    Route::patch('/user/{id}/update', [App\Http\Controllers\userController::class, 'update'])->name('user.update');
    Route::patch('/user/{id}/updatePassword', [App\Http\Controllers\userController::class, 'updatePassword'])->name('user.updatePassword');
    Route::delete('/user/{id}/destroy', [App\Http\Controllers\userController::class, 'destroy'])->name('user.destroy');

    //Chanllenges
    Route::get('/challenges', [App\Http\Controllers\challengesController::class, 'index'])->name('challenge.index');
    Route::get('/challenge/create', [App\Http\Controllers\challengesController::class, 'create'])->name('challenge.create');
    Route::post('/challenge/store', [App\Http\Controllers\challengesController::class, 'store'])->name('challenge.store');
    Route::get('/challenge/{id}/edit', [App\Http\Controllers\challengesController::class, 'edit'])->name('challenge.edit');
    Route::patch('/challenge/{id}/update', [App\Http\Controllers\challengesController::class, 'update'])->name('challenge.update');
    Route::delete('/challenge/{id}/destroy', [App\Http\Controllers\challengesController::class, 'destroy'])->name('challenge.destroy');

    //Opciones
    Route::get('/opciones', [App\Http\Controllers\opcionController::class, 'index'])->name('opcion.index');
    Route::get('/opcion/create', [App\Http\Controllers\opcionController::class, 'create'])->name('opcion.create');
    Route::post('/opcion/store', [App\Http\Controllers\opcionController::class, 'store'])->name('opcion.store');
    Route::get('/opcion/{id}/edit', [App\Http\Controllers\opcionController::class, 'edit'])->name('opcion.edit');
    Route::patch('/opcion/{id}/update', [App\Http\Controllers\opcionController::class, 'update'])->name('opcion.update');
    Route::delete('/opcion/{id}/destroy', [App\Http\Controllers\opcionController::class, 'destroy'])->name('opcion.destroy');

    Route::get('/config', [App\Http\Controllers\configController::class, 'index'])->name('config.index');

    // Dashboard
    Route::post('/dashboard/store', [App\Http\Controllers\dashboardController::class, 'store'])->name('dashboard.store');
    Route::post('/dashboard/{id}/update', [App\Http\Controllers\dashboardController::class, 'update'])->name('dashboard.update');

    //Objetivo
    Route::get('/objetivos', [App\Http\Controllers\objetivosController::class, 'index'])->name('objetivo.index');
    Route::get('/objetivo/create', [App\Http\Controllers\objetivosController::class, 'create'])->name('objetivo.create');
    Route::post('/objetivo/store', [App\Http\Controllers\objetivosController::class, 'store'])->name('objetivo.store');
    Route::get('/objetivo/{objetivo}/edit', [App\Http\Controllers\objetivosController::class, 'edit'])->name('objetivo.edit');
    Route::patch('/objetivo/{objetivo}/update', [App\Http\Controllers\objetivosController::class, 'update'])->name('objetivo.update');
    Route::delete('/objetivo/{objetivo}/destroy', [App\Http\Controllers\objetivosController::class, 'destroy'])->name('objetivo.destroy');

    //minutero
    Route::get('/minutas', [App\Http\Controllers\minutasController::class, 'index'])->name('minutas.index');
    Route::get('/minutas/create', [App\Http\Controllers\minutasController::class, 'create'])->name('minutas.create');
    Route::post('/minutas/store', [App\Http\Controllers\minutasController::class, 'store'])->name('minutas.store');
    Route::get('/minutas/{minuta}', [App\Http\Controllers\minutasController::class, 'show'])->name('minutas.show');
    Route::get('/minutas/{minuta}/edit', [App\Http\Controllers\minutasController::class, 'edit'])->name('minutas.edit');
    Route::patch('/minutas/{minuta}/update', [App\Http\Controllers\minutasController::class, 'update'])->name('minutas.update');
    Route::delete('/minutas/{minuta}/destroy', [App\Http\Controllers\minutasController::class, 'destroy'])->name('minutas.destroy');

    // Proceso
    Route::get('/procesos', [App\Http\Controllers\procesoController::class, 'index'])->name('proceso.index');
    Route::get('/proceso/create', [App\Http\Controllers\procesoController::class, 'create'])->name('proceso.create');
    Route::post('/proceso/store', [App\Http\Controllers\procesoController::class, 'store'])->name('proceso.store');
    Route::get('/proceso/{proceso}/edit', [App\Http\Controllers\procesoController::class, 'edit'])->name('proceso.edit');
    Route::patch('/proceso/{proceso}/update', [App\Http\Controllers\procesoController::class, 'update'])->name('proceso.update');
    Route::delete('/proceso/{proceso}/destroy', [App\Http\Controllers\procesoController::class, 'destroy'])->name('proceso.destroy');

    // Procedimiento
    Route::get('/procedimientos', [App\Http\Controllers\procedimientoController::class, 'index'])->name('procedimiento.index');
    Route::get('/procedimiento/create', [App\Http\Controllers\procedimientoController::class, 'create'])->name('procedimiento.create');
    Route::post('/procedimiento/store', [App\Http\Controllers\procedimientoController::class, 'store'])->name('procedimiento.store');
    Route::get('/procedimiento/{procedimiento}/edit', [App\Http\Controllers\procedimientoController::class, 'edit'])->name('procedimiento.edit');
    Route::patch('/procedimiento/{procedimiento}/update', [App\Http\Controllers\procedimientoController::class, 'update'])->name('procedimiento.update');
    Route::delete('/procedimiento/{procedimiento}/destroy', [App\Http\Controllers\procedimientoController::class, 'destroy'])->name('procedimiento.destroy');

    // Estandar
    Route::get('/estandares', [App\Http\Controllers\EstandarController::class, 'index'])->name('estandar.index');
    Route::get('/estandar/create', [App\Http\Controllers\EstandarController::class, 'create'])->name('estandar.create');
    Route::post('/estandar/store', [App\Http\Controllers\EstandarController::class, 'store'])->name('estandar.store');
    Route::get('/estandar/{estandar}/edit', [App\Http\Controllers\EstandarController::class, 'edit'])->name('estandar.edit');
    Route::patch('/estandar/{estandar}/update', [App\Http\Controllers\EstandarController::class, 'update'])->name('estandar.update');
    Route::delete('/estandar/{estandar}/destroy', [App\Http\Controllers\EstandarController::class, 'destroy'])->name('estandar.destroy');

    // KPI
    Route::get('/kpis', [App\Http\Controllers\kpiController::class, 'index'])->name('kpi.index');
    Route::get('/kpi/create', [App\Http\Controllers\kpiController::class, 'create'])->name('kpi.create');
    Route::post('/kpi/store', [App\Http\Controllers\kpiController::class, 'store'])->name('kpi.store');
    Route::get('/kpi/{kpi}/edit', [App\Http\Controllers\kpiController::class, 'edit'])->name('kpi.edit');
    Route::post('/kpi/{kpi}/update', [App\Http\Controllers\kpiController::class, 'update'])->name('kpi.update');
    Route::delete('/kpi/{kpi}/destroy', [App\Http\Controllers\kpiController::class, 'destroy'])->name('kpi.destroy');

    Route::get('admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');

    Route::get('roles', [App\Http\Controllers\rolesController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [App\Http\Controllers\rolesController::class, 'create'])->name('roles.create');
    Route::post('roles/store', [App\Http\Controllers\rolesController::class, 'store'])->name('roles.store');
    Route::get('roles/{rol}/edit', [App\Http\Controllers\rolesController::class, 'edit'])->name('roles.edit');
    Route::patch('roles/{role}/update', [App\Http\Controllers\rolesController::class, 'update'])->name('roles.update');

    Route::get('permisos', [App\Http\Controllers\permisosController::class, 'index'])->name('permisos.index');
    Route::get('permisos/create', [App\Http\Controllers\permisosController::class, 'create'])->name('permisos.create');
    Route::post('permisos/store', [App\Http\Controllers\permisosController::class, 'store'])->name('permisos.store');

    //Tareas
    Route::get('tareas', [App\Http\Controllers\tareasController::class, 'index'])->name('tareas.index');
    Route::get('tareas/create', [App\Http\Controllers\tareasController::class, 'create'])->name('tareas.create');
    Route::post('tareas/store', [App\Http\Controllers\tareasController::class, 'store'])->name('tareas.store');
    Route::get('tareas/{tarea}/show', [App\Http\Controllers\tareasController::class, 'show'])->name('tareas.show');
    Route::get('tareas/{tarea}/editar', [App\Http\Controllers\tareasController::class, 'edit'])->name('tareas.edit');
    Route::get('tareas/{tarea}/detalle', [App\Http\Controllers\tareasController::class, 'detail'])->name('tareas.detail');
    Route::patch('tareas/{tarea}/update', [App\Http\Controllers\tareasController::class, 'update'])->name('tareas.update');
    Route::delete('tarea/{tarea}/destroy', [App\Http\Controllers\tareasController::class, 'destroy'])->name('tarea.destroy');

    //SysGestion
    Route::get('sysgestion', [App\Http\Controllers\sysGestionController::class, 'index'])->name('sysgestion.index');
});
