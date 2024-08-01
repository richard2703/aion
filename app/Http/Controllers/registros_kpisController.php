<?php

namespace App\Http\Controllers;

use App\Models\Kpis;
use App\Models\registros_kpi;
use Illuminate\Http\Request;

class registros_kpisController extends Controller
{
    public function store(Request $request)
    {
        // dd($request);
        $registro_kpi = new registros_kpi();
        $registro_kpi->kpi_id = $request->kpi_id;
        $registro_kpi->actual = $request->actual;
        $registro_kpi->created_for = auth()->user()->id;

        $kpi = Kpis::find($request->kpi_id);
        $kpi->actual = $request->actual;
        $kpi->save();

        $registro_kpi->save();
        return response()->json(['success' => 'KPI Creado'], 200);
    }
}
