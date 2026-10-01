<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <tr>
            <th>ID Comparación</th>
            <td>{{ $compare['id'] }}</td>
        </tr>
        <tr>
            <th>Nombre</th>
            <td>{{ $compare['nombre'] }}</td>
        </tr>
        <tr>
            <th>Fecha creación</th>
            <td>{{ $compare['created_at'] }}</td>
        </tr>
        <tr>
            <th>Última actualización</th>
            <td>{{ $compare['updated_at'] }}</td>
        </tr>
    </table>

    <br>

    <table border="1">
        <thead>
            <tr>
                <th>Rank</th>
                <th>ID Variedad</th>
                <th>Variedad</th>
                <th>Padre</th>
                <th>Madre</th>
                <th>Obs1</th>
                <th>Descripción</th>
                <th>Acción</th>
                @foreach($compare['features'] as $feature)
                    <th>{{ $feature['name'] }}</th>
                    <th>{{ $feature['name'] }} STD</th>
                    <th>{{ $feature['name'] }} n</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($compare['details'] as $detail)
                <tr>
                    <td>{{ $detail['rank'] }}</td>
                    <td>{{ $detail['varity_id'] }}</td>
                    <td>{{ $detail['varity'] }}</td>
                    <td>{{ $detail['father'] }}</td>
                    <td>{{ $detail['mother'] }}</td>
                    <td>{{ $detail['free_description'] }}</td>
                    <td>{{ $detail['description'] }}</td>
                    <td>{{ $detail['action'] }}</td>
                    @foreach($detail['features'] as $feature)
                        <td>{{ $feature['value'] }}</td>
                        <td>{{ $feature['std'] }}</td>
                        <td>{{ $feature['num'] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
