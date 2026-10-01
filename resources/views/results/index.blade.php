@extends('layouts.app')
@section('content')
<style type="text/css">
    .w100{
        max-width: 100px;
        line-height: 15px;
    }

    .w50{
        max-width: 100px;
        font-size: 10px;

    }   
    .ui-tooltip {background-color: white;}
    * { box-sizing: border-box; }

    .autocomplete {
      /*the container must be positioned relative:*/
      position: relative;
      display: inline-block;
    }
    input {
      border: 1px solid transparent;
      background-color: #f1f1f1;
      padding: 10px;
      font-size: 16px;
    }
    input[type=text] {
      background-color: #f1f1f1;
      width: 100%;
    }
    input[type=submit] {
      background-color: DodgerBlue;
      color: #fff;
    }
    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;
      /*position the autocomplete items to be the same width as the container:*/
      top: 100%;
      left: 0;
      right: 0;
    }
    .autocomplete-items div {
      padding: 10px;
      cursor: pointer;
      background-color: #fff;
      border-bottom: 1px solid #d4d4d4;
    }
    .autocomplete-items div:hover {
      /*when hovering an item:*/
      background-color: #e9e9e9;
    }
    .autocomplete-active {
      /*when navigating through the items using the arrow keys:*/
      background-color: DodgerBlue !important;
      color: #ffffff;
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
                    Configuración Búsqueda
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <div class="autocomplete" style="width:300px;">
                        <input id="myInput" class="form-control" type="text" name="myCountry" attr-id="" placeholder="Buscador Variedades">
                    </div>
                </li>
                <li class="m-portlet__nav-item">
                    <a href="{{ route('results.feature-guide') }}" class="btn btn-outline-info" style="margin-right: 10px;">
                        <i class="fa fa-book"></i> Guia Caracteristicas
                    </a>
                </li>
                <li class="m-portlet__nav-item">
                    <button type="button" id="btn-agregar-caracteristica" class="btn btn-outline-success">
                        <i class="fa fa-plus" style="color: green"></i> Agregar Característica
                    </button>
                </li>
                <li class="m-portlet__nav-item">
            <button type="button" class="btn btn-outline-warning " onclick="compare()">
                <i class="fa fa-filter" style="color: orange"></i> Comparar
            </button>
        </li>
        <li class="m-portlet__nav-item">
            <button type="button" class="btn btn-outline-brand" onclick="compareBeta()">
                <i class="fa fa-table"></i> Comparar beta
            </button>
        </li>
    </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <form id="search-varity">
            <div class="row">
                <div class="col-md-3" style="margin-bottom: 30px;">
                    <strong>Filtrar Localidad</strong>
                    {!! Form::select('locality_id', $localities, null , array('placeholder' => 'Listado de Localidades','class' => 'form-control', 'id' => 'locality_id')) !!}
                </div>
                 <div class="col-md-3" style="margin-bottom: 30px;">
                    <strong>Filtrar Ensayos por Año</strong>
                    {!! Form::select('years', $years, null , array('placeholder' => 'Listado de Años','class' => 'form-control')) !!}
                </div>
                <div class="col-md-3" style="margin-bottom: 30px;">
                    <strong>Busqueda Ensayos</strong>
                    {!! Form::select('block[]', $blocks_list, null , array('class' => 'form-control', 'id' => 'block', 'multiple' => 'multiple', 'data-placeholder' => 'Listado de Ensayos')) !!}
                </div>
            </div>
           
            <div class="col-md-12 row" id="div-varities" style="display: none;">
                <div class="col-md-3">
                    <strong>Variedades</strong>
                    <select class="form-control" name="varities_list"></select>
                </div>
                <!--div class="col-md-3" id="div-varities-children" >
                    <select class="form-control" name="children_children_list"></select>
                </div-->
                <div class="col-md-2" style="margin-top: 20px;">
                    <button id="btn_childrens" class="btn btn-info" disabled>Mostrar Hijos</button>
                </div>
                <div class="col-md-4" style="margin-left: -50px;">
                    <strong>Busqueda por Hijos</strong>
                    <div class="col-md-2">
                        <span class="m-switch m-switch--lg m-switch--icon m-switch--brand">
                            <label>
                                <input type="checkbox" name="only_childrens" value="1">
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
            </div>
            <hr>
        </form>
        <div class="col-md-12" style="margin-top: 30px;">
            <button type="button" id="buscar-prospecto" class="btn btn-success" >Buscar</button>    
            <div id="resultado"></div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modal-childrens" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-children"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
                <div class="modal-body">
                    <table class="table-bordered table-hover table-checkable datatables_p" id="table-childrens">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Madre</th>
                                <th>Padre</th>
                            </tr>
                        </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modal-features" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-children"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="content-features"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modal-resume" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tagResume"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="content-resume"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modal-compare-beta" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comparar beta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-3">
                        <strong>Configuración</strong>
                        <select class="form-control" id="compare-beta-config"></select>
                    </div>
                    <div class="col-md-3">
                        <strong>Nombre configuración</strong>
                        <input type="text" class="form-control" id="compare-beta-config-name" placeholder="Nombre">
                    </div>
                    <div class="col-md-3">
                        <strong>Título comparación</strong>
                        <input type="text" class="form-control" id="compare-beta-title" placeholder="Título">
                    </div>
                    <div class="col-md-3" style="margin-top: 22px;">
                        <button type="button" class="btn btn-success" id="compare-beta-save-config">
                            <i class="fa fa-save"></i> Guardar configuración
                        </button>
                    </div>
                </div>
                <div class="accordion" id="compare-beta-accordion" style="margin-bottom: 20px;"></div>
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-4">
                        <strong>Nueva acción</strong>
                        <input type="text" class="form-control" id="compare-beta-new-action" placeholder="Nombre de acción">
                    </div>
                    <div class="col-md-3" style="margin-top: 22px;">
                        <button type="button" class="btn btn-outline-info" id="compare-beta-add-action">
                            <i class="fa fa-plus"></i> Agregar acción
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="compare-beta-clear-filters">
                            <i class="fa fa-eraser"></i> Limpiar filtros
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-checkable" id="table-compare-beta" style="width: 100%;">
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="compare-beta-save">
                    <i class="fa fa-save"></i> Guardar comparación
                </button>
                <button type="button" class="btn btn-primary" id="compare-beta-update" style="display:none;">
                    <i class="fa fa-refresh"></i> Actualizar
                </button>
                <button type="button" class="btn btn-outline-brand" id="compare-beta-new" style="display:none;">
                    <i class="fa fa-plus"></i> Crear nueva comparación
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>


    getVaritiesList = function(e) {
        $.ajax({
            url: '/results/getVaritiesFromBlock',
            type: 'get',
            dataType: 'json',
            data:{ block: $("#block").val()}
        }).done(function(data) {
            $("#div-varities").hide();

            if(data.length > 0){
                $("#div-varities").show();
                $("select[name=varities_list]").empty()

                $.each(data, function(i,v){
                    $("select[name=varities_list]").append("<option value='"+v.id+"'>"+v.name+"</option>")
                })

                $("select[name=varities_list]").change()
                $("select[name=varities_list]").select2();

            }
        })
    }

    getChildrenList = function(e) {
        $("#btn_childrens").attr('disabled', true)
        $.ajax({
            url: '/results/getChildrensFromVarity',
            type: 'get',
            dataType: 'json',
            data:{ varity: $(e.currentTarget).val()}
        }).done(function(data) {
            if(data.length > 0){
                $("#btn_childrens").attr('disabled', false)
                load_childrens(data)
            }
        })
    }

    getBlocksByYear = function(e){


        $.ajax({
            url: '/results/getBlocksByYear',
            type: 'get',
            dataType: 'json',
            data:{ year: $(e.currentTarget).val(), locality: $("#locality_id").val()}
        }).done(function(data) {
            if(data.length > 0){
                loadBlocks(data)
            }
        })
    }

    getVarityInfo = function(e){

        $.ajax({
            url: '/results/getVarityBynameInfo',
            type: 'get',
            data:{ name: $("#myInput").val(), locality: $("#locality_id").val()}
        }).done(function(data) {
            $("#tagResume").empty().append("Información Variedad:<strong>"+$("#myInput").val()+"</strong")
            $("#content-resume").empty().html(data);
            $("#modal-resume").modal('show');
        })
    }

    load_childrens = function(data){

        tablechildrens.clear().draw()
        $.each(data, function(i,v){
            tablechildrens.row.add([
                v.id,
                v.name,
                v.mother_name,
                v.father_name
            ]).draw()
        })
    }

    loadBlocks = function(data){
        $("#block").empty();
        $.each(data, function(i,v){
            $("#block").append("<option value='"+v.id+"'>"+v.description+"</option>");

        })
    }


    $(document).ready(function(){
        var i = 0;
        $(".table").not("#table-compare-beta").DataTable();
        
        tablechildrens = $("#table-childrens").DataTable({
            "bPaginate": false,
            "ordering": false

        });
        $("#block").select2({placeholder: "Listado de Ensayos", allowClear: true});
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

        $("select[name=years]").change((e) => getBlocksByYear(e))
        $("#block").change((e) => getVaritiesList(e))
        $("#myInput").change((e) => setTimeout(function(){ getVarityInfo() }, 3000));

        $("select[name=varities_list]").change((e) => getChildrenList(e))
        
        $("#btn_childrens").click(function(e){
            e.preventDefault()
            $("#modal-childrens").modal('show')
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
            selected_varity = [];
            start_loading('buscar-prospecto')
            $.ajax({
                url: '/varities/searchHome',
                type: 'get',
                dataType: 'html',
                data: $("#search-varity").serialize()
            }).done(function(data) {
                console.log(data)
                if(data){
                   $("#resultado").html(data)
                   end_loading()
                }else{
                    alert("No data Found")
                    end_loading()
                }
                
            })
        })

        eliminarValor = function(element){
            $(element).parent().parent().parent().remove()
        }

selected_varity = [];

sync_selected_varities_from_grid = function(){
selected_varity = [];
$(".result-select-check:checked").each(function(){
var id = $(this).attr("attr-id");
var name = $(this).attr("attr-name");
if(!id || isNaN(parseInt(id)) || parseInt(id) <= 0){ return; }
selected_varity.push({
IDVARITY: id,
NAME: name
});
});
}

selected_varity_payload = function(){
return $.map(selected_varity, function(item){
var id = parseInt(item.IDVARITY);
if(isNaN(id) || id <= 0){ return null; }
return {IDVARITY: id};
});
}

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

if($(".result-select-check").length > 0){
$("#select-all-results").prop('checked', $(".result-select-check").length == $(".result-select-check:checked").length);
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
            var cleanName = $('<div>').html(v.NAME).text();
            rename = cleanName.split(';');
            rename[0] = rename[0] || '-';
            rename[1] = rename[1] || '-';
            rename[2] = rename[2] || '-';

            $('#table_datos_compare thead tr').append("<th class='text-center 50-w'><p class='w100'>"+rename[0]+"</p><p class='w50'>"+rename[1]+"</p><p class='w50'>"+rename[2]+"</p></th>");
            columns.push(i + 1);
        })

            dt_datos = $('#table_datos_compare').DataTable({
                scrollX: true,
                paging:         false,
                "ordering": false,
                "columnDefs": [ 
                    { className: "text-left", "targets": [ 0]},    
                    { className: "text-center", "targets": columns},    
                ],
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    
                }]
            })


        }

compare = function(){
sync_selected_varities_from_grid();
$.ajax({
url: "/results/info_to_compare",
type: 'GET',
data: {selected_p: selected_varity_payload(), locality: $("#locality_id").val()},
                dataType: 'json',
            }).done(function(d) {

                arr = [];
                console.log(d)
                if(d){
                    resetHeaders(selected_varity);
                    dt_datos.clear().draw();
                    global_data_test = d.data;
                    $.each(d.data, function(i,v){

                        //drop_btn = '<button class="btn btn-danger" onclick="deleteVarity(\''+v.name+'\','+v.varity_id+')" type="button"><i class="fa fa-trash"></i> Eliminar</button>'
                        console.log(v)
                        name = v.NAME.name != "" ? v.NAME.name : v.NAME.code;
                        arr = [
                            name,
                        ]

                        $.each(selected_varity, function(ix,vx){
                            var span_values = "<span class='white-tooltip' data-toggle='tooltip' data-placement='top' data-html='true'  title='Desv. STD: "+v[vx['IDVARITY']]['std']+" | n: "+v[vx['IDVARITY']]['num']+"'>"+v[vx['IDVARITY']]['value']+"</span>"
                            arr.push(span_values)
                        })

                        /*
                        $.each(v, function(index, val){
                            arr.push(val['value'])
                        })
                        */

                        dt_datos.row.add(arr).draw()
                    }) 

                $('[data-toggle="tooltip"]').tooltip();
                $("#modal-compare").modal('show')
                if (dt_datos && dt_datos.columns) {
                    try {
                        dt_datos.columns.adjust();
                    } catch (e) {
                        console.warn(e);
                    }
                }

            }
            })
        }

compareBetaState = {
loaded: false,
configs: [],
groups: [],
actions: [],
descriptions: [],
pendingConfigId: null,
compareId: null,
rowMeta: {},
filterState: {},
lastRows: [],
lastFeatures: []
};

escapeCompareBeta = function(value){
return $('<div>').text(value === null || value === undefined ? '' : value).html();
}

compareBetaSelectedFeatureIds = function(){
var ids = [];
$(".compare-beta-feature:checked").each(function(){
ids.push($(this).val());
});
return ids;
}

compareBeta = function(){
sync_selected_varities_from_grid();
if(selected_varity.length == 0){
Swal.fire('Seleccione variedades', 'Debe seleccionar al menos una variedad para comparar.', 'warning');
return;
}

if(compareBetaState.loaded){
$("#modal-compare-beta").modal('show');
compareBetaRefreshTable();
return;
}

$.ajax({
url: '/results/compare-beta/bootstrap',
type: 'GET',
dataType: 'json'
}).done(function(data){
compareBetaState.loaded = true;
compareBetaState.configs = data.configs || [];
compareBetaState.groups = data.groups || [];
compareBetaState.actions = data.actions || [];
compareBetaState.descriptions = data.descriptions || [];
compareBetaRenderConfigs();
compareBetaRenderAccordion();
compareBetaApplyConfig($("#compare-beta-config").val());
compareBetaToggleSaveButtons();
$("#modal-compare-beta").modal('show');
});
}

compareBetaRenderConfigs = function(){
var select = $("#compare-beta-config");
select.empty();

$.each(compareBetaState.configs, function(i, config){
select.append("<option value='"+config.id+"'>"+escapeCompareBeta(config.name)+"</option>");
});

var testConfig = compareBetaState.configs.find(function(config){
return config.name == 'test';
});

if(compareBetaState.pendingConfigId){
select.val(compareBetaState.pendingConfigId);
compareBetaState.pendingConfigId = null;
}else if(testConfig){
select.val(testConfig.id);
}

$("#compare-beta-config-name").val(select.find('option:selected').text());
}

compareBetaRenderAccordion = function(){
var accordion = $("#compare-beta-accordion");
accordion.empty();

$.each(compareBetaState.groups, function(i, group){
if(!group.features || group.features.length == 0){
return;
}

var groupId = 'compare-beta-group-'+group.id;
var body = "";

$.each(group.features, function(ix, feature){
body += "<label class='m-checkbox' style='display:inline-block; min-width:220px; margin-right:15px; margin-bottom:8px;'>";
body += "<input type='checkbox' class='compare-beta-feature' value='"+feature.id+"'> ";
body += escapeCompareBeta(feature.name);
body += "<span></span></label>";
});

accordion.append(
"<div class='card' style='margin-bottom:8px;'>"+
"<div class='card-header' style='padding:8px 12px;'>"+
"<a class='collapsed' data-toggle='collapse' href='#"+groupId+"' style='display:block;'>"+
"<i class='fa fa-chevron-down'></i> "+escapeCompareBeta(group.name)+
"</a>"+
"</div>"+
"<div id='"+groupId+"' class='collapse' data-parent='#compare-beta-accordion'>"+
"<div class='card-body' style='padding:12px;'>"+body+"</div>"+
"</div>"+
"</div>"
);
});

$(".compare-beta-feature").change(function(){
compareBetaRefreshTable();
});
}

compareBetaApplyConfig = function(configId){
var config = compareBetaState.configs.find(function(item){
return item.id == configId;
});

$(".compare-beta-feature").prop('checked', false);

if(config){
$("#compare-beta-config-name").val(config.name);
$.each(config.feature_ids || [], function(i, featureId){
$(".compare-beta-feature[value='"+featureId+"']").prop('checked', true);
});
}

compareBetaRefreshTable();
}

compareBetaActionOptions = function(selectedId){
var html = "<option value=''>Sin acción</option>";
$.each(compareBetaState.actions, function(i, action){
var selected = selectedId == action.id ? " selected" : "";
html += "<option value='"+action.id+"'"+selected+">"+escapeCompareBeta(action.nombre)+"</option>";
});
return html;
}

compareBetaCaptureMeta = function(){
$("#table-compare-beta tbody tr").each(function(){
var id = $(this).attr('data-varity-id');
if(!id){ return; }
compareBetaState.rowMeta[id] = {
rank: $(this).find('.compare-beta-rank').val(),
free_description: $(this).find('.compare-beta-free-description').val(),
description: $(this).find('.compare-beta-description').val(),
id_type_action: $(this).find('.compare-beta-action').val()
};
});
}

compareBetaCaptureFilters = function(){
$(".compare-beta-filter").each(function(){
var featureId = $(this).attr('data-feature-id');
if(!featureId){ return; }
compareBetaState.filterState[featureId] = compareBetaState.filterState[featureId] || {};
compareBetaState.filterState[featureId].value = $(this).val();
});
$(".compare-beta-filter-op").each(function(){
var featureId = $(this).attr('data-feature-id');
if(!featureId){ return; }
compareBetaState.filterState[featureId] = compareBetaState.filterState[featureId] || {};
compareBetaState.filterState[featureId].op = $(this).val();
});
}

compareBetaRestoreFilters = function(){
$.each(compareBetaState.filterState, function(featureId, filter){
$(".compare-beta-filter[data-feature-id='"+featureId+"']").val(filter.value || '');
$(".compare-beta-filter-op[data-feature-id='"+featureId+"']").val(filter.op || '=');
});
compareBetaApplyExcelFilters();
}

compareBetaRefreshActionSelectors = function(selectedActionId){
$(".compare-beta-action").each(function(){
var current = selectedActionId || $(this).val();
$(this).html(compareBetaActionOptions(current));
});
}

compareBetaRefreshTable = function(){
compareBetaCaptureMeta();
compareBetaCaptureFilters();
var featureIds = compareBetaSelectedFeatureIds();
var thead = $("#table-compare-beta thead");
var tbody = $("#table-compare-beta tbody");

thead.empty();
tbody.empty();

if(featureIds.length == 0){
thead.append("<tr><th>Variedad</th><th>Padre</th><th>Madre</th><th>Rank</th><th>Obs1</th><th>Descripción</th><th>Acción</th></tr>");
compareBetaState.lastRows = [];
compareBetaState.lastFeatures = [];
return;
}

$.ajax({
url: '/results/compare-beta/data',
type: 'POST',
dataType: 'json',
data: {
_token: $('meta[name="csrf-token"]').attr('content'),
selected_p: selected_varity_payload(),
feature_ids: featureIds,
locality: $("#locality_id").val()
}
}).done(function(data){
compareBetaState.lastRows = data.rows || [];
compareBetaState.lastFeatures = data.features || [];

var filterRow = "<tr class='compare-beta-filter-row'><th></th><th></th><th></th><th></th><th></th><th></th><th></th>";
var header = "<tr><th>Variedad</th><th>Padre</th><th>Madre</th><th>Rank</th><th>Obs1</th><th>Descripción</th><th>Acción</th>";

$.each(compareBetaState.lastFeatures, function(i, feature){
header += "<th>"+escapeCompareBeta(feature.name)+"</th>";
filterRow += "<th><div class='input-group input-group-sm'><select class='form-control compare-beta-filter-op' data-feature-id='"+feature.id+"' style='max-width:52px; padding-left:4px; padding-right:4px;'><option value='='>=</option><option value='>'>&gt;</option><option value='<'>&lt;</option></select><input type='text' class='form-control compare-beta-filter' data-feature-id='"+feature.id+"' placeholder='Filtro'></div></th>";
});

header += "</tr>";
filterRow += "</tr>";
thead.html(header + filterRow);

$.each(compareBetaState.lastRows, function(i, row){
var meta = compareBetaState.rowMeta[row.id] || {};
var html = "<tr data-varity-id='"+row.id+"'>";
html += "<td>"+escapeCompareBeta(row.varity)+"</td>";
html += "<td>"+escapeCompareBeta(row.father)+"</td>";
html += "<td>"+escapeCompareBeta(row.mother)+"</td>";
html += "<td><input type='number' class='form-control form-control-sm compare-beta-rank' value='"+escapeCompareBeta(meta.rank)+"'></td>";
html += "<td><input type='text' class='form-control form-control-sm compare-beta-free-description' value='"+escapeCompareBeta(meta.free_description)+"'></td>";
html += "<td><input type='text' class='form-control form-control-sm compare-beta-description' value='"+escapeCompareBeta(meta.description)+"'></td>";
html += "<td><select class='form-control form-control-sm compare-beta-action'>"+compareBetaActionOptions(meta.id_type_action)+"</select></td>";

$.each(compareBetaState.lastFeatures, function(ix, feature){
var value = row.features && row.features[feature.id] ? row.features[feature.id] : {value: '-'};
html += "<td class='compare-beta-feature-cell' data-feature-id='"+feature.id+"' title='n: "+escapeCompareBeta(value.num)+" | Desv. STD: "+escapeCompareBeta(value.std)+"'>"+escapeCompareBeta(value.value)+"</td>";
});

html += "</tr>";
tbody.append(html);
});

compareBetaBindRowMetaEvents();
compareBetaBindExcelFilters();
compareBetaRestoreFilters();
});
}

compareBetaBindRowMetaEvents = function(){
$(".compare-beta-rank, .compare-beta-free-description, .compare-beta-description, .compare-beta-action").off('change keyup').on('change keyup', function(){
compareBetaCaptureMeta();
});
}

compareBetaBindExcelFilters = function(){
$(".compare-beta-filter, .compare-beta-filter-op").off('keyup change input').on('keyup change input', function(){
compareBetaCaptureFilters();
compareBetaApplyExcelFilters();
});
}

compareBetaApplyExcelFilters = function(){
var filters = {};
$(".compare-beta-filter").each(function(){
var value = $(this).val();
if(value !== ''){
var featureId = $(this).attr('data-feature-id');
filters[featureId] = {
op: $(".compare-beta-filter-op[data-feature-id='"+featureId+"']").val(),
value: value.toString().toLowerCase()
};
}
});

$("#table-compare-beta tbody tr").each(function(){
var show = true;
var row = $(this);
$.each(filters, function(featureId, filter){
var text = row.find(".compare-beta-feature-cell[data-feature-id='"+featureId+"']").text().toLowerCase();
var numberText = text.replace(',', '.');
var numberFilter = filter.value.replace(',', '.');
var textNumber = parseFloat(numberText);
var filterNumber = parseFloat(numberFilter);

if(filter.op == '=' && !isNaN(textNumber) && !isNaN(filterNumber)){
if(textNumber !== filterNumber){ show = false; }
}else if(filter.op == '='){
if(text !== filter.value){ show = false; }
}else if(!isNaN(textNumber) && !isNaN(filterNumber)){
if(filter.op == '>' && !(textNumber >= filterNumber)){ show = false; }
if(filter.op == '<' && !(textNumber <= filterNumber)){ show = false; }
}else{
show = false;
}
});
row.toggle(show);
});
}

compareBetaClearFilters = function(){
compareBetaState.filterState = {};
$(".compare-beta-filter").val('');
$(".compare-beta-filter-op").val('=');
compareBetaApplyExcelFilters();
}

compareBetaPayloadDetails = function(){
compareBetaCaptureMeta();
var details = [];
$.each(compareBetaState.lastRows, function(i, row){
var meta = compareBetaState.rowMeta[row.id] || {};
var featureValues = {};
$.each(compareBetaState.lastFeatures, function(ix, feature){
featureValues[feature.id] = row.features && row.features[feature.id] ? row.features[feature.id] : {value: '-'};
});
details.push({
id_varity: row.id,
rank: meta.rank || '',
free_description: meta.free_description || '',
description: meta.description || '',
id_type_action: meta.id_type_action || '',
feature_values: featureValues
});
});
return details;
}

compareBetaToggleSaveButtons = function(){
if(compareBetaState.compareId){
$("#compare-beta-save").hide();
$("#compare-beta-update").show();
$("#compare-beta-new").show();
}else{
$("#compare-beta-save").show();
$("#compare-beta-update").hide();
$("#compare-beta-new").hide();
}
}

compareBetaSaveSnapshot = function(forceNew){
var title = $.trim($("#compare-beta-title").val());
if(forceNew){
compareBetaState.compareId = null;
compareBetaToggleSaveButtons();
title = '';
$("#compare-beta-title").val('');
}

if(title === ''){
Swal.fire('Título requerido', 'Ingresa el título en el campo Título comparación.', 'warning');
$("#compare-beta-title").focus();
return;
}

Swal.fire({
title: '¿Confirmar acción?',
text: forceNew ? 'Se creará una nueva comparación.' : (compareBetaState.compareId ? 'Se reemplazará el detalle guardado.' : 'Se guardará la comparación actual.'),
type: 'question',
showCancelButton: true,
confirmButtonText: 'Confirmar'
}).then(function(confirm){
if(!confirm.value){ return; }
$.ajax({
url: '/results/compare-beta/save',
type: 'POST',
dataType: 'json',
data: {
_token: $('meta[name="csrf-token"]').attr('content'),
compare_id: forceNew ? '' : compareBetaState.compareId,
nombre: title,
feature_ids: compareBetaSelectedFeatureIds(),
details: compareBetaPayloadDetails()
}
}).done(function(response){
compareBetaState.compareId = response.compare_id;
$("#compare-beta-title").val(title);
compareBetaToggleSaveButtons();
Swal.fire('Comparación guardada', '', 'success');
});
});
}

$("#compare-beta-config").off('change').on('change', function(){
compareBetaApplyConfig($(this).val());
});

$("#compare-beta-save-config").off('click').on('click', function(){
$.ajax({
url: '/results/compare-beta/config',
type: 'POST',
dataType: 'json',
data: {
_token: $('meta[name="csrf-token"]').attr('content'),
id: $("#compare-beta-config").val(),
name: $("#compare-beta-config-name").val(),
feature_ids: compareBetaSelectedFeatureIds()
}
}).done(function(response){
compareBetaState.loaded = false;
compareBetaState.pendingConfigId = response.config_id;
Swal.fire('Configuración guardada', '', 'success');
compareBeta();
});
});

$("#compare-beta-add-action").off('click').on('click', function(){
var name = $("#compare-beta-new-action").val();
Swal.fire({
title: '¿Agregar acción?',
text: name,
type: 'question',
showCancelButton: true,
confirmButtonText: 'Agregar'
}).then(function(result){
if(!result.value){ return; }
$.ajax({
url: '/results/compare-beta/action',
type: 'POST',
dataType: 'json',
data: {
_token: $('meta[name="csrf-token"]').attr('content'),
nombre: name
}
}).done(function(response){
compareBetaCaptureMeta();
compareBetaState.actions = response.actions || [];
$("#compare-beta-new-action").val('');
compareBetaRefreshActionSelectors();
Swal.fire('Acción agregada', '', 'success');
});
});
});

$("#compare-beta-save").off('click').on('click', function(){ compareBetaSaveSnapshot(false); });
$("#compare-beta-update").off('click').on('click', function(){ compareBetaSaveSnapshot(false); });
$("#compare-beta-new").off('click').on('click', function(){ compareBetaSaveSnapshot(true); });
$("#compare-beta-clear-filters").off('click').on('click', function(){ compareBetaClearFilters(); });

getDetailInfo  = function(feature, varity){
            global_feature = feature

            $.ajax({
                url: '/results/getDataFeature/',
                type: 'GET',
                data: {varity: varity, feature : feature},
            }).done(function(data) {
                $("#content-features").empty().html(data);
                $("#modal-features").modal('show');
            })
        }

        $('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
            class_x = $(this).attr('class')
          alert(class_x)
        })

        $("#modal-compare").on('hidden.bs.modal', function (e) {
            dt_datos.clear().draw();
            alert("limpio")
        })

        /* TRASPASO DE VARIEDADES */

        allblockdata = <?= json_encode($blocks_list); ?>;

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
                        data: {selected_p: selected_varity, block: $("#id_block").val(), destiny: inputValue.value},
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


    })

function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i]['name'].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i]['name'].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i]['name'].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' attr-id='"+arr[i]['name']+"' value='" + arr[i]['name'] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              inp.setAttribute("attr-id", this.getElementsByTagName("input")[0].getAttribute("attr-id"))
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}

/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);

});
}
listVarities =  <?= json_encode($varities); ?>;

autocomplete(document.getElementById("myInput"), listVarities);

    
</script>
@endsection
