<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class empresaController extends Controller
{
    //
    function findAll()
    {
        return response()->json(Empresa::all());
    }
}
