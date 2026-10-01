@extends('layouts.app')
@section('content')


<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    @if($crossing->crossing == 1 AND $crossing->typecrossing == 2)
                        Editar Codificado
                    @else
                        Editar Cruce
                    @endif
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        {!! Form::model($crossing, ['method' => 'PATCH','id' => 'FormEditTypeEngine','route' => ['crossings.update', $crossing->id]]) !!}
            @if($crossing->crossing == 1 AND $crossing->typecrossing == 2)
                @include('crossings.formcodify')
            @else
                @include('crossings.form')
            @endif
		    <button type="submit" class="btn btn-primary">Guardar Edición</button>
		{!! Form::close() !!}
    </div>
    <div id="findModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width:70%">
            <div class="modal-content ver" style="border-radius: 0">
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
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body" style="border-bottom: 1px solid #ebedf2;">
                        <form id="search-varity2"></form>
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
        $("#FormEditTypeEngine").validate()
        $("#FormCreate").validate()
        var i = 0;

        $(".find-btn").click(function(){
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
                console.log(data)
                data2 = data
                $("#search-varity2").append(data)
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

        $("#buscar-prospecto").click(function(){
            $.ajax({
                url: '/varities/searchHome',
                type: 'get',
                dataType: 'html',
                data: $("#search-varity").serialize()
            }).done(function(data) {
                console.log(data)
                if(data){
                   $("#resultado").html(data)
                }else{
                    alert("No data Found")
                }
                
            })
        })

        eliminarValor = function(element){
            $(element).parent().parent().parent().remove()
        }
	});
</script>
@endsection
