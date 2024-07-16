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
    public function index()
    {
        // dd("objetivos");
        return Inertia::render('Minutas/MinutasIndex');
    }

    function findAll(Request $request)
    {
        $query = minutas::query(); // Replace 'Model' with the actual model name
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('id', 'like', '%' . $filter . '%')
                    ->orWhere('alias', 'like', '%' . $filter . '%')
                    ->orWhere('tareas', 'like', '%' . $filter . '%')
                    ->orWhereHas('area', function ($q) use ($filter) {
                        $q->where('areas.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('areas.descripcion', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('departamento', function ($q) use ($filter) {
                        $q->where('departamentos.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('departamentos.descripcion', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('lider', function ($q) use ($filter) {
                        $q->where('Users.name', 'like', '%' . $filter . '%');
                    });
            });
        }

        // if (in_array($sortField, ['id', 'tareas', 'departamento.nombre', 'departamento.nombre', 'usuario.name',])) {
        //     if (strpos($sortField, 'area.') === 0) {
        //         $query->join('areas', 'challenges.area_id', '=', 'areas.id')
        //             ->orderBy('areas.' . substr($sortField, 5), $sortOrder);
        //     } else if (strpos($sortField, 'departamento.') === 0) {
        //         $query->join('departamentos', 'challenges.departamento_id', '=', 'departamentos.id')
        //             ->orderBy('departamentos.' . substr($sortField, 12), $sortOrder);
        //     } else if (strpos($sortField, 'usuario.') === 0) {
        //         $query->join('usuarios', 'minutas.responsable_id', '=', 'responsable_id.id')
        //             ->orderBy('Users.' . substr($sortField, 5), $sortOrder);
        //     } else {
        //         $query->orderBy($sortField, $sortOrder);
        //     }
        // }
        if (in_array($sortField, ['id', 'titulo', 'objetivo'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        // $result = $query->with('challenge')->paginate($pageSize, ['*'], 'page', $page);
        $result = $query->with('area', 'departamento', 'lider', 'proceso')->paginate($pageSize, ['*'], 'page', $page);
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
        $minuta->proceso_id = $request->proceso_id["id"];
        $minuta->lider_id = $request->lider_id["id"];
        $minuta->alias = $request->alias;
        $minuta->tipo = $request->tipo;
        $minuta->notas = $request->notas;
        $minuta->estatus = $request->estatus;
        $minuta->save();

        return redirect()->route('minutas.show', $minuta->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(minutas $minuta)
    {
        // Carga las relaciones necesarias
        $minuta->load('lider', 'area', 'departamento', 'proceso');

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
        $minuta->area_id = $request->area_id;
        $minuta->departamento_id = $request->departamento_id;
        $minuta->proceso_id = $request->proceso_id["id"]; // Asegúrate de ajustar según la estructura de tu solicitud
        $minuta->lider_id = $request->lider_id["id"]; // Asegúrate de ajustar según la estructura de tu solicitud
        $minuta->alias = $request->alias;
        $minuta->tipo = $request->tipo;
        $minuta->notas = $request->notas;
        $minuta->estatus = $request->estatus;

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
