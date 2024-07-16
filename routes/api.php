<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hello', function (Request $request) {
    return response('Hello World', 200);
})->name('hello');

Route::get('/areas', [App\Http\Controllers\areaController::class, 'findAll'])->name('areas.findAll');
Route::get('/departamentos/{area_id}', [App\Http\Controllers\departamentoController::class, 'byArea'])->name('departamentos.byArea');
Route::get('/departamentos', [App\Http\Controllers\departamentoController::class, 'findAll'])->name('departamentos.findAll');
Route::get('/departamentos/find/todo', [App\Http\Controllers\departamentoController::class, 'find'])->name('departamentos.find');
Route::get('/usuarios', [App\Http\Controllers\userController::class, 'findAll'])->name('users.findAll');
Route::get('/usuarios/all/todo', [App\Http\Controllers\userController::class, 'all'])->name('users.all');
Route::get('/usuarios/{area_id}', [App\Http\Controllers\userController::class, 'byArea'])->name('usuarios.byArea');



Route::get('/challenges', [App\Http\Controllers\challengesController::class, 'findAll'])->name('challenges.findAll');

Route::get('/config-dashboard', [App\Http\Controllers\dashboardController::class, 'getConfig'])->name('config.getConfig');

Route::get('/challenges/{departamento_id}', [App\Http\Controllers\challengesController::class, 'byDepartamento'])->name('challenges.byArea');
Route::get('/opciones', [App\Http\Controllers\opcionController::class, 'findAll'])->name('opciones.findAll');
Route::get('/objetivos', [App\Http\Controllers\objetivosController::class, 'findAll'])->name('objetivos.findAll');
Route::get('/minutas', [App\Http\Controllers\minutasController::class, 'findAll'])->name('minutas.findAll');
Route::get('/procesos', [App\Http\Controllers\procesoController::class, 'findAll'])->name('procesos.findAll');
Route::get('/procedimientos', [App\Http\Controllers\ProcedimientoController::class, 'findAll'])->name('procedimientos.findAll');
Route::get('/estandares', [App\Http\Controllers\EstandarController::class, 'findAll'])->name('estandares.findAll');
Route::get('/kpis', [App\Http\Controllers\kpiController::class, 'findAll'])->name('kpis.findAll');
Route::get('/roles', [App\Http\Controllers\rolesController::class, 'findAll'])->name('roles.findAll');
Route::get('/permisos', [App\Http\Controllers\permisosController::class, 'findAll'])->name('permisos.findAll');
Route::get('/permisos/all', [App\Http\Controllers\permisosController::class, 'all'])->name('permisos.All');




// Route::get('/objetivos/{objetivos_id}', [App\Http\Controllers\objetivosController::class, 'byDepartamento'])->name('challenges.byArea');
