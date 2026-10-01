@extends('layouts.app')
@section('content')
<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
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

    #chartdiv {
        width: 100%;
        height: 500px;
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
                    Configuración Búsqueda Frituras
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <div class="autocomplete" style="width:300px;">
                        <input id="myInput" class="form-control" type="text" name="myCountry" placeholder="Buscador Variedades">
                    </div>
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
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <form id="search-varity">
            <div class="row">
                <input type="hidden" name="fries" value="1">
                 <div class="col-md-3" style="margin-bottom: 30px;">
                    <strong>Filtrar Ensayos por Año</strong>
                    {!! Form::select('years', $years, null , array('placeholder' => 'Listado de Años','class' => 'form-control')) !!}
                </div>
                <div class="col-md-3" style="margin-bottom: 30px;">
                    <strong>Busqueda Ensayos</strong>
                    {!! Form::select('block', $blocks_list, null , array('placeholder' => 'Listado de Ensayos','class' => 'form-control')) !!}
                </div>
                <div class="col-md-4" style="margin-left: ;">
                    <strong>Búsqueda Ponderada</strong>
                    <div class="col-md-2">
                        <span class="m-switch m-switch--lg m-switch--icon m-switch--brand">
                            <label>
                                <input type="checkbox" name="ponderada" value="1">
                                <span></span>
                            </label>
                        </span>
                    </div>
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

<div class="modal fade show" id="modal-fries" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-children"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="content-fries"></div>
            </div>
            <div class="modal-footer">
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
            data:{ block: $(e.currentTarget).val()}
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
            data:{ year: $(e.currentTarget).val()}
        }).done(function(data) {
            if(data.length > 0){
                loadBlocks(data)
            }
        })
    }

    getVarityInfo = function(e){

        $.ajax({
            url: '/results/getVarityByname',
            type: 'get',
            dataType: 'json',
            data:{ year: $("#myInput").val()}
        }).done(function(data) {
            if(data){
                //selected_varity.length > 0 ? selected_varity.push(data) : 
                selected_varity.push(data)
                console.log(selected_varity)
            }
        })
    }

    load_childrens = function(data){
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
        $("select[name=block]").empty();
        $.each(data, function(i,v){
            $("select[name=block]").append("<option value='"+v.id+"'>"+v.description+"</option>");

        })
    }


    $(document).ready(function(){
        var i = 0;
        $(".table").DataTable();
        
        tablechildrens = $("#table-childrens").DataTable({
            "bPaginate": false,
            "ordering": false

        });
        $("select[name=block]").select2();
        $("#btn-agregar-caracteristica").click(function(){
            $.ajax({
                url: '/varities/addFeature',
                type: 'get',
                dataType: 'html',
                data:{ i: i, fries: 1}
            }).done(function(data) {
                i = i + 1
                $("#search-varity").append(data)
                $(".ts-2").select2();
            })
        })

        $("select[name=years]").change((e) => getBlocksByYear(e))
        $("select[name=block]").change((e) => getVaritiesList(e))
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
                url: '/varities/searchHomeFries',
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
                console.log(d)
                if(d){
                    resetHeaders(selected_varity);
                    dt_datos.clear().draw();
                    global_data_test = d.data;
                    $.each(d.data, function(i,v){

                        //drop_btn = '<button class="btn btn-danger" onclick="deleteVarity(\''+v.name+'\','+v.varity_id+')" type="button"><i class="fa fa-trash"></i> Eliminar</button>'
                        console.log(v)
                        arr = [
                            v.NAME.name,
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
                    dt_datos.columns.adjust();

                }
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
          b.innerHTML += "<input type='hidden' value='" + arr[i]['name'] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
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
    var x = document.getElementsByClassName("autocomplete-items");
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
listVarities =  <?= json_encode($varities); ?>;;

autocomplete(document.getElementById("myInput"), listVarities);

getDetailInfo  = function(feature, varity){

    global_feature = feature

    $.ajax({
        url: '/fries/getData/',
        type: 'GET',
        data: {varity: varity, feature : feature},
    }).done(function(data) {
        $("#content-fries").empty().html(data);
        $("#modal-fries").modal('show');
    })
}

updateFries = function(year, varity_id, id_data, option = null){


    params_fries = {
        year: year, feature : global_feature, varity_id: varity_id, id_data: id_data,
        enero: $("#"+id_data+"_enero").val(),
        febrero: $("#"+id_data+"_febrero").val(), 
        marzo: $("#"+id_data+"_marzo").val(), 
        abril: $("#"+id_data+"_abril").val(), 
        mayo: $("#"+id_data+"_mayo").val(), 
        junio: $("#"+id_data+"_junio").val(), 
        julio: $("#"+id_data+"_julio").val(), 
        agosto: $("#"+id_data+"_agosto").val(), 
        septiembre: $("#"+id_data+"_septiembre").val(), 
        octubre: $("#"+id_data+"_octubre").val(), 
        noviembre: $("#"+id_data+"_noviembre").val(), 
        diciembre: $("#"+id_data+"_diciembre").val(), 
        option: option
    }

    $.ajax({
        url: '/fries/updateFries/',
        type: 'GET',
        data: params_fries,
        dataType: 'json',
    }).done(function(d) {
        if(d.status){
            swal('acción completada Correctamente', 'success')
            getDetailInfo(global_feature, varity_id)
        }else{
            swal('No se pudo actualizar', 'error')
        }
    })
}
    
</script>
@endsection

