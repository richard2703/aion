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


    public function findAll(Request $request)
    {
        $query = tareas::query();
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        // Filter logic
        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('tareas.id', 'like', '%' . $filter . '%')
                    ->orWhere('tareas.tarea', 'like', '%' . $filter . '%')
                    ->orWhere('tareas.fecha', 'like', '%' . $filter . '%')
                    ->orWhere('tareas.nota', 'like', '%' . $filter . '%')
                    ->orWhereHas('area', function ($q) use ($filter) {
                        $q->where('areas.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('areas.descripcion', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('departamento', function ($q) use ($filter) {
                        $q->where('departamentos.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('departamentos.descripcion', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('minuta', function ($q) use ($filter) {
                        $q->where('minutas.alias', 'like', '%' . $filter . '%');
                        // ->orWhere('minutas.descripcion', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('responsable', function ($q) use ($filter) {
                        $q->where('users.name', 'like', '%' . $filter . '%');
                        // ->orWhere('responsables.apellido', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('estatus', function ($q) use ($filter) {
                        $q->where('estatus.titulo', 'like', '%' . $filter . '%');
                        // ->orWhere('responsables.apellido', 'like', '%' . $filter . '%');
                    });
            });
        }

        // Sorting logic
        if (in_array($sortField, ['id', 'tarea', 'fecha', 'nota', 'area.nombre', 'departamento.nombre', 'minuta.nombre', 'responsable.nombre'])) {
            if (strpos($sortField, 'area.') === 0) {
                $query->join('areas', 'tareas.area_id', '=', 'areas.id')
                    ->select('tareas.*', 'areas.nombre as area_nombre') // Select distinct columns
                    ->orderBy('areas.nombre', $sortOrder);
            } else if (strpos($sortField, 'departamento.') === 0) {
                $query->join('departamentos', 'tareas.departamento_id', '=', 'departamentos.id')
                    ->select('tareas.*', 'departamentos.nombre as departamento_nombre') // Select distinct columns
                    ->orderBy('departamentos.nombre', $sortOrder);
            } else if (strpos($sortField, 'minuta.') === 0) {
                $query->join('minutas', 'tareas.minuta_id', '=', 'minutas.id')
                    ->select('tareas.*', 'minutas.alias as minuta_alias') // Select distinct columns
                    ->orderBy('minutas.alias', $sortOrder);
            } else if (strpos($sortField, 'responsable.') === 0) {
                $query->join('responsables', 'tareas.responsable_id', '=', 'users.id')
                    ->select('tareas.*', 'users.name as responsable_name') // Select distinct columns
                    ->orderBy('users.name', $sortOrder);
            } else if (strpos($sortField, 'estatus.') === 0) {
                $query->join('estatus', 'tareas.estatus_id', '=', 'estaus.id')
                    ->select('tareas.*', 'estatus.tululo as estatus_tilulo') // Select distinct columns
                    ->orderBy('estatus.tilulo', $sortOrder);
            } else {
                $query->orderBy('tareas.' . $sortField, $sortOrder);
            }
        } else {
            // Default sorting if the provided sortField is not allowed
            $query->orderBy('id', $sortOrder);
        }

        $tareas = $query->with('area', 'departamento', 'minuta', 'responsable', 'estatus')->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($tareas);
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
        $data = [
            'area_id' => $request->area_id,
            'departamento_id' => $request->departamento_id,
            'minuta_id' => $request->minuta_id,
            'tarea' => $request->tarea,
            'responsable_id' => $request->responsable_id,
            'fecha' => $request->fecha,
            'nota' => $request->nota,
            'estatus_id' => $request->estatus_id,
        ];

        tareas::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(tareas $tarea)
    {
        $tarea->load('area', 'departamento', 'minuta', 'responsable', 'estatus');
        return response()->json($tarea);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tareas $tarea)
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
            'nota',
            'estatus_id',
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

    public function byMinuta($minuta_id, Request $request)
    {

        $query = tareas::query();
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        if ($request->formFilter) {
            $area_id = $request->formFilter['area_id'];
            $departamento_id = $request->formFilter['departamento_id'];
            $responsable_id = $request->formFilter['responsable_id'];
            $estatus_id = $request->formFilter['estatus_id'];
            $fecha_from = $request->formFilter['fecha_from'];
            $fecha_to = $request->formFilter['fecha_to'];

            $query->where(function ($q) use ($area_id, $departamento_id, $responsable_id, $estatus_id, $fecha_from, $fecha_to) {
                if ($area_id) {
                    $q->orWhere('tareas.area_id', '=', $area_id);
                }
                if ($departamento_id) {
                    $q->orWhere('tareas.departamento_id', '=', $departamento_id);
                }
                if ($responsable_id) {
                    $q->orWhere('tareas.responsable_id', '=', $responsable_id);
                }
                if ($estatus_id) {
                    $q->orWhere('tareas.estatus_id', '=', $estatus_id);
                }
                if ($fecha_from && $fecha_to) {
                    $q->orWhereBetween('tareas.fecha', [$fecha_from, $fecha_to]);
                }
            });
        } else {
            $query->where('tareas.minuta_id', '=', $minuta_id);
        }


        // Filter logic
        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('tareas.id', 'like', '%' . $filter . '%')
                    ->orWhere('tareas.tarea', 'like', '%' . $filter . '%')
                    ->orWhere('tareas.fecha', 'like', '%' . $filter . '%')
                    ->orWhere('tareas.nota', 'like', '%' . $filter . '%')
                    ->orWhereHas('area', function ($q) use ($filter) {
                        $q->where('areas.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('areas.descripcion', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('departamento', function ($q) use ($filter) {
                        $q->where('departamentos.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('departamentos.descripcion', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('minuta', function ($q) use ($filter) {
                        $q->where('minutas.alias', 'like', '%' . $filter . '%');
                        // ->orWhere('minutas.descripcion', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('responsable', function ($q) use ($filter) {
                        $q->where('users.name', 'like', '%' . $filter . '%');
                        // ->orWhere('responsables.apellido', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('estatus', function ($q) use ($filter) {
                        $q->where('estatus.titulo', 'like', '%' . $filter . '%');
                        // ->orWhere('responsables.apellido', 'like', '%' . $filter . '%');
                    });
            });
        }

        // Sorting logic
        if (in_array($sortField, ['id', 'tarea', 'fecha', 'nota', 'area.nombre', 'departamento.nombre', 'minuta.nombre', 'responsable.nombre'])) {
            if (strpos($sortField, 'area.') === 0) {
                $query->join('areas', 'tareas.area_id', '=', 'areas.id')
                    ->select('tareas.*', 'areas.nombre as area_nombre') // Select distinct columns
                    ->orderBy('areas.nombre', $sortOrder);
            } else if (strpos($sortField, 'departamento.') === 0) {
                $query->join('departamentos', 'tareas.departamento_id', '=', 'departamentos.id')
                    ->select('tareas.*', 'departamentos.nombre as departamento_nombre') // Select distinct columns
                    ->orderBy('departamentos.nombre', $sortOrder);
            } else if (strpos($sortField, 'minuta.') === 0) {
                $query->join('minutas', 'tareas.minuta_id', '=', 'minutas.id')
                    ->select('tareas.*', 'minutas.alias as minuta_alias') // Select distinct columns
                    ->orderBy('minutas.alias', $sortOrder);
            } else if (strpos($sortField, 'responsable.') === 0) {
                $query->join('responsables', 'tareas.responsable_id', '=', 'users.id')
                    ->select('tareas.*', 'users.name as responsable_name') // Select distinct columns
                    ->orderBy('users.name', $sortOrder);
            } else if (strpos($sortField, 'estatus.') === 0) {
                $query->join('estatus', 'tareas.estatus_id', '=', 'estaus.id')
                    ->select('tareas.*', 'estatus.tululo as estatus_tilulo') // Select distinct columns
                    ->orderBy('estatus.tilulo', $sortOrder);
            } else {
                $query->orderBy('tareas.' . $sortField, $sortOrder);
            }
        } else {
            // Default sorting if the provided sortField is not allowed
            $query->orderBy('id', $sortOrder);
        }

        $tareas = $query->with('area', 'departamento', 'minuta', 'responsable', 'estatus')->where('minuta_id', $minuta_id)->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($tareas);
    }
}
