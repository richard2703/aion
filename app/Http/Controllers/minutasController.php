<?php

namespace App\Http\Controllers;

use App\Models\minutas;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Carbon\Carbon;

class minutasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Minutas/MinutasIndex', [
            'area_id' => $request->get('area_id'),
            'departamento_id' => $request->get('departamento_id'),
        ]);
    }

    function findAll(Request $request)
    {
        $query = minutas::query(); // Replace 'Model' with the actual model name
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filters = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'desc');

        // Apply box filters
        // if ($request->formFilter) {

        //     $area_id = $request->formFilter['area_id'];
        //     $departamento_id = $request->formFilter['departamento_id'];
        //     $lider_id = $request->formFilter['lider_id'];
        //     $tipo = $request->formFilter['tipo'];
        //     $proceso_id = $request->formFilter['proceso_id'];
        //     $fecha_from = $request->formFilter['fecha_from'];
        //     $fecha_to = $request->formFilter['fecha_to'];

        //     $query->where(function ($q) use ($tipo, $area_id, $departamento_id, $lider_id, $proceso_id, $fecha_from, $fecha_to) {
        //         if ($area_id) {
        //             $q->orWhere('minutas.area_id', '=', $area_id);
        //         }
        //         if ($departamento_id) {
        //             $q->orWhere('minutas.departamento_id', '=', $departamento_id);
        //         }
        //         if ($lider_id) {
        //             $q->orWhere('minutas.lider_id', '=', $lider_id);
        //         }
        //         if ($tipo) {
        //             $q->orWhere('minutas.tipo', '=', $tipo);
        //         }
        //         if ($proceso_id) {
        //             $q->orWhere('minutas.proceso_id', '=', $proceso_id);
        //         }
        //         if ($fecha_from && $fecha_to) {
        //             $q->orWhereBetween('minutas.created_at', [$fecha_from, $fecha_to]);
        //         }
        //     });
        // }
        // ////

        // // Apply global search
        // if ($filter) {
        //     $query->where(function ($q) use ($filter) {
        //         $q->where('id', 'like', '%' . $filter . '%')
        //             ->orWhere('alias', 'like', '%' . $filter . '%')
        //             ->orWhere('created_at', 'like', '%' . $filter . '%')
        //             ->orWhereHas('area', function ($q) use ($filter) {
        //                 $q->where('areas.nombre', 'like', '%' . $filter . '%')
        //                     ->orWhere('areas.descripcion', 'like', '%' . $filter . '%');
        //             })
        //             ->orWhereHas('departamento', function ($q) use ($filter) {
        //                 $q->where('departamentos.nombre', 'like', '%' . $filter . '%')
        //                     ->orWhere('departamentos.descripcion', 'like', '%' . $filter . '%');
        //             })
        //             ->orWhereHas('lider', function ($q) use ($filter) {
        //                 $q->where('Users.name', 'like', '%' . $filter . '%');
        //             })
        //             ->orWhereHas('tipoMinuta', function ($q) use ($filter) {
        //                 $q->where('tipos_minuta.titulo', 'like', '%' . $filter . '%');
        //             });
        //     });
        // }

        // if (in_array($sortField, ['id', 'titulo', 'objetivo'])) {
        //     $query->orderBy($sortField, $sortOrder);
        // }

        // new stuff

        // Apply global filter if it exists
        if (isset($filters['global']['value']) && !empty($filters['global']['value'])) {
            $globalFilter = $filters['global']['value'];
            $query->where(function ($q) use ($globalFilter) {
                $q->where('id', 'like', '%' . $globalFilter . '%')
                    ->orWhere('alias', 'like', '%' . $globalFilter . '%')
                    ->orWhere('created_at', 'like', '%' . $globalFilter . '%')
                    ->orWhereHas('area', function ($q) use ($globalFilter) {
                        $q->where('areas.nombre', 'like', '%' . $globalFilter . '%')
                            ->orWhere('areas.descripcion', 'like', '%' . $globalFilter . '%');
                    })
                    ->orWhereHas('departamento', function ($q) use ($globalFilter) {
                        $q->where('departamentos.nombre', 'like', '%' . $globalFilter . '%')
                            ->orWhere('departamentos.descripcion', 'like', '%' . $globalFilter . '%');
                    })
                    ->orWhereHas('lider', function ($q) use ($globalFilter) {
                        $q->where('Users.name', 'like', '%' . $globalFilter . '%');
                    })
                    ->orWhereHas('proceso', function ($q) use ($globalFilter) {
                        $q->where('procesos.nombre', 'like', '%' . $globalFilter . '%');
                    })
                    ->orWhereHas('tipoMinuta', function ($q) use ($globalFilter) {
                        $q->where('tipos_minuta.titulo', 'like', '%' . $globalFilter . '%');
                    });
            });
        }

        // Apply specific filters
        $filterableFields = ['area_id', 'departamento_id', 'lider_id', 'tipo', 'proceso_id'];
        foreach ($filterableFields as $field) {

            if (isset($filters[$field]['value']) && !empty($filters[$field]['value'])) {
                $query->Where($field, '=', $filters[$field]['value']);
            }
        }
        // dd($query->toSql());

        // Apply date range filters
        if (isset($filters['desde']['value']) && !empty($filters['desde']['value'])) {
            $query->where('created_at', '>=', $filters['desde']['value']);
        }
        if (isset($filters['hasta']['value']) && !empty($filters['hasta']['value'])) {
            $query->where('created_at', '<=', $filters['hasta']['value']);
        }

        //


        // Sorting logic
        if (in_array($sortField, ['id', 'alias', 'created_at', 'tipo', 'area.nombre', 'departamento.nombre', 'lider.name', 'proceso.nombre', 'tipo_minuta'])) {
            if (strpos($sortField, 'area.') === 0) {
                $query->join('areas', 'minutas.area_id', '=', 'areas.id')
                    ->select('minutas.*', 'areas.nombre as area_nombre') // Select distinct columns
                    ->orderBy('areas.nombre', $sortOrder);
            } else if (strpos($sortField, 'departamento.') === 0) {
                $query->join('departamentos', 'minutas.departamento_id', '=', 'departamentos.id')
                    ->select('minutas.*', 'departamentos.nombre as departamento_nombre') // Select distinct columns
                    ->orderBy('departamentos.nombre', $sortOrder);
            } else if (strpos($sortField, 'lider.') === 0) {
                $query->join('users', 'minutas.lider_id', '=', 'users.id')
                    ->select('minutas.*', 'users.name as lider_name') // Select distinct columns
                    ->orderBy('users.name', $sortOrder);
            } else if (strpos($sortField, 'proceso.') === 0) {
                $query->join('procesos', 'minutas.proceso_id', '=', 'procesos.id')
                    ->select('minutas.*', 'procesos.nombre as procesos_nombre') // Select distinct columns
                    ->orderBy('procesos.nombre', $sortOrder);
            } else if (strpos($sortField, 'tipo_minuta.') === 0) {
                $query->join('tipos_minuta', 'minutas.tipo', '=', 'tipos_minuta.id')
                    ->select('minutas.*', 'tipos_minuta.titulo as tipos_minuta_titulo') // Select distinct columns
                    ->orderBy('tipos_minuta.titulo', $sortOrder);
            } else {
                $query->orderBy('minutas.' . $sortField, $sortOrder);
            }
        } else {
            // Default sorting if the provided sortField is not allowed
            $query->orderBy('id', $sortOrder);
        }

        $user = auth()->user();
        if ($user->hasRole('lider_pilar')) {
            // dd('Admin'); // Devuelve una colección de nombres de roles
        } else {
            $query->where('privada', 0);
        }
        // $result = $query->with('challenge')->paginate($pageSize, ['*'], 'page', $page);
        $result = $query->with('area', 'departamento', 'lider', 'proceso', 'tipoMinuta')->paginate($pageSize, ['*'], 'page', $page);
        return response()->json($result);
    }

    public function create()
    {
        return Inertia::render('Minutas/MinutasCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $minuta = new minutas();
        $minuta->area_id = $request->area_id;
        $minuta->departamento_id = $request->departamento_id;
        if (isset($request->proceso_id["id"])) {
            # code...
            $minuta->proceso_id = $request->proceso_id["id"];
        }
        $minuta->lider_id = $request->lider_id["id"];
        $minuta->alias = $request->alias;
        $minuta->tipo = $request->tipo;
        $minuta->notas = $request->notas;
        $minuta->estatus = $request->estatus;
        $minuta->privada = $request->privada;
        $minuta->save();

        return redirect()->route('minutas.show', $minuta->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(minutas $minuta)
    {
        // Carga las relaciones necesarias
        $minuta->load('lider', 'area', 'departamento', 'proceso', 'tipoMinuta');

        // Format the date
        $dateFromDatabase = $minuta->created_at;
        $formattedDate = Carbon::parse($dateFromDatabase)->format('Y-m-d');

        $minuta->fecha = $formattedDate; // add the formatted date to the object

        return Inertia::render('Minutas/MinutasShow', ['minuta' => $minuta]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(minutas $minuta)
    {
        $minuta->load('lider', 'area', 'departamento', 'proceso');
        $user = User::find($minuta->responsable_id);
        return Inertia::render('Minutas/MinutasEdit', ['minuta' => $minuta, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, minutas $minuta)
    {
        if (is_array($request->lider_id)) {
            $minuta->lider_id = $request->lider_id["id"];
        }
        if (is_array($request->proceso_id)) {
            $minuta->proceso_id = $request->proceso_id["id"];
        }
        $minuta->area_id = $request->area_id;
        $minuta->departamento_id = $request->departamento_id;
        $minuta->alias = $request->alias;
        $minuta->tipo = $request->tipo;
        $minuta->notas = $request->notas;
        $minuta->estatus = $request->estatus;
        $minuta->privada = $request->privada;

        // Guardar los cambios en la base de datos
        $minuta->save();

        // Redireccionar a la ruta deseada después de la actualización
        return redirect()->route('minutas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(minutas $minuta)
    {
        //
        $minuta->delete();
        return response()->json(['success' => true]);
    }
}
