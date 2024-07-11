<?php

namespace App\Http\Controllers;

use App\Models\Kpis;
use Illuminate\Http\Request;
use Inertia\Inertia;

class kpiController extends Controller
{
    //
    function index()
    {
        return Inertia::render('Kpi/KpiIndex');
    }

    public function findAll(Request $request)
    {
        $query = Kpis::query();
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        // Filter logic
        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('kpis.id', 'like', '%' . $filter . '%')
                    ->orWhere('kpis.nombre', 'like', '%' . $filter . '%')
                    ->orWhere('kpis.descripcion', 'like', '%' . $filter . '%')
                    ->orWhere('kpis.link_externo', 'like', '%' . $filter . '%')
                    ->orWhereHas('procedimiento', function ($q) use ($filter) {
                        $q->where('procedimientos.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('procedimientos.descripcion', 'like', '%' . $filter . '%');
                    });
            });
        }

        // Sorting logic
        if (in_array($sortField, ['id', 'nombre', 'descripcion', 'link_externo', 'procedimiento.nombre'])) {
            if (strpos($sortField, 'procedimiento.') === 0) {
                $query->join('procedimientos', 'kpis.procedimiento_id', '=', 'procedimientos.id')
                    ->select('kpis.*', 'procedimientos.nombre as procedimiento_nombre') // Select distinct columns
                    ->orderBy('procedimientos.nombre', $sortOrder);
            } else {
                $query->orderBy('kpis.' . $sortField, $sortOrder);
            }
        } else {
            // Default sorting if the provided sortField is not allowed
            $query->orderBy('id', $sortOrder);
        }

        $kpis = $query->with('procedimiento')->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($kpis);
    }

    public function create()
    {
        return Inertia::render('Kpi/KpiCreate');
    }

    public function edit(Kpis $kpis)
    {
        return Inertia::render('Kpis/KpisEdit', [
            'Kpis' => $kpis
        ]);
    }

    public function store(Request $request)
    {
        $kpis = Kpis::create($request->only(['procedimiento_id', 'nombre', 'descripcion', 'link_externo']));
        return redirect()->route('kpi.index');
    }

    public function update(Request $request, Kpis $kpis)
    {
        $kpis->update($request->only('procedimiento_id', 'nombre', 'descripcion', 'link_externo'));
        return redirect()->route('kpi.index');
    }

    public function destroy(Kpis $kpis)
    {
        $kpis->delete();
        return response()->json(['success' => true]);
    }
}
