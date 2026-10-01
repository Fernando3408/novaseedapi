@extends('layouts.app')
@section('content')
<style type="text/css">
    .invertir_down{
        background: url(../img/arrow-down.png)!important;
        background-position-x: 50%!important;
    }

    .invertir{
        background: url(../img/arrow.png)!important;
        background-position-x: 50%!important;

    }

</style>
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-calendar"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Mapeo {{ $mapout->year }}
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    @if($mapout->step == 0)
                        <button id="codificar" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x" <?= (!$enabledCodify)?'disabled="disabled"':'';?>>
                        Codificar Mapeo
                    </button>
                    @endif
                    @if($mapout->step == 1)
                        <button id="anularCodificados" style="margin-left: 10px" class="m-portlet__nav-link btn btn-danger m-btn m-btn--outline-2x" >
                            <i class="fa fa-trash"></i> Anular Codificados
                        </button>
                        <button id="codificados" style="margin-left: 10px" class="m-portlet__nav-link btn btn-warning m-btn m-btn--outline-2x" >
                            <i class="fa fa-list"></i> Codificados
                        </button>
                    @endif
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <ul id="mapout-first-step" class="width{{ $mapout->lines }}">
            @for($i = 1;$i <= $mapout->lines;$i++)
                
                <li id="linea_{{$i}}" data-line="{{ $i }}" data-mapout="{{$mapout->id}}" style="margin-top: 30px;margin-bottom: 30px;">
                <i class="fas fa-sync" style="position: absolute;top: -25px;cursor: pointer" onclick="changeLine({{$i}})"></i>

                    {{$i}}
                    @if(array_key_exists($i, $positions))
                        @foreach($positions[$i] as $pos)
                            @if($pos->crossing)
                            <h6 class="step{{ $pos->step }}" style="top:{{ $pos->positionY }}px" onclick="modificarCruce(this)" data-id="{{ $pos->id }}" data-mother="{{ @$pos->crossing->motherName() }}" json="{{ json_encode($pos) }}" data-father="{{ @$pos->crossing->fatherName() }}">
                                {!! @$pos->crossing->motherName() !!}<br>
                                {!! @$pos->crossing->fatherName() !!}<br>
                                <b>
                                    @if(isset($pos->crossing->market))
                                        ({!! $pos->crossing->market->name !!})
                                    @else
                                        (Sin M.O)
                                    @endif
                                </b>
                            </h6>
                            @endif
                        @endforeach
                    @endif
                </li>
            @endfor
        </ul>
    </div>
</div>

<div class="modal fade show" id="modal-add-cruce" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Cruce</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
                <div class="modal-body">
                    {!! Form::open(array('class' => 'm-form','route' => 'mapouts.addPositions','method'=>'POST','id' => 'FormAddPositions')) !!}
                            <input type="hidden" name="mapout_id" value="{{ $mapout->id }}">
                            <input type="hidden" name="positionY">
                            <input type="hidden" name="line">
                            <input type="hidden" name="varity_id">
                        </form>

                    <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
                        <thead>
                            <tr>
                                
                                <th>Temporada</th>
                                <th>Id</th>
                                <th>Madre</th>
                                <th>Padre</th>
                                <th>M.Objetivo</th>
                                <th>Etapa</th>
                                <th>Estado</th>
                                <th style="width:350px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($crossings))
                                @foreach($crossings as $record)
                                <tr>
                                    <td>T{{ $record->year }}-{{ $record->year+1 }}</td>
                                    <td>{{ $record->id }}</td>
                                    <td>
                                        <b>
                                            <a href="/varities/{{ @$record->mother->id }}">
                                                {{ @$record->motherName() }}
                                            </a>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            <a href="/varities/{{ @$record->father->id }}">
                                                {{ @$record->fatherName() }}
                                            </a>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            @if(!empty($record->marketold_id))
                                                @if($record->marketold_id == $record->market_id)
                                                    {{ $record->market->name }}
                                                @else
                                                    Original: {{ $record->marketold->name }}, Modificado: {{ $record->market->name }}
                                                @endif
                                            @endif
                                        </b>
                                    </td>
                                    <td>
                                        <center>
                                            <span class="m-badge m-badge--warning m-badge--wide">Año {{ date('Y')-$record->year }}</span>
                                        </center>
                                    </td>
                                    <td>
                                        <center>
                                            @if($record->typecrossing == 1)
                                                <span class="m-badge m-badge--info m-badge--wide">Por codificar</span>
                                            @else
                                                <span class="m-badge m-badge--success m-badge--wide">Codificado</span>
                                            @endif
                                        </center>
                                    </td>
                                    <td>
                                        <center>
                                            <a class="btn btn-outline-info btn-sm" onclick="agregarCruce({{ $record->id }})">Seleccionar</a>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade show" id="modal-update-position" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modificación Cruce</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            {!! Form::open(array('class' => 'm-form','route' => 'mapouts.updatePosition','method'=>'POST','id' => 'FormUpdatePosition')) !!}
                <div class="modal-body">
                    <input type="hidden" name="id" >
                    <!--<div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <strong>
                                    ¿Qué tipo de ensayo quiere asignar al cruce?
                                </strong>
                                {!! Form::select('type', array(0 => 'No califica', '6x1' => '6x1','12x2' => '12x2','12x3' => '12x3'),null, array('placeholder' => 'Seleccione ensayo','class' => 'form-control  required')) !!}
                            </div>
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Roja</label>
                                <input type="text" name="red" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Roja Carne</label>
                                <input type="text" name="redmeat" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Azul</label>
                                <input type="text" name="blue" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Amarilla</label>
                                <input type="text" name="yellow" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Alargada</label>
                                <input type="text" name="elongated" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <strong>Observación:</strong>
                                {!! Form::textarea('observation', null, array('placeholder' => 'Ingrese Observación','class' => 'form-control ')) !!}
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deletePosition()">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="margin-top: 0px;"><i class="fa fa-save"></i> Actualizar Cruce</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade show" id="modal-codificados" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Codificados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Temporada</th>
                            <th>Mercado</th>
                            <th>Berries</th>
                            <th>Madre</th>
                            <th>Padre</th>
                            <th>Etapa</th>
                            <th style="width:180px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($mapout->coded))
                            @foreach($mapout->coded as $c)
                                <tr>
                                    <td><b>{{ $c->code }}</b></td>
                                    <td>T{{ $c->year }}-{{ $c->year+1 }}</td>
                                    <td>
                                        <b>
                                            @if($c->marketold_id == $c->market_id)
                                                {{ @$c->market->name }}
                                            @else
                                                Original: {{ @$c->marketold->name }}, Modificado: {{ @$c->market->name }}
                                            @endif
                                        </b>
                                    </td>
                                    <td>{{ $c->berries }}</td>
                                    <td>
                                        <b>
                                            <a href="/varities/{{ @$c->mother->id }}">
                                                {{ @$c->motherName() }}
                                            </a>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            <a href="/varities/{{ @$c->father->id }}">
                                                {{ @$c->fatherName() }}
                                            </a>
                                        </b>
                                    </td>
                                    <td>
                                        <center>
                                            <span class="m-badge m-badge--warning m-badge--wide">Año {{ date('Y')-$c->year }}</span>
                                        </center>
                                    </td>
                                    <td>
                                        <center>
                                            <a class="btn btn-outline-info btn-sm" href="{{ route('crossings.show',$c->id) }}">Ver</a>
                                            <a class="btn btn-outline-success btn-sm" href="{{ route('crossings.edit',$c->id) }}">Editar</a>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .button {
      margin: 0 0 0 5px;
      text-indent: -9999px;
      cursor: pointer;
      width: 29px;
      height: 29px;
      float: left;
      text-align: center;
      background: url(/img/increments/buttons.png) no-repeat;
    }
    .dec {
      background-position: 0 -29px;
    }

    .numbers-row{
        position: relative;
        margin-bottom: 10px;
        width: 100%;
    }

    .inc.button{
        position: absolute;
        top: 31px;
        right: 6px;
    }
    .dec.button{
        position: absolute;
        top: 31px;
        right: 40px;
    }
</style>


<script>
    function isOdd(num) { return num % 2;}

    changeLine= function(linea){
        if(isOdd(linea) == 1){ //impar
            if($("#linea_"+linea).hasClass( "invertir_down" )){
                $("#linea_"+linea).removeClass("invertir_down")
            }else{
                $("#linea_"+linea).addClass("invertir_down")    
            }
        }else{
             if($("#linea_"+linea).hasClass( "invertir" )){
                $("#linea_"+linea).removeClass("invertir")
            }else{
                $("#linea_"+linea).addClass("invertir")    
            }
        }
    }

    deletePosition = function(){
        id_varity = $("#FormUpdatePosition input[name=id]").val()

        Swal.fire({
          title: '¿Quieres quitar el codificado del mapeo?',
          text: "Se van a eliminar los codificados, no habrá vuelta atrás.",
          type: 'error',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, anular codificación'
        }).then((result) => {
          if (result.value) {
            location.href = "/mapouts/deleteCodify/<?= $mapout->id;?>?varity_id="+id_varity
          }
        })
    }

    $(document).ready(function(){

        

        let step = "<?= $mapout->step;?>"
        $("#FormUpdatePosition").validate()
        var y = 0;
        var line = 0;
        $("#mapout-first-step li").click(function(e){
            if(step == 0){
                $("#modal-add-cruce").modal("show")
                var posX = $(this).offset().left, posY = $(this).offset().top;
                y = e.pageY - posY;
                line = $(this).attr("data-line")
            }
        }).children().click(function(e) {
          return false;
        });

        agregarCruce = function(id){
            $("#FormAddPositions input[name=positionY]").val(y)
            $("#FormAddPositions input[name=line]").val(line)
            $("#FormAddPositions input[name=varity_id]").val(id)
            $("#FormAddPositions").submit()
        }

        modificarCruce = function(element){
            
            $("#FormUpdatePosition button[type=submit]").show()
            $(".dec.button").show()
            $(".inc.button").show()
            $("#FormUpdatePosition input,#FormUpdatePosition textarea").removeAttr("disabled")
            
            var json = JSON.parse($(element).attr("json"))
            var id = $(element).attr("data-id")
            var mother = $(element).attr("data-mother")
            var father = $(element).attr("data-father")
            $("#modal-update-position").modal("show")
            $("#modal-update-position h5").html("Modificar Cruce: <b>"+mother+" | "+father+"</b>")
            $("#FormUpdatePosition input[name=id]").val(id)
            $("#FormUpdatePosition textarea[name=observation]").val(json.observation)
            $("#FormUpdatePosition select[name=type]").val(json.count)
            $("#FormUpdatePosition input[name=red]").val(json.red)
            $("#FormUpdatePosition input[name=redmeat]").val(json.redmeat)
            $("#FormUpdatePosition input[name=blue]").val(json.blue)
            $("#FormUpdatePosition input[name=yellow]").val(json.yellow)
            $("#FormUpdatePosition input[name=elongated]").val(json.elongated)
            return false;
            
        }

        $("#codificar").click(function(){
            Swal.fire({
              title: '¿Quieres codificar el mapeo?',
              text: "Se van a codificar los <?= $mapout->positions->count();?> cruces.",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si, codificar'
            }).then((result) => {
              if (result.value) {
                location.href = "/mapouts/generateCodify/<?= $mapout->id;?>"
              }
            })
        })

        $("#anularCodificados").click(function(){
            Swal.fire({
              title: '¿Quieres anular los codificados del mapeo?',
              text: "Se van a eliminar los codificados, no habrá vuelta atrás.",
              type: 'error',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si, anular codificación'
            }).then((result) => {
              if (result.value) {
                location.href = "/mapouts/deleteCodify/<?= $mapout->id;?>"
              }
            })
        })

        $("#codificados").click(function(){
            $("#modal-codificados").modal("show")
        })


        $(".table").DataTable()
    })
</script>
@endsection
