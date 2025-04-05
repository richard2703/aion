<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class empresaController extends Controller
{
    function index()
    {
        return Inertia::render('Empresa/EmpresaIndex');
    }
    //
    function findAll()
    {
        return response()->json(Empresa::all());
    }

    function create()
    {
        return Inertia::render('Empresa/EmpresaCreate');
    }

    function edit(Empresa $empresa)
    {
        return Inertia::render('Empresa/EmpresaEdit');
    }

    function store(Request $request)
    {
        $empresa = Empresa::create($request->all());
        return redirect()->route('empresas.index');
    }

    function update(Request $request, Empresa $empresa)
    {
        $empresa->update($request->all());
        return redirect()->route('empresas.index');
    }

    function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return response()->json(['success' => true]);
    }
}
