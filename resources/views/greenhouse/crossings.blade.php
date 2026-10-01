@extends('layouts.app')
@section('content')
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
<script src="https://cdn.amcharts.com/lib/4/geodata/usaAlbersLow.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<style type="text/css">
    #chartdiv {
      width: 100%;
      height: 3200px;
    }
</style>
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-calendar"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    {{ $greenhouse->name }}
                    <input type="hidden" id="id_greenhouse" value="{{ $greenhouse->id }}">
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
                <a class="nav-link active" data-toggle="tab" href="#add_crossings">Agregar Cruces</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#added_crossings">Listado de Cruces Agregados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#resumen_crossings">Resumen Cruces Agregados</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active show" id="add_crossings" role="tabpanel">
            </div>
            <div class="tab-pane" id="added_crossings" role="tabpanel">
            </div>
            <div class="tab-pane" id="resumen_crossings" role="tabpanel">
                <dir class="row">
                    <div class="col-md-3">
                        <input placeholder="Fecha Desde" class="form-control timepicker" required="" id="desde" name="desde" type="text">
                    </div>
                     <div class="col-md-3">
                        <input placeholder="Fecha Hasta" class="form-control timepicker" required="" id="hasta" name="hasta" type="text">
                    </div>
                    <button type="button" class="btn btn-info" onclick="filtrar()"><i class="fa fa-search"></i> Filtrar</button>
                    <button type="button" class="btn btn-success" style="margin-left: 20px;" onclick="download()"><i class="fa fa-download"></i> Exportar</button>
                </dir>
                
                <div id="chartdiv"></div>       
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    
    array_find = [];
    array_delete = [];

    $(document).ready(function(){

        table_crossings = $("#table_crossings").DataTable({
            bPaginate:false,
            
        })

        $(".timepicker").datetimepicker({
            orientation: "bottom",
        });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        draw_crossings_add()
        draw_crossings_added();

        data_chart = <?= json_encode($data_chart); ?>;
        draw_chart(data_chart);
    })

   

    draw_chart = function(usData){
        am4core.useTheme(am4themes_animated);
        // Themes end
        let modifiedData = usData.map(item => {
          return {
            ...item,
            age: item.age + " (" + (parseInt(item.male)  + parseInt(item.female)) +")" // Concatenar texto personalizado
          };
        });
        var mainContainer = am4core.create("chartdiv", am4core.Container);
        mainContainer.width = am4core.percent(100);
        mainContainer.height = am4core.percent(100);
        mainContainer.layout = "horizontal";

        var maleChart = mainContainer.createChild(am4charts.XYChart);
        maleChart.paddingRight = 0;
        maleChart.data = JSON.parse(JSON.stringify(modifiedData));

        // Create axes
        var maleCategoryAxis = maleChart.yAxes.push(new am4charts.CategoryAxis());
        maleCategoryAxis.dataFields.category = "age";
        maleCategoryAxis.renderer.grid.template.location = 0;
        //maleCategoryAxis.renderer.inversed = true;
        maleCategoryAxis.renderer.minGridDistance = 15;

        var maleValueAxis = maleChart.xAxes.push(new am4charts.ValueAxis());
        maleValueAxis.renderer.inversed = true;
        maleValueAxis.min = 0;
        maleValueAxis.max = 20;
        maleValueAxis.strictMinMax = true;

        maleValueAxis.numberFormatter = new am4core.NumberFormatter();
        maleValueAxis.numberFormatter.numberFormat = "#.#'%'";

        // Create series
        var maleSeries = maleChart.series.push(new am4charts.ColumnSeries());
        maleSeries.dataFields.valueX = "male";
        maleSeries.dataFields.valueXShow = "percent";
        maleSeries.calculatePercent = true;
        maleSeries.dataFields.categoryY = "age";
        maleSeries.interpolationDuration = 1000;
        maleSeries.columns.template.tooltipText = "{categoryY}: {valueX} ({valueX.percent.formatNumber('#.0')}%)";
        var labelBullet = maleSeries.bullets.push(new am4charts.LabelBullet())
        labelBullet.label.horizontalCenter = "right";
        labelBullet.label.text = "{valueX}";
        labelBullet.label.textAlign = "end";
        labelBullet.label.dx = -10;
        //maleSeries.sequencedInterpolation = true;


        var femaleChart = mainContainer.createChild(am4charts.XYChart);
        femaleChart.paddingLeft = 0;
        femaleChart.data = JSON.parse(JSON.stringify(modifiedData));

        // Create axes
        var femaleCategoryAxis = femaleChart.yAxes.push(new am4charts.CategoryAxis());
        femaleCategoryAxis.renderer.opposite = true;
        femaleCategoryAxis.dataFields.category = "age";
        femaleCategoryAxis.renderer.grid.template.location = 0;
        femaleCategoryAxis.renderer.minGridDistance = 15;

        var femaleValueAxis = femaleChart.xAxes.push(new am4charts.ValueAxis());
        femaleValueAxis.min = 0;
        femaleValueAxis.max = 20;
        femaleValueAxis.strictMinMax = true;
        femaleValueAxis.numberFormatter = new am4core.NumberFormatter();
        femaleValueAxis.numberFormatter.numberFormat = "#.#'%'";
        femaleValueAxis.renderer.minLabelPosition = 0.01;

        // Create series
        var femaleSeries = femaleChart.series.push(new am4charts.ColumnSeries());
        femaleSeries.dataFields.valueX = "female";
        femaleSeries.dataFields.valueXShow = "percent";
        femaleSeries.calculatePercent = true;
        femaleSeries.fill = femaleChart.colors.getIndex(4);
        femaleSeries.stroke = femaleSeries.fill;
        //femaleSeries.sequencedInterpolation = true;
        femaleSeries.columns.template.tooltipText = "{categoryY}: {valueX} ({valueX.percent.formatNumber('#.0')}%)";
        femaleSeries.dataFields.categoryY = "age";
        femaleSeries.interpolationDuration = 1000;

        var labelBullet = femaleSeries.bullets.push(new am4charts.LabelBullet())
        labelBullet.label.horizontalCenter = "right";
        labelBullet.label.text = "{valueX}";
        labelBullet.label.textAlign = "end";
        labelBullet.label.dx = 10;

        mainContainer.exporting.menu = new am4core.ExportMenu();
        mainContainer.exporting.filePrefix = "Resumen Cruces Agregados";
    }

    draw_crossings_add = function(){
        $('#add_crossings').empty()

        $.ajax({
          url:  "/greenhouse/crossings_add",
          success: function(data) {
            $('#add_crossings').empty().html(data);
          }
        });
    } 

    filtrar =  function(){
        var params = {
            desde : $("#desde").val(),
            hasta : $("#hasta").val(),
            id: $("#id_greenhouse").val(),
        }

        $.ajax({
            url: '/greenhouse/get_data_chart',
            type: 'GET',
            dataType: 'json',
            data: params
        }).done(function(data) {
            if(data){
                if(data.length > 0){
                    data_chart = data;
                    draw_chart(data);
                }else{
                    sweetAlert("No se encontraron datos para las fechas ingresadas")
                }
            }
        })
    }

     download = function(){
        let jsonData = JSON.stringify(data_chart);
        link = "/greenhouse/export?data="+jsonData
        window.location.href = link;
        $("#frame_download").attr("src",link)
    }

    draw_crossings_added = function(){
        $('#added_crossings').empty()

        $.ajax({
          url:  "/greenhouse/crossings_added/"+$("#id_greenhouse").val(),
          success: function(data) {
            $('#added_crossings').empty().html(data);
          }
        });
    } 

    registrarCheck = function(indice){
        var item = {};
        
        for (var i = 0; i < array_find.length; i++) {
            if ( (array_find[i]['IDVARITY'] == indice)) {
                array_find.splice(i, 1);
            };
        };

        if ( $("#index_"+indice).is(':checked') ) {
            item['IDVARITY'] = indice;
            array_find.push(item);
        }
    }

    seleccionar = function(){
        $.ajax({
            url: '/greenhouse/store_crossings',
            type: 'POST',
            dataType: 'json',
            data: {arr_crossings : array_find, greenhouse_id: $("#id_greenhouse").val(),_token: "{{ csrf_token() }}"},
        }).done(function(data) {
            if(data){
                sweetAlert("Registro Ingresado Correctamente")
                draw_crossings_add()
                draw_crossings_added()
            }
        })
    }

    toDelete = function(indice){
        var item = {};
        
        for (var i = 0; i < array_delete.length; i++) {
            if ( (array_delete[i]['IDVARITY'] == indice)) {
                array_delete.splice(i, 1);
            };
        };

        if ( $("#_dindex_"+indice).is(':checked') ) {
            item['IDVARITY'] = indice;
            array_delete.push(item);
        }
    }

    delete_selected = function(){
        $.ajax({
            url: '/greenhouse/delete_crossings',
            type: 'POST',
            dataType: 'json',
            data: {arr_crossings : array_delete, greenhouse_id: $("#id_greenhouse").val(),_token: "{{ csrf_token() }}"},
        }).done(function(data) {
            if(data){
                sweetAlert("Registro Eliminado Correctamente")
                draw_crossings_add()
                draw_crossings_added()
            }
        })
    }
</script>
@endsection
