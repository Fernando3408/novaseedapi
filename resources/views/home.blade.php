@extends('layouts.app')
@section('content')



<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                    <i class="flaticon-calendar"></i>
                </span>

                <h3 class="m-portlet__head-text" >
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
    <div class="m-portlet__body">
        <form id="search-varity">
            
        </form>
        <button type="button" id="buscar-prospecto" class="btn btn-success" >Buscar</button>
        <div id="resultado">
            
        </div>
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

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-calendar"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Versión 1.1 (Actualización 2 de abril de 2019) 
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <!--<ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <button type="button" id="btn-agregar-caracteristica" class="btn btn-outline-success">
                        Agregar Característica
                    </button>
                </li>
            </ul>-->
        </div>
    </div>
    <div class="m-portlet__body">
        <p>
            Módulos realizados:
            <ul>
                <li>Variedad: Año se puede cambiar</li>
                <li>Características por estandar y no por año</li>
                <li>Asociar variedad a mercado productivo</li>
                <li>Se está validando que no se agregue la misma variedad</li>
                <li>Cruce: Agregar objetivo del cruce</li>
                <li>Características: Se agregaron todas las características para revisión y los valores predefinidos</li>
                <li>Resumen Características: Se agregó promedio, repeticiones y desviación estándar</li>
                <li>Buscador de prospectos</li>
                <li>Acceso de usuarios: Se creó módulo para revisión de inicio de sesión de usuarios.</li>
            </ul>
            Módulos Pendientes:
            <ul>
                <li>Termino del módulo de mapeo</li>
                <li>Módulo pre-informe SAG</li>
            </ul>
            <hr>
            Versión 1.0
            <ul>
                <li>
                    Módulo de Usuarios
                    <ul>
                        <li>Crear</li>
                        <li>Editar</li>
                        <li>Eliminar</li>
                        <li>Listado</li>
                        <li>Tipo de Usuarios</li>
                    </ul>
                </li>
                <li>
                    Módulo de Variedades
                    <ul>
                        <li>Crear</li>
                        <li>Editar</li>
                        <li>Eliminar</li>
                        <li>Asociar Características</li>
                        <li>Listado</li>
                    </ul>
                </li>
                <li>
                    Módulo de Cruces
                    <ul>
                        <li>Crear</li>
                        <li>Editar</li>
                        <li>Eliminar</li>
                        <li>Asociar Características</li>
                        <li>Listado</li>
                    </ul>
                </li>
                <li>
                    Módulo de Características
                    <ul>
                        <li>Crear</li>
                        <li>Editar</li>
                        <li>Eliminar</li>
                        <li>Listado</li>
                    </ul>
                </li>
                <li>
                    Módulo de Mapeo
                    <ul>
                        <li>Diseño Conceptual del mapeo (pendiente de revisión)</li>
                    </ul>
                </li>
                <li>
                    Módulo Ensayo de Zona
                    <ul>
                        <li>Crear</li>
                        <li>Editar</li>
                        <li>Eliminar</li>
                        <li>Listado</li>
                        <li>Ver Ensayo</li>
                        <li>Asociar Características</li>
                    </ul>
                </li>
            </ul>
        </p>
    </div>
</div>

<script>
    $(document).ready(function(){



        $(".timepicker").datepicker({
        });

        var i = 0;
        $("#btn-agregar-caracteristica").click(function(){
            $.ajax({
                url: '/varities/addFeature',
                type: 'get',
                dataType: 'html',
                data:{ i: i}
            }).done(function(data) {
                i = i + 1
                $("#search-varity").append(data)
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

        

    })
</script>
@endsection
