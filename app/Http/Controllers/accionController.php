<?php

namespace App\Http\Controllers;

use App\Models\Accion;
use Illuminate\Http\Request;

class accionController extends Controller
{
    //
    public function index()
    {
        // return Inertia::render('Acciones/Index');
    }

    public function create()
    {
    }

    public function edit(Accion $accion)
    {
        return response()->json($accion);
    }

    public function store(Request $request)
    {
        Accion::create($request->only('proceso_id', 'area_id', 'tipo_id', 'titulo', 'link'));
        return response()->json(['success' => true]);
    }

    public function update(Request $request, Accion $accion)
    {
        $accion->update($request->only('proceso_id', 'area_id', 'tipo_id', 'titulo', 'link'));
        return response()->json(['success' => true]);
    }

    public function destroy(Accion $accion)
    {
        $accion->delete();
        return response()->json(['success' => true]);
    }

    public function byArea($area_id, $tipo)
    {
        // dd($area_id, $tipo);
        switch ($tipo) {
            case 'correctiva':
                $tipo_id = 1;
                break;

            case 'preventiva':
                $tipo_id = 2;
                break;

            case 'mejora':
                $tipo_id = 3;
                break;

            default:
                $tipo_id = 1;
                break;
        }

        $accion = Accion::with('area', 'proceso', 'tipo')->where('area_id', $area_id)->where('tipo_id', $tipo_id)->orderBy('proceso_id', 'asc')->get();
        return response()->json($accion);
    }
}
