<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class proveedorController extends Controller
{
    //
    function index()
    {
        return Inertia::render('Proveedor/ProveedorIndex');
    }

    function create()
    {
        return Inertia::render('Proveedor/ProveedorCreate');
    }

    function edit(Proveedor $proveedor)
    {
        return Inertia::render(
            'Proveedor/ProveedorEdit',
            ['proveedor' => $proveedor]
        );
    }

    function findAll($area_id, Request $request)
    {
        $proceso_id = $request->get('proceso_id');
        $query = Proveedor::query();

        if ($proceso_id) {
            $query->where('proceso_id', $proceso_id);
        }
        $proveedores = $query->with('area', 'proceso')->where('area_id', $area_id)->get();

        $proveedoresPorProceso = $proveedores->groupBy(function ($proveedor) {
            return $proveedor->proceso->nombre;
        });
        return response()->json($proveedoresPorProceso);
    }

    function store(Request $request)
    {
        //
        Proveedor::create($request->all());
        return redirect()->route('proveedores.index');
    }

    function update(Request $request, Proveedor $proveedor)
    {
        $proveedor->update($request->all());
        return redirect()->route('proveedores.index');
    }

    function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return response()->json(['success' => true]);
    }
}
