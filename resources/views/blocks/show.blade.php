@extends('layouts.app')
@section('content')
<style type="text/css">
    .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
        color: #495057;
        color: white!important;
        background-color: #df6403;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    .last_updated{
        background-color: #34bfa3!important;
    }
    .w-50{
        width: 30%
    }

    .w-30{
        width: 30%;
        margin-right: 20px;
    }
    /*
    th, td { white-space: nowrap!important; }
    div.dataTables_wrapper {
        width: 800px!important;
        margin: 0 auto!important;
    }
    .DTFC_LeftHeadWrapper{

    }
    */
</style>



<input type="hidden" id="id_block" value="{{$block->id}}">
<input type="hidden" id="locality_id" value="{{$locality_id}}">

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list"></i>
                </span>
                <h3 class="m-portlet__head-text">Bloque / {{ $block->description }} / {{ $block->year }}
                    
                </h3>
                    
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('blocks.index') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        <i class="fa fa-arrow-left"></i> Volver a bloques
                    </a>
                </li>
                <li class="m-portlet__nav-item">
                    <button type="button" id="agregar-codificados" class="m-portlet__nav-link btn btn-success m-btn m-btn--outline-2x ">
                        <i class="fa fa-plus"></i> Agregar codificados
                    </button>
                </li>
                <li class="m-portlet__nav-item">
                    <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--outline-2x" onclick="donwloadExcel({{$block->id}})"><i class="fa fa-download"></i> Descargar</button>
                    <iframe src="" id="frame_download" style="display: none;"></iframe>
                </li>
                <li class="m-portlet__nav-item">
                 <button type="button" class="btn btn-outline-danger m-btn m-btn--custom m-btn--outline-2x" style="" onclick="dropVarity()"><i class="fa fa-trash"></i> Eliminar</button>
                    
                </li>

            </ul>

        </div>
        
    </div>
    <!-- Content dynamic for storage or block -->
    <div class="col-md-12" style="margin-bottom: 30px;">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <label style="width: 50%; margin-top:25px;margin-bottom: 25px;">Tipo Visualización</label>
                    <select class="form-control" id="type_content" style="width: 50%;" onchange="make_show_view()">
                        <option value="1">Campo</option>
                        <option value="2">Bodega</option>
                    </select>
                </div>
            </div>
            <!--div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" style="margin-top: 23px;" onclick="reorder()"><i class="fa fa-check"></i> Re ordenar</button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-danger" style="margin-top: 23px;" onclick="discard()"><i class="fa fa-trash"></i> Descartar</button>
                    </div>
                    <div class="col-md-2">
                         <label style="width: 50%;">Tipo Visualización</label>
                        <select class="form-control" id="type_content" style="width: 50%;" onchange="make_show_view()">
                            <option value="1">Campo</option>
                            <option value="2">Bodega</option>
                        </select>
                    </div>
                </div>
            </div-->
            <div class="col-md-12">
                <div class="col-md-12" style="margin-top: 30px;">
                    <div class="row">
                        <div class="col-md-3">
                            <label style="width: 100%">Revisión</label>
                            <select class="form-control" id="review" style="width: 100%;">
                                <option value="">Seleccionar Opción</option>
                                <option value="0">Revisado</option>
                                <option value="1">Revisar</option>
                                <option value="2">Urgencia</option>
                            </select>
                        </div>
                        <div class="col-md-6" style="margin-top: 25px;">
                            <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--outline-2x" style="" onclick="reorder()"><i class="fa fa-check"></i> Re ordenar</button>
                            <button type="button" class="btn btn-outline-danger m-btn m-btn--custom m-btn--outline-2x" style="" onclick="discard()"><i class="fa fa-trash"></i> Descartar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="m-portlet__body" id="content"></div>
    <!-- End conten dynamic for storage or Block -->
</div>

<div class="modal fade show" id="modal-codificados" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Codificados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="search-filters">
                    <div class="row" style="margin-bottom: 20px;" id="search-varity">

                        <div class="col-lg-3">
                            <label for="">Mapeos</label>
                            {!! Form::select('mapout_id', $mapouts,null,['class' => 'form-control required','placeholder' => 'Sin filtro']); !!}
                        </div>
                        <div class="col-lg-3">
                            <label for="">Ensayos</label>
                            {!! Form::select('block_id', $allBlocks,null,['class' => 'form-control required','placeholder' => 'Sin filtro']); !!}
                        </div>

                        <div class="col-lg-6">
                            <button type="button" id="filtrar" class="btn btn-outline-success" style="margin-top: 23px;float: right;"> <i class="fa fa-search"></i> Filtrar</button>
                            <button type="button" id="btn-agregar-caracteristica" class="btn btn-outline-danger" style="margin-top: 23px;float: right;margin-right: 10px;"> <i class="fa fa-plus"></i> Agregar Filtro</button>
                        </div>
                    </div>
                </form>
                <hr>

                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="">Tipo de Ensayo</label>
                            <!-- ,'12x2' => '12x2','12x3' => '12x3'-->
                            {!! Form::select('type', array('6x1' => '6x1','12x1' => '12x1','12x2' => '12x2','12x3' => '12x3'),null, array('placeholder' => 'Seleccione ensayo','class' => 'form-control  required')) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <button type="button" id="agregar-bloque" class="btn btn-primary" style="margin-top: 23px;float: right;"><i class="fa fa-plus"></i> Agregar codificados</button>
                    </div>
                </div>
                <hr>
                <a class="btn btn-warning btn-sm" style="position: absolute;right: 360px;z-index: 11" href="#" id="checkedall">(Seleccionar todas)</a>
                <a class="btn btn-warning btn-sm" style="position: absolute;right: 240px;z-index: 11" href="#" id="unchecked">(Quitar todos)</a>
                <table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table-coded">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Temporada</th>
                            <th>Mercado</th>
                            <th>Berries</th>
                            <th>Madre</th>
                            <th>Padre</th>
                            <th>Mapeo</th>
                            <th style="width:180px">Seleccionar</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="modal-repeticiones" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Repeticiones <strong id="name-repeats"></strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">

                    <div class="row repeats_normal" style="margin-bottom: 20px;">
                        <div class="col-lg-3">
                            <label for="">Número de Repeticiones</label>
                            <input type="text" id="n_repeats">
                        </div>
                        <div class="col-lg-3">
                            <button type="button" id="agregar-bloque" class="btn btn-primary" style="margin-top: 10px;" onclick="addRepeat()"><i class="fa fa-plus"></i> Agregar repeticiones</button>
                        </div>
                    </div>
                    <hr class="repeats_normal">
                    <div class="row repeats_normal" id="container_repeats">
                        
                    </div>

                    <div class="repeats_calib">
                        <table class="table">
                            <thead>
                                <tr id="repeat_label">
                                    <th></th>
                                    <th>Repetición 1</th>
                                    <th>Repetición 2</th>
                                    <th>Repetición 3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="repeat_kg_block"></tr>
                                <tr id="repeat_potatoes"></tr>
                                <tr id="repeat_ton_ha"></tr>
                                <tr id="repeat__mas_65"></tr>
                                <tr id="repeat_55-65"></tr>
                                <tr id="repeat_45-55"></tr>
                                <tr id="repeat_35-45"> </tr>
                                <tr id="repeat_25-35"></tr>
                                <tr id="sums"></tr>
                                <tr id="repeat_calculo_calib"></tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-success" style="float:right" onclick="processRepeats()"><i class="fa fa-save" ></i> Procesar</button>
                            <button type="button" class="btn btn-danger" style="float:right;margin-right: 5px" onclick="closeRepears()"><i class="fa fa-close" ></i> Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="modal-rendimiento" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle Calculo Rendimiento <strong id="name-repeats"></strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-12">
                            <p>Factor: <strong>(13333/0,25)</strong></p>
                            <p>Papas por Ensayo: <strong>Tipo de ensayo o valor asignado en Papas por Ensayo</strong></p>
                            <p>Fórmula: <strong>(Kg ensayo x factor) / Papas por Ensayo</strong></p>

                        </div>
                        <div class="col-lg-3">
                            <label for="">Kg Ensayo</label>
                            <input type="text" id="kg_block" onchange="aplicar_calculo()">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Valor Factor</label>
                            <input type="text" id="factor" onchange="aplicar_calculo()">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Papas por Ensayo</label>
                            <input type="text" id="papas" onchange="aplicar_calculo()">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Resultado</label>
                            <input type="text" id="result" onchange="aplicar_calculo()">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-success" style="float:right" onclick="process_calc()"><i class="fa fa-save" ></i> Aplicar</button>
                            <button type="button" class="btn btn-danger" style="float:right;margin-right: 5px" onclick="close_rend()"><i class="fa fa-close" ></i> Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modal-rendimiento-prote" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle Calculo Rendimiento Proteínas <strong id="name-repeats"></strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-12">
                            
                            <p>Fórmula: <strong>ton/ ha x proteins x 10</strong></p>

                        </div>
                        <div class="col-lg-3">
                            <label for="">Ton/ha</label>
                            <input type="text" id="tonprotein" onchange="aplicar_calculo_protein()">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Proteínas</label>
                            <input type="text" id="protein" onchange="aplicar_calculo_protein()">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Factor</label>
                            <input type="text" id="factor_protein" onchange="aplicar_calculo_protein()" value="10">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Resultado</label>
                            <input type="text" id="result_protein" onchange="aplicar_calculo_protein()">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-success" style="float:right" onclick="process_calc()"><i class="fa fa-save" ></i> Aplicar</button>
                            <button type="button" class="btn btn-danger" style="float:right;margin-right: 5px" onclick="close_rend()"><i class="fa fa-close" ></i> Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modal-rendimiento-pigmento" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle Calculo Rendimiento Pigmentos<strong id="name-repeats"></strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-12">
                            
                            <p>Fórmula: <strong>(ton/ha x materia seca(%)) / Pigmentos</strong></p>

                        </div>
                        <div class="col-lg-3">
                            <label for="">Ton/Ha</label>
                            <input type="text" id="tonpigments" onchange="aplicar_calculo_pigmentos()">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Materia Seca</label>
                            <input type="text" id="drymatter" onchange="aplicar_calculo_pigmentos()">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Pigmentos</label>
                            <input type="text" id="pigmentos" onchange="aplicar_calculo_pigmentos()">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Resultado</label>
                            <input type="text" id="result_pigmentos" onchange="aplicar_calculo_pigmentos()">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-success" style="float:right" onclick="process_calc()"><i class="fa fa-save" ></i> Aplicar</button>
                            <button type="button" class="btn btn-danger" style="float:right;margin-right: 5px" onclick="close_rend()"><i class="fa fa-close" ></i> Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modal-tuberculos" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle Calculo Tuberculos <strong id="name-repeats"></strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-12">
                            <p>Papas por Ensayo: <strong>Tipo de ensayo o valor asignado en Papas por Ensayo</strong></p>
                            <p>Fórmula: <strong>(Nro ingreso) / Papas por Ensayo</strong></p>

                        </div>
                        <div class="col-lg-3">
                            <label for="">Nro Ingreso</label>
                            <input type="text" id="kg_block_tuberculos" onchange="aplicar_calculo_tub()">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Papas por Ensayo</label>
                            <input type="text" id="papas_tuberculos" onchange="aplicar_calculo_tub()">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Resultado</label>
                            <input type="text" id="result_tuberculos" onchange="aplicar_calculo_tub()">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-success" style="float:right" onclick="process_calc_tub()"><i class="fa fa-save" ></i> Aplicar</button>
                            <button type="button" class="btn btn-danger" style="float:right;margin-right: 5px" onclick="close_tub()"><i class="fa fa-close" ></i> Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="modal-fries" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ingreso Datos Fritura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="row" style="margin-bottom: 20px;">
                        <table>
                            <form id="form-fries">
                                <tr>
                                    <td>Enero</td>
                                    <td>Febrero</td>
                                    <td>Marzo</td>
                                    <td>Abril</td>
                                    <td>Mayo</td>
                                    <td>Junio</td>
                                </tr>
                                <tr>
                                    <td><input type="text" id="Enero" onchange="change(this)" value="0"></td>
                                    <td><input type="text" id="Febrero"  onchange="change(this)" value="0"></td>
                                    <td><input type="text" id="Marzo" onchange="change(this)" value="0"></td>
                                    <td><input type="text" id="Abril" onchange="change(this)" value="0"></td>
                                    <td><input type="text" id="Mayo" onchange="change(this)" value="0"></td>
                                    <td><input type="text" id="Junio" onchange="change(this)" value="0"></td>
                                <tr style="margin-top: 30px;">
                                    <td>Julio</td>
                                    <td>Agosto</td>
                                    <td>Septiembre</td>
                                    <td>Octubre</td>
                                    <td>Noviembre</td>
                                    <td>Diciembre</td>
                                </tr>
                                </tr>
                                <tr>
                                    <td><input type="text" id="Julio" onchange="change(this)" value="0"></td>
                                    <td><input type="text" id="Agosto" onchange="change(this)" value="0"></td>
                                    <td><input type="text" id="Septiembre" onchange="change(this)" value="0"></td>
                                    <td><input type="text" id="Octubre" onchange="change(this)" value="0"></td>
                                    <td><input type="text" id="Noviembre" onchange="change(this)" value="0"></td>
                                    <td><input type="text" id="Diciembre" onchange="change(this)" value="0"></td>
                                </tr>
                            </form>
                        </table>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-success" style="float:right" onclick="procesaFries()"><i class="fa fa-save" ></i> Procesar</button>
                            <button type="button" class="btn btn-danger" style="float:right;margin-right: 5px" onclick="closeFries()"><i class="fa fa-close" ></i> Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="modal-caracteristicas" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="width: 30%">Características y fotos: 
                    <strong id="idblockname" ></strong>
                    <p style="color: #36a3f7" id="fathers" ></p>
                </h5>

                <select onchange="selectView(this)" class="form-control" style="width: 200px;" id="selectView">
                    <option value="anho">Seleccione Visualización</option>
                    <option value="anho">Todos</option>
                    <option value="anho_0">Año 0</option>
                    <option value="anho_1">Año 1</option>
                    <option value="anho_2">Año 2</option>
                    <option value="anho_3">Año 3</option>
                    <option value="anho_4">Año 4</option>
                </select>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab" href="#modal-carac">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#modal-fot">Fotos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#modal-obs">Observación</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active show" id="modal-carac" role="tabpanel">

                    </div>
                    <div class="tab-pane" id="modal-fot" role="tabpanel">
                        
                    </div>
                    <div class="tab-pane" id="modal-obs" role="tabpanel">
                        <form id="FormObservation">
                            <input type="hidden" name="blockvarity_id">
                            <input type="hidden" name="id_varity_obs">
                            <label for="">Observación</label>
                            <textarea name="observation" class="form-control" cols="30" rows="10"></textarea>
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar Observación</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade show" id="modal-compare" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Comparación Resultados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="stripe row-border order-column" id="table_datos_compare">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Code</th>
                            <th>Ensayo</th>
                            <th>Año Variedad</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>



<script>

    //Arreglo configuracion de calibres
    features_ids = [
        {value : 23},
        {value : 26},
        {value : 27},
        {value : 28},
        {value : 29},
        {value : 30},
        {value : 123},
        {value : 136},
    ]

    $(document).ready(function(){
        
        var i = 0;
        counter = 0;
        paramsFries = []
        ln  = 0;
        global_process_fries = false;
        filter_target = ""
        filter_name   = ""

        $("#agregar-codificados").click(function(){
            $("#modal-codificados").modal("show")
        })

        $("#btn-agregar-caracteristica").click(function(){
            $.ajax({
                url: '/blocks/addFeature',
                type: 'get',
                dataType: 'html',
                data:{ i: i}
            }).done(function(data) {
                i = i + 1
                $("#search-varity").append(data)
                $(".ts-2").select2();
                
            })
        })

            /*
        $("#filter_varity").change(function(e){
            filter_name = this.value;
            
            if(filter_name != ""){

                $(".name_filter").hide();
                $(".name_"+filter_name).show();

                if(filter_target != ""){
                    $(".target_"+filter_target).show();
                }
            }else{                
                $(".name_filter").show();
            }
        })
            */
        $("#review").change(function(e){
            if(this.value != ""){
                $.ajax({
                    url: "/blocks/review",
                    type: 'GET',
                    data: {selected_p: selected_position, block: $("#id_block").val(), review: this.value},
                    dataType: 'json',
                    }).done(function(data) {
                        selected_position = [];
                        make_show_view()
                        $("#review").val("").change()
                })
            }
        })


        $("#filter_target").change(function(e){

            filter_target = this.value;

            if(filter_target != ""){
                
                $(".target_filter").hide();
                $(".target_"+filter_target).show();

                if(filter_name != ""){
                    $(".name_"+filter_name).show();
                }
            }else{
                $(".target_filter").show();
            }
        })

        getDataFeature = function(val){
            if(val.value != ""){
                $.ajax({
                    url: '/varities/addValues',
                    type: 'get',
                    dataType: 'html',
                    data:{ feature: val.value}  
                }).done(function(data) {
                    $(val).parent().next().next('.col-lg-8').html(data)
                })
            }
        }
        
        eliminarValor = function(element){
            $(element).parent().next().remove()
            $(element).parent().prev().remove()
            $(element).parent().remove()
            $(element).remove()
        }

        selectCode = function(element){
            if ( $(element).is(':checked') ) {
                $(element).val(ln)
                ln++
            }else{
                ln--
            }
        }

    table = $('#table-coded').DataTable( {
        "order": [[ 1, "desc" ]],
        "paging": true,
        "searching": true,
        processing: false,
        ajax: {
            url: '/crossings/codedJSON',
            data: function ( d ) {
                d.mapout_id = $("select[name=mapout_id]").val(),
                d.market_id = $("select[name=market_id]").val(),
                d.block_id  = $("select[name=block_id]").val(),
                d.filters   = $("#search-filters").serialize()
            }
        },
        columns: [
            { 
                "data": "name",
                
                "render": function ( data, type, row ) {
                    if(row.name != null){
                        if(typeof row.name !== 'undefined'){
                            return row.name;
                        }else{
                            return row.code
                        }

                    }else if(row.code != null){
                        return row.code
                    }

                    return "-";
                } 
                
            },
            { 
                "data": "year",
                "render": function ( data, type, row ) {
                    return "T"+(row.year - 1)+"-"+row.year
                } 
            },
            { 
                "data": "market.name",
                "render": function ( data, type, row ) {
                    if(row.market_id != null){
                        /*
                        if(typeof row.market.name !== 'undefined'){
                            return row.market.name;
                        }else{
                            return ""
                        }
                        */

                        return "-"
                    }
                    return "-";
                } 
            },
            { 
                "data": "berries"
            },
            { 
                "data": "mother.name",
                "render": function ( data, type, row ) {
                    /*
                    if(row.mother.crossing != 1){
                      return row.mother.name;
                    }else{
                      return row.mother.code;
                    }
                    */
                    return "-"
                } 
            },
            { 
                "data": "father.name",
                "render": function ( data, type, row ) {
                    /*
                    if(row.father.crossing != 1){

                      return row.father.name;
                    }else{
                      return row.father.code;
                    }
                    */

                    return "-";
                } 
            },
            { 
                "data": "mapout.year",
                "render": function ( data, type, row ) {
                    /*
                    if(row.mapout_id != null){
                        return row.mapout.year;
                    }
                    */
                    return "-";
                } 
            },
            { 
                "data": "code",
                "render": function ( data, type, row ) {
                    if(row.code == null){
                        row.code = "";
                    }
                    return '<center><input name="codificados['+row.id+']" onclick="selectCode(this)" value="'+row.id+'" type="checkbox" class="codificados" /></center>';
                } 
            }
        ],"drawCallback": function (settings) { 
            // Here the response
            $("#filtrar").html('<i class="fa fa-search"></i> Filtrar')
            var response = settings.json;
        },
     } );

        $("#filtrar").click(function(){
            $("#filtrar").html('<i class="fa fa-spinner fa-spin"></i> Cargando')
            table.ajax.reload()
        })

        $("#agregar-bloque").click(function(){
            
            if($("select[name=type]").val() == ""){
                Swal.fire(
                    'Tipo de ensayo',
                    'Debe seleccionar tipo de ensayo',
                    'error'
                )
                return false
            }

            $("#agregar-bloque").html('<i class="fa fa-spinner fa-spin"></i> Cargando')

            location.href = "/blocks/codify?block_id=<?= $block->id;?>&"+table.$('input.codificados').serialize()+"&type="+$("select[name=type]").val()
        })

        $("#checkedall").click(function(){
            table.$('input.codificados').attr("checked",true)
        })

        $("#unchecked").click(function(){
            table.$('input.codificados').attr("checked",false)
        })

        verCaracteristicas = function(id,element_id,type,block_id,idactual,blockname,ele,review, father=null, mother=null, target_origen = null, position_block =  null){

            console.log(position_block)
            global_idactua = idactual
            global_type = type;
            var fathers = "("+mother+"x"+father+")";
            blockname += " / "+target_origen

            $("#idblockname").empty().html(blockname)
            $("#fathers").empty().html(fathers)
            $("#FormObservation input[name=blockvarity_id]").val(block_id)
            $("#FormObservation input[name=id_varity_obs]").val(id)
            $("#modal-caracteristicas").modal("show")
            $("#modal-caracteristicas .modal-body #modal-carac").html('<center><h4><i class="fas fa-spinner fa-spin"></i> Cargando características.</h4></center>')
            $.ajax({
                url: '/blocks/getCaracteristicas',
                type: 'GET',
                dataType: 'html',
                data: {crossing: id,element_id:element_id, type: type,block_id:block_id,block_name:blockname, position_block:position_block},
            }).done(function(data) {
                $("#modal-caracteristicas #modal-carac").html(data)

            })

            $.ajax({
                url: '/blocks/getFotos',
                type: 'GET',
                dataType: 'html',
                data: {crossing: id},
            }).done(function(data) {
                global_process_fries = false;
                $("#modal-caracteristicas #modal-fot").html(data)

            })

            observation = $(ele).attr("attr-obs");
            position_selected = $(ele).attr("attr-pos")
            review_selected = review
            
            $("#FormObservation textarea[name=observation]").val(observation)
        }

        $("#FormObservation").submit(function(){
            $.ajax({
                url: '/blocks/saveObservation',
                type: 'GET',
                dataType: 'json',
                data: $("#FormObservation").serialize()
            }).done(function(data) {
                if(data){
                    Swal({
                      title: 'Observación guardada correctamente.',
                      text: "",
                      type: 'success',
                      showCancelButton: false,
                      showConfirmButton: false,
                    })
                }else{
                    Swal({
                      title: 'Error,no se pudo guardar la Observación.',
                      text: "",
                      type: 'error',
                      showCancelButton: false,
                      showConfirmButton: false,
                    })
                }
            })
            
            return false;
        })

        $("#type_content").change()
    })

    insertRepeat = function(ele, feature_id, varity_id,year,vname){

        global_repeats = false;
        name = $(ele).attr('attr-name')
        set_n_repeats = "";
        counter = 0;
        arr = [];


        $("#name-repeats").empty().html(vname)

        global_year = year;
        global_varity = varity_id;
        global_feature= feature_id;

        //attr-type 1: Repeticiones - 2 Frituras
        if($(ele).attr('attr-fries') == 1){
            $("#modal-caracteristicas").modal('hide')
            $("#form-fries").trigger("reset");

            var params = {
                varity_id :  varity_id,
                year : year,
                feature_id: feature_id
            }
            getDetailFries(params);

            setTimeout(function(){ $("#modal-fries").modal('show'); }, 500);
        }

        // Ej: 6x1 -> set_n_repeats = 1; Setea numero de repeticiones
        if(global_type != ""){
            set_n_repeats = global_type.split('x')[1]
            if(set_n_repeats == 1 || $(ele).attr('attr-type') == 0){
                //rendimientos
                rendimientos()
                return false;
            }
        }   

        //repeticiones
        if($(ele).attr('attr-type') == 1) {
            global_repeats = true
            $("#modal-caracteristicas").modal('hide')
            if($("input[name='"+name+"[value_repeats]']").val() == ""){
                $("#container_repeats").empty()
                $("#n_repeats").val(set_n_repeats)
                addRepeat()
            }else{ 

                if(global_feature > 25 && global_feature < 31){

                    $.each(features_ids, function(i,v){
                        index_name = "feature["+global_year+"]["+v.value+"]"
                        if(typeof arr["repeat_"+v.value] === 'undefined') {
                            arr["repeat_"+v.value] = [];
                        }
                        if(typeof $("input[name='"+index_name+"[value_repeats]']").val() !== 'undefined') {
                            arr["repeat_"+v.value].push($("input[name='"+index_name+"[value_repeats]']").val().split(","))
                        }
                    })

                    addRepeat(set_n_repeats)
                }else{
                    arr = $("input[name='"+name+"[value_repeats]']").val().split(",")
                    addRepeat(arr.length) 
                }
                
                $("#n_repeats").val(set_n_repeats)
            }
            setTimeout(function(){ $("#modal-repeticiones").modal('show'); }, 500);
        }  

        
    }

    rendimientos = function(i = null){
        name_repeat = null

        if(i){
            name_repeat =  "nr_"+i;
        }

        //Tonha o Tuberculos
        if(global_feature == 23 || global_feature == 25 || global_feature == 147 || global_feature == 148){
                    
            $.ajax({
                url: "/blocks/getDataChar",
                type: 'GET',
                data: {year: global_year, varity_id: global_varity, block: $("#id_block").val(),feature: global_feature},
                dataType: 'json',
            }).done(function(d) {

                d? value_ = d.value : value_ = 0;
                if(global_feature == 23){
                    calcula_rendimiento(value_,global_type, global_feature)
                }

                if(global_feature == 25){
                    calcula_tuberculos(value_,global_type)
                }

                if(global_feature == 147){
                    d.ton ? ton = d.ton : ton = 0;
                    d.find ? find = d.find.value : find = 0;
                    d.drymatter ? drymatter = d.drymatter : drymatter = 0;
                    calcula_pigmentos(ton, find, drymatter)
                }

                if(global_feature == 148){
                    d.ton.value ? ton = d.ton.value : ton = 0;
                    d.find ? find = d.find.value : find = 0;
                    calcula_proteina(ton, find)
                }
            })
        }

    }

    calcula_rendimiento = function(papas,tipo_ensayo, feature_id){

        $("#kg_block").val("")
        $("#result").val("")

        type_factor = 0.25;
        feature_id == 23 ? type_factor = 0.25: "";

        factor = 13333/type_factor;

        papas > 0 ? value_to_calc = papas : value_to_calc = tipo_ensayo.split("x")[0];

        $("#factor").val(factor)
        $("#papas").val(value_to_calc)
        $("#result").val()

        $("#modal-caracteristicas").modal('hide');
        $("#modal-rendimiento").modal('show');

    }

    calcula_pigmentos = function(ton,pigments, drymatter){
        $("#tonpigments").val("")
        $("#drymatter").val("")
        $("#pigmentos").val("")
        $("#result_pigmentos").val("")

        $("#tonpigments").val(ton)
        $("#pigmentos").val(pigments)
        $("#drymatter").val(drymatter)

        $("#modal-caracteristicas").modal('hide');
        $("#modal-rendimiento-pigmento").modal('show');
        aplicar_calculo_pigmentos()

    }

     calcula_proteina = function(ton,protein){

        $("#tonprotein").val("")
        $("#protein").val("")
        $("#factor_protein").val(10)
        $("#result_protein").val("")

        $("#tonprotein").val(ton)
        $("#protein").val(protein)

        $("#modal-caracteristicas").modal('hide');
        $("#modal-rendimiento-prote").modal('show');
        aplicar_calculo_protein()


    }

    calcula_tuberculos = function(papas_tuberculos,tipo_ensayo){
        papas_tuberculos > 0 ? value_to_calc = papas_tuberculos : value_to_calc = tipo_ensayo.split("x")[0];
        $("#papas_tuberculos").val(value_to_calc)
        $("#modal-caracteristicas").modal('hide');
        $("#kg_block_tuberculos").val("");
        $("#result_tuberculos").val("");
        $("#modal-tuberculos").modal('show');

    }

    arreglo_calibres = [];

    values_calibres = function(feature_id, year, elem_val,repeat){
        name_index = "calib_"+repeat;
        
        if(typeof arreglo_calibres[name_index] === 'undefined') {
            arreglo_calibres[name_index] = [];
        }

         // Buscar y eliminar el índice repetido
        $.each(arreglo_calibres[name_index], function(i, v) {
            if (v.year === year && v.feature_id === feature_id) {
                arreglo_calibres[name_index].splice(i, 1);
                return false; // Salir del bucle each
            }
        });

        // Agregar el nuevo elemento
        arreglo_calibres[name_index].push({ year: year, feature_id: feature_id, elem_val: elem_val });
    }

    calculoCalibres = function(repeat_cal,no_repeat_cal = null){

        /*
            Reteat_cal = Numer de repetición
            no_repeat_cal = Variable para indicar que el calculo es con o sin repeticiones y lo destino a otro input
        */

        sum_calibres_0 = 0;
        sum_calibres_1 = 0;
        sum_calibres_2 = 0;
        sum_calibres_3 = 0;
        sum_calibres_4 = 0;
        name_index = "calib_"+repeat_cal

        $.each(arreglo_calibres[name_index], function(i,v){
            if(v.year == 0 && parseFloat(v.elem_val) > 0){    
                sum_calibres_0 += parseFloat(v.elem_val);
            }
            if(v.year == 1 && parseFloat(v.elem_val) > 0){
                sum_calibres_1 += parseFloat(v.elem_val);
            }
            if(v.year == 2 && parseFloat(v.elem_val) > 0){
                sum_calibres_2 += parseFloat(v.elem_val);
            }
            if(v.year == 3 && parseFloat(v.elem_val) > 0){
                sum_calibres_3 += parseFloat(v.elem_val);
            }
            if(v.year == 4 && parseFloat(v.elem_val) > 0){
                sum_calibres_4 += parseFloat(v.elem_val);

            }
        })

        ac_por = 0;
        $.each(arreglo_calibres[name_index], function(i,v){
            if(v.year == 0){   
                factor_100 = sum_calibres_0;
                valor = v.elem_val*100/factor_100;
                valor = valor.toFixed(1)
                isNaN(valor) ? valor = 0 : "";
                ac_por += parseFloat(valor);
                //Variable para el calculo inicial sin repeticiones por defecto
                if(no_repeat_cal == 1){
                    n_input = "feature["+v.year+"]["+v.feature_id+"]"
                    $("input[name='"+n_input+"[value]']").val(valor)
                }else{
                    n_input = v.feature_id+"_"+repeat_cal
                    //general
                    $("#"+n_input).val(valor)
                    $("#total_detalle_"+repeat_cal).val(factor_100)
                    $("#total_porcentaje_"+repeat_cal).val(ac_por)
                    //repeat
                    $("#136_"+repeat_cal).val(factor_100)
                    calib_rend_calculo(factor_100,repeat_cal)
                }
            }

            if(v.year == 1){   
                factor_100 = sum_calibres_1;
                valor = v.elem_val*100/factor_100;
                valor = valor.toFixed(1)
                isNaN(valor) ? valor = 0 : "";
                ac_por += parseFloat(valor);
                if(no_repeat_cal == 1){
                    n_input = "feature["+v.year+"]["+v.feature_id+"]"
                    $("input[name='"+n_input+"[value]']").val(valor)
                }else{
                     n_input = v.feature_id+"_"+repeat_cal
                    $("#"+n_input).val(valor)
                    $("#136_"+repeat_cal).val(factor_100)
                    calib_rend_calculo(factor_100,repeat_cal)
                    $("#total_detalle_"+repeat_cal).val(factor_100)
                    $("#total_porcentaje_"+repeat_cal).val(ac_por)
                }
            }

            if(v.year == 2){   
                factor_100 = sum_calibres_2;
                valor = v.elem_val*100/factor_100;
                valor = valor.toFixed(1)
                isNaN(valor) ? valor = 0 : "";
                ac_por += parseFloat(valor);
                if(no_repeat_cal == 1){
                    n_input = "feature["+v.year+"]["+v.feature_id+"]"
                    $("input[name='"+n_input+"[value]']").val(valor)
                }else{
                     n_input = v.feature_id+"_"+repeat_cal
                    $("#"+n_input).val(valor)
                    $("#136_"+repeat_cal).val(factor_100)
                    calib_rend_calculo(factor_100,repeat_cal)
                    $("#total_detalle_"+repeat_cal).val(factor_100)
                    $("#total_porcentaje_"+repeat_cal).val(ac_por)
                }
            }

            if(v.year == 3){   
                factor_100 = sum_calibres_3;
                valor = v.elem_val*100/factor_100;
                valor = valor.toFixed(1)
                isNaN(valor) ? valor = 0 : "";
                ac_por += parseFloat(valor);
                if(no_repeat_cal == 1){
                    n_input = "feature["+v.year+"]["+v.feature_id+"]"
                    $("input[name='"+n_input+"[value]']").val(valor)
                }else{
                     n_input = v.feature_id+"_"+repeat_cal
                    $("#"+n_input).val(valor)
                    $("#136_"+repeat_cal).val(factor_100)
                    calib_rend_calculo(factor_100,repeat_cal)
                    $("#total_detalle_"+repeat_cal).val(factor_100)
                    $("#total_porcentaje_"+repeat_cal).val(ac_por)
                }
            }

            if(v.year == 4){   
                factor_100 = sum_calibres_4;
                valor = v.elem_val*100/factor_100;
                valor = valor.toFixed(1)
                isNaN(valor) ? valor = 0 : "";
                ac_por += parseFloat(valor);
                if(no_repeat_cal == 1){
                    n_input = "feature["+v.year+"]["+v.feature_id+"]"
                    $("input[name='"+n_input+"[value]']").val(valor)
                }else{
                     n_input = v.feature_id+"_"+repeat_cal
                    $("#"+n_input).val(valor)
                    $("#136_"+repeat_cal).val(factor_100)
                    calib_rend_calculo(factor_100,repeat_cal)
                    $("#total_detalle_"+repeat_cal).val(factor_100)
                    $("#total_porcentaje_"+repeat_cal).val(ac_por)
                }
            }
        })

        $.ajax({
          url: '/blocks/saveCalibres',
          type: 'GET',
          dataType: 'json',
          data: { calibres: arreglo_calibres[name_index], block_id: $("#id_block").val(), varity: global_varity, repeat_nro: repeat_cal, year: global_year },
          success: function(r) {
            if(r){
              Swal.fire(
                'Detalle Guardado Correctamente',
                'success'
                )
            }else{
              Swal.fire(
                'Ocurrio un Error al guardar los datos',
                'error'
                )
            }
          },
          error: function(xhr, status, error) {
            console.log(xhr.responseText);
          }
        });

        //arreglo_calibres[name_index] = [];
    }

    calib_rend_calculo = function(kg, nro_repeat){
        factor = 13333 / 0.25
        var c1 = kg * factor
        var papas_repeat =  $("#123_"+nro_repeat).val()
        var res = (c1/papas_repeat)/1000
        res = res.toFixed(1)
        isNaN(res) ? res = 0 : "";
        $("#23_"+nro_repeat).val(res);

        

    }

    aplicar_calculo = function(){
        if($("#kg_block").val() != "" && $("#factor").val() != "" && $("#papas").val()){
            var c1 = $("#kg_block").val() * $("#factor").val();
            var c2 = (c1/$("#papas").val())/1000
            $("#result").val(c2.toFixed(0))    
        }
    }

    aplicar_calculo_tub = function(){
        if($("#kg_block_tuberculos").val() != "" && $("#papas_tuberculos").val() != ""){
            var c1 = $("#kg_block_tuberculos").val();
            var c2 = (c1/$("#papas_tuberculos").val())
            $("#result_tuberculos").val(c2.toFixed(0))    
        }
    }

    aplicar_calculo_pigmentos = function(){
        // - rendimiento pigmento (kg/ha) = ton /ha x materia seca (%) x  pigments 
        if($("#drymatter").val() > 0 && $("#tonpigments").val() > 0 && $("#pigmentos").val() > 0){
            ton_pigments = parseFloat($("#tonpigments").val())
            drymatter = parseFloat($("#drymatter").val())
            pigments = parseFloat($("#pigmentos").val())

            res_pigments = ton_pigments*drymatter*pigments;
            res_pigments = res_pigments.toFixed(1);

            $("#result_pigmentos").val(res_pigments);

        }

    }

    aplicar_calculo_protein = function(){
        //- rendimiento proteínas (kg/ha) = ton/ ha x proteins x 10
        if($("#protein").val() > 0 && $("#factor_protein").val() > 0 && $("#tonprotein").val() > 0){
            tonprotein = parseFloat($("#tonprotein").val())
            factor_protein = parseFloat($("#factor_protein").val())
            protein = parseFloat($("#protein").val())

            res_protein = tonprotein*factor_protein*protein;
            res_protein = res_protein.toFixed(1);

            $("#result_protein").val(res_protein);

        }
    }

    process_calc = function(){
        name_to_set = name_repeat ? name_repeat : name;
        if(name_repeat){
            $("#"+name_repeat+"").val($("#result").val())
            $("#modal-rendimiento").modal('hide')
            setTimeout(function(){ $("#modal-repeticiones").modal('show'); }, 500);
        }else{

            

            $("input[name='"+name+"[value_repeats]']").val($("#result").val())
            $("input[name='"+name+"[value]']").val($("#result").val())
            $("#modal-rendimiento").modal('hide')
            $("#modal-rendimiento-pigmento").modal('hide')
            $("#modal-rendimiento-prote").modal('hide')

            if(global_feature == 147 || global_feature == 148){

                var r = global_feature == 147 ? $("#result_pigmentos").val() : $("#result_protein").val()
                $("input[name='"+name+"[value_repeats]']").val(r)
                $("input[name='"+name+"[value]']").val(r)
            }

            setTimeout(function(){ $("#modal-caracteristicas").modal('show'); }, 500);
        }
    }

    process_calc_tub = function(){
        name_to_set = name_repeat ? name_repeat : name;
        if(name_repeat){
            $("#"+name_repeat+"").val($("#result_tuberculos").val())
            $("#modal-tuberculos").modal('hide')
            setTimeout(function(){ $("#modal-repeticiones").modal('show'); }, 500);
        }else{
            $("input[name='"+name+"[value_repeats]']").val($("#result_tuberculos").val())
            $("input[name='"+name+"[value]']").val($("#result_tuberculos").val())
            $("#modal-tuberculos").modal('hide')
            setTimeout(function(){ $("#modal-caracteristicas").modal('show'); }, 500);
        }
    }

    getDetailFries = function(info){
        $.ajax({
            url: '/fries/getData/',
            type: 'GET',
            data: {year: info.year, varity: info.varity_id, feature : info.feature_id},
            dataType: 'json',
        }).done(function(data) {
            if(data.status){
                var r = data.data
                $("#Enero").val(r.enero)
                $("#Febrero").val(r.febrero)
                $("#Marzo").val(r.marzo)
                $("#Mayo").val(r.mayo)
                $("#Abril").val(r.abril)
                $("#Junio").val(r.junio)
                $("#Julio").val(r.julio)
                $("#Agosto").val(r.agosto)
                $("#Septiembre").val(r.septiembre)
                $("#Octubre").val(r.octubre)
                $("#Noviembre").val(r.noviembre)
                $("#Diciembre").val(r.diciembre)
            }else{
                swal("No se encontró detalle")
            }
        })
    }

    addRepeat = function(x = null){
        if(x == null){
            x = $("#n_repeats").val()
        }

        $("#container_repeats").empty()

        if(global_feature > 25 && global_feature < 31 ){
            $(".repeats_normal").hide();
            $(".repeats_calib").show();
            $("#repeat_kg_block").empty().append('<th>Kg Ensayo</th>')
            $("#repeat_potatoes").empty().append('<th>Papas por Ensayo</th>')
            $("#repeat_ton_ha").empty().append('<th>Ton/ha</th>')
            $("#repeat__mas_65").empty().append('<th>>65</th>')
            $("#repeat_55-65").empty().append('<th>55-65</th>')
            $("#repeat_45-55").empty().append('<th>45-55</th>')
            $("#repeat_35-45").empty().append('<th>35-45</th>')
            $("#repeat_25-35").empty().append('<th>25-35</th>')
            $("#sums").empty().append('<th>Totales</th>')
            $("#repeat_calculo_calib").empty().append('<th></th>')
            //feature_id, year, elem_val,repeat
            for (var i = 1; i <= x; i++) {
                $("#repeat_ton_ha").append('<th><input type="text" id="23_'+i+'" readonly></th>')
                $("#repeat_potatoes").append('<th><input type="text" value="12" id="123_'+i+'" ></th>')
                $("#repeat_kg_block").append('<th><input type="text" id="136_'+i+'" readonly></th>')
                $("#repeat__mas_65").append('<th><input type="text" class="w-30" id="d_26_'+i+'" onchange="values_calibres(26,'+global_year+',this.value,'+i+')"><input type="text" class="w-30" id="26_'+i+'" onchange="values_calibres(26,'+global_year+',this.value,'+i+')"></th>')
                $("#repeat_55-65").append('<th><input type="text" class="w-30" id="d_27_'+i+'" onchange="values_calibres(27,'+global_year+',this.value,'+i+')"><input type="text" id="27_'+i+'" class="w-30" onchange="values_calibres(27,'+global_year+',this.value,'+i+')"></th>')
                $("#repeat_45-55").append('<th><input type="text" class="w-30" id="d_28_'+i+'" onchange="values_calibres(28,'+global_year+',this.value,'+i+')"><input type="text" id="28_'+i+'" class="w-30" onchange="values_calibres(28,'+global_year+',this.value,'+i+')"></th>')
                $("#repeat_35-45").append('<th><input type="text" class="w-30" id="d_29_'+i+'" class="w-30" onchange="values_calibres(29,'+global_year+',this.value,'+i+')"><input class="w-30" type="text" id="29_'+i+'" onchange="values_calibres(29,'+global_year+',this.value,'+i+')"></th>')
                $("#repeat_25-35").append('<th><input type="text" class="w-30" id="d_30_'+i+'" onchange="values_calibres(30,'+global_year+',this.value,'+i+')"><input type="text" id="30_'+i+'" class="w-30" onchange="values_calibres(30,'+global_year+',this.value,'+i+')"></th>')
                $("#sums").append('<th><input type="text" class="w-30" id="total_detalle_'+i+'"><input type="text" id="total_porcentaje_'+i+'" class="w-30"></th>')
                var btn = '<th><button type="button" class="m-portlet__nav-link btn btn-primary m-btn m-btn--outline-2x" onclick="calculoCalibres('+i+')">Calcular Calibres</button></th>'
                $("#repeat_calculo_calib").append(btn)

                if(!arr.length > 0){
                    if(  typeof arr["repeat_23"] !== 'undefined' && arr["repeat_23"].length > 0 && typeof arr["repeat_23"][0][i-1] !== 'undefined'){
                        $("#23_"+i).val(arr["repeat_23"][0][i-1])
                    }

                    if( typeof arr["repeat_123"] !== 'undefined' && arr["repeat_123"].length > 0 &&  typeof arr["repeat_123"][0][i-1] !== 'undefined' ){
                        $("#123_"+i).val(arr["repeat_123"][0][i-1])
                    }

                    if(  typeof arr["repeat_136"] !== 'undefined'  && arr["repeat_136"].length > 0 && typeof arr["repeat_136"][0][i-1] !== 'undefined'){
                        $("#136_"+i).val(arr["repeat_136"][0][i-1])
                    }

                    if(  typeof arr["repeat_26"] !== 'undefined' && arr["repeat_26"].length > 0 && typeof arr["repeat_26"][0][i-1] !== 'undefined'){
                        $("#26_"+i).val(arr["repeat_26"][0][i-1])
                    }

                    if( typeof arr["repeat_27"] !== 'undefined' && arr["repeat_27"].length > 0 && typeof arr["repeat_27"][0][i-1] !== 'undefined'){
                        $("#27_"+i).val(arr["repeat_27"][0][i-1])
                    }

                    if( typeof arr["repeat_28"] !== 'undefined' && arr["repeat_28"].length > 0 && typeof arr["repeat_28"][0][i-1] !== 'undefined'){
                        $("#28_"+i).val(arr["repeat_28"][0][i-1])
                    }

                    if( typeof arr["repeat_29"] !== 'undefined' && arr["repeat_29"].length > 0 && typeof arr["repeat_29"][0][i-1] !== 'undefined'){

                        $("#29_"+i).val(arr["repeat_29"][0][i-1])
                    }

                    if( typeof arr["repeat_30"] !== 'undefined'  && arr["repeat_30"].length > 0 && typeof arr["repeat_30"][0][i-1] !== 'undefined'){
                        $("#30_"+i).val(arr["repeat_30"][0][i-1])
                    }

                    if( typeof arr["repeat_303"] !== 'undefined' && arr["repeat_303"].length > 0 && typeof arr["repeat_303"][0][i-1] !== 'undefined'){
                        $("#303_"+i).val(arr["repeat_303"][0][i-1])
                    }
                }
                
            }

        }else{
            $(".repeats_normal").show();
            $(".repeats_calib").hide();

            for (var i = 1; i <= x; i++) {
                if(global_feature == 23){
                    $("#container_repeats").append("<div class='col-lg-3' style='margin-top:5px;'><label>Repeteción Nº"+i+"</label><input type='text' id='nr_"+i+"' onclick='rendimientos("+i+")'></div>")
                }else if(global_feature == 25){
                    $("#container_repeats").append("<div class='col-lg-3' style='margin-top:5px;'><label>Repeteción Nº"+i+"</label><input type='text' id='nr_"+i+"' onclick='rendimientos("+i+")'></div>")

                }else{
                    $("#container_repeats").append("<div class='col-lg-3' style='margin-top:5px;'><label>Repeteción Nº"+i+"</label><input type='text' id='nr_"+i+"'></div>")
                }
                
                //El primero siempre en 0
                if(!arr[i-1]){
                    $("#nr_"+i).val(0)
                }else{
                    $("#nr_"+i).val(arr[i-1])

                }
            }
        }
    }

    processRepeats = function(){
        const x = $("#n_repeats").val()
                
        valueToShow = 0;  //valor promedio
        valueToSave = ""; //detalle repeticion
        counter_ = 0;
        test_valueToSave = 0;
        //Exception Calibres
        if(global_feature > 25 && global_feature < 31){
            $.each(features_ids, function(index, value){

                counter_ = 0;
                valueToSave = 0;
                valueToShow = 0;

                var name_input = "feature["+global_year+"]["+value.value+"]"
                console.log("index:"+index+"value"+value.value)
                for (var i = 1; i <= x; i++) {
                    if($("#"+value.value+"_"+i).val() > 0){
                        valueToShow += parseFloat($("#"+value.value+"_"+i).val())
                        valueToSave += $("#"+value.value+"_"+i).val()+","
                        counter_++
                    }else{
                        valueToShow += 0
                        valueToSave += 0
                        counter_++
                    }
                    test_valueToSave = valueToSave;
                }  

                if(counter_ > 0){
                    test_valueToSave = valueToSave;
                    valueToShow = (valueToShow / parseInt(counter_)).toFixed(0)
                    if(valueToSave > 0){
                        valueToSave = valueToSave.slice(0, -1); 
                    }
                }

                $("input[name='"+name_input+"[value_repeats]']").val(valueToSave)
                $("input[name='"+name_input+"[value]']").val(valueToShow)   
            })
        } else { //repeats. normal

            for (var i = 1; i <= x; i++) {
                valueToShow += parseFloat($("#nr_"+i).val())
                valueToSave += $("#nr_"+i).val()+","
                if(i > 0){
                    counter_++
                }
            }   

            valueToShow = (valueToShow / parseInt(counter_)).toFixed(1)
            if(valueToSave > 0){
                valueToSave = valueToSave.slice(0, -1); 
            }

            $("input[name='"+name+"[value_repeats]']").val(valueToSave)
            $("input[name='"+name+"[value]']").val(valueToShow)   
        }
        
        $("#modal-repeticiones").modal('hide')
        setTimeout(function(){ $("#modal-caracteristicas").modal('show'); }, 500);
    }

    closeRepears = function(){
        $("#modal-repeticiones").modal('hide')
        setTimeout(function(){ $("#modal-caracteristicas").modal('show'); }, 500);
    }

    closeFries = function(){
        $("#modal-fries").modal('hide')
        setTimeout(function(){ $("#modal-caracteristicas").modal('show'); }, 500);
    }

    function allowDrop(ev) {
        ev.preventDefault();

    }

    function drag(ev) {

        a = ev.dataTransfer.setData("text", ev.target.id);

    }

    function drop(ev) {
        ev.preventDefault();
        var src = document.getElementById(ev.dataTransfer.getData("text"));
        var srcParent = src.parentNode;
        var tgt = ev.currentTarget.firstElementChild;
        ev.currentTarget.replaceChild(src, tgt);
        srcParent.appendChild(tgt);

        position_drag = $(src).attr("attr-position")
        position_drop = $(tgt).attr("attr-position")

        $.ajax({
            url: "/blocks/move_position",
            type: 'GET',
            data: {position_drag: position_drag, position_drop: position_drop,block: $("#id_block").val()},
            dataType: 'json',
        }).done(function(dataxx) {
            $("#container_"+position_drag).data('attr-position', position_drop)
            $("#container_"+position_drop).data('attr-position', position_drag)
        })
    
    }


    change = function(ele){
        $(ele).val() != "" ? counter++ : counter -- ;
    }


    procesaFries =  function(){
        paramsFriesArray = {};
        global_process_fries = true;
        promedio = 0;
        counter = 0;
        v1 = 0;
        v1 += !isNaN(parseInt($("#Enero").val())) ? parseInt($("#Enero").val()) : 0;
        parseInt($("#Enero").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Febrero").val())) ? parseInt($("#Febrero").val()) : 0;
        parseInt($("#Febrero").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Marzo").val())) ? parseInt($("#Marzo").val()) : 0;
        parseInt($("#Marzo").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Mayo").val())) ? parseInt($("#Mayo").val()) : 0;
        parseInt($("#Mayo").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Junio").val())) ? parseInt($("#Junio").val()) : 0;
        parseInt($("#Junio").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Julio").val())) ? parseInt($("#Julio").val()) : 0;
        parseInt($("#Julio").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Agosto").val())) ? parseInt($("#Agosto").val()) : 0;
        parseInt($("#Agosto").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Septiembre").val())) ?  parseInt($("#Septiembre").val()) : 0;
        parseInt($("#Septiembre").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Octubre").val())) ?  parseInt($("#Octubre").val()) : 0;
        parseInt($("#Octubre").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Noviembre").val())) ?  parseInt($("#Noviembre").val()) : 0;
        parseInt($("#Noviembre").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Diciembre").val())) ?  parseInt($("#Diciembre").val()) : 0;
        parseInt($("#Diciembre").val()) > 0 ? counter ++ : "";
        console.log(v1)
        v1 += !isNaN(parseInt($("#Abril").val())) ?  parseInt($("#Abril").val()) : 0;
        parseInt($("#Abril").val()) > 0 ? counter ++ : "";
        console.log(v1)

        if(counter != 0){
            promedio = v1/counter;
        }

        paramsFriesArray = {
            "_token": "{{ csrf_token() }}",
            Enero : $("#Enero").val(),
            Febrero : $("#Febrero").val(),
            Marzo : $("#Marzo").val(),
            Mayo : $("#Mayo").val(),
            Junio : $("#Junio").val(),
            Julio : $("#Julio").val(),
            Agosto : $("#Agosto").val(),
            Septiembre : $("#Septiembre").val(),
            Octubre : $("#Octubre").val(),
            Noviembre : $("#Noviembre").val(),
            Diciembre : $("#Diciembre").val(),
            Abril : $("#Abril").val(),
            promedio: promedio.toFixed(2),
            year: global_year,
            feature: global_feature,
            varity: global_varity,
            block_id : $("#id_block").val(),
            locality : $("#locality_id").val(),
            id: null
        }
        /*
        if (paramsFries.length > 0) {
            $.each(paramsFries, function(i,v){
                if(v.feature == global_feature && v.varity == global_varity){
                    paramsFries.splice(i, 1);
                }
            })

        }
        */

        paramsFries = [];

        paramsFries.push(paramsFriesArray);

        $.ajax({
            url: "/fries/save",
            type: 'POST',
            dataType: 'json',
            data: {params : paramsFries, _token: paramsFries[0]['_token']},
          }).done(function(data) {
            if(data == true){
              Swal.fire(
                'Detalle Guardado Correctamente',
                'success'
                )
            }else{
              Swal.fire(
                'Ocurrio un Error al guardar los datos',
                'error'
                )
            }
          })


        $("input[name='"+name+"[value]']").val(promedio)
        $("#modal-fries").modal('hide')
        setTimeout(function(){ $("#modal-caracteristicas").modal('show'); }, 500);    
        
        
    }

    selectView = function(e){
        var selected = $(e).val();
        $(".anho").hide()
        $("."+selected).show()
    }

    selected_position = [];

    select_block =  function(element){
        var id = $(element).val()
        var pos_selected = $(element).attr("attr-position");
        var item = {};
        
        for (var i = 0; i < selected_position.length; i++) {
            if ( (selected_position[i]['IDVARITY'] == id) && selected_position[i]['POSITION'] == pos_selected) {
                selected_position.splice(i, 1);
            };
        };

        if ( $(element).is(':checked') ) {
            item['IDVARITY'] = id;
            item['POSITION'] = pos_selected
            selected_position.push(item);
        }
    }

    compare = function(){
        $.ajax({
            url: "/blocks/info_to_compare",
            type: 'GET',
            data: {selected_p: selected_position, block: $("#id_block").val()},
            dataType: 'json',
        }).done(function(d) {

            arr = [];

            if(d){
                

                resetHeaders(d.headers);

                $.each(d.data, function(i,v){

                    drop_btn = '<button class="btn btn-danger" onclick="deleteVarity(\''+v.name+'\','+v.varity_id+')" type="button" style="width:50%"><i class="fa fa-trash"></i> Eliminar</button>'

                    arr = [
                        drop_btn,
                        v.name,
                        v.mother,
                        v.father,
                        v.target
                    ]

                    $.each(d.headers, function(index, value){
                        arr.push(v[index])
                    })

                    dt_datos.row.add(arr).draw()
                }) 

            }
        })
    }

    resetHeaders = function(dx){

        if($.fn.DataTable.isDataTable('#table_datos_compare')){
            dt_datos.clear().draw()
            $("#table_datos_compare").dataTable().fnDestroy();
        }   

        $("#table_datos_compare thead tr").empty()
        $('#table_datos_compare thead tr').append("<th>-</th>");
        $('#table_datos_compare thead tr').append("<th>Codigo</th>");
        $('#table_datos_compare thead tr').append("<th>Madre</th>");
        $('#table_datos_compare thead tr').append("<th>Padre</th>");
        $('#table_datos_compare thead tr').append("<th>Mercado</th>");

        $.each(dx, function(i, v){
            $('table thead tr').append("<th>"+ v +"</th>");
        })


        dt_datos = $('#table_datos_compare').DataTable({
            scrollX: true,
            paging:         false,
        })

                $("#modal-compare").modal('show')

    }

    deleteVarity = function(name, id_to_delete){
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
              url: "/blocks/deleteCodify/"+id_to_delete,
              type: 'GET',
              dataType: 'json',
            }).done(function(data) {
                make_show_view()
            })
          }
        })
    }

    /* TRASPASO DE VARIEDADES */

    allblockdata = <?= json_encode($allBlocks); ?>;

    var options = {};

    $.map(allblockdata, function(o,i) {
        options[i] = o;
    });

    sendVarities = function(){
        swal({
            title:'Traspaso Variedades',
            text: 'Seleccione Ensayo al cual hacer el Traspaso',
            input: 'select',
            inputOptions: options,
            showCancelButton: true,
            animation: 'slide-from-top',
            inputPlaceholder: 'Please select'
        }).then(function (inputValue) {
            if (inputValue) {
                 $.ajax({
                    url: "/blocks/send_to_blocks",
                    type: 'GET',
                    data: {selected_p: selected_position, block: $("#id_block").val(), destiny: inputValue.value},
                    dataType: 'json',
                }).done(function(d) {
                    if(d.status){
                        Swal({
                          title: 'Traspaso Generado Correctamente',
                          text: "",
                          type: 'success',
                          showCancelButton: false,
                          showConfirmButton: false,
                        })
                    }else{
                        Swal.fire(
                            'Ocurrio un Error al guardar los datos',
                            'error'
                        )
                    }
                })
            }
        });
    }

    /* FIN TRASPASO DE VARIEDADES */


    //Cargo el contenido dependiendo si es campo o bodega
    make_show_view = function(){
        $.ajax({
          data: {type_content:$("#type_content").val()},
          url:  "/blocks/"+$("#id_block").val(),
          success: function(data) {
            $('#content').empty().html(data);
          }
        });
    }


    close_rend = function(){

        $("#modal-rendimiento").modal('hide')
        $("#modal-rendimiento-pigmento").modal('hide')
        $("#modal-rendimiento-prote").modal('hide')
        global_repeats ? $("#modal-repeticiones").modal('show')   : $("#modal-caracteristicas").modal('show')  
        
    }

    close_tub = function(){
        $("#modal-tuberculos").modal('hide')
        global_repeats ? $("#modal-repeticiones").modal('show')   : $("#modal-caracteristicas").modal('show')  
    }

    reorder = function(){
         $.ajax({
          data: {type_content:$("#type_content").val()},
          url:  "/blocks/reorder/"+$("#id_block").val(),
          success: function(data) {
            Swal({
              title: 'Reorden Ejecutado correctamente',
              text: "",
              type: 'success',
              showCancelButton: false,
              showConfirmButton: false,
            })
            make_show_view()
          }
        });
    }

    discard = function(){
        $.ajax({
            url: "/blocks/discard",
            type: 'GET',
            data: {selected_p: selected_position, block: $("#id_block").val()},
            dataType: 'json',
        }).done(function(data) {
            selected_position = [];
            make_show_view()
        })
    }

    dropVarity = function(){


        Swal.fire({
          title: '¿Quieres eliminar del sistema ?',
          text: "Se va a eliminar, no habrá vuelta atrás.",
          type: 'error',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, anular'
        }).then((result) => {
          if (result.value) {
            $.ajax({
                url: "/blocks/drop",
                type: 'GET',
                data: {selected_p: selected_position, block: $("#id_block").val()},
                dataType: 'json',
            }).done(function(data) {
                selected_position = [];
                make_show_view()
            })
          }
        })

        
    }

    donwloadExcel = function(id){
        link = "/blocks/export/"+id
        $("#frame_download").attr("src",link)
    }

    filtroql = function(el){
            var rex = new RegExp($(el).val(), 'i');
            $('.container_ql a').hide();
            $('.container_ql a').filter(function () {
                return rex.test($(el).text());
            }).show();
        }

</script>
@endsection