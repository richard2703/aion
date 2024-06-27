<?php

namespace App\Http\Controllers;

use App\Models\Opcion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class opcionController extends Controller
{
    //
    function index()
    {
        return Inertia::render('Opcion/OpcionIndex');
    }

    function findAll(Request $request)
    {
        $query = Opcion::query(); // Replace 'Model' with the actual model name
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('id', 'like', '%' . $filter . '%')
                    ->orWhere('alias', 'like', '%' . $filter . '%')
                    ->orWhere('formal', 'like', '%' . $filter . '%')
                    ->orWhere('informal', 'like', '%' . $filter . '%')
                    ->orWhereHas('challenge', function ($q) use ($filter) {
                        $q->where('challenges.challenge', 'like', '%' . $filter . '%');
                    });
            });
        }

        if (in_array($sortField, ['id', 'alias', 'formal', 'informal', 'challenge.challenge'])) {
            if ($sortField == 'challenge.challenge') {
                $query->join('challenges', 'models.challenge_id', '=', 'challenges.id')
                    ->orderBy('challenges.challenge', $sortOrder);
            } else {
                $query->orderBy($sortField, $sortOrder);
            }
        }

        $result = $query->with('challenge')->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($result);
    }


    public function create()
    {
        return Inertia::render('Opcion/OpcionCreate');
    }

    public function store(Request $request)
    {
        Opcion::create($request->only('challenge_id', 'area_id', 'departamento_id', 'madurez', 'formal', 'informal'));
        return redirect()->route('opcion.index');
    }

    public function show($challenges)
    {
        //
    }

    public function edit($id)
    {
        $challenge = Opcion::find($id);
        return Inertia::render('Opcion/OpcionEdit', ['opcion' => $challenge]);
    }

    public function update(Request $request, $id)
    {

        $challenge = Opcion::find($id);
        $challenge->update($request->only('challenge_id', 'alias', 'formal', 'informal'));
        return redirect()->route('opcion.index');
    }

    public function destroy($id)
    {
        $challenge = Opcion::find($id);
        $challenge->delete();
        return response()->json(['opcion' => Opcion::with('challenge')->get(),]);
    }
}
