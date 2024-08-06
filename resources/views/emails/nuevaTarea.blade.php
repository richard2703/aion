<!DOCTYPE html>
<html>

<head>
    <title>Nueva Tarea</title>
</head>

<body>

    <h1>Se ha creado una nueva</h1>
    <hr>
    <br>
    <p>
        Se ha creado una nueva tarea, en la que se te ha etiquetado.
        <br>
        Titulo: <a href="http://localhost:8000/tareas/{{ $mailData['id'] }}/detalle"> {{ $mailData['tarea'] }}</a>
        <br>
        Fecha de entrega: {{ $mailData['fecha'] }}
        <br>


    </p>
    <br>
    <p>verificar detalles en <a href="http://localhost:8000">AION</a></p>
</body>

</html>
