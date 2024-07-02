<?php

namespace App\Http\Controllers;

use App\Models\objetivos;
use Illuminate\Http\Request;
use Inertia\Inertia;

class objetivosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd("objetivos");
        return Inertia::render('Objetivos/ObjetivosIndex');
    }

    function findAll(Request $request)
    {
        $query = objetivos::query(); // Replace 'Model' with the actual model name
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('id', 'like', '%' . $filter . '%')
                    ->orWhere('titulo', 'like', '%' . $filter . '%')
                    ->orWhere('objetivo', 'like', '%' . $filter . '%');
                // ->orWhere('informal', 'like', '%' . $filter . '%');
                // ->orWhereHas('challenge', function ($q) use ($filter) {
                //     $q->where('challenges.challenge', 'like', '%' . $filter . '%');
                // });
            });
        }

        // if (in_array($sortField, ['id', 'alias', 'formal', 'informal', 'challenge.challenge'])) {
        //     if ($sortField == 'challenge.challenge') {
        //         $query->join('challenges', 'models.challenge_id', '=', 'challenges.id')
        //             ->orderBy('challenges.challenge', $sortOrder);
        //     } else {
        //         $query->orderBy($sortField, $sortOrder);
        //     }
        // }
        if (in_array($sortField, ['id', 'titulo', 'objetivo'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        // $result = $query->with('challenge')->paginate($pageSize, ['*'], 'page', $page);
        $result = $query->paginate($pageSize, ['*'], 'page', $page);


        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Objetivos/ObjetivoCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $objetivos = objetivos::create($request->only('titulo', 'objetivo'));
        return redirect()->route('objetivo.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(objetivos $objetivos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(objetivos $objetivo)
    {
        dd($objetivo->titulo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, objetivos $objetivos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(objetivos $objetivo)
    {
        // $departamento = Departamento::find($id);
        // dd($objetivo);
        $objetivo->delete();
        // return response()->json(['departamentos' => Departamento::with('area')->get(),]);
        return response()->json(['objetivos' => "ok"]);
    }
}
