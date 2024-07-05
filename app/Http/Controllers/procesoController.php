<?php

namespace App\Http\Controllers;

use App\Models\Proceso;
use Illuminate\Http\Request;
use Inertia\Inertia;

class procesoController extends Controller
{
    //

    public function index()
    {
        return Inertia::render('Proceso/ProcesoIndex', [
            'procesos' => Proceso::with('area', 'departamento')->get(),
        ]);
    }

    public function findAll(Request $request)
    {
        $query = Proceso::query();
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        if ($filter) {

            $query->where(function ($q) use ($filter) {
                $q->where('procesos.id', 'like', '%' . $filter . '%')
                    ->orWhere('procesos.nombre', 'like', '%' . $filter . '%')
                    ->orWhere('procesos.descripcion', 'like', '%' . $filter . '%') // Se omite el filtro por link externo

                    ->orWhereHas('area', function ($q) use ($filter) {
                        $q->where('areas.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('areas.descripcion', 'like', '%' . $filter . '%');
                    })

                    ->orWhereHas('departamento', function ($q) use ($filter) {
                        $q->where('departamentos.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('departamentos.descripcion', 'like', '%' . $filter . '%');
                    });
            });
        }

        if (in_array($sortField, ['id', 'challenge', 'nombre', 'descripcion', 'link_externo', 'area.nombre', 'departamento.nombre'])) {

            if (strpos($sortField, 'area.') === 0) {
                $query->join('areas', 'challenges.area_id', '=', 'areas.id')
                    ->select('challenges.*', 'areas.nombre as area_nombre') // Select distinct columns
                    ->orderBy('areas.' . substr($sortField, 5), $sortOrder);
            } else if (strpos($sortField, 'departamento.') === 0) {
                $query->join('departamentos', 'challenges.departamento_id', '=', 'departamentos.id')
                    ->select('challenges.*', 'departamentos.nombre as departamento_nombre') // Select distinct columns
                    ->orderBy('departamentos.' . substr($sortField, 12), $sortOrder);
            } else {
                $query->orderBy($sortField, $sortOrder);
            }
        }

        $challenges = $query->with('area', 'departamento')->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($challenges);
    }
}
