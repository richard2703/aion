<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class eventoController extends Controller
{
    //
    function index()
    {
        return Inertia::render('Evento/EventoIndex', [
            'eventos' => Evento::with('area')->get()
        ]);
    }

    function findAll()
    {
        $eventos = Evento::where('fecha_inicio', '>=', date('Y-m-d'))
            ->orderBy('fecha_inicio', 'asc')
            ->with('area')
            ->get();
        return response()->json($eventos);
    }

    function edit() {}

    function show() {}

    function create() {}

    function store(Request $request)
    {
        $data = [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_final' => $request->fecha_final,
            'area_id' => $request->area_id
        ];
        Evento::create($data);
        return response()->json(['success' => true]);
    }

    function update(Request $request, Evento $evento)
    {
        $data = [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_final' => $request->fecha_final,
            'area_id' => $request->area_id
        ];
        $evento->update($data);
        return response()->json(['success' => true]);
    }

    function destroy(Evento $evento)
    {
        $evento->delete();
        return response()->json(['success' => true]);
    }
}
