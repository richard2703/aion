<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Semanal: Semana {{ $reporteSemanal->numeroSemana }}</title>

    <!-- Agregar Bootstrap desde CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

    <style>
        body {}

        .reporte {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        h2 {
            font-size: 1.25rem;
            font-weight: bold;
            color: #6b46c1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        th {
            background-color: #f7fafc;
        }

        .bg-green-100 {
            background-color: #f0fff4;
        }

        @page: first {
            margin: 0px;
        }

        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
            padding: 0px;
        }
    </style>
    <title>Reporte Semanal: Semana {{ $reporteSemanal->numeroSemana }}</title>

</head>

<body>
    {{--  <div>
        <h1>Reporte Semanal: Semana {{ $reporteSemanal->numeroSemana }}</h1>
        <img src="{{ public_path('portadas/test.jpg') }}" alt="Logo">
    </div>  --}}

    <div style="position: relative; width: 100%; height: 1100px; text-align: center;">
        {{--  <img src="{{ public_path('portadas/portada.png') }}" alt="Logo"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 1200px; z-index: -1;">  --}}
        <img src="{{ storage_path('app/public/' . $personalizar->portada) }}" alt="Logo"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 1200px; z-index: -1;">

        {{--  <h1 style="position: relative; color: white; font-size: 3rem; z-index: 2; padding-top: 600px;">
            Reporte Semanal <br>
            Semana: {{ $reporteSemanal->numeroSemana }}
        </h1>  --}}

        <h1
            style="position: relative; color: black; font-size: 2rem; z-index: 2; padding-top: 900px; text-align: right; padding-right: 40px;">
            Reporte Semanal <br>
            De Actividades <br>
            Semana: {{ $reporteSemanal->numeroSemana }}
        </h1>
    </div>

    <div class="container py-2">
        @foreach ($reportes as $reporte)
            <div class="reporte">
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th colspan="2"> <!-- Nombre del departamento -->
                                <div class="departamento-header">
                                    <h3 class="text-lg font-bold">{{ $reporte->departamento->nombre }}</h3>
                                    {{--  <h3 class="text-lg font-bold">
                                        {{ storage_path('app/public/' . $personalizar->portada) }}</h3>
                                    <h3 class="text-lg font-bold">{{ storage_path('app/public/portadas/test.jpg') }}
                                    </h3>  --}}

                                    {{--  @if ($reporte->departamento->deleted_at)
                                        <p class="text-danger " style="size: 15px">Este Flujo ha sido eliminado.</p>
                                    @endif  --}}
                                </div>
                            </th>
                        </tr>
                        <tr colspan="3">
                            <th style="text-align: center;">
                                <div style="display: grid; justify-content: space-between;" class="grid grid-cols-3">
                                    <div class="col-4 text-center" style="grid-column: 1;">
                                        <h2>30</h2>
                                        <ul class="mt-2">
                                            @forelse ($reporte->treintas as $treinta)
                                                <li>{{ $treinta->meta }}</li>
                                            @empty
                                                <p>No hay Metas a 30 días disponibles.</p>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div class="col-4 text-center" style="grid-column: 2;">
                                        <h2>60</h2>
                                        <ul class="mt-2">
                                            @forelse ($reporte->sesentas as $sesenta)
                                                <li>{{ $sesenta->meta }}</li>
                                            @empty
                                                <p>No hay Metas a 60 días disponibles.</p>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div class="col-4 text-center" style="grid-column: 3;">
                                        <h2>90</h2>
                                        <ul class="mt-2">
                                            @forelse ($reporte->noventas as $noventa)
                                                <li>{{ $noventa->meta }}</li>
                                            @empty
                                                <p>No hay Metas a 90 días disponibles.</p>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-6">
                                <div class="col-12">
                                    <h2>Highlights</h2>
                                    <ul class="mt-2">
                                        @forelse ($reporte->highlights as $highlight)
                                            <li>{{ $highlight->light }}</li>
                                        @empty
                                            <p>No hay Highlight disponibles.</p>
                                        @endforelse
                                    </ul>
                                </div>
                            </td>
                            <td class="col-6">
                                <div class="col-12">
                                    <h2>Lowlights</h2>
                                    <ul class="mt-2">
                                        @forelse ($reporte->lowlights as $lowlight)
                                            <li>{{ $lowlight->light }}</li>
                                        @empty
                                            <p>No hay Lowlights disponibles.</p>
                                        @endforelse
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-6">
                                <div class="col-12">
                                    <h2>Kpis</h2>
                                    {{--  @foreach ($reporte->kpis as $kpi)  --}}
                                    @forelse ($reporte->kpis as $kpi)
                                        <table class="table table-bordered mt-3">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">{{ $kpi->titulo }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Plan</td>
                                                    <td>Hoy</td>
                                                    <td>Promedio</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $kpi->objetivo }}</td>
                                                    {{--  <td class="bg-green-100">{{ $kpi->actual }}</td>  --}}
                                                    <td class="bg-green-100">{{ $kpi->actual }}</td>
                                                    <td class="bg-green-100">{{ $kpi->promedio }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @empty
                                        <p>No hay kpis disponibles.</p>
                                    @endforelse
                                </div>
                            </td>
                            <td class="col-6">
                                <div class="col-12">
                                    <h2>Avisos</h2>
                                    <ul class="mt-2">
                                        @forelse ($reporte->avisos as $aviso)
                                            <li>{{ $aviso->aviso }}</li>
                                        @empty
                                            <p>No hay Avisos disponibles.</p>
                                        @endforelse
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <!-- Agregar el script de Bootstrap para funcionalidades como modales, popovers, etc. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
