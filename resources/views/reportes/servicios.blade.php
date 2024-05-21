<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Servicios</title>
    <style type="text/css">
        * {
            font-family: "Gill Sans", "Gill Sans MT", "Trebuchet MS", sans-serif !important;
        }

        @page {
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 1.5cm;
            margin-right: 1cm;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        {
        color: #1867C0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-before: avoid;
            border-color: #0854ac;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            word-wrap: break-word;
            border: solid 0.5px;
            border-color: #1867C0;
        }

        table thead tr th {
            font-size: 7pt;
        }

        table tbody tr td {
            font-size: 6.8pt;
            font-weight: 700;
            color: #1867C0;
        }


        .encabezado {
            width: 100%;
        }

        .logo {
            text-align: center;
        }

        .logo img {
            height: 80px;
        }

        .empresa {
            text-align: center;
            font-size: 14pt;
            width: 60%;
        }

        .cod {
            color: red;
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
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

        .nueva_pagina {
            page-break-after: always;
        }

        .cell_text_largo {
            display: block;
            min-height: 40px;
        }

        .contenedor_firma {
            padding: 60px 0px 10px 0px;
        }

        .contenedor_firma .firma {
            color: #1867C0;
            font-weight: 700;
            padding-top: 10px;
            border-top: solid 1px #1867C0;
        }

        .celda_texto {
            background: #dbe9fa;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @php
        $cont = 1;
    @endphp
    @foreach ($servicios as $servicio)
        <table>
            <tbody>
                <tr>
                    <td class="logo">
                        <img src="{{ $configuracion->first()->url_logo }}">
                    </td>
                    <td class="empresa">
                        {{ $configuracion->first()->razon_social }}
                        <br />
                        NOTA DE SERVICIO
                    </td>
                    <td class="cod">
                        {{ $servicio->cod }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td rowspan="2" class="centreado">FECHA</td>
                    <td class="bg-principal centreado">DÍA</td>
                    <td class="bg-principal centreado">MES</td>
                    <td class="bg-principal centreado">AÑO</td>
                    <td width="4%" style="border-bottom:none;border-top:none;"></td>
                    <td rowspan="2" class="centreado">HORA</td>
                    <td class="bg-principal centreado">INICIAL</td>
                    <td class="bg-principal centreado">FINAL</td>
                    <td width="2%" style="border-bottom:none;border-top:none;"></td>
                    <td class="bg-principal centreado">TIEMPO</td>
                    <td>{{ $servicio->total_horas }}h.</td>
                </tr>
                <tr>
                    <td class="centreado">{{ date('d', strtotime($servicio->fecha)) }}</td>
                    <td class="centreado">{{ date('m', strtotime($servicio->fecha)) }}</td>
                    <td class="centreado">{{ date('Y', strtotime($servicio->fecha)) }}</td>
                    <td style="border-top:none;border-bottom:none;"></td>
                    <td class="centreado">{{ date('H:i', strtotime($servicio->hora_ini)) }}</td>
                    <td class="centreado">{{ date('H:i', strtotime($servicio->hora_fin)) }}</td>
                    <td style="border-top:none;border-bottom:none;"></td>
                    <td class="bg-principal">COST./HOR.</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td class="bg-principal" colspan="4">DATOS DEL CLIENTE</td>
                </tr>
                <tr>
                    <td width="13%" class="celda_texto">RAZÓN SOCIAL:</td>
                    <td>{{ $servicio->cliente->razon_social }}</td>
                    <td width="13%" class="celda_texto">RESPONSABLE:</td>
                    <td>{{ $servicio->personal->full_name }}</td>
                </tr>
                <tr>
                    <td class="celda_texto">DIRECCIÓN:</td>
                    <td>{{ $servicio->cliente->dir }}</td>
                    <td class="celda_texto">TIPO:</td>
                    <td>{{ $servicio->cliente->tipo }}</td>
                </tr>
                <tr>
                    <td class="celda_texto">DESCRIPCIÓN:</td>
                    <td colspan="3">{{ $servicio->cliente->descripcion }}</td>
                </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td class="bg-principal" colspan="4">DATOS DEL EQUIPO</td>
                    <td class="celda_texto">UBICACIÓN:</td>
                    <td>{{ $servicio->ubicacion }}</td>
                </tr>
                <tr>
                    <td class="celda_texto">TIPO:</td>
                    <td>{{ $servicio->tipo }}</td>
                    <td class="celda_texto">MARCA</td>
                    <td>{{ $servicio->marca }}</td>
                    <td class="celda_texto"># SERIE</td>
                    <td>{{ $servicio->nro_serie }}</td>
                </tr>
                <tr>
                    <td class="celda_texto">MODELO:</td>
                    <td>{{ $servicio->modelo }}</td>
                    <td class="celda_texto"># PARTE</td>
                    <td>{{ $servicio->nro_parte }}</td>
                    <td class="celda_texto"># ACTIVO</td>
                    <td>{{ $servicio->nro_activo }}</td>
                </tr>
                <tr>
                    <td class="celda_texto">TIPO DE SERVICIO:</td>
                    <td colspan="5">{{ $servicio->tipo_servicio }}</td>
                </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td class="bg-principal">PROBLEMA REPORTADO SEGÚN EL CLIENTE</td>
                </tr>
                <tr>
                    <td class="cell_text_largo">{{ $servicio->problema }}</td>
                </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td class="bg-principal">TRABAJOS REALIZADOS</td>
                </tr>
                <tr>
                    <td class="cell_text_largo">{{ $servicio->trabajo_realizado }}</td>
                </tr>
            </tbody>
        </table>
        <table>
            <tr>
                <td class="celda_texto" width="13%">TIPO DE TRABAJO:</td>
                <td>{{ $servicio->tipo_trabajo }}</td>
            </tr>
        </table>
        <table>
            <tbody>
                <tr>
                    <td class="bg-principal">PARTES UTILIZADAS</td>
                </tr>
                <tr>
                    <td class="cell_text_largo">{{ $servicio->partes }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td class="bg-principal" colspan="2">CONFORMIDAD Y AUTORIZACIÓN DE COSTO</td>
                </tr>
                <tr>
                    <td class="centreado contenedor_firma">
                        <div class="firma">TÉCNICO</div>
                    </td>
                    <td class="centreado contenedor_firma">
                        <div class="firma">CLIENTE</div>
                    </td>
                </tr>
            </tbody>
        </table>
        @if ($cont < count($servicios))
            <div class="nueva_pagina"></div>
        @endif

        @php
            $cont++;
        @endphp
    @endforeach
</body>

</html>
