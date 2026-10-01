@extends('layouts.app',['title' => "Usuarios",'subtitle' => 'Listado'])
@section('content')
<!-- DataTables Buttons CSS y JS para exportación -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.3/css/buttons.dataTables.min.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- DataTables Buttons JS y dependencias -->
<script src="https://cdn.datatables.net/buttons/2.4.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.3/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<style type="text/css">
    @media (min-width: 1400px){
        .m-container.m-container--responsive.m-container--xxl {
            width: 100vw!important;
        }
    }

    .row-danger{
        background-color: #ff000036;
    }

    .row-warning{
        background-color: #ffff007d;
    }

    .row-success{
        background-color: #1aab408a;
    }

    .row-info{
        background-color: #447dcd2b;
    }

</style>
<div class="ns-mascot-demo" aria-label="Asistente NovaSeed">
    <div class="ns-mascot-demo__copy">
        @include('components.novaseed-ai-mascot', ['variant' => 'blob', 'state' => 'idle', 'size' => 'sm', 'animated' => true, 'placement' => 'inline', 'label' => 'Asistente NovaSeed disponible'])
        <div class="ml-2">
            <p class="ns-mascot-demo__title">NovaSeed IA está disponible</p>
            <p class="ns-mascot-demo__text">Papa Blob Premium · asistente cálido para revisar tus datos.</p>
        </div>
    </div>
    <a class="ns-mascot-demo__link" href="{{ url('/bets_compare') }}">Abrir asesor</a>
</div>
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__body">
        <div class="m-form__section m-form__section--first">
            {!! Form::open([
        'class'  => 'm-form',
        'route'  => 'bets.show',
        'method' => 'GET',
        'id'     => 'FormFilter'
    ]) !!}
    <div class="row">
        {{-- Grupo --}}
        <div class="col-md-6">
            <div class="form-group">
                <strong>Selector Grupo</strong>
                {!! Form::select(
                        'groups[]',
                        $groups,
                        request('groups', []),      {{-- ← aquí --}}
                        [
                          'multiple' => 'multiple',
                          'class'    => 'form-control select2',
                          'id'       => 'groups'
                        ]
                    )
                !!}
            </div>
        </div>

        {{-- Botones cargar/compare --}}
        <div class="col-md-6">
            <button type="button" id="load_players" class="btn btn-outline-info" style="margin-top:15px">Cargar Players</button>
            <button type="button" id="compare_players" class="btn btn-outline-warning" style="margin-top:15px">Comparar</button>
        </div>

        {{-- Jugadores --}}
        <div class="col-md-3">
            <div class="form-group">
                <strong>Jugador</strong>
                @php
                  // 1) Recoger los que vienen en la URL
                  $selPlayers = request('player', []);
                  // 2) Generar las options para que blade pinte esos valores
                  $playerOptions = [];
                  foreach($selPlayers as $p){
                      $playerOptions[$p] = $p;
                  }
                @endphp

                {!! Form::select(
                        'player[]',
                        $playerOptions,
                        $selPlayers,             {{-- ← aquí --}}
                        [
                          'multiple'    => 'multiple',
                          'class'       => 'form-control required select2',
                          'id'          => 'players',
                          'placeholder' => 'Filtrar Jugador'
                        ]
                    )
                !!}
            </div>
        </div>

        {{-- Fecha --}}
        <div class="col-md-3">
            <div class="form-group">
                <strong>Fecha</strong>
                <input
                    class="form-control"
                    type="text"
                    name="daterange"
                    value="{{ request('daterange','01/01/2025 - 12/31/2025') }}"  {{-- ← aquí --}}
                />
            </div>
        </div>

        {{-- Submit --}}
        <div class="col-md-3">
            <button type="submit" class="btn btn-outline-success" style="margin-top:15px">
              Cargar Datos
            </button>
        </div>
    </div>
    {!! Form::close() !!}

        </div>      
        <div class="row " id="indicadores" style="display: none;">
            <div class="col-md-1"></div>
            <div class="col-md-2">
                <div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded" style="border-style: solid;">
                    <div style="border-radius: 30px;">
                       <br>
                        <h4 style="text-align: center;">MVP</h4>
                        <h4 style="text-align: center;" class="name_text">({{@$mvp}})</h4>
                        <br>
                         <h3 style="text-align: center;" id="percentage">{{@$mvp_per}} %</h3>
                        <br>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded" style="border-style: solid;">
                    <div style="border-radius: 30px;">
                        <br>
                        <h4 style="text-align: center;">Equipo</h4>
                        <h4 style="text-align: center;" class="name_text">({{@$mvp_team}})</h4>
                        <br>
                         <h3 style="text-align: center;" id="vol_ac_act">{{@$mvp_team_per}} %</h3>
                        <br>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded" style="border-style: solid;">
                    <div style="border-radius: 30px;">
                        <br>
                        <h4 style="text-align: center;">Rival</h4>
                        <h4 style="text-align: center;" class="name_text">({{@$mvp_rival}})</h4>
                        <br>
                         <h3 style="text-align: center;" id="vol_ac_ant">{{@$rival_per}}%</h3>
                        <br>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded" style="border-style: solid;">
                    <div style="border-radius: 30px;">
                        <br>
                        <h4 style="text-align: center;">Equipo Rival</h4>
                        <h4 style="text-align: center;" class="name_text">({{@$rival_team}})</h4>
                        <br>
                         <h3 style="text-align: center;" id="vol_ac_variacion">{{@$rival_team_per}}%</h3>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="display: none;">
            <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>Games</th>
                        <th>0.5%</th>
                        <th>1.5%</th>
                        <th>2.5%</th>
                        <th>3.5%</th>
                        <th>4.5%</th>
                        <th>5.5%</th>
                        <th>6.5%</th>
                        <th>SP 0</th>
                        <th>SP 1</th>
                        <th>SP 2</th>
                        <th>SP 3</th>
                        <th>SP 4</th>
                        <th>SP 5</th>
                    </tr>
                </thead>
                <tbody>
    @foreach($agrup as $row)
        @if($row->partidos_jugados > 1)
            <tr>
                <td>{{ $row->jugador }}</td>
                <td>{{ $row->partidos_jugados }}</td>
                
                <td>{{ $row->pct_05 }}%</td>
                <td>{{ $row->pct_15 }}%</td>
                <td>{{ $row->pct_25 }}%</td>
                <td class="{{ $row->pct_35 > 70 ? 'row-success' : '' }}">{{ $row->pct_35 }}%</td>
                <td>{{ $row->pct_45 }}%</td>
                <td>{{ $row->pct_55 }}%</td>
                <td>{{ $row->pct_65 }}%</td> <td>{{ $row->sp_exact_0 }}%</td>
                <td>{{ $row->sp_exact_1 }}%</td>
                <td>{{ $row->sp_exact_2 }}%</td>
                <td>{{ $row->sp_exact_3 }}%</td>
                <td>{{ $row->sp_exact_4 }}%</td>
                <td>{{ $row->sp_exact_5 }}%</td>
            </tr>
        @endif
    @endforeach
</tbody>

<tbody>
    @foreach($agrupEne as $row)
         <tr>
            <td>{{ $row->jugador }}</td>
            <td>{{ $row->enemigo }}</td>
            <td>{{ $row->partidos_jugados }}</td>
            
            <td>{{ $row->pct_05 }}%</td>
            <td>{{ $row->pct_15 }}%</td>
            <td>{{ $row->pct_25 }}%</td>
            <td class="row-danger">{{ $row->pct_35 }}%</td>
            <td class="row-warning">{{ $row->pct_45 }}%</td>
            <td class="row-success">{{ $row->pct_55 }}%</td>
            <td class="row-info">{{ $row->pct_65 }}%</td>

            <td>{{ $row->sp_05 }}%</td>
            <td>{{ $row->sp_15 }}%</td>
            
            <td>
                <button type="button" class="btn btn-outline-warning compare_players" 
                    attr-player1="{{$row->jugador}}" 
                    attr-player2="{{$row->enemigo}}">
                    Comparar
                </button>
            </td>
            <td>
                <button class="btn btn-outline-success sync-h2h" 
                    data-player1="{{ $row->p1 }}" 
                    data-player2="{{ $row->p2 }}">
                    Sincronizar
                </button>
            </td>
        </tr>
    @endforeach
</tbody>
            </table> 
        </div>
        <div class="col-md-12">
           <!-- Bloque para filtrar FT1 y FT2 en la tabla de enemigos -->
            <div class="row mb-3">
                <div class="col-md-2">
                    <input type="number" name="ft1_filter" id="ft1_filter" class="form-control" placeholder="FT 1">
                </div>
                <div class="col-md-1 text-center" style="padding-top: 8px;">
                    <label>vs</label>
                </div>
                <div class="col-md-2">
                    <input type="number" name="ft2_filter" id="ft2_filter" class="form-control" placeholder="FT 2">
                </div>
                <div class="col-md-2">
                    <button type="button" id="btn_filter_ft" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table table-striped- table-bordered table-hover datatables_p">
                <thead>
    <tr>
        <th>Player</th>
        <th>Enemigo</th>
        <th>Partidos</th>
        
        <th>0.5%</th>
        <th>1.5%</th>
        <th>2.5%</th>
        <th>3.5%</th>
        <th>4.5%</th>
        <th>5.5%</th>
        <th>6.5%</th>

        <th>SP 0.5%</th>
        <th>SP 1.5%</th>
        <th>SP 2.5%</th>
        <th>SP 3.5%</th>
        <th>SP 4.5%</th>
        <th>SP 5.5%</th>

        <th>Comparar</th>
        <th>Sincronizar</th>
    </tr>
</thead>
             <tbody>
    @if(!empty($agrupEne))
        @foreach($agrupEne as $row)
            <tr>
                <td>{{ $row->jugador }}</td>
                <td>{{ $row->enemigo }}</td>
                <td>{{ $row->partidos_jugados }}</td>
                
                <td>{{ $row->pct_05 }}%</td>
                <td>{{ $row->pct_15 }}%</td>
                <td>{{ $row->pct_25 }}%</td>

                <td class="{{ $row->pct_35 > 75 ? 'row-success' : '' }}">
                    {{ $row->pct_35 }}%
                </td>
                
                <td class="row-warning">{{ $row->pct_45 }}%</td>
                <td class="row-success">{{ $row->pct_55 }}%</td>
                <td class="row-info">{{ $row->pct_65 }}%</td>

                <td>{{ $row->sp_05 }}%</td>
                <td>{{ $row->sp_15 }}%</td>
                <td>{{ $row->sp_25 }}%</td>
                <td>{{ $row->sp_35 }}%</td>
                <td>{{ $row->sp_45 }}%</td>
                <td>{{ $row->sp_55 }}%</td>

                {{-- Botones de acción --}}
                <td>
                    <button type="button" class="btn btn-outline-warning compare_players" 
                        attr-player1="{{ $row->jugador }}" 
                        attr-player2="{{ $row->enemigo }}">
                        Comparar
                    </button>
                </td>
                <td>
                    <button type="button" class="btn btn-outline-success sync-h2h"
                        data-player1="{{ $row->p1 }}"
                        data-player2="{{ $row->p2 }}">
                        Sincronizar
                    </button>
                </td>
            </tr>
        @endforeach
    @endif
</tbody>
            </table> 
        </div>
        </div>
        <div class="col-md-12">
           <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
                <thead>
                    <tr>
                        <th>Player 1</th>
                        <th>Player 2</th>
                        <th>Team 1</th>
                        <th>Team 2</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>HT 1 </th>
                        <th>HT 2 </th>
                        <th>HT</th>
                        <th>FT 1 </th>
                        <th>FT 2 </th>
                        <th>FT</th>
                        <th>SP</th>
                        <th>SP</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $success = "#3a811d57";
                        $false = "#ff00002b";
                    @endphp
                    @if(!empty($bets))
                        @foreach($bets as $bet)
                            @php
                                $count_ft  = ($bet->ft1 + $bet->ft2 ) - ($bet->ht1 + $bet->ht2 );
                                $status = (float)$count_ft > 2? $success : $false ;
                                $status_text = (float)$count_ft > 2? "okay" : "fail" ;
                            @endphp
                            <tr style="background-color: {{$status}}">
                                <td>{{ $bet->player1 }}</td>
                                <td>{{ $bet->player2 }}</td>
                                <td>{{ $bet->team1 }}</td>
                                <td>{{ $bet->team2 }}</td>
                                <td>{{ $bet->hora }}</td>
                                <td>{{ $bet->fecha }}</td>
                                <td>{{ (int)$bet->ht1}}</td>
                                <td>{{ (int)$bet->ht2}}</td>
                                <td>{{ ($bet->ht1 + $bet->ht2 ) }}</td>
                                <td>{{ (int)$bet->ft1}}</td>
                                <td>{{ (int)$bet->ft2}}</td>
                                <td>{{ ($bet->ft1 + $bet->ft2 ) }}</td>
                                <td>{{ ($bet->ft1 + $bet->ft2 ) - ($bet->ht1 + $bet->ht2 ) }}</td>
                                <td>{{ $bet->spread }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).on('click', '.sync-h2h', function () {
    const player1 = $(this).data('player1');
    const player2 = $(this).data('player2');

    if (!player1 || !player2) {
        alert('IDs inválidos');
        return;
    }

    $(this).prop('disabled', true).text('Sincronizando...');

    $.ajax({
        url: '/h2h/sync',
        method: 'POST',
        data: {
            player1: player1,
            player2: player2,
            _token: '{{ csrf_token() }}'
        },
        success: (res) => {
            alert(res.message ?? 'Sincronizado OK');
        },
        error: (err) => {
            console.error(err);
            alert('Error al sincronizar');
        },
        complete: () => {
            $('.sync-h2h').prop('disabled', false).text('Sincronizar');
        }
    });
});
</script>
<script>
    $("#btn_filter_ft").click(function(){
        var ft1 = $("#ft1_filter").val();
        var ft2 = $("#ft2_filter").val();
        var currentUrl = window.location.href;
        var newUrl = updateQueryStringParameter(currentUrl, 'ft1_filter', ft1);
        newUrl = updateQueryStringParameter(newUrl, 'ft2_filter', ft2);
        window.open(newUrl, '_blank');
    });

    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return uri + separator + key + "=" + value;
        }
    }

    array_find = [];

    registrarCheck = function(index){
        var item = {};  
        player = $('#compare_'+index).attr('attr-player')
        
        for (var i = 0; i < array_find.length; i++) {
            if ( (array_find[i] == player)) {
                array_find.splice(i, 1);
            };
        };

        if ($('#compare_'+index).is(':checked')) {
            array_find.push(player);
        }
    }

    $(document).ready(function() {
        // Tablas normales
        $(".table").not('.table-checkable').DataTable({
            scrollX: true
        });

        // Tabla con export (la última)
        $('.table-checkable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel'
                },
                {
                    extend: 'csvHtml5',
                    text: 'Exportar a CSV'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Exportar a PDF',
                    orientation: 'landscape',
                    pageSize: 'A4'
                },
                {
                    extend: 'print',
                    text: 'Imprimir'
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });

        var drValue = $('input[name="daterange"]').val().split(' - ');


        $('input[name="daterange"]').daterangepicker({
            startDate: drValue[0],
            endDate:   drValue[1],
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 3 días': [moment().subtract(3, 'days'), moment()],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 14 días': [moment().subtract(13, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Últimos 60 días': [moment().subtract(59, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Ultimo Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "alwaysShowCalendars": true
        }, function(start, end, label) {
          console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });

        $(".teams").change(function(){
            selected = $(this).find("option:selected");
            teams_player = selected.attr("attr-data")
            teams_player_data = teams_player.split(",");
            elementoID = teams_player_data[0];
            elementoID = elementoID.replace(/\(/g, '\\(').replace(/\)/g, '\\)');
            $("#matches-"+elementoID).text(teams_player_data[1])
            $("#wins-"+elementoID).text(teams_player_data[2])
            $("#por-"+elementoID).text(teams_player_data[3])
            $("#ga-"+elementoID).text(teams_player_data[4])
            $("#gc-"+elementoID).text(teams_player_data[5])
            $("#por_3-"+elementoID).text(teams_player_data[6])
            $("#por_1-"+elementoID).text(teams_player_data[7])
            $("#por_0-"+elementoID).text(teams_player_data[8])
        })

        // Inicializa Select2 sin AJAX
        $('#groups').select2({ placeholder: 'Filtrar Grupo' });
        $('#players').select2({ placeholder: 'Filtrar Jugador' });

        // Cuando cambie la selección de grupos, limpiamos los jugadores
        $('#groups').on('change', function() {
            $('#players')
            .empty()
            .trigger('change');
        });

        // Al pulsar “Load Players”, cargamos via AJAX y marcamos todo
        $('#load_players').on('click', function() {
            var groups = $('#groups').val();
            if (!groups || groups.length === 0) {
                return alert('Selecciona al menos un grupo primero.');
            }

            $.ajax({
                url: '{{ route("players.by.groups") }}',
                data: { groups: groups },
                dataType: 'json',
                success: function(data) {
                    var $players = $('#players');
                    $players.empty();
                    // Añadimos cada opción y la marcamos selected
                    $.each(data.results, function(i, item) {
                        var option = new Option(item.text, item.text, true, true);
                        $players.append(option);
                    });
                    // Refresca Select2 para que muestre las nuevas opciones seleccionadas
                    $players.trigger('change');
                },
                error: function() {
                    alert('Error al cargar jugadores');
                }
            });
        });

        // Función para convertir un objeto en una cadena de consulta
        function objetoACadena(objeto) {
            var partes = [];
            for (var clave in objeto) {
                if (objeto.hasOwnProperty(clave)) {
                    var valor = encodeURIComponent(objeto[clave]); // Codificar el valor
                    partes.push(clave+';' + valor);
                }
            }
            return partes.join('&');
        }

        // Función para generar la cadena de consulta a partir del array de objetos
        function generarQueryStringDesdeArray(arr) {
            partes = arr.map(objetoACadena);
            return partes.join('&');
        }

        $(".compare_players").click(function(){
            p1 = $(this).attr('attr-player1')
            p2 = $(this).attr('attr-player2')
            var urlBase = '/bets_compare';
            urlCompleta = urlBase + '?player1=' + p1+ "&" + "player2="+ p2;
            window.open(urlCompleta, '_blank');
            array_find = []
        })
    })
</script>
@endsection
