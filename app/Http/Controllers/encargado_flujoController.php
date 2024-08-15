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

        $encargados = $query->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($encargados);
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
