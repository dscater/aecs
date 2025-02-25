<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Solicitud de atención</title>
    <style>
        html,
        body {
            color: black;
        }

        .bg-black {
            background: black;
            color: white;
        }

        .text-md {
            font-size: 1.3em;
        }

        b {
            font-size: 1.15em;
        }
    </style>
</head>

<body>

    {{-- PENDIENTE --}}
    @if ($datos['estado'] == 'PENDIENTE')
        <h1>Solicitud de Atención</h1>
        <p>Señor(es) {{ $datos['cliente']->razon_social }} su solicitud se encuentra <b>Pendiente</b>.</p>
        <p><strong>Cliente: </strong> {{ $datos['cliente']->razon_social }}</p>
        <p><strong>Personal Técnico: </strong> {{ $datos['solicitud_atencion']->personal->full_name }}</p>
        <p><strong>Descripción de atención: </strong> {{ $datos['solicitud_atencion']->descripcion }}</p>
        <p><strong>Fecha de atención y hora de llegada: </strong> {{ $datos['solicitud_atencion']->fecha_hora_t }}</p>
        <p><strong>Estado: </strong> {{ $datos['solicitud_atencion']->estado }}</p>
    @endif

    {{-- EN PROCESO --}}
    @if ($datos['estado'] == 'EN PROCESO')
        <h1>Solicitud de Atención</h1>
        <p>Señor(es) {{ $datos['cliente']->razon_social }} su solicitud se encuentra <b>En Proceso</b>.</p>
        <p><strong>Cliente: </strong> {{ $datos['cliente']->razon_social }}</p>
        <p><strong>Personal Técnico: </strong> {{ $datos['solicitud_atencion']->personal->full_name }}</p>
        <p><strong>Descripción de atención: </strong> {{ $datos['solicitud_atencion']->descripcion }}</p>
        <p><strong>Fecha de atención y hora de llegada: </strong> {{ $datos['solicitud_atencion']->fecha_hora_t }}</p>
        <p><strong>Estado: </strong> {{ $datos['solicitud_atencion']->estado }}</p>
    @endif

    {{-- ATENDIDO --}}
    @if ($datos['estado'] == 'ATENDIDO')
        <h1>Solicitud de Atención</h1>
        <p>Señor(es) {{ $datos['cliente']->razon_social }} su solicitud se encuentra <b>Finalizada</b>.</p>
        <p><strong>Cliente: </strong> {{ $datos['cliente']->razon_social }}</p>
        <p><strong>Personal Técnico: </strong> {{ $datos['solicitud_atencion']->personal->full_name }}</p>
        <p><strong>Descripción de atención: </strong> {{ $datos['solicitud_atencion']->descripcion }}</p>
        <p><strong>Fecha de atención y hora de llegada: </strong> {{ $datos['solicitud_atencion']->fecha_hora_t }}</p>
        <p><strong>Estado: </strong> {{ $datos['solicitud_atencion']->estado }}</p>
    @endif
</body>

</html>
