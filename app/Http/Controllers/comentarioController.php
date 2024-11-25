<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;

class comentarioController extends Controller
{
    //
    public function index($minuta_id)
    {
        return  response()->json(Comentario::orderBy('created_at', 'desc')->where('minuta_id', $minuta_id)->where('user_id', auth()->user()->id)->get());
    }

    function edit(Comentario $comentario)
    {
        return response()->json($comentario);
    }

    function store(Request $request)
    {
        $user = auth()->user();
        $data = [
            'minuta_id' => $request->minuta_id,
            'user_id' => $user->id,
            'texto' => $request->texto
        ];
        Comentario::create($data);
        return response()->json(['success' => true]);
    }

    function update(Request $request, Comentario $comentario)
    {
        $comentario->update($request->all());
        return response()->json(['success' => true]);
    }

    function destroy(Comentario $comentario)
    {
        $comentario->delete();
        return response()->json(['success' => true]);
    }
}
