<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class testController extends Controller
{
    public function primero()
    {
        // return response()->json(['message' => 'Hello World'], 200);
        return Inertia::render('test/HelloWorld', [
            'message' => 'Hello World'
        ]);
    }
    public function cambiarMensaje()
    {
        return response()->json(['message' => 'Hola Mundo']);
    }
}
