<?php
    use App\Models\Varity;
?>
@extends('layouts.app')
@section('content')
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-calendar"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Invernadero {{ $mapout->year }}
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active  show" data-toggle="tab" href="#listado_f1">Listado de Cruces</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#listado_ready">Listado de Cruces Listos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link  " data-toggle="tab" href="#listado_cruces">Invernadero</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" id="listado_cruces" role="tabpanel">
            </div>
            <div class="tab-pane active  show" id="listado_f1" role="tabpanel">
                <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Temporada</th>
                            <th>Madre</th>
                            <th>Padre</th>
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
                                    <td>
                                    {!! Form::open(['method' => 'PATCH','route' => ['greenhouse.update', $record->id],'style'=>'display:inline']) !!}
                                    <input type="hidden" name="record_id" value="{{@$record->id}}">
                                    <input type="hidden" name="record_status" value="1">
                                    {!! Form::submit('OK', ['class' => 'btn btn-outline-success btn-sm']) !!}
                                    {!! Form::close() !!}
                                    </td>
                                    <td>T{{ $record->year }}-{{ $record->year+1 }}</td>
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
                                                    {{ @$record->market->name }}
                                                @else
                                                    Original: {{ @$record->marketold->name }}, Modificado: {{ @$record->market->name }}
                                                @endif
                                            @endif
                                        </b>
                                    </td>
                                    <td>{{ $record->berries_objective }}</td>
                                    <td>{{ $record->observation }}</td>
                                    <td>
                                        <center>
                                            <a class="btn btn-outline-success btn-sm" href="{{ route('crossings.edit',$record->id) }}">Editar</a>
                                            {!! Form::open(['method' => 'DELETE','route' => ['greenhouse.destroy', $record->id],'style'=>'display:inline']) !!}
                                            {!! Form::submit('Eliminar', ['class' => 'btn btn-outline-danger btn-sm btn-delete']) !!}
                                            {!! Form::close() !!}
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="listado_ready" role="tabpanel">
                <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Fecha Término</th>
                            <th>Temporada</th>
                            <th>Madre</th>
                            <th>Padre</th>
                            <th>M.Objetivo</th>
                            <th>Objetivo Bayas</th>
                            <th>Objetivo Cruce</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($crossings_ready))
                            @foreach($crossings_ready as $record)
                                <tr>
                                    <td>
                                    {!! Form::open(['method' => 'PATCH','route' => ['greenhouse.update', $record->id],'style'=>'display:inline']) !!}
                                    <input type="hidden" name="record_id" value="{{@$record->id}}">
                                    <input type="hidden" name="record_status" value="0">
                                    {!! Form::submit('Cancelar', ['class' => 'btn btn-outline-success btn-sm']) !!}
                                    {!! Form::close() !!}
                                    </td>
                                    <td>{{ $record->updated_at }}</td>
                                    <td>T{{ $record->year }}-{{ $record->year+1 }}</td>
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
                                                    {{ @$record->market->name }}
                                                @else
                                                    Original: {{ @$record->marketold->name }}, Modificado: {{ @$record->market->name }}
                                                @endif
                                            @endif
                                        </b>
                                    </td>
                                    <td>{{ $record->berries_objective }}</td>
                                    <td>{{ $record->observation }}</td>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
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
                    {!! Form::open(array('class' => 'm-form','route' => 'greenhouse.addPositions','method'=>'POST','id' => 'FormAddPositions')) !!}
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
                                <th>Variedad</th>
                                <th>M.Objetivo</th>
                                <th>Etapa</th>
                                <th>Estado</th>
                                <th style="width:350px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                $list_new = [];
                            ?>

                            @if(!empty($crossings))
                                @foreach($crossings as $r)
                                    
                                        <?php 
                                            $record = Varity::where("id",@$r->mother->id)->first();
                                            $list_new[] = @$record->id;
                                            $record = Varity::where("id",@$r->father->id)->first();
                                            $list_new[] = @$record->id;
                                        ?>
                                @endforeach
                                @foreach(array_unique($list_new) as $r)
                                    <?php 
                                        $record = Varity::where("id",$r)->first();
                                        $list_new[] = @$record->id;
                                    ?>
                                    <tr>
                                        <td>T{{ @$record->year }}-{{ @$record->year+1 }}</td>
                                        <td>{{ @$record->id }}</td>
                                        <td>
                                            <b>
                                                <a href="/varities/{{ @$record->id }}">
                                                    {{ @$record->name }}
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
                                                <span class="m-badge m-badge--warning m-badge--wide">Año {{ date('Y')-@$record->year }}</span>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                @if(@$record->typecrossing == 1)
                                                    <span class="m-badge m-badge--info m-badge--wide">Por codificar</span>
                                                @else
                                                    <span class="m-badge m-badge--success m-badge--wide">Codificado</span>
                                                @endif
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <a class="btn btn-outline-info btn-sm" onclick="agregarCruce({{ @$record->id }})">Seleccionar</a>
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
            {!! Form::open(array('class' => 'm-form','route' => 'greenhouse.updatePosition','method'=>'POST','id' => 'FormUpdatePosition')) !!}
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
                                <label for="name">Flor</label>
                                <input type="text" name="red" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Polem</label>
                                <input type="text" name="redmeat" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Bayas</label>
                                <input type="text" name="blue" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Nº Abortos</label>
                                <input type="text" name="yellow" class="form-control" value="0">
                            </div>
                        </div>
                        <!--div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Alargada</label>
                                <input type="text" name="elongated" class="form-control" value="0">
                            </div>
                        </div-->
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    @if($mapout->step == 0)
                    <button type="submit" class="btn btn-primary" style="margin-top: 0px;"><i class="fa fa-save"></i> Actualizar</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>



<style>

    .green{
        background-color: green!important;

    }

    .red{
        background-color: red!important;

    }


    .blue{
        background-color: blue!important;
    }

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

    #mapout-first-step{
        transform: rotate(90deg);

    }

    #mapout-first-step li h6{
        transform: rotate(270deg);
    }

    #mapout-first-step li{
        min-width: 59px;
        margin-left: 0px;
        margin-right:0px;
        background-size: 95%;
        background-repeat: no-repeat;
    }

    #mapout-first-step li:nth-child(even){
       background: url('../img/arrow-down.png')!important;
       background-position-x: 50%!important;
        background-size: 95%!important;
        background-repeat: no-repeat!important;
    }


    #mapout-first-step li h6{
        height: 30px;
        width:65%;
        font-size: 09px;
        margin-left: 10px;
        margin-top: 5px;
        
    }

    #mapout-first-step {
        padding-left: 0px;
        min-height: 640px;
    }
    .simple-card.same {
        background: #e36f00;
        display: none;
        width: 42px;
    }
    .hv-wrapper .hv-item .hv-item-parent:after {
        position: absolute;
        content: '';
        width: 2px;
        height: 25px;
        bottom: 0;
        left: 50%;
        background-color: rgba(93, 93, 93, 0.7);
        transform: translateY(100%);
        display: none;
    }   

    .no-rotate{
        transform: rotate(180deg);
    }
    .simple-card {
        background: #df6403;
        width: 43px;
    }
    .fa-times-circle{
        cursor: pointer;
    }
</style>


<script>
    $(document).ready(function(){

        $("#codificados").click(function(){
            $("#modal-codificados").modal("show")
        })

        $(".table").DataTable()

        draw_greenhouse = function(){
            $('#listado_cruces').empty()


           $.ajax({
              url:  "/draw_greenhouse/",
              success: function(data) {
                $('#listado_cruces').empty().html(data);
              }
            });
        } 
        draw_greenhouse()
    })

    agregarCruce = function(id){
        $("#FormAddPositions input[name=positionY]").val(y)
        $("#FormAddPositions input[name=line]").val(line)
        $("#FormAddPositions input[name=varity_id]").val(id)
        $.ajax({
          url: $("#FormAddPositions").attr('action'),
          type: 'POST',
          dataType: 'json',
          data: $("#FormAddPositions").serialize(),
        }).done(function(data) {
            $("#modal-add-cruce").modal('hide')
            draw_greenhouse()
        })
        //$("#FormAddPositions").submit()
    }

    modificarCruce = function(element){
        if(step != 0){
            $("#FormUpdatePosition button[type=submit]").hide()
            $(".dec.button").hide()
            $(".inc.button").hide()
            $("#FormUpdatePosition input,#FormUpdatePosition textarea").attr("disabled","disabled")
        }else{
            $("#FormUpdatePosition button[type=submit]").show()
            $(".dec.button").show()
            $(".inc.button").show()
            $("#FormUpdatePosition input,#FormUpdatePosition textarea").removeAttr("disabled")
        }
        var json = JSON.parse($(element).attr("json"))
        var id = $(element).attr("data-id")
        var mother = $(element).attr("data-mother")
        var father = $(element).attr("data-father")
        $("#modal-update-position").modal("show")
        $("#modal-update-position h5").html("Actualizar Información de: <b>"+mother+"")
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

    delete_position = function(id,name){
        Swal.fire({
          title: '¿Quieres eliminar '+name+' ?',
          text: "Se va a eliminar, no habrá vuelta atrás.",
          type: 'error',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, anular'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/greenhouse/deleteCodify/"+id,
              type: 'GET',
              dataType: 'json',
            }).done(function(data) {
                draw_greenhouse()
            })
          }
        })
    }  
</script>
@endsection
