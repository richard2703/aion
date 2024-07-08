<?php

namespace App\Http\Controllers;

use App\Models\Procedimiento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProcedimientoController extends Controller
{
    //
    public function index()
    {
        return Inertia::render('Procedimiento/ProcedimientoIndex');
    }

    public function findAll(Request $request)
    {
        $query = Procedimiento::query();
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('procedimientos.id', 'like', '%' . $filter . '%')
                    ->orWhere('procedimientos.nombre', 'like', '%' . $filter . '%')
                    ->orWhere('procedimientos.descripcion', 'like', '%' . $filter . '%')
                    ->orWhereHas('proceso', function ($q) use ($filter) {
                        $q->where('procesos.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('procesos.descripcion', 'like', '%' . $filter . '%');
                    });
            });
        }

        if (in_array($sortField, ['id', 'nombre', 'descripcion', 'proceso.nombre'])) {
            $query->join('procesos', 'procedimientos.proceso_id', '=', 'procesos.id')
                ->select('procedimientos.*', 'procesos.nombre as proceso_nombre') // Select distinct columns
                ->orderBy('procesos.' . $sortField, $sortOrder);
        }

        $procedimientos = $query->with('proceso')->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($procedimientos);
    }

    public function create()
    {
        return Inertia::render('Procedimiento/ProcedimientoCreate');
    }

    public function edit(Procedimiento $procedimiento)
    {
        return Inertia::render('Procedimiento/ProcedimientoEdit', [
            'procedimiento' => $procedimiento
        ]);
    }

    public function store(Request $request)
    {
        $procedimiento = Procedimiento::create($request->only(['proceso_id', 'nombre', 'descripcion', 'link_externo']));
        return redirect()->route('procedimiento.index');
    }

    public function update(Request $request, Procedimiento $procedimiento)
    {
        $procedimiento->update($request->only('proceso_id', 'nombre', 'descripcion', 'link_externo'));
        return redirect()->route('procedimiento.index');
    }

    public function destroy(Procedimiento $procedimiento)
    {
        $procedimiento->delete();
        return response()->json(['success' => true]);
    }
}
