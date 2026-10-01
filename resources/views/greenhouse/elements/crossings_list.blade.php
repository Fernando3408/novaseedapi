<?php
    use App\Models\Varity;
    use App\Models\Position;
?>


<table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table_crossings">
    <thead>
        <tr>
            <th>Estado M</th>
            <th>Estado P</th>
            <th>Temporada</th>
            <th>Madre</th>
            <th>PS</th>
            <th></th>
            <th>Padre</th>
            <th>PS</th>
            <th>M.Objetivo</th>
            <th>Objetivo Bayas</th>
            <th>Objetivo Cruce</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($crossings))
            @foreach($crossings as $record)
                <tr>
                    <!-- CONDICIONES DE PARA EL TRASPASO 

                    1.- SI TIENEN FLOR -> OK
                    2.- SI TIENEN POLEN GUARDADO -> OK
                    3.- POLEN ESTERIL X POLEN ESTERIL -> NO

                    P1 -> 1 OK: CONDICIONES DE LA MADRE
                    P2 -> 2 OK: CONDICIONES DEL PADRE
                    
                    EN EL RED SE GUARDO LA FLOR -> POR POSICION
                    -->
                    <td>
                        <?php 
                            $class = "fa fa-times-circle";
                            $p1 = 0;
                            $p2 = 0;
                            $values_encoded =  null;
                            $model_position = Position::where('varity_id', @$record->mother_id)->where('mapout_id',@$mapout->id);
                            if(!empty($model_position)){
                                
                                $values_encoded = json_encode($model_position->where('red','>','0')->get());

                                foreach ($model_position->get() as $key => $value) {
                                    if ($value->red > 0 && $value->steril_mom != 1 ) {
                                        $class = "fa fa-check-circle show-tooltip";
                                        $p1 = 1;
                                    }
                                }
                            }
                        ?>
                        
                        @if ($p1 > 0 )
                            <i class="{{$class}}" style="cursor: pointer;z-index: 999;" attr-position="{{$values_encoded}}" onclick="showDetail(this)"></i> OK
                        @else
                            <i class="{{$class}}" style="cursor: pointer;z-index: 999;" attr-position="{{$values_encoded}}" onclick="showDetail(this)"></i>
                        @endif
                    </td>

                    <td>

                        <!-- SEPARACIÓN-->
                        <?php 
                            $class = "fa fa-times-circle";
                            $values_encoded =  null;
                            $model_position = Position::where('varity_id', $record->father_id)->where('mapout_id',@$mapout->id);
                            $polen = Varity::find($record->father_id);

                            if($polen && $polen->polem_save > 0){
                                $p2 = 1;
                                $class = "fa fa-check-circle show-tooltip";
                                $id = @$record->id;

                            }

                            if(!empty($model_position)){
                                $values_encoded = json_encode($model_position->where('red','>','0')->get());
                                foreach ($model_position->get() as $key => $value) {

                                    #EVALUO SI TIENE FLOR
                                    if ($value->red > 0 ) {
                                        $class = "fa fa-check-circle show-tooltip";
                                        $id = @$value->id;
                                        $p2 = 1;
                                    }

                                    #EVALUO SI TIENE POLEN GUARDADO Y QUE NO SEA ESTERIL
                                    if($value->crossing && $value->crossing->polem_save > 0 && $value->polem_volume != 3){
                                        $p2 = 1;
                                        $class = "fa fa-check-circle show-tooltip";
                                        $id = @$value->id;
                                    }
                                }
                            }
                            
                        ?>
                        

                        <!-- SEPARACIÓN EN CASO DE QUE SE CUMPLAN LAS CONDICIONES SE MUESTRA EL OK -->
                        @if ($p1 > 0 && $p2 > 0)
                            <i class="{{$class}}" style="cursor: pointer;z-index: 999;" attr-position="{{$values_encoded}}" onclick="showDetail(this)"></i>
                            {!! Form::open(['method' => 'PATCH','route' => ['greenhouse.update', @$record->id],'style'=>'display:inline', 'id' => 'FormOk']) !!}
                            <input type="hidden" name="record_id" value="{{@$record->id}}">
                            <input type="hidden" name="record_status" value="1">
                            <button type="button" class="btn btn-outline-success btn-sm cancelar" attr-id-ok="{{$record->id}}" onclick="enviaaaa(this)">LISTO</button>
                            {!! Form::close() !!}
                        @else
                            @if ($p2 > 0 )
                                <i class="{{$class}}" style="cursor: pointer;z-index: 999;" attr-position="{{$values_encoded}}" onclick="showDetail(this)"></i> OK
                            @else
                                <i class="{{$class}}" style="cursor: pointer;z-index: 999;" attr-position="{{$values_encoded}}" onclick="showDetail(this)"></i>
                            @endif
                        @endif
                    </td>

                    <td>T{{ $record->year }}-{{ $record->year+1 }}</td>
                    <td>
                        <b><a href="/varities/{{ @$record->mother->id }}">{{ @$record->motherName() }}</a></b>
                    </td>
                    <td>
                        @if(@$record->mother()->first()->polem_save > 0)
                            <i class="fas fa-check-double" style="cursor: pointer;" onclick="savePolem(0,{{$record->mother_id}},0)"></i>
                        @else
                            <i class="far fa-times-circle" style="cursor: pointer;" onclick="savePolem(0,{{$record->mother_id}},1)"></i>
                        @endif
                    </td>

                    <td style="cursor: pointer;" onclick="switchElement({{$record->id}},{{$record->father_id}},{{$record->mother_id}})"><i class="fas fa-random"></i></td>

                    <td>
                        <b><a href="/varities/{{ @$record->father->id }}">{{ @$record->fatherName() }}</a></b>
                    </td>
                   <td>
                        @if(@$record->father()->first()->polem_save > 0)
                            <i class="fas fa-check-double" style="cursor: pointer;" onclick="savePolem({{$record->father_id}}, 0,0)"></i>
                        @else
                            <i class="far fa-times-circle" style="cursor: pointer;" onclick="savePolem({{$record->father_id}}, 0,1)"></i>
                        @endif
                    </td>
                    <td>
                        <b>{{ @$record->market->name }}</b>
                    </td>
                    <td>{{ $record->berries_objective }}</td>
                    <td>{{ $record->observation }}</td>
                    <td>
                        <center>
                            <a class="btn btn-outline-success btn-sm" href="{{ route('crossings.edit',$record->id) }}">Editar</a>
                            {!! Form::open(['method' => 'DELETE','route' => ['greenhouse.destroy', $record->id],'style'=>'display:inline']) !!}
                            <input type="hidden" value="{{$record->id}}" name="record_id">
                            {!! Form::submit('Eliminar', ['class' => 'btn btn-outline-danger btn-sm btn-delete']) !!}
                            {!! Form::close() !!}
                        </center>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        table_crossings = $("#table_crossings").DataTable({
    bPaginate: false,
    searching: true,
    ordering: true,
    columnDefs: [
      {
        targets: "_all",
        render: function (data, type, row, meta) {
          // data viene como HTML de la celda
          if (type === "sort" || type === "filter") {
            const $tmp = $("<div>").html(data);

            // 1) si hay input/select/textarea, usa su value
            const $input = $tmp.find("input, select, textarea").first();
            if ($input.length) {
              return ($input.val() ?? "").toString().trim();
            }

            // 2) fallback: texto visible
            return $tmp.text().trim();
          }
          return data; // display
        }
      }
    ]
  });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        enviaaaa = function(element){
            id_ok = $(element).attr('attr-id-ok')
            table_crossings
                .row($(element).parents('tr'))
                .remove()
                .draw();

            $.ajax({
                url: "/public/greenhouse/"+id_ok,
                    type: 'PATCH',
                    data: {record_id: id_ok, record_status: 1},                    
                    dataType: 'json',
                }).done(function(dataxx) {
                    draw_croosings_ok()
                })
        }
    })
</script>
