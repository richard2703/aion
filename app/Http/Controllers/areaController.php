<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Throwable;

class areaController extends Controller
{
    //

    public function index()
    {

        return Inertia::render('Area/AreaIndex', [
            'areas' => Area::all(),
        ]);
    }

    public function findAll(Request $request)
    {
        $pageSize = $request->get('rows', 10); // Default page size
        $page = $request->get('page', 1); // Current page
        $filter = $request->get('filter', ''); // Filter value
        $sortField = $request->get('sortField', 'id'); // Default sort field
        $sortOrder = $request->get('sortOrder', 'asc'); // Default sort order

        $query = Area::query();

        if ($filter) {
            $query->where('id', 'like', '%' . $filter . '%')
                ->orWhere('nombre', 'like', '%' . $filter . '%')
                ->orWhere('descripcion', 'like', '%' . $filter . '%');
        }

        if (in_array($sortField, ['id', 'nombre', 'descripcion'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        $areas = $query->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($areas);
    }

    function create()
    {
        return Inertia::render('Area/AreaCreate');
    }

    function store(Request $request)
    {

        $area = Area::create($request->only('nombre', 'descripcion'));
        return redirect()->route('area.index');
    }

    // function show($id)
    // {
    //     return Inertia::render('Area/AreaShow', [
    //         'area' => Area::find($id),
    //     ]);

    // }

    function edit($id)
    {
        return Inertia::render('Area/AreaEdit', [
            'area' => Area::find($id),
        ]);
    }

    function update(Request $request, $id)
    {
        $area = Area::find($id);
        $area->update($request->only('nombre', 'descripcion'));
        return redirect()->route('area.index');
    }

    function destroy($id)
    {
        $area = Area::find($id);
        $area->delete();
        return response()->json(['areas' => Area::all()]);
    }
}
