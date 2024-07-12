<?php

namespace App\Http\Controllers;

use App\Models\tareas;
use Illuminate\Http\Request;
use Inertia\Inertia;

class tareasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Tareas/TareasIndex');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('minutas/minutasCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tarea = tareas::create($request->only(
            'area_id',
            'departamento_id',
            'minuta_id',
            'tarea',
            'responsable_id',
            'fecha',
            'notas',
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(tareas $tareas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tareas $tareas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tareas $tarea)
    {
        $tarea->update($request->only(
            'area_id',
            'departamento_id',
            'minuta_id',
            'tarea',
            'responsable_id',
            'fecha',
            'notas',
            'estatus',
        ));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tareas $tarea)
    {
        $tarea->delete();
        return response()->json(['success' => true]);
    }

    public function byMinuta($minuta_id)
    {
        return response()->json(['tareas' => tareas::where('minuta_id', $minuta_id)->get()]);
    }
}
