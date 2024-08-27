<?php

namespace App\Http\Controllers;

use App\Models\Kpis;
use App\Models\registros_kpi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class registros_kpisController extends Controller
{
    public function store(Request $request)
    {
        // Registrar valor de kpi
        $registro_kpi = new registros_kpi();
        $registro_kpi->kpi_id = $request->kpi_id;
        $registro_kpi->actual = $request->actual;
        $registro_kpi->created_for = auth()->user()->id;
        $registro_kpi->save();

        // Actualizar promedio y actual de kpi
        $promedio = registros_kpi::where('kpi_id', $request->kpi_id)
            ->latest('created_at')
            ->take(12)
            ->avg('actual');
        $kpi = Kpis::find($request->kpi_id);
        $kpi->promedio = $promedio;
        $kpi->actual = $request->actual;
        $kpi->save();

        return response()->json(['success' => 'KPI Creado'], 200);
    }

    public function edit(registros_kpi $registros_kpi)
    {
        return response()->json($registros_kpi);
    }

    public function update(Request $request, registros_kpi $registros_kpi)
    {
        $registros_kpi->update($request->only('actual'));

        $promedio = registros_kpi::where('kpi_id', $registros_kpi->kpi_id)
            ->latest('created_at')
            ->take(12)
            ->avg('actual');

        $kpi = Kpis::find($registros_kpi->kpi_id);
        $kpi->promedio = $promedio;
        $kpi->save();

        return response()->json(['success' => true]);
    }

    public function promedio()
    {

        // $fechaInicio = Carbon::now()->subMonths(12);

        // $promedio = registros_kpi::where('kpi_id', $id)
        //     ->where('created_at', '>=', $fechaInicio)
        //     ->avg('actual');

        $promedio = registros_kpi::where('kpi_id', 4)
            ->latest('created_at')
            ->take(12)
            // ->get();
            ->avg('actual');
        return response()->json(['promedio' => $promedio], 200);
    }

    public function registros($id)
    {
        $registros = registros_kpi::select(
            'id',
            'kpi_id',
            'actual',
            'promedio',
            DB::raw("DATE_FORMAT(created_at, '%d %m %y') as mes")
        )->where('kpi_id', $id)->get();

        Carbon::setLocale('es');

        return response()->json($registros);
    }
}
