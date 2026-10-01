<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Player 1</th>
                <th>Player 2</th>
                <th>Team 1</th>
                <th>Team 2</th>
                <th>HT1</th>
                <th>HT2</th>
                <th>FT1</th>
                <th>FT2</th>
                <th>Spread</th>
            </tr>
        </thead>
        <tbody>
            @forelse($details as $d)
                <tr>
                    <td>{{ $d->fecha }}</td>
                    <td>{{ $d->hora }}</td>
                    <td>{{ $d->player1 }}</td>
                    <td>{{ $d->player2 }}</td>
                    <td>{{ $d->team1 }}</td>
                    <td>{{ $d->team2 }}</td>
                    <td>{{ $d->ht1 }}</td>
                    <td>{{ $d->ht2 }}</td>
                    <td>{{ $d->ft1 }}</td>
                    <td>{{ $d->ft2 }}</td>
                    <td>{{ $d->spread }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No se encontraron detalles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
