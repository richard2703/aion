<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class eventoController extends Controller
{
    //
    function index()
    {
        return Inertia::render('Evento/EventoIndex', [
            'eventos' => Evento::all()
        ]);
    }

    function findAll()
    {
        return response()->json(Evento::all());
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
            'color' => $request->color
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
            'color' => $request->color
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
