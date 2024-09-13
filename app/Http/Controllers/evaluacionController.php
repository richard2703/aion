<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAsignado;
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

    public function details() {}

    public function destroy() {}

    public function update() {}

    public function store(Request $request)
    {
        $assessmentAsignado_id = $request->post('challenge1')['assessmentAsignado_id'];

        // dd($assessmentAsignado);
        // dd($request->post('challenge1')['assessmentAsignado_id']);
        foreach ($request->all() as $key => $value) {

            switch ($value['valor_opcion']) {
                case 'Nulo':
                    $valor = 5;
                    break;
                case 'Basico':
                    $valor = 30;
                    break;
                case 'Maduro':
                    $valor = 55;
                    break;
                case 'Avanzado':
                    $valor = 90;
                    break;

                default:
                    $valor = 5;
                    break;
            }
            $data = [
                'assessment_id' => $value['assessment_id'],
                'seccion_id' => $value['seccion_id'],
                'area_id' => $value['area_id'],
                'departamento_id' => $value['departamento_id'],
                'challenge_id' => $value['challenge_id'],
                'opcion_id' => $value['opcion_id'],
                'valor_opcion' => $valor,
            ];

            $assessmentAsignado = Respuesta::create($data);
        }
        if ($assessmentAsignado) {
            $assessmentAsignado = AssessmentAsignado::find($assessmentAsignado_id);
            $assessmentAsignado->update(['estatus' => 'COMPLETADO']); // INCOMPLETO, COMPLETADO

        }

        return response()->json('success', 201);
    }
}
