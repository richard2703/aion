<?php

namespace App\Http\Controllers;

use App\Mail\ActualizacionTareaMail;
use App\Mail\NuevaTareaMail;
use App\Models\Notificacion;
use App\Models\tareas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class tareasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Tareas/TareasIndex', [
            'authUser' => auth()->user(),
        ]);
    }


    public function findAll(Request $request)
    {
        $user_rol = $request->user()->roles()->first()->name;
        $user_id = $request->user()->id;


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
        if (in_array($sortField, ['id', 'tarea', 'fecha', 'nota', 'area.nombre', 'departamento.nombre', 'minuta.nombre', 'responsable.name', 'revisor.name', 'estatus.titulo'])) {
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
                $query->join('users', 'tareas.responsable_id', '=', 'users.id')
                    ->select('tareas.*', 'users.name as responsable_name') // Select distinct columns
                    ->orderBy('users.name', $sortOrder);
            } else if (strpos($sortField, 'revisor.') === 0) {
                $query->join('users', 'tareas.revisor_id', '=', 'users.id')
                    ->select('tareas.*', 'users.name as revisor_name') // Select distinct columns
                    ->orderBy('users.name', $sortOrder);
            } else if (strpos($sortField, 'estatus.') === 0) {
                $query->join('estatus', 'tareas.estatus_id', '=', 'estatus.id')
                    ->select('tareas.*', 'estatus.titulo as estatus_titulo') // Select distinct columns
                    ->orderBy('estatus.titulo', $sortOrder);
            } else {
                $query->orderBy('tareas.' . $sortField, $sortOrder);
            }
        } else {
            // Default sorting if the provided sortField is not allowed
            $query->orderBy('id', $sortOrder);
        }

        if ($user_rol == 'admin') {
            $result = $query->with('area', 'departamento', 'minuta', 'responsable', 'estatus', 'revisor')->paginate($pageSize, ['*'], 'page', $page);
        } else {

            $result = $query->with('area', 'departamento', 'minuta', 'responsable', 'estatus', 'revisor')->where('responsable_id', $user_id)->paginate($pageSize, ['*'], 'page', $page);
        }

        // $result = $query->with('area', 'departamento', 'minuta', 'responsable', 'estatus', 'revisor')->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($result);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tareas/TareasCreate');
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
            'fecha' => $request->fecha,
            'nota' => $request->nota,
            'estatus_id' => $request->estatus_id,
        ];

        if (is_array($request->responsable_id)) {
            $data['responsable_id'] = $request->responsable_id["id"];
        }
        if (is_array($request->revisor_id)) {
            $data['revisor_id'] = $request->revisor_id["id"];
        }

        if (is_int($request->responsable_id)) {
            $data['responsable_id'] = $request->responsable_id;
        }
        if (is_int($request->revisor_id)) {
            $data['revisor_id'] = $request->revisor_id;
        }



        $mailData = tareas::create($data);

        $notification = [
            'user_id' => $request->responsable_id,
            'titulo' => $mailData->tarea,
            'link' => '/tareas/' . $mailData->id . '/detalle',
            'readed' => 0,
        ];

        $notification = Notificacion::create($notification);

        $responsable = User::find($request->responsable_id);

        Mail::to($responsable->email)->send(new NuevaTareaMail($mailData));
    }

    /**
     * Display the specified resource.
     */
    public function show(tareas $tarea)
    {
        $tarea->load('area', 'departamento', 'minuta', 'responsable', 'estatus', 'revisor');
        return response()->json($tarea);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tareas $tarea)
    {
        //
        $tarea->load('area', 'departamento', 'minuta', 'responsable', 'revisor', 'estatus');
        return Inertia::render('Tareas/TareasEdit', ['tarea' => $tarea]);
    }

    public function detail(tareas $tarea)
    {
        //
        $tarea->load('area', 'departamento', 'minuta', 'responsable', 'revisor', 'estatus');
        $notifications = Notificacion::where('user_id', $tarea->responsable_id)
            ->where('titulo', $tarea->tarea)
            ->where('readed', '<>', 1)
            ->get();

        if ($notifications->isNotEmpty()) {
            Notificacion::whereIn('id', $notifications->pluck('id'))
                ->update(['readed' => 1]);
        }

        return Inertia::render('Tareas/TareaDetail', ['tarea' => $tarea]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tareas $tarea)
    {
        if (is_array($request->responsable_id)) {
            $tarea->responsable_id = $request->responsable_id["id"];
        }
        if (is_array($request->revisor_id)) {
            $tarea->revisor_id = $request->revisor_id["id"];
        }

        if (is_int($request->responsable_id)) {
            $tarea->responsable_id = $request->responsable_id;
        }
        if (is_int($request->revisor_id)) {
            $tarea->revisor_id = $request->revisor_id;
        }

        $tarea->area_id = $request->area_id;
        $tarea->departamento_id = $request->departamento_id;
        $tarea->minuta_id = $request->minuta_id;
        $tarea->tarea = $request->tarea;
        $tarea->fecha = $request->fecha;
        $tarea->nota = $request->nota;
        $tarea->estatus_id = $request->estatus_id;
        $tarea->save();

        $mailData = $tarea->load('responsable', 'revisor', 'estatus');
        $responsable = User::find($tarea->responsable_id);

        Mail::to($responsable->email)->send(new ActualizacionTareaMail($mailData));
    }

    /**
     * Update the validacion field in storage.
     */
    public function validar(Request $request, tareas $tarea)
    {

        $tarea->update($request->only(
            'validacion',
            'estatus_id',
        ));
        return response()->json(['success' => true]);
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
        if (in_array($sortField, ['id', 'tarea', 'fecha', 'nota', 'area.nombre', 'departamento.nombre', 'minuta.nombre', 'responsable.nombre', 'revisor.name',  'estatus.titulo'])) {
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
            } else if (strpos($sortField, 'revisor.') === 0) {
                $query->join('users', 'tareas.revisor_id', '=', 'users.id')
                    ->select('tareas.*', 'users.name as revisor_name') // Select distinct columns
                    ->orderBy('users.name', $sortOrder);
            } else if (strpos($sortField, 'estatus.') === 0) {
                $query->join('estatus', 'tareas.estatus_id', '=', 'estatus.id')
                    ->select('tareas.*', 'estatus.titulo as estatus_titulo') // Select distinct columns
                    ->orderBy('estatus.titulo', $sortOrder);
            } else {
                $query->orderBy('tareas.' . $sortField, $sortOrder);
            }
        } else {
            // Default sorting if the provided sortField is not allowed
            $query->orderBy('id', $sortOrder);
        }

        $tareas = $query->with('area', 'departamento', 'minuta', 'responsable', 'revisor', 'estatus')->where('minuta_id', $minuta_id)->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($tareas);
    }

    public function countTareas($minuta_id)
    {
        $tareas = new tareas;
        $tareas->load('area', 'departamento', 'minuta', 'responsable', 'estatus');
        $tareasCount = tareas::where('minuta_id', $minuta_id)->count();
        return response()->json(['count' => $tareasCount]);
    }

    public function countTareasTerminadas($minuta_id)
    {
        $count = tareas::where('minuta_id', $minuta_id)
            ->whereHas('estatus', function ($query) {
                $query->where('titulo', 'Terminado');
            })
            ->count();

        return response()->json(['count' => $count]);
    }

    public function misTareas()
    {
        return Inertia::render('Tareas/MisTareas');
    }
    public function byUser(Request $request)
    {

        $user = Auth::user();

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
            $query->where('responsable_id', $user->id);
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
        if (in_array($sortField, ['id', 'tarea', 'fecha', 'nota', 'area.nombre', 'departamento.nombre', 'minuta.nombre', 'responsable.nombre', 'revisor.name',  'estatus.titulo'])) {
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
            } else if (strpos($sortField, 'revisor.') === 0) {
                $query->join('users', 'tareas.revisor_id', '=', 'users.id')
                    ->select('tareas.*', 'users.name as revisor_name') // Select distinct columns
                    ->orderBy('users.name', $sortOrder);
            } else if (strpos($sortField, 'estatus.') === 0) {
                $query->join('estatus', 'tareas.estatus_id', '=', 'estatus.id')
                    ->select('tareas.*', 'estatus.titulo as estatus_titulo') // Select distinct columns
                    ->orderBy('estatus.titulo', $sortOrder);
            } else {
                $query->orderBy('tareas.' . $sortField, $sortOrder);
            }
        } else {
            // Default sorting if the provided sortField is not allowed
            $query->orderBy('id', $sortOrder);
        }

        $tareas = $query->with('area', 'departamento', 'minuta', 'responsable', 'revisor', 'estatus')->where('responsable_id', $user->id)->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($tareas);
    }
}
