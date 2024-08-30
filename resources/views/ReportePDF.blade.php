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
    </style>
    <title>Reporte Semanal: Semana {{ $reporteSemanal->numeroSemana }}</title>

</head>

<body>
    {{--  <title>Reporte Semanal: Semana {{ $reporteSemanal->numeroSemana }}</title>  --}}
    <div>
        <h1>Reporte Semanal: Semana {{ $reporteSemanal->numeroSemana }}</h1>
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
                                    {{--  @if ($reporte->departamento->deleted_at)
                                        <p class="text-danger " style="size: 15px">Este Flujo ha sido eliminado.</p>
                                    @endif  --}}
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{--  <tr>
                            <td>
                                <h2>Highlights</h2>
                            </td>
                            <td>
                                <h2>Lowlights</h2>
                            </td>
                        </tr>  --}}
                        <tr>
                            <td>
                                <div class="col-6">
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
                            <td>
                                <div class="col-6">
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
                            <td>
                                <div class="col-6">
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
                            <td>
                                <div class="col-6">
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