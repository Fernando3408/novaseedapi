@extends('layouts.app')
@section('content')
<style type="text/css">
    .w100{ max-width: 100px; line-height: 15px; }
    .w50{ max-width: 100px; font-size: 10px; }   
    .ui-tooltip {background-color: white;}
    * { box-sizing: border-box; }

    .autocomplete {
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
    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;
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
      background-color: #e9e9e9;
    }
    .autocomplete-active {
      background-color: DodgerBlue !important;
      color: #ffffff;
    }
</style>

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Módulo de Reportes
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
                    <button type="button" id="btn-agregar-variedad" class="btn btn-outline-success">
                        <i class="fa fa-plus" style="color: green"></i> Agregar
                    </button>
                </li>
                <li class="m-portlet__nav-item">
                    <button type="button" class="btn btn-outline-info" onclick="modificarCaracteristicas()">
                        <i class="fa fa-list"></i> Características
                    </button>
                </li>
                <li class="m-portlet__nav-item">
                    <button type="button" class="btn btn-outline-primary" onclick="loadData()">
                        <i class="fa fa-search"></i> Vista Previa
                    </button>
                </li>
                <li class="m-portlet__nav-item">
                    <button type="button" class="btn btn-outline-warning" onclick="compare()">
                        <i class="fa fa-filter" style="color: orange"></i> Comparar
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <table class="table table-striped table-bordered table-hover" id="varities_selected">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">Selección</th>
                    <th>Nombre</th>
                    <th>Padre</th>
                    <th>Madre</th>
                    <th style="width: 100px; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__body" id="preview_container">
    </div>
</div>

<div class="modal fade show" id="modal-caracteristicas" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Selección de características</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<div class="modal fade show" id="modal-resume" tabindex="-1" role="dialog" >
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

<div class="modal fade show" id="modal-compare" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comparación de Variedades</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="table_datos_compare">
                    <thead>
                        <tr></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var table_selected;
    var dt_datos;
    var array_find = []; // Almacena las características seleccionadas
    
    // Construimos el arreglo con los datos reales de forma segura
    var listVarities = [];
    @if(isset($varities))
        @foreach($varities as $v)
            listVarities.push({
                id: "{{ $v->id }}",
                name: "{!! addslashes($v->name) !!}",
                nombre_padre_real: "{!! addslashes($v->fatherName() ?? '') !!}",
                nombre_madre_real: "{!! addslashes($v->motherName() ?? '') !!}"
            });
        @endforeach
    @endif

    $(document).ready(function(){
        table_selected = $("#varities_selected").DataTable({
            "language": {
                "url": "/js/datatables.spanish.json"
            }
        });

        $("#myInput").change(function(e) {
            setTimeout(function(){ getVarityInfo() }, 1000);
        });

        $("#btn-agregar-variedad").click(function(){
            var inputVal = document.getElementById("myInput").value;
            if (inputVal === "") return;

            var varityData = listVarities.find(function(v) { return v.name === inputVal; });

            if (varityData) {
                var checkbox = '<center><input type="checkbox" class="check-compare" value="' + varityData.id + '" data-name="' + varityData.name + '"></center>';
                var btnDelete = '<center><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)"><i class="fa fa-trash"></i> Eliminar</button></center>';
                
                var padre = (varityData.nombre_padre_real && varityData.nombre_padre_real !== "") ? varityData.nombre_padre_real : 'Sin datos';
                var madre = (varityData.nombre_madre_real && varityData.nombre_madre_real !== "") ? varityData.nombre_madre_real : 'Sin datos';

                table_selected.row.add([
                    checkbox,
                    varityData.name,
                    padre,
                    madre,
                    btnDelete
                ]).draw();

                document.getElementById("myInput").value = "";
            } else {
                alert("Por favor, seleccione una variedad válida de la lista.");
            }
        });

        autocomplete(document.getElementById("myInput"), listVarities);
    });

    // --- Funciones de Características y Vista Previa ---

    modificarCaracteristicas = function(id = null){ 
        $.ajax({
            url: '/results/modCaracteristicas',
            type: 'GET',
            dataType: 'html',
            data:{id:id}
        }).done(function(data) {
            array_find = []; // Se reinicia el arreglo cada vez que se abre
            $("#modal-caracteristicas .modal-body").html(data);
            $("#modal-caracteristicas").modal("show");
        });
    }

    selectTypes = function(index, id){
        if($(index).prop('checked')){
            $(".type_"+id).click();
        }else{
            $(".type_"+id).removeAttr('checked');
        }
    }

    registrarCheck = function(indice,name){
        var item = {};
        
        for (var i = 0; i < array_find.length; i++) {
            if ( (array_find[i]['IDFEATURE'] == indice)) {
                array_find.splice(i, 1);
            };
        };

        if ( $("#index_"+indice).is(':checked') ) {
            item['IDFEATURE'] = indice;
            item['NAMEFEATURE'] = name;
            array_find.push(item);
        }
    }

    loadData = function(){
        var array_varity = [];
        
        // Obtenemos las variedades seleccionadas desde los checkboxes de la grilla
        $(".check-compare:checked").each(function(index) {
            array_varity.push({
                'INDICE': index,
                'IDVARITY': $(this).val()
            });
        });

        if(array_varity.length === 0){
            alert("Debe seleccionar al menos una variedad de la lista usando los checkbox.");
            return;
        }

        $.ajax({
            url: '/results/load_preview',
            type: 'GET',
            dataType: 'html',
            data:{array_find: array_find, array_varity: array_varity}
        }).done(function(data) {
            $("#preview_container").html(data);
        });
    }

    // --- Funciones originales del módulo ---

    getVarityInfo = function(){
        var localidad = $("#locality_id").length ? $("#locality_id").val() : "";
        var nombreVariedad = $("#myInput").val();

        if(nombreVariedad !== "" && listVarities.find(v => v.name === nombreVariedad)) {
            $.ajax({
                url: '/results/getVarityBynameInfo',
                type: 'get',
                data:{ name: nombreVariedad, locality: localidad }
            }).done(function(data) {
                $("#tagResume").empty().append("Información Variedad:<strong>"+nombreVariedad+"</strong>");
                $("#content-resume").empty().html(data);
                $("#modal-resume").modal('show');
            }).fail(function() {
                console.log("Error al consultar la variedad.");
            });
        }
    }

    eliminarFila = function(btn) {
        table_selected.row($(btn).parents('tr')).remove().draw();
    }

    resetHeaders = function(selected_varity){
        if($.fn.DataTable.isDataTable('#table_datos_compare')){
            dt_datos.clear().draw();
            $("#table_datos_compare").dataTable().fnDestroy();
        }   

        $("#table_datos_compare thead tr").empty();
        $('#table_datos_compare thead tr').append("<th>Característica</th>");
        var columns = [];
        
        $.each(selected_varity, function(i,v){
            var rename = v.NAME.split(';');
            var colName = rename[0] || v.NAME;
            $('#table_datos_compare thead tr').append("<th class='text-center w100'>" + colName + "</th>");
            columns.push(i + 1);
        });

        dt_datos = $('#table_datos_compare').DataTable({
            scrollX: true,
            paging: false,
            "ordering": false,
            "columnDefs": [ 
                { className: "text-left", "targets": [ 0 ] },    
                { className: "text-center", "targets": columns }    
            ],
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: 'Excel',
            }]
        });
    }

    compare = function(){
        var selected_varity = [];
        
        $(".check-compare:checked").each(function() {
            var item = {
                'IDVARITY': $(this).val(),
                'NAME': $(this).attr('data-name')
            };
            selected_varity.push(item);
        });

        if(selected_varity.length === 0){
            alert("Debe seleccionar al menos una variedad de la lista usando los checkbox.");
            return;
        }

        var localidad = $("#locality_id").length ? $("#locality_id").val() : "";

        $.ajax({
            url: "/results/info_to_compare",
            type: 'GET',
            data: { selected_p: selected_varity, locality: localidad },
            dataType: 'json',
        }).done(function(d) {
            if(d && d.data){
                resetHeaders(selected_varity);
                dt_datos.clear().draw();
                
                $.each(d.data, function(i,v){
                    var name = v.NAME.name != "" ? v.NAME.name : v.NAME.code;
                    var arr = [name];

                    $.each(selected_varity, function(ix,vx){
                        var valObj = v[vx['IDVARITY']];
                        if(valObj){
                            var span_values = "<span class='white-tooltip' data-toggle='tooltip' data-placement='top' data-html='true' title='Desv. STD: "+valObj['std']+" | n: "+valObj['num']+"'>"+valObj['value']+"</span>";
                            arr.push(span_values);
                        } else {
                            arr.push("-");
                        }
                    });

                    dt_datos.row.add(arr).draw();
                }); 

                $('[data-toggle="tooltip"]').tooltip();
                $("#modal-compare").modal('show');
                setTimeout(function(){ dt_datos.columns.adjust(); }, 200);
            }
        });
    }

    function autocomplete(inp, arr) {
      var currentFocus;
      inp.addEventListener("input", function(e) {
          var a, b, i, val = this.value;
          closeAllLists();
          if (!val) { return false;}
          currentFocus = -1;
          a = document.createElement("DIV");
          a.setAttribute("id", this.id + "autocomplete-list");
          a.setAttribute("class", "autocomplete-items");
          this.parentNode.appendChild(a);
          for (i = 0; i < arr.length; i++) {
            if (arr[i]['name'].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
              b = document.createElement("DIV");
              b.innerHTML = "<strong>" + arr[i]['name'].substr(0, val.length) + "</strong>";
              b.innerHTML += arr[i]['name'].substr(val.length);
              b.innerHTML += "<input type='hidden' attr-id='"+arr[i]['name']+"' value='" + arr[i]['name'] + "'>";
              b.addEventListener("click", function(e) {
                  inp.value = this.getElementsByTagName("input")[0].value;
                  inp.setAttribute("attr-id", this.getElementsByTagName("input")[0].getAttribute("attr-id"))
                  closeAllLists();
                  $(inp).trigger("change");
              });
              a.appendChild(b);
            }
          }
      });
      inp.addEventListener("keydown", function(e) {
          var x = document.getElementById(this.id + "autocomplete-list");
          if (x) x = x.getElementsByTagName("div");
          if (e.keyCode == 40) {
            currentFocus++;
            addActive(x);
          } else if (e.keyCode == 38) { 
            currentFocus--;
            addActive(x);
          } else if (e.keyCode == 13) {
            e.preventDefault();
            if (currentFocus > -1) {
              if (x) x[currentFocus].click();
            }
          }
      });
      function addActive(x) {
        if (!x) return false;
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        x[currentFocus].classList.add("autocomplete-active");
      }
      function removeActive(x) {
        for (var i = 0; i < x.length; i++) {
          x[i].classList.remove("autocomplete-active");
        }
      }
      function closeAllLists(elmnt) {
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
          if (elmnt != x[i] && elmnt != inp) {
            x[i].parentNode.removeChild(x[i]);
          }
        }
      }
      document.addEventListener("click", function (e) {
          closeAllLists(e.target);
      });
    }
</script>
@endsection