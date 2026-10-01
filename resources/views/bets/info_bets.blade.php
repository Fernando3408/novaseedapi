@extends('layouts.app',['title' => "Usuarios",'subtitle' => 'Listado'])
@section('content')
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded" >
    <div class="m-portlet__body">
        <div class="m-form__section m-form__section--first">
            {!! Form::open(array('class' => 'm-form','route' => 'bets.info_bets','method'=>'GET','id' => 'FormFilter')) !!}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Selector Jugadores</strong>
                        <input type="text" class="form-control" id="import_players_actual" name="import_players_actual">
                    </div>
                </div>
                <div class="col-md-6">
                    <button type="button" id="load_players" style="margin-top: 15px" class="btn btn-outline-info">Cargar Players</button>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <strong>Jugador</strong>
                        {!! Form::select('player[]', $players , null , array('placeholder' => 'Filtrar Jugador','multiple' => 'multiple','class' => 'form-control  required select2', 'id' => 'players')) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" style="margin-top: 15px" class="btn btn-outline-success">Cargar Datos</button>
                </div>
            </div>
        {!! Form::close() !!}
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th>Enemigo</th>
                                <th>Partidos</th>
                                <th>0 - 2</th>
                                <th> % </th>
                                <th>3.5</th>
                                <th> % </th>
                                <th>4.5</th>
                                <th> % </th>
                                <th>5.5</th>
                                <th> % </th>
                                <th>6.5</th>
                                <th> % </th>
                                <th> AVG </th>
                                <th> (-)4.5</th>
                                <th> (-)5.5</th>
                                <th> (-)6.5</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($resultados_global))
                                @foreach($resultados_global as $ene)
                                @if($ene->partidos_jugados > 2)
                                    @php
                                        $por_0 = round($ene->minor*100/$ene->partidos_jugados);
                                        $por_3 = round($ene->three*100/$ene->partidos_jugados);
                                        $por_4 = round($ene->four*100/$ene->partidos_jugados);
                                        $por_5 = round($ene->five*100/$ene->partidos_jugados);
                                        $por_6 = round($ene->six*100/$ene->partidos_jugados);
                                    @endphp

                                	<tr >
                                        <td>{{$ene->jugador_principal}}</td>                                    
                                        <td>{{$ene->enemigo_principal}}</td>                                    
                                        <td>{{$ene->partidos_jugados}}</td>                                    
                                        <td>{{$ene->minor}}</td> 
                                        <td>{{$por_0}}</td>                                   
                                        <td>{{$ene->three}}</td>                                    
                                        <td>{{$por_3}}</td>                                   
                                        <td>{{$ene->four}}</td>                                    
                                        <td>{{$por_4}}</td>                                   
                                        <td>{{$ene->five}}</td>                                    
                                        <td>{{$por_5}}</td>                                   
                                        <td>{{$ene->six}}</td>                                    
                                        <td>{{$por_6}}</td>                                   
                                        <td>{{$ene->avg}}</td>                                   
                                        <td>{{$por_0+$por_3+$por_4}}</td>                                   
                                        <td>{{$por_0+$por_3+$por_4 + $por_5}}</td>                                   
                                        <td>{{$por_0+$por_3+$por_4 + $por_5 + $por_6}}</td>                                   
                                    </tr>
                            @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".table").DataTable()
        $(".select2").select2();  
        $("#load_players").click(function(){
            texto = $("#import_players_actual").val()
            const regex = /\((.*?)\)/g;
            let match;
            const nombresSet = new Set(); // Usamos un Set para evitar duplicados

            // Buscar todos los nombres entre paréntesis y almacenarlos en un Set
            while ((match = regex.exec(texto)) !== null) {
                nombresSet.add(match[0]); // Agregar el match completo (incluyendo paréntesis) al Set
            }

            // Convertir nombres a mayúsculas y mantener paréntesis
            nombresMayusculas = Array.from(nombresSet).map(nombre => {
                return nombre.toUpperCase();
            });

            // Imprimir los nombres en mayúsculas manteniendo paréntesis
            nombresMayusculas.forEach(nombre => {
                console.log(nombre)
            });

            //$("#players").val(nombresMayusculas).trigger('change');
            $("#players option").each(function() {
            // Comprueba si el texto de la opción está incluido en los nombres en mayúsculas
                if (nombresMayusculas.includes($(this).text().toUpperCase())) {
                    // Marca la opción como seleccionada
                    $(this).prop('selected', true);
                } else {
                    // Desmarca la opción si no coincide
                    $(this).prop('selected', false);
                }
            });  
            
            $("#players").trigger('change');
        });  
    });
</script>
@endsection
