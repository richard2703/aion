<?php

namespace App\Http\Controllers;

use App\Models\minutas;
use Illuminate\Http\Request;
use Inertia\Inertia;

class minutasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd("objetivos");
        return Inertia::render('Minutas/MinutasIndex');
    }

    function findAll(Request $request)
    {
        $query = minutas::query(); // Replace 'Model' with the actual model name
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('id', 'like', '%' . $filter . '%')
                    ->orWhere('alias', 'like', '%' . $filter . '%')
                    ->orWhere('tareas', 'like', '%' . $filter . '%');
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
        $result = $query->with('area', 'departamento')->paginate($pageSize, ['*'], 'page', $page);
        return response()->json($result);
    }

    public function create()
    {
        return Inertia::render('Minutas/MinutasCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        // $minuta = minutas::create($request->only(`alias`, `tipo`, `tareas`, `responsable`, `notas`, `estatus`));
        $minuta = minutas::create($request->all());


        return redirect()->route('minutas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(minutas $minutas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(minutas $minutas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, minutas $minutas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(minutas $minutas)
    {
        //
    }
}
