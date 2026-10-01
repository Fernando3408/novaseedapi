<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Potato Vision</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #1f2933; }
        .grid { display: grid; grid-template-columns: repeat(4, minmax(120px, 1fr)); gap: 12px; }
        .card { border: 1px solid #d9e2ec; border-radius: 6px; padding: 14px; background: #fff; }
        .label { font-size: 12px; color: #627d98; text-transform: uppercase; }
        .value { font-size: 28px; font-weight: bold; margin-top: 6px; }
        table { border-collapse: collapse; width: 100%; margin-top: 16px; }
        th, td { border-bottom: 1px solid #d9e2ec; padding: 8px; text-align: left; }
        select, button { padding: 8px; }
        .error { color: #b42318; margin-top: 12px; }
    </style>
</head>
<body>
    <h1>Potato Vision</h1>
    <form method="get">
        <select name="session_id">
            <option value="">Seleccione sesion</option>
            @foreach ($sessions as $session)
                <option value="{{ $session->id }}" {{ (string) $sessionId === (string) $session->id ? 'selected' : '' }}>
                    #{{ $session->id }} {{ $session->lot_code }} {{ $session->line_name }}
                </option>
            @endforeach
        </select>
        <button type="submit">Ver</button>
    </form>

    <div id="empty" class="error" style="display:none;">No hay datos para mostrar.</div>
    <div id="error" class="error" style="display:none;">No se pudo cargar metricas.</div>

    <div class="grid" style="margin-top:18px;">
        <div class="card"><div class="label">Conteo total</div><div id="total" class="value">0</div></div>
        <div class="card"><div class="label">Papas/min</div><div id="ppm" class="value">0</div></div>
        <div class="card"><div class="label">Dispositivo</div><div id="device" class="value">-</div></div>
        <div class="card"><div class="label">Anomalias</div><div id="anomalies" class="value">0</div></div>
    </div>

    <h2>Colores</h2>
    <table><tbody id="colors"></tbody></table>

    <h2>Ultimos eventos</h2>
    <table>
        <thead><tr><th>ID</th><th>Color</th><th>Anomalia</th><th>Capturado</th></tr></thead>
        <tbody id="events"></tbody>
    </table>

    <script>
        var sessionId = {!! json_encode($sessionId) !!};
        function render(data) {
            document.getElementById('empty').style.display = data.total ? 'none' : 'block';
            document.getElementById('total').innerText = data.total || 0;
            document.getElementById('ppm').innerText = data.potatoes_per_minute || 0;
            document.getElementById('device').innerText = data.device_status || '-';
            document.getElementById('anomalies').innerText = (data.anomalies || 0) + ' (' + (data.anomaly_percent || 0) + '%)';
            document.getElementById('colors').innerHTML = (data.colors || []).map(function (row) {
                return '<tr><td>' + row.color_class + '</td><td>' + row.total + '</td></tr>';
            }).join('');
            document.getElementById('events').innerHTML = (data.recent_events || []).map(function (row) {
                return '<tr><td>' + row.event_id + '</td><td>' + row.color_class + '</td><td>' + row.anomaly_score + '</td><td>' + row.captured_at + '</td></tr>';
            }).join('');
        }
        function poll() {
            if (!sessionId) return;
            fetch('/potato-vision/dashboard/sessions/' + sessionId + '/metrics')
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    document.getElementById('error').style.display = 'none';
                    render(data);
                })
                .catch(function () { document.getElementById('error').style.display = 'block'; });
        }
        poll();
        setInterval(poll, 1000);
    </script>
</body>
</html>
