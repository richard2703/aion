<?php

namespace App\Http\Controllers;

use App\Models\encargado_flujo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class encargado_flujoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('EncargadoFlujo/EncargadoFlujoIndex');
    }


    public function findAll(Request $request)
    {

        $query = encargado_flujo::query();
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        // Filter logic
        // if ($filter) {
        //     $query->where(function ($q) use ($filter) {
        //         $q->where('estandares.id', 'like', '%' . $filter . '%')
        //             ->orWhere('estandares.nombre', 'like', '%' . $filter . '%')
        //             ->orWhere('estandares.descripcion', 'like', '%' . $filter . '%')
        //             ->orWhere('estandares.link_externo', 'like', '%' . $filter . '%')
        //             ->orWhereHas('procedimiento', function ($q) use ($filter) {
        //                 $q->where('procedimientos.nombre', 'like', '%' . $filter . '%')
        //                     ->orWhere('procedimientos.descripcion', 'like', '%' . $filter . '%');
        //             });
        //     });
        // }

        // Sorting logic
        // if (in_array($sortField, ['id', 'nombre', 'descripcion', 'link_externo', 'procedimiento.nombre'])) {
        //     if (strpos($sortField, 'procedimiento.') === 0) {
        //         $query->join('procedimientos', 'estandares.procedimiento_id', '=', 'procedimientos.id')
        //             ->select('estandares.*', 'procedimientos.nombre as procedimiento_nombre') // Select distinct columns
        //             ->orderBy('procedimientos.nombre', $sortOrder);
        //     } else {
        //         $query->orderBy('estandares.' . $sortField, $sortOrder);
        //     }
        // } else {
        //     // Default sorting if the provided sortField is not allowed
        //     $query->orderBy('id', $sortOrder);
        // }

        $encargados = $query->with('usuario', 'departamento')->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($encargados);
    }


    public function create()
    {
        return Inertia::render('EncargadoFlujo/EncargadoFlujoCreate');
    }

    public function store(Request $request)
    {
        $encargado_flujo = new encargado_flujo();
        // $encargado_flujo->area_id = $request->area_id;
        $encargado_flujo->departamento_id = $request->departamento_id;
        $encargado_flujo->user_id = $request->lider_id["id"];
        $revision = encargado_flujo::where('departamento_id', $request->departamento_id)->where('user_id', $request->lider_id["id"])->first();
        if ($revision) {
            // dd("no hay revision");
            return redirect()->route('encargadoFlujo.index');
        }
        // dd("si hay revision");
        $encargado_flujo->save();

        return redirect()->route('encargadoFlujo.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(encargado_flujo $encargado_flujo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(encargado_flujo $encargado_flujo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, encargado_flujo $encargado_flujo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(encargado_flujo $encargado_flujo)
    {
        $encargado_flujo->delete();
        return response()->json(['success' => true]);
    }
}
