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
                    Configuración Búsqueda Cruzamientos
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        <form id="search-varity">
            <div class="row">
                 <div class="col-md-3" style="margin-bottom: 30px;">
                    <strong>Filtrar por Invernadero</strong>
                    {!! Form::select('mapout_id', $greenhouse,null,['class' => 'form-control required','placeholder' => 'Seleccione Invernadero']); !!}
                </div>
                <div class="col-md-3" style="margin-bottom: 30px;">
                    <strong>Filtrar por Bayas</strong>
            		{!! Form::text('berries', null, array('placeholder' => 'Ingrese Nº Bayas a buscar','class' => 'form-control')) !!}
                </div>
                <div class="col-md-3" style="margin-bottom: 30px;">
                    <strong>Filtrar por Vasos</strong>
            		{!! Form::text('glasses', null, array('placeholder' => 'Ingrese Nº Vasos a buscar','class' => 'form-control')) !!}
                </div>
                <div class="col-md-3" style="margin-top: 15px;">
            		<button type="button" id="buscar-prospecto" class="btn btn-success" >Buscar</button>    
                </div>
            </div>
            <hr>
        </form>
        <div class="col-md-12" style="margin-top: 30px;">
            <div id="resultado"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
	
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
                url: '/results/searchCrossings',
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

    })
</script>
@endsection

