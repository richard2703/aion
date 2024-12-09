<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Semanal: Semana {{ $reporteSemanal->numeroSemana }}</title>

    <style>
        @page {
            margin-left: 0;
            margin-right: 0;
            margin-top: 0;
            margin-bottom: 0;
        }

        /* Global body styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            /* background-color: #f9fafb;*/
        }

        /* Report styling */
        .reporte {
            /* background-color: #f9fafb;*/
            padding: 20px;
            padding: 0px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /*margin-bottom: 20px;*/
            margin-bottom: 0px;

        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            padding: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        th {
            /* background-color: #f7fafc;*/
            font-weight: bold;
        }

        /* Custom highlight class */
        .bg-green-100 {
            /*background-color: #f0fff4;*/
        }

        h1,
        h2,
        h3 {
            margin: 0;
        }

        h1 {
            font-size: 2rem;
            text-align: center;
            color: #333;
        }

        h2 {
            font-size: 1.25rem;
            font-weight: bold;
            color: #6b46c1;
            margin-bottom: 10px;
        }

        h3 {
            font-size: 2.15rem;
            margin-bottom: 5px;
            color: #333;
        }

        ul {
            margin: 0;
            padding-left: 20px;
        }

        ul li {
            margin-bottom: 5px;
        }

        .departamento-header {
            margin-bottom: 5px;
            text-align: center;
        }

        /* Container and layout styling */
        .container {
            width: 100%;
            margin: 0 auto;
        }

        .py-2 {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        /* Custom grid-style layout for the plan sections */
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* Center the first section with custom background image */
        .report-cover {
            position: relative;
            width: 100%;
            /*height: 1100px;*/
            height: 1050px;
            text-align: center;
        }

        .report-cover img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 1200px;
            z-index: -1;
        }

        .report-title {
            position: relative;
            color: black;
            font-size: 2rem;
            z-index: 2;
            padding-top: 900px;
            text-align: right;
            padding-right: 40px;
        }

        .check-icon {
            color: green;
            font-size: 1.1em;
            padding-left: 5px;
        }
    </style>
</head>

<body>

    <div class="report-cover">
        <img src="{{ storage_path('app/public/' . $personalizar->portada) }}" alt="Logo">
        <h1 class="report-title">
            Reporte Semanal <br>
            De Actividades <br>
            Semana: {{ $reporteSemanal->numeroSemana }} <br>
            Del {{ $reporteSemanal->inicio }} al {{ $reporteSemanal->fin }}
        </h1>
    </div>

    <div class="container py-2">
        @foreach ($reportes as $reporte)
            <div class="reporte">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="2">
                                <div class="departamento-header">
                                    <h3>{{ $reporte->departamento->nombre }}</h3>
                                    <h4>{{ $reporte->usuario->name }}</h4>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="2">
                                <div class="grid">
                                    <div>
                                        <h2>Plan a 30 Dias</h2>
                                        {{--  <ul>
                                            @forelse ($reporte->treintas as $treinta)
                                                <li
                                                    style="{{ $treinta->status == 1 ? 'text-decoration: line-through;' : '' }}">
                                                    {{ $treinta->meta }}</li>
                                            @empty
                                                <p>No hay Metas a 30 días disponibles.</p>
                                            @endforelse
                                        </ul>  --}}
                                        <ul>
                                            @forelse ($reporte->treintas as $treinta)
                                                <li
                                                    style="{{ $treinta->status == 1 ? 'color: green; font-weight: bold;' : '' }}">
                                                    @if ($treinta->status == 1)
                                                        <span style="font-family: DejaVu Sans;">&#10004;</span>
                                                    @endif
                                                    {{ $treinta->meta }}

                                                </li>
                                            @empty
                                                <p>No hay Metas a 30 días disponibles.</p>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div>
                                        <h2>Plan a 60 Dias</h2>
                                        <ul>
                                            @forelse ($reporte->sesentas as $sesenta)
                                                <li
                                                    style="{{ $sesenta->status == 1 ? 'color: green; font-weight: bold; ' : '' }}">
                                                    @if ($sesenta->status == 1)
                                                        <span style="font-family: DejaVu Sans;">&#10004;</span>
                                                    @endif
                                                    {{ $sesenta->meta }}
                                                </li>
                                            @empty
                                                <p>No hay Metas a 60 días disponibles.</p>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div>
                                        <h2>Plan a 90 Dias</h2>
                                        <ul>
                                            @forelse ($reporte->noventas as $noventa)
                                                <li
                                                    style="{{ $noventa->status == 1 ? 'color: green; font-weight: bold;' : '' }}">
                                                    @if ($noventa->status == 1)
                                                        <span style="font-family: DejaVu Sans;">&#10004;</span>
                                                    @endif
                                                    {{ $noventa->meta }}
                                                </li>
                                            @empty
                                                <p>No hay Metas a 90 días disponibles.</p>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <h2>Destacado</h2>
                                <ul>
                                    @forelse ($reporte->highlights as $highlight)
                                        <li>{{ $highlight->light }}</li>
                                    @empty
                                        <p>No hay Aspecto destacado disponibles.</p>
                                    @endforelse
                                </ul>
                            </td>
                            <td>
                                <h2>Negativo</h2>
                                <ul>
                                    @forelse ($reporte->lowlights as $lowlight)
                                        <li>{{ $lowlight->light }}</li>
                                    @empty
                                        <p>No hay Aspecto negativo disponibles.</p>
                                    @endforelse
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h2>KPIs</h2>
                                @forelse ($reporte->kpis as $kpi)
                                    <table style="border-collapse: collapse; width: 100%; margin-bottom: 10px;">
                                        <thead>
                                            <tr>
                                                <th colspan="3"
                                                    style="border: 1px solid black; padding: 8px; background-color: #f3f3f3;">
                                                    {{ $kpi->titulo }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 8px;">Plan</td>
                                                <td style="border: 1px solid black; padding: 8px;">Hoy</td>
                                                <td style="border: 1px solid black; padding: 8px;">Promedio</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 8px;">{{ $kpi->objetivo }}
                                                </td>

                                                <td
                                                    style="border: 1px solid black; padding: 8px;
                                                    background-color: {{ $kpi->regla == 1 ? ($kpi->actual >= $kpi->objetivo ? '#d4edda' : '#f8d7da') : ($kpi->actual <= $kpi->objetivo ? '#d4edda' : '#f8d7da') }};">
                                                    {{ $kpi->actual }}
                                                </td>

                                                <td
                                                    style="border: 1px solid black; padding: 8px;
                                                    background-color: {{ $kpi->regla == 1 ? ($kpi->promedio >= $kpi->objetivo ? '#d4edda' : '#f8d7da') : ($kpi->promedio <= $kpi->objetivo ? '#d4edda' : '#f8d7da') }};">
                                                    {{ $kpi->promedio }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @empty
                                    <p>No hay KPIs disponibles.</p>
                                @endforelse
                            </td>
                            <td>
                                <h2>Aviso y Acciones de mejora</h2>
                                <ul>
                                    @forelse ($reporte->avisos as $aviso)
                                        <li>{{ $aviso->aviso }}</li>
                                    @empty
                                        <p>No hay Avisos disponibles.</p>
                                    @endforelse
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="page-break-after:always;"></div>
        @endforeach
    </div>

</body>

</html>
