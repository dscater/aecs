<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Horas por Servicios</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 1.5cm;
            margin-right: 1cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
            page-break-before: avoid;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            word-wrap: break-word;
        }

        table thead tr th {
            font-size: 7pt;
        }

        table tbody tr td {
            font-size: 6pt;
        }


        .encabezado {
            width: 100%;
        }

        .logo img {
            position: absolute;
            height: 100px;
            top: -20px;
            left: 0px;
        }

        h2.titulo {
            width: 450px;
            margin: auto;
            margin-top: 0PX;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14pt;
        }

        .texto {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .fecha {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: normal;
            font-size: 0.85em;
        }

        .total {
            text-align: right;
            padding-right: 15px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table thead {
            background: rgb(236, 236, 236)
        }

        tr {
            page-break-inside: avoid !important;
        }

        .centreado {
            padding-left: 0px;
            text-align: center;
        }

        .datos {
            margin-left: 15px;
            border-top: solid 1px;
            border-collapse: collapse;
            width: 250px;
        }

        .txt {
            font-weight: bold;
            text-align: right;
            padding-right: 5px;
        }

        .txt_center {
            font-weight: bold;
            text-align: center;
        }

        .cumplimiento {
            position: absolute;
            width: 150px;
            right: 0px;
            top: 86px;
        }

        .b_top {
            border-top: solid 1px black;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .bg-principal {
            background: #1867C0;
            color: white;
        }

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    <div class="encabezado">
        <div class="logo">
            <img src="{{ $configuracion->first()->url_logo }}">
        </div>
        <h2 class="titulo">
            {{ $configuracion->first()->razon_social }}
        </h2>
        <h4 class="texto">HORAS POR SERVICIOS</h4>
        <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
    </div>

    @foreach ($clientes as $cliente)
        <table border="1">
            <thead class="bg-principal">
                <tr>
                    <th>RAZON SOCIAL: </th>
                    <th colspan="6" style="text-align:left;">{{ $cliente->razon_social }}</th>
                </tr>
                <tr>
                    <th>CÓDIGO SERVICIO</th>
                    <th>RESPONSABLE</th>
                    <th>TIPO DE SERVICIO</th>
                    <th>FECHA</th>
                    <th>HORA INICIO</th>
                    <th>HORA FINAL</th>
                    <th>TOTAL HORAS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                    $suma_total_horas = 0;
                @endphp
                @foreach ($servicios_clientes[$cliente->id] as $servicio)
                    @php
                        $suma_total_horas += (float) $servicio->total_horas;
                    @endphp
                    <tr>
                        <td>{{ $servicio->cod }}</td>
                        <td>{{ $servicio->personal->full_name }}</td>
                        <td>{{ $servicio->tipo_servicio }}</td>
                        <td>{{ $servicio->fecha_t }}</td>
                        <td>{{ $servicio->hora_ini }}</td>
                        <td>{{ $servicio->hora_fin }}</td>
                        <td class="centreado">{{ $servicio->total_horas }}</td>
                    </tr>
                @endforeach
                <tr class="bg-principal">
                    <td colspan="6">TOTAL</td>
                    <td class="centreado">{{ $suma_total_horas }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>

</html>
