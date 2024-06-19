<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use \App\Models\Departamento;

class departamentoController extends Controller
{
    //

    public function index()
    {

        return Inertia::render('Departamento/DepartamentoIndex', [
            'departamentos' => Departamento::with('area')->get(),
        ]);
    }

    function byArea($area_id)
    {
        return response()->json(['departamentos' => Departamento::where('area_id', $area_id)->get()]);
    }
    function findAll()
    {
        return response()->json(['departamentos' => Departamento::all()]);
    }

    function create()
    {
        return Inertia::render('Departamento/DepartamentoCreate');
    }

    function store(Request $request)
    {
        Departamento::create($request->only('nombre', 'area_id', 'descripcion'));
        return redirect()->route('departamento.index');
    }

    function edit($id)
    {
        return Inertia::render('Departamento/DepartamentoEdit', [
            'departamento' => Departamento::find($id),
        ]);
    }

    function update(Request $request, $id)
    {
        $departamento = Departamento::find($id);
        $departamento->update($request->only('nombre', 'area_id', 'descripcion'));
        return redirect()->route('departamento.index');
    }

    function destroy($id)
    {
        $departamento = Departamento::find($id);
        $departamento->delete();
        return response()->json(['departamentos' => Departamento::with('area')->get(),]);
    }
}
