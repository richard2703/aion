<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\ComentarioMencion;
use Illuminate\Http\Request;

class comentarioController extends Controller
{
    //
    public function index($reporte_semanal_id)
    {
        return  response()->json(Comentario::orderBy('created_at', 'desc')->where('reporte_semanal_id', $reporte_semanal_id)->where('user_id', auth()->user()->id)->get());
    }

    function edit(Comentario $comentario)
    {
        return response()->json($comentario);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Crear el comentario
        $comentario = Comentario::create([
            'reporte_semanal_id' => $request->reporte_semanal_id,
            'user_id' => $user->id,
            'texto' => $request->texto,
        ]);

        // Guardar las menciones
        if (!empty($request->menciones)) {
            foreach ($request->menciones as $usuarioId) {
                ComentarioMencion::create([
                    'comentario_id' => $comentario->id,
                    'user_id' => $usuarioId,
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    function update(Request $request, Comentario $comentario)
    {
        $comentario->update($request->all());
        return response()->json(['success' => true]);
    }

    function destroy(Comentario $comentario)
    {
        // Delete associated records in comentario_menciones
        ComentarioMencion::where('comentario_id', $comentario->id)->delete();


        $comentario->delete();
        return response()->json(['success' => true]);
    }
}
