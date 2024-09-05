<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class seccionController extends Controller
{
    public function index()
    {
        $sercciones = Seccion::get();
        return Inertia::render('Seccion/SeccionIndex');
    }

    public function findAll(Request $request)
    {

        $query = Seccion::query();
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('secciones.id', 'like', '%' . $filter . '%')
                    ->orWhere('secciones.titulo', 'like', '%' . $filter . '%')
                    ->orWhere('secciones.descripcion', 'like', '%' . $filter . '%');
            });
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortOrder);
        }

        $result = $query->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($result);
    }

    public function create()
    {
        $secciones = Seccion::get();
        return Inertia::render('Seccion/SeccionCreate', [
            'secciones' => $secciones
        ]);
    }

    public function edit(Seccion $seccion)
    {
        $sercciones = Seccion::get();
        return Inertia::render('Seccion/SeccionEdit', [
            'seccion' => $seccion,
            'secciones' => $sercciones
        ]);
    }

    public function store(Request $request)
    {
        $data = [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'created_by' => auth()->user()->id,
        ];
        Seccion::create($data);
        return redirect()->route('seccion.index');
    }

    public function update(Seccion $seccion, Request $request)
    {

        $seccion->update($request->only('titulo', 'descripcion'));
        return redirect()->route('seccion.index');
    }

    public function destroy(Seccion $seccion)
    {

        $seccion->delete();
        return response()->json(['seccion' => Seccion::with('challenge')->get(),]);
    }
}
