@extends('layouts.app')

@section('content')

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Agregar Cruce
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">   

        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" data-toggle="tab" href="#listado_cruces">Crear Cruce</a>
            </li>
            <!--li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#listado_f1">Listado de Cruces</a>
            </li-->
        </ul>
        <div class="tab-content">
            <div class="tab-pane active show" id="listado_cruces" role="tabpanel">
                {!! Form::open(array('class' => 'm-form','route' => 'crossings.store','method'=>'POST','id' => 'FormCreate')) !!}
                    <input type="hidden" name="user_id" value="<?= Auth::user()->id;?>">
                    <div class="m-form__section m-form__section--first">        
                        @include('crossings.form')
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Cruce</button>

                {!! Form::close() !!}
            </div>
            <!--div class="tab-pane" id="listado_f1" role="tabpanel">
                <table class="table-striped- table-bordered table-hover table-checkable " id="table-cross">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Temporada</th>
                            <th>Madre</th>
                            <th>Padre</th>
                            <th>M.Objetivo</th>
                            <th>Creado por</th>
                            <th>Invernadero</th>
                            <th>Objetivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($crossings))
                            @foreach($crossings as $record)
                                <tr>
                                    <td>{{ $record->id }}</td>
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
                                            @else
                                                {{ @$record->market->name }}
                                            @endif
                                        </b>
                                    </td>
                                    <td>{{ @$record->user->name }}</td>
                                    <td>{{ @$record->greenhouse->name }}</td>
                                    <td>{{ @$record->observation }}</td>
                                    
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div-->
        </div>
    </div>

    <div class="modal fade show" id="modal-colors" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Información Colores</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul>
                        @foreach ($colors as $v)
                            <li>
                                {{$v->name}} <i class="fa fa-circle" style="color: {{$v->hexa}}"></i> | valor: {{$v->value}} 
                            </li>
                        @endforeach
                    </ul>
                    <h5>
                        Para la asignación de valores, esto corresponde a la mezla entre el color predominante y un color secundario
                    </h5>
                    <h5>
                        Por ejemplo si predomina el Marron pero también blanco, el valor a consultar seria <strong>31</strong>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show" id="modal-compare"  tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" style="min-width: 1000px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Comparación Resultados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="stripe row-border order-column" id="table_datos_compare" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Code</th>
                                <th>Ensayo</th>
                                <th>Año Variedad</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="findModal" class="modal fade" >
        <div class="modal-dialog modal-lg" style="max-width: 100%;width: 70%;">
            <div class="modal-content ver" style="border-radius: 0; width: 100%">
                    <div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded" style="margin-bottom: 0px!important">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon">
                                        <i class="flaticon-calendar"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        Buscador de prospectos
                                    </h3>
                                </div>          
                            </div>
                            <div class="m-portlet__head-tools">
                                <ul class="m-portlet__nav">
                                    <li class="m-portlet__nav-item">
                                        <button type="button" id="btn-agregar-caracteristica" class="btn btn-outline-success">
                                            Agregar Característica
                                        </button>
                                        <li class="m-portlet__nav-item">
                                            <button type="button" class="btn btn-outline-warning " onclick="compare()">
                                                <i class="fa fa-filter" style="color: orange"></i> Comparar
                                            </button>
                                        </li>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="m-portlet__body" style="border-bottom: 1px solid #ebedf2;">
                            <form id="search-varity">
                                
                            </form>
                            <button type="button" id="buscar-prospecto" class="btn btn-success" >Buscar</button>
                            <div id="resultado">
                                
                            </div>
                        </div>
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="float: right;margin-right: 2.2rem;margin-bottom: 10px;margin-top: 10px"> <i class="fa fa-close"></i> Cerrar</button>
                    </div>
            </div>
        </div>
    </div>
    
</div>
<script>
    $(document).ready(function() {
        $("#table-cross").DataTable()
        $("#FormCreate").validate()
        var i = 0;

        $(".find-btn").click(function(){
            global_selector = $(this).attr('attr-selector')
            $("#findModal").modal('show')
        })

        $("#btn-agregar-caracteristica").click(function(){
            $.ajax({
                url: '/varities/addFeature',
                type: 'get',
                dataType: 'html',
                data:{ i: i}
            }).done(function(data) {
                i = i + 1
                $("#search-varity").append(data)
                $(".ts-2").select2();
                
            })
        })

        getDataFeature = function(val){
            if(val.value != ""){
                $.ajax({
                    url: '/varities/addValues',
                    type: 'get',
                    dataType: 'html',
                    data:{ feature: val.value}  
                }).done(function(data) {
                    $(val).parent().next('.col-lg-8').html(data)
                })
            }
        }

        array_descripcion = "";

        $("#buscar-prospecto").click(function(){
            
            /*
            arr_temp = $("#search-varity").serializeArray();
            $.each(arr_temp, function(i,v){
                if(v.name != "type"){
                    console.log($("input [name='"+v.name+"]'").val())
                }
            })
            */
            start_loading("buscar-prospecto")
            
            $("#resultado").empty()
            $.ajax({
                url: '/varities/searchHomeCrossing',
                type: 'get',
                dataType: 'html',
                data: $("#search-varity").serialize()
            }).done(function(data) {
                console.log(data)
                if(data){
                    end_loading()
                   $("#resultado").html(data)
                }else{
                    end_loading()
                    alert("No data Found")
                }
                
            })
        })

        eliminarValor = function(element){
            $(element).parent().parent().parent().remove()
        }

        seleccionar = function(id){
            $("#select_"+global_selector).val(id).change()
            $("#findModal").modal('hide')
        }

        showColors = function(){
            $("#modal-colors").modal('show')
            $("#findModal").modal('hide')
        }

        selected_varity = [];

        select_block = function(element){
            var id = $(element).attr("attr-id")
            var name = $(element).attr("attr-name")
            var item = {};

            console.log($(element).is(':checked'))

            for (var i = 0; i < selected_varity.length; i++) {
                if ( selected_varity[i]['IDVARITY'] == id) {
                    selected_varity.splice(i, 1);
                };
            };

            if ( $(element).is(':checked') ) {
                item['IDVARITY'] = id;
                item['NAME'] = name;
                selected_varity.push(item);
            }
        }

         resetHeaders = function(dx){

            if($.fn.DataTable.isDataTable('#table_datos_compare')){
                dt_datos.clear().draw()
                $("#table_datos_compare").dataTable().fnDestroy();
            }   

            $("#table_datos_compare thead tr").empty()
            $('#table_datos_compare thead tr').append("<th>Característica</th>");
            var columns = [];

            $.each(selected_varity, function(i,v){
                rename = v.NAME.split(';');

                $('#table_datos_compare thead tr').append("<th class='text-center 50-w'><p class='w100'>"+rename[0]+"</p><p class='w50'>"+rename[1]+"</p><p class='w50'>"+rename[2]+"</p></th>");
                columns.push(i++);
            })

            dt_datos = $('#table_datos_compare').DataTable({
                scrollX: true,
                paging:         false,
                "ordering": false,
                "columnDefs": [ 
                    { className: "text-left", "targets": [ 0]},    
                    { className: "text-center", "targets": columns},    
                ],
            })


        }

        compare = function(){
            $.ajax({
                url: "/results/info_to_compare",
                type: 'GET',
                data: {selected_p: selected_varity},
                dataType: 'json',
            }).done(function(d) {

                arr = [];

                if(d){
                    resetHeaders(selected_varity);
                    dt_datos.clear().draw();
                    global_data_test = d.data;
                    $.each(d.data, function(i,v){
                        
                        name = v.NAME.name != "" ? v.NAME.name : v.NAME.code;

                        arr = [
                            v.NAME.name,
                        ]

                        $.each(selected_varity, function(ix,vx){
                            arr.push(v[vx['IDVARITY']]['value'])
                        })

                        dt_datos.row.add(arr).draw()
                    }) 

                    $("#findModal").modal('hide')
                    
                    setTimeout(function(){ $("#modal-compare").modal('show');  }, 1000);
                    setTimeout(function(){ dt_datos.columns.adjust(); }, 2000);
                }
            })
        }

        $("#modal-compare").on('hidden.bs.modal', function (e) {
            $("#findModal").modal('show')
        })

        $("#select_mother, #select_father").change(function(){
            if($("#select_mother").val() > 0 && $("#select_father").val() > 0 ){
                $.ajax({
                    url: '/crossings/check_ancester',
                    type: 'get',
                    dataType: 'json',
                    data: {father: $("#select_father").val(), mother: $("#select_mother").val()}
                }).done(function(data) {
                    if(data.status){
                        swal(
                            {
                                title: data.msj,
                                text:"¿Desea cancelar Cruzamiento?",
                                type:"warning",
                                showCancelButton:!0,
                                confirmButtonText:"Si, cancelar",
                                cancelButtonText:"No, continuar",
                                reverseButtons:!0
                            }
                        ).then(function(e){
                            if(e.value){
                               $("#select_mother, #select_father").val("").change()
                            }else{
                                return false;
                            }
                        })
                    }else{
                        end_loading()
                       
                    }
                })
            }
        })

	});
</script>


@endsection


