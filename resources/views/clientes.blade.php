<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
    @for($i=0; $i<5; $i++)
        {{ $i }}<br/>
    @endfor
<table border="1"><tr>
    @foreach($clientes as $cliente)
        <td>{{ $cliete->email }}</td>
    @endforeach
    </tr></table>
</body>
</html>
