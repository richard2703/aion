<?php

namespace App\Http\Controllers;

use App\Models\AreaEvaluacion;
use App\Models\Assessment;
use App\Models\AssessmentAsignado;
use App\Models\DepartamentoEvaluacion;
use App\Models\Respuesta;
use App\Models\Seccion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class evaluacionController extends Controller
{

    public function index()
    {
        return Inertia::render('Evaluacion/EvaluacionIndex');
    }

    public function findAll(Request $request)
    {

        $query = Assessment::query();
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        // if ($filter) {
        //     $query->where(function ($q) use ($filter) {
        //         $q->where('assessments.id', 'like', '%' . $filter . '%')
        //             ->orWhere('assessments.titulo', 'like', '%' . $filter . '%');
        //     });
        // }

        // $query->withCount(['AssessmentAsignado as seccion_incompleta' => function ($query) {
        //     $query->where('estatus', 'INCOMPLETO');
        // }]);

        $query->withCount('AssessmentAsignado as seccion_total');

        $query->withCount(['AssessmentAsignado as seccion_completa' => function ($query) {
            $query->where('estatus', 'COMPLETADO');
        }]);

        if ($sortField) {
            $query->orderBy($sortField, $sortOrder);
        }

        $result = $query->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($result);
    }

    public function edit() {}

    public function create()
    {
        $assessment = new Assessment;
        $assessment->created_by = auth()->user()->id;
        $assessment->save();

        $seecciones = Seccion::get();
        foreach ($seecciones as $key => $seccion) {
            AssessmentAsignado::create([
                'assessment_id' => $assessment->id,
                'seccion_id' => $seccion->id,
                'estatus' => 'INCOMPLETO',
            ]);
        }

        return redirect(route('evaluacion.select', $assessment->id));
        // return Inertia::render('Evaluacion/EvaluacionCreate');
    }

    public function show(Assessment $evaluacion) {}

    public function select(Assessment $evaluacion)
    {
        $evaluacion->load(
            'AssessmentAsignado',
            'AssessmentAsignado.seccion',
            'AssessmentAsignado.seccion.departamento',
            'AssessmentAsignado.seccion.area'
        );
        $AssessmentAsignado = AssessmentAsignado::with('assessment', 'seccion')
            ->where('assessment_id', $evaluacion->id)
            ->get();

        return Inertia::render(
            'Evaluacion/EvaluacionSelector',
            ['evaluacion' => $evaluacion,]
        );
    }

    public function form(AssessmentAsignado $assessmentAsignado)
    {
        $assessmentAsignado->load(
            'assessment',
            'seccion.challenge.opciones',
        );

        return Inertia::render(
            'Evaluacion/EvaluacionForm',
            ['assessmentAsignado' => $assessmentAsignado,]
        );
    }

    public function details(Assessment $evaluacion)
    {

        return Inertia::render('Evaluacion/EvaluacionBenchmark', ['evaluacion' => $evaluacion]);
    }

    public function destroy() {}

    public function update() {}

    public function store(Request $request)
    {
        $assessmentAsignadoId = $request->post('challenge1')['assessmentAsignado_id'];
        $assessmentId = $request->post('challenge1')['assessment_id'];

        foreach ($request->all() as $key => $value) {
            $score = match ($value['valor_opcion']) {
                'Nulo' => 5,
                'Basico' => 30,
                'Maduro' => 55,
                'Avanzado' => 90,
                default => 5,
            };

            $data = [
                'assessment_id' => $value['assessment_id'],
                'seccion_id' => $value['seccion_id'],
                'area_id' => $value['area_id'],
                'departamento_id' => $value['departamento_id'],
                'challenge_id' => $value['challenge_id'],
                'opcion_id' => $value['opcion_id'],
                'valor_opcion' => $score,
            ];

            $assessmentRespuestas = Respuesta::create($data);
        }

        if ($assessmentRespuestas) {
            $assessmentAsignado = AssessmentAsignado::find($assessmentAsignadoId);
            $assessmentAsignado->update(['estatus' => 'COMPLETADO']);

            $countIncompleted = AssessmentAsignado::where('assessment_id', $assessmentId)
                ->where('estatus', '=', 'INCOMPLETO')
                ->count();

            if ($countIncompleted === 0) {
                $promedioDepartamentos = Respuesta::where('assessment_id', $assessmentId)
                    ->select()
                    ->get()
                    ->groupBy('departamento_id')
                    ->map(fn($group) => $group->avg('valor_opcion'));

                foreach ($promedioDepartamentos as $departamentoId => $score) {
                    DepartamentoEvaluacion::create([
                        'assessment_id' => $assessmentId,
                        'departamento_id' => $departamentoId,
                        'score' => $score,
                    ]);
                }

                $promedioArea = Respuesta::where('assessment_id', $assessmentId)
                    ->get()
                    ->groupBy('area_id')
                    ->map(fn($group) => $group->avg('valor_opcion'));

                foreach ($promedioArea as $areaId => $score) {
                    AreaEvaluacion::create([
                        'assessment_id' => $assessmentId,
                        'area_id' => $areaId,
                        'score' => $score,
                    ]);
                }
            }
        }

        return response()->json('success', 201);
    }


    function radarChart() {}
}
