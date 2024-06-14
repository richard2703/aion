<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Throwable;

// FIXME: Revisar los controladores hay un bug al hacer los retornos
class areaController extends Controller
{
    //

    public function index()
    {

        return Inertia::render('Area/AreaIndex', [
            'areas' => Area::all(),
        ]);
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

        return Inertia::render('Area/AreaIndex', [
            'areas' => Area::all(),
        ]);
    }

    function destroy($id)
    {
        $area = Area::find($id);
        $area->delete();
        return response()->json(['areas' => Area::all()]);
    }
}
