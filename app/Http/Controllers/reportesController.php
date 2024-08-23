<?php

namespace App\Http\Controllers;

use App\Models\avisos;
use App\Models\Departamento;
use App\Models\Kpis;
use App\Models\reporteSemanal;
use App\Models\reportes;
use App\Models\lights;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;


class reportesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Reportes/ReportesIndex');
    }

    public function misReportes()
    {
        $reportes = reportes::where('created_for', auth()->id())->with(['semana', 'departamento'])->orderby('created_at', 'desc')->get();
        // dd($reportes);
        return Inertia::render('Reportes/ReportesMisReportes', ['reportes' => $reportes]);
    }

    public function create()
    {
        return Inertia::render('Reportes/ReporteCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtener el número de la semana actual
        $numeroSemana = Carbon::now()->weekOfYear;

        // Buscar el reporte semanal por el número de semana actual
        $reporteSemanal = ReporteSemanal::where('numeroSemana', $numeroSemana)->first();

        // Si no existe, crear un nuevo registro en reportesemanal
        if (!$reporteSemanal) {
            // Obtener la fecha de inicio y fin de la semana actual
            $inicioSemana = Carbon::now()->startOfWeek()->toDateString();
            $finSemana = Carbon::now()->endOfWeek()->toDateString();

            $reporteSemanal = ReporteSemanal::create([
                'numeroSemana' => $numeroSemana,
                'inicio' => $inicioSemana,
                'fin' => $finSemana,
            ]);
        }

        if (reportes::where('departamento_id', $request->departamento_id)->where('semana_id', $reporteSemanal->id)->count() > 0) {
            // dd('ya existe');
            // return Inertia::resolved('Reportes/ReporteCreate', ['reporteSemanal' => $reporteSemanal]);
            // return Inertia::render('Reportes/ReporteCreate', [
            //     'errors' => [
            //         'message' => 'Solo se puede un reporte por semana para este departamento.'
            //     ]
            // ])->withStatus(422);
            return back()->withErrors([
                'message' => 'Solo se puede un reporte por semana para este departamento.',
            ]);
        }

        // Crear el nuevo aviso en la tabla reportes
        $reporte = reportes::create([
            'departamento_id' => $request->departamento_id,
            'semana_id' => $reporteSemanal->id,
            // 'aviso' => $request->aviso,
            'created_for' => auth()->id(),

        ]);

        // Guardar los avisos
        foreach ($request->avisos as $aviso) {
            if ($aviso != null) {
                # code...
                avisos::create([
                    'departamento_id' => $request->departamento_id,
                    'semana_id' => $reporteSemanal->id,
                    'reporte_id' => $reporte->id,
                    'aviso' => $aviso,
                    'created_for' => auth()->id(), // Asignar al usuario autenticado
                ]);
            }
        }

        // Guardar los highlights
        foreach ($request->highlights as $highlight) {
            if ($highlight != null) {
                # code...
                lights::create([
                    'departamento_id' => $request->departamento_id,
                    'semana_id' => $reporteSemanal->id,
                    'reporte_id' => $reporte->id,
                    'tipo' => 1, // 1 para highlights
                    'light' => $highlight,
                    'created_for' => auth()->id(), // Asignar al usuario autenticado
                ]);
            }
        }

        // Guardar los lowlights
        foreach ($request->lowlights as $lowlight) {
            if ($lowlight != null) {
                # code...
                lights::create([
                    'departamento_id' => $request->departamento_id,
                    'semana_id' => $reporteSemanal->id,
                    'reporte_id' => $reporte->id,
                    'tipo' => 0, // 0 para lowlights
                    'light' => $lowlight,
                    'created_for' => auth()->id(), // Asignar al usuario autenticado
                ]);
            }
        }

        // Retornar una respuesta exitosa
        // return response()->json(['message' => 'Reporte creado con éxito', 'reporte' => $reporte], 201);
        return redirect()->route('reporte.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(reporteSemanal $reporte)
    {
        $reporteSemanal = $reporte;
        // dd($reporteSemanal);
        return Inertia::render('Reportes/ReporteShow', ['reporteSemanal' => $reporteSemanal]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($reporte)
    {
        $reporte = reportes::where('id', $reporte)->with(['semana', 'departamento', 'avisos', 'lowlights', 'highlights'])->orderby('created_at', 'desc')->first();
        return Inertia::render('Reportes/ReporteEdit', ['reporte' => $reporte]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $reporteId)
    {
        // dd($request);
        // Validate and update the report
        $validatedData = $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
            'avisos' => 'array',
            'highlights' => 'array',
            'lowlights' => 'array',
        ]);

        $reporte = reportes::findOrFail($reporteId);
        $reporte->update($validatedData);

        // Actualizar los highlights
        $reporte->highlights()->delete(); // Elimina los existentes
        foreach ($validatedData['highlights'] as $highlight) {
            if (!empty($highlight)) {
                $reporte->highlights()->create([
                    'light' => $highlight,
                    'tipo' => 1, // tipo 1 para highlights
                    'departamento_id' => $reporte->departamento_id,
                    'semana_id' => $reporte->semana_id,
                    'reporte_id' => $reporte->id,
                    'created_for' => auth()->id(), // Asignar al usuario autenticado
                ]);
            }
        }

        // Actualizar los lowlights
        $reporte->lowlights()->delete(); // Elimina los existentes
        foreach ($validatedData['lowlights'] as $lowlight) {
            if (!empty($lowlight)) {
                $reporte->lowlights()->create([
                    'light' => $highlight,
                    'tipo' => 1, // tipo 1 para highlights
                    'departamento_id' => $reporte->departamento_id,
                    'semana_id' => $reporte->semana_id,
                    'reporte_id' => $reporte->id,
                    'created_for' => auth()->id(), // Asignar al usuario autenticado

                ]);
            }
        }

        // Actualizar los avisos
        $reporte->avisos()->delete(); // Elimina los existentes
        foreach ($validatedData['avisos'] as $aviso) {
            if (!empty($aviso)) {
                $reporte->avisos()->create([
                    'aviso' => $aviso,
                    'departamento_id' => $reporte->departamento_id,
                    'semana_id' => $reporte->semana_id,
                    'reporte_id' => $reporte->id,
                    'created_for' => auth()->id(), // Asignar al usuario autenticado

                ]);
            }
        }

        return redirect()->route('reporte.index')->with('success', 'Reporte actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reportes $reportes)
    {
        //
    }

    public function findAll()
    {
        $query = Departamento::query();
        // if ($pageSize && $page) {
        //     $departamentos = $query->with('kpis')->paginate($pageSize, ['*'], 'page', $page);
        // } else {
        //     $departamentos = $query->with('kpis')->get();
        // }
        $reporteSemanal = $query->orderBy('nombre', 'asc')->get();

        return response()->json($reporteSemanal);
    }

    public function findAllReporteSemanal()
    {
        $query = reporteSemanal::query();
        // if ($pageSize && $page) {
        //     $departamentos = $query->with('kpis')->paginate($pageSize, ['*'], 'page', $page);
        // } else {
        //     $departamentos = $query->with('kpis')->get();
        // }
        $reporteSemanal = $query->orderBy('created_at', 'desc')->get();

        return response()->json($reporteSemanal);
    }

    public function findAllReportes($id)
    {
        $query = reportes::query();
        // if ($pageSize && $page) {
        //     $departamentos = $query->with('kpis')->paginate($pageSize, ['*'], 'page', $page);
        // } else {
        //     $departamentos = $query->with('kpis')->get();
        // }
        $reportes = $query->where('semana_id', $id)->with(['departamento', 'highlights', 'lowlights', 'avisos', 'kpis' => function ($query) {
            $query->where('tipo', 2);
        }])->get();
        // if (isset($reportes[0]->departamento_id)) {
        //     // dd($reportes[0]->departamento_id);
        //     $kpis = Kpis::where('departamento_id', $reportes[0]->departamento_id)->get();
        // }

        // dd($reportes);
        return response()->json($reportes);
    }
}
