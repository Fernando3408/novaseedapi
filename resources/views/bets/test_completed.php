@extends('layouts.app', ['title' => 'Testing', 'subtitle' => 'Listado de Partidos'])

@section('content')
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__body">
        {{-- Filtro de Fecha --}}
        {!! Form::open(['route' => 'bets.test', 'method' => 'GET', 'id' => 'FormFilter', 'class' => 'm-form']) !!}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <strong>Fecha</strong>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ request('fecha') }}">
                </div>
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-outline-success" style="margin-top: 15px;">Filtrar Partidos</button>
            </div>
        </div>
        {!! Form::close() !!}

        <hr>

        {{-- Tabla de Partidos --}}
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Player 1</th>
                        <th>Team 1</th>
                        <th>Player 2</th>
                        <th>Team 2</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>HT1</th>
                        <th>HT2</th>
                        <th>HT Total</th>
                        <th>Market</th>
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partidos as $index => $partido)
                    @php
                      $htt = ($partido->ht1 + $partido->ht2);
                    @endphp
                    <tr data-player1="{{ $partido->player1 }}"
                        data-player2="{{ $partido->player2 }}"
                        data-ht1="{{ $partido->ht1 }}"
                        data-ht2="{{ $partido->ht2 }}"
                        data-fecha="{{ $partido->fecha }}"
                        data-hora="{{ $partido->hora }}"
                        data-htt="{{ $htt }}">
                        <td>{{ $partido->player1 }}</td>
                        <td>{{ $partido->team1 }}</td>
                        <td>{{ $partido->player2 }}</td>
                        <td>{{ $partido->team2 }}</td>
                        <td>{{ $partido->fecha }}</td>
                        <td>{{ $partido->hora }}</td>
                        <td>{{ $partido->ht1 }}</td>
                        <td>{{ $partido->ht2 }}</td>
                        <td>{{ $htt }}</td>
                        <td>
                            @php
                                $markets = [1.5,2.5,3.5,4.5,5.5,6.5,7.5,8.5];
                                $marketsDropdown = array_merge($markets, array_map(function($m){ return -$m; }, $markets));
                            @endphp
                            <select class="market-selector" data-index="{{ $index }}">
                                @foreach($marketsDropdown as $market)
                                    <option value="{{ $market }}">{{ $market }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="resultado-market" data-index="{{ $index }}">{{$partido->ftt}}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11">No se encontraron partidos para la fecha seleccionada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal para mostrar resultados del helper --}}
<div class="modal fade" id="helperModal" tabindex="-1" role="dialog" aria-labelledby="helperModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helperModalLabel">Análisis del Partido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="helperModalContent">
        <p class="text-center">Cargando datos...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

{{-- Script para manejar la apertura del modal y mostrar resultados --}}
<script>
$(document).ready(function(){
    $('select.market-selector').on('change', function(){
        var marketSeleccionado = parseFloat($(this).val());
        var index = $(this).data('index');
        var $row = $(this).closest('tr');

        var player1 = $row.data('player1');
        var player2 = $row.data('player2');
        var ht1 = $row.data('ht1');
        var ht2 = $row.data('ht2');
        var fecha = $row.data('fecha');
        var hora = $row.data('hora');
        var htt = $row.data('htt');

        $('#helperModal').modal('show');
        $('#helperModalContent').html('<p class="text-center">Cargando datos...</p>');

        $.ajax({
            url: "{{ route('bets.process_data') }}",
            method: "GET",
            dataType: "json", // <- Aquí está la corrección importante
            data: {
                player1: player1,
                player2: player2,
                ht1: ht1,
                ht2: ht2,
                fecha: fecha,
                hora: hora,
                htt: htt,
                market: marketSeleccionado
            },
            success: function(response){
                console.log(response);

                if (!response.promedios || !response.market || !response.remontada) {
                    $('#helperModalContent').html("<p class='text-danger'>Error al cargar datos del helper.</p>");
                    return;
                }

                let p1 = response.promedios;
let resumenTabla = `
  <table class="table table-bordered text-center">
    <thead class="thead-dark">
      <tr>
        <th>Player 1</th>
        <th>Enfrentamiento (Promedios de goles)</th>
        <th>Player 2</th>
      </tr>
    </thead>
    <tbody>
      <tr class="table-active">
        <td colspan="3"><strong>Promedios Históricos (últimos 90 días)</strong></td>
      </tr>
      <tr>
        <td>
          FT: ${parseFloat(p1.x_p1_hist_ft).toFixed(2)}<br>
          HT: ${parseFloat(p1.x_p1_hist_ht).toFixed(2)}
        </td>
        <td>
          FT: ${parseFloat(p1.x_p1_hist_ft_vs).toFixed(2)} / ${parseFloat(p1.x_p2_hist_ft_vs).toFixed(2)}<br>
          HT: ${parseFloat(p1.x_p1_hist_ht_vs).toFixed(2)} / ${parseFloat(p1.x_p2_hist_ht_vs).toFixed(2)}
        </td>
        <td>
          FT: ${parseFloat(p1.x_p2_hist_ft).toFixed(2)}<br>
          HT: ${parseFloat(p1.x_p2_hist_ht).toFixed(2)}
        </td>
      </tr>

      <tr class="table-active">
        <td colspan="3"><strong>Promedios del Día (sólo partidos del mismo día)</strong></td>
      </tr>
      <tr>
        <td>
          FT: ${parseFloat(p1.x_p1_d_ft).toFixed(2)}<br>
          HT: ${parseFloat(p1.x_p1_d_ht).toFixed(2)}
        </td>
        <td>
          FT: ${parseFloat(p1.x_p1_d_ft_vs).toFixed(2)} / ${parseFloat(p1.x_p2_d_ft_vs).toFixed(2)}<br>
          HT: ${parseFloat(p1.x_p1_d_ht_vs).toFixed(2)} / ${parseFloat(p1.x_p2_d_ht_vs).toFixed(2)}
        </td>
        <td>
          FT: ${parseFloat(p1.x_p2_d_ft).toFixed(2)}<br>
          HT: ${parseFloat(p1.x_p2_d_ht).toFixed(2)}
        </td>
      </tr>
    </tbody>
  </table>
`;

let m = response.market;

let resumenMarkets = `
  <table class="table table-bordered text-center mt-4">
    <thead class="thead-dark">
      <tr>
        <th colspan="2">Market seleccionado: Probabilidad de Cumplimiento</th>
      </tr>
    </thead>
    <tbody>
      <tr class="table-active">
        <td><strong>Histórico del Enfrentamiento</strong></td>
        <td>
          FT: ${parseFloat(m.market_hist_vs_ft).toFixed(1)}%<br>
          SP: ${parseFloat(m.market_hist_vs_sp).toFixed(1)}%
        </td>
      </tr>
      <tr class="table-active">
        <td><strong>Enfrentamiento del Día</strong></td>
        <td>
          FT: ${parseFloat(m.market_dia_vs_ft).toFixed(1)}%<br>
          SP: ${parseFloat(m.market_dia_vs_sp).toFixed(1)}%
        </td>
      </tr>
      <tr class="table-active">
        <td><strong>Histórico con mismo marcador HT (${ht1}-${ht2})</strong></td>
        <td>
          FT: ${parseFloat(m.market_hist_vs_ft_filtered_ht).toFixed(1)}%<br>
          SP: ${parseFloat(m.market_hist_vs_sp_filtered_ht).toFixed(1)}%
        </td>
      </tr>
      <tr class="table-active">
        <td><strong>Día actual con mismo marcador HT (${ht1}-${ht2})</strong></td>
        <td>
          FT: ${parseFloat(m.market_dia_vs_ft_filtered_ht).toFixed(1)}%<br>
          SP: ${parseFloat(m.market_dia_vs_sp_filtered_ht).toFixed(1)}%
        </td>
      </tr>
    </tbody>
  </table>
`;

let r = response.remontada;

let resumenRemontadas = `
  <table class="table table-bordered text-center mt-4">
    <thead class="thead-dark">
      <tr>
        <th colspan="3">📈 Frecuencia de Remontadas (Cuando uno de los jugadores iba perdiendo al HT)</th>
      </tr>
      <tr>
        <th>Contexto</th>
        <th>Cantidad</th>
        <th>Porcentaje (%)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>Histórico total</strong></td>
        <td>${r.remontada_his_n}</td>
        <td>${parseFloat(r.remontada_his_por).toFixed(1)}%</td>
      </tr>
      <tr>
        <td><strong>Histórico del día</strong></td>
        <td>${r.remontada_his_d_n}</td>
        <td>${parseFloat(r.remontada_his_d_por).toFixed(1)}%</td>
      </tr>
      <tr>
        <td><strong>Histórico (mismo HT)</strong></td>
        <td>${r.remontada_his_n_f}</td>
        <td>${parseFloat(r.remontada_his_por_f).toFixed(1)}%</td>
      </tr>
      <tr>
        <td><strong>Día actual (mismo HT)</strong></td>
        <td>${r.remontada_his_d_n_f}</td>
        <td>${parseFloat(r.remontada_his_d_por_f).toFixed(1)}%</td>
      </tr>
    </tbody>
  </table>
`;


$('#helperModalContent').html(resumenTabla+resumenMarkets+resumenRemontadas);



                if (response.resultado !== undefined) {
                    $('td.resultado-market[data-index="'+index+'"]').text(response.resultado);
                }
            },
            error: function(xhr){
                $('#helperModalContent').html('<div class="alert alert-danger">Error al obtener datos.</div>');
            }
        });
    });
});
</script>

@endsection
