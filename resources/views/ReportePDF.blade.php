<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Semanal: Semana {{ $reporteSemanal->numeroSemana }}</title>

    <style>
        /* Global body styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
        }

        /* Report styling */
        .reporte {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        /* Table styling */
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
            font-weight: bold;
        }

        /* Custom highlight class */
        .bg-green-100 {
            background-color: #f0fff4;
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
            font-size: 1.15rem;
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
            margin-bottom: 20px;
        }

        /* Container and layout styling */
        .container {
            width: 90%;
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
            height: 1100px;
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
    </style>
</head>

<body>

    <div class="report-cover">
        <img src="{{ storage_path('app/public/' . $personalizar->portada) }}" alt="Logo">
        <h1 class="report-title">
            Reporte Semanal <br>
            De Actividades <br>
            Semana: {{ $reporteSemanal->numeroSemana }}
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
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <div class="grid">
                                    <div>
                                        <h2>Plan a 30 Dias</h2>
                                        <ul>
                                            @forelse ($reporte->treintas as $treinta)
                                                <li>{{ $treinta->meta }}</li>
                                            @empty
                                                <p>No hay Metas a 30 días disponibles.</p>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div>
                                        <h2>Plan a 60 Dias</h2>
                                        <ul>
                                            @forelse ($reporte->sesentas as $sesenta)
                                                <li>{{ $sesenta->meta }}</li>
                                            @empty
                                                <p>No hay Metas a 60 días disponibles.</p>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div>
                                        <h2>Plan a 90 Dias</h2>
                                        <ul>
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
                                <h2>Kpis</h2>
                                @forelse ($reporte->kpis as $kpi)
                                    <table class="table">
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
                                                <td class="bg-green-100">{{ $kpi->actual }}</td>
                                                <td class="bg-green-100">{{ $kpi->promedio }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @empty
                                    <p>No hay kpis disponibles.</p>
                                @endforelse
                            </td>
                            <td>
                                <h2>Avisos y Acciones</h2>
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
        @endforeach
    </div>

</body>

</html>
