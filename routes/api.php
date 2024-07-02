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
Route::get('/usuarios', [App\Http\Controllers\userController::class, 'findAll'])->name('users.findAll');
Route::get('/challenges', [App\Http\Controllers\challengesController::class, 'findAll'])->name('challenges.findAll');

Route::get('/config-dashboard', [App\Http\Controllers\dashboardController::class, 'getConfig'])->name('config.getConfig');

Route::get('/challenges/{departamento_id}', [App\Http\Controllers\challengesController::class, 'byDepartamento'])->name('challenges.byArea');
Route::get('/opciones', [App\Http\Controllers\opcionController::class, 'findAll'])->name('opciones.findAll');
