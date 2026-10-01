<?php
    use App\Models\Varity;
    use App\Models\Position;
?>
@extends('layouts.app')
@section('content')
<style>
    #chartdiv,#chartdiv2,#chartdiv3,#chartdiv4,#chartdiv5,#chartdiv6,#chartdiv7 {
        padding: 0px;
        width: 100%;
        height: 350px;
    }
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/material.js"></script>

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-calendar"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    {{ @$mapout->year }}
                    <input type="hidden" name="id_greenhouse" id="id_greenhouse" value="{{$id}}">
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
                <a class="nav-link " data-toggle="tab" href="#listado_f1">Listado de Cruces</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#listado_ready">Listado de Cruces Listos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active  show tab_listado_cruces" data-toggle="tab" href="#listado_cruces">Invernadero</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#resumen">Resumen</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active show" id="listado_cruces" role="tabpanel" style="max-width: 2000px; overflow-x: auto;">
            </div>
            <div class="tab-pane " id="listado_f1" role="tabpanel">
                
            </div>
            <div class="tab-pane" id="listado_ready" role="tabpanel">
                
            </div>
            <div class="tab-pane" id="resumen" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <h5 style="padding:5px;"><i class="fas fa-tachometer-alt"></i><span class="semi-bold"> % Cruces Listos:{{$crossings_ready->count()}} / {{$crossings_ready->count() + $crossings->count()}}</span></h5>
                        <div id="chartdiv3"></div>   
                    </div>
                     <div class="col-md-6"> 
                        <h5 style="padding:5px;"><i class="fas fa-tachometer-alt"></i><span class="semi-bold"> Bayas vs Objetivo:{{$array_info_berries['avance_berries']}} / {{$array_info_berries['berries_objective']}}</span></h5>
                        <div id="chartdiv6"></div>   
                    </div>
                    <div class="col-md-12">
                        <h5 style="padding:5px;"><i class="fas fa-tachometer-alt"></i><span class="semi-bold">Avance Bayas Por Mercado Objetivo</span></h5>
                        <div id="chartdiv4"></div> 
                    </div>
                    <div class="col-md-12">
                        <h5 style="padding:5px;"><i class="fas fa-tachometer-alt"></i><span class="semi-bold">Cruces Listos por Mercado Objetivo</span></h5>
                        <div id="chartdiv5"></div> 
                    </div>
                    <div class="col-md-6">
                        <h5 style="padding:5px;"><i class="fas fa-tachometer-alt"></i><span class="semi-bold"> Total Objetivo Bayas por Mercado</span></h5>
                        <div id="chartdiv"></div>        
                    </div>
                    <div class="col-md-6">
                        <h5 style="padding:5px;"><i class="fas fa-tachometer-alt"></i><span class="semi-bold"> Actual Bayas por Mercado</span></h5>
                        <div id="chartdiv7"></div>        
                    </div>
                    <div class="col-md-6">
                        <h5 style="padding:5px;"><i class="fas fa-tachometer-alt"></i><span class="semi-bold"> Total Cruzamientos por Mercado</span></h5>
                        <div id="chartdiv2"></div>        
                    </div>
                </div>
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
                            <input type="hidden" name="mapout_id" value="{{ @$mapout->id }}">
                            <input type="hidden" name="positionY">
                            <input type="hidden" name="line">
                            <input type="hidden" name="varity_id">
                        </form>

                    <table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table-add-crossing">
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
                                            $record = Varity::where("id",@$r->mother->id)->withTrashed()->first();
                                            $list_new[] = @$record->id;
                                            $record = Varity::where("id",@$r->father->id)->withTrashed()->first();
                                            $list_new[] = @$record->id;
                                        ?>
                                @endforeach
                                @foreach(array_unique($list_new) as $r)
                                    <?php 
                                        $record = Varity::where("id",$r)->withTrashed()->first();
                                        $list_new[] = @$record->id;
                                    ?>
                                    <tr>
                                        <td>T{{ @$record->year }}-{{ @$record->year+1 }}</td>
                                        <td>{{ @$record->id }}</td>
                                        <td>
                                            <b>
                                                <a href="/varities/{{ @$record->id }}">
                                                    {!! !empty(@$record->name) ? @$record->name : @$record->code !!}
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
                <button type="button" class="btn btn-primary" style="margin-top: 0px;" id="submit_update"><i class="fa fa-save"></i> Actualizar</button>
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
                                <label for="name">N° Bayas</label>
                                <input type="text" name="blue" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Nº Abortos</label>
                                <input type="text" name="yellow" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="name">Floración</label>
                            <select name="flowering">
                                <option value="0">Seleccionar</option>
                                <option value="1">Abundante</option>
                                <option value="2">Escaza</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="name">Polen</label>
                            <select name="polem_volume">
                                <option value="0">Seleccionar</option>
                                <option value="1">Abundante</option>
                                <option value="2">Escazo</option>
                                <option value="3">Esteril</option>
                            </select>
                        </div>
                        <!-- Se tiene que actualizar desde ahora estes valor en conjunto de berries y mostrar a su vez 
                            si es que existe algun valor de la caracteristica en la tabla characteristics y mostrarlo.
                         !-->
                        <div class="col-lg-4">
                            <label for="name">Bayas</label>
                            <select name="steril_mom">
                                <option value="0">Seleccionar</option>
                                <option value="1">Sin Berries</option>
                                <option value="2">Normal</option>
                                <option value="3">Alto</option>
                            </select>
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
                    
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade show" id="modal-details" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle Ubicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
                <div class="modal-body">
                    <table class="table-striped- table-bordered table-hover table-checkable datatables_p" id="tableDetail">
                        <thead>
                            <tr>
                                <th>Linea</th>
                                <th>Posición</th>
                                <th>Flores</th>
                                <th>Polen</th>
                                <th>Bayas</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
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

    .tooltip-table{
        display:none;
        position:absolute;
    }

</style>


<script>
    $(document).ready(function(){

        $("#submit_update").click(function(){
            //$("#FormUpdatePosition").submit()
            $.ajax({
              url: $("#FormUpdatePosition").attr('action'),
              type: 'POST',
              dataType: 'json',
              data: $("#FormUpdatePosition").serialize(),
            }).done(function(data) {
                if(data){
                    $("#modal-update-position").modal('hide')
                    draw_greenhouse()
                    setTimeout(function(){ moveToPositionY(this_position,this_line)},2000)
                   /*
                    $('html, body, .tab-content').animate({
                        scrollTop: $("#"+this_line).position().top - 600
                      }, 1000); 
                    setTimeout(function(){ $("p[attr-position="+this_position+"]").effect( "bounce",800 ); }, 2000);
                    $(window).scrollTop($("#"+this_line).position().top);
                    */

                }
            })
        })

        $("#codificados").click(function(){
            $("#modal-codificados").modal("show")
        })


        tableDetail = $("#tableDetail").DataTable()
        

        $("#table-add-crossing").DataTable();


        draw_greenhouse = function(){
            $('#listado_cruces').empty()


           $.ajax({
              url:  "/draw_greenhouse/"+$("#id_greenhouse").val(),
              success: function(data) {
                $('#listado_cruces').empty().html(data);
              }
            });
        } 

        draw_croosings_list = function(){
            $('#listado_cruces').empty()


           $.ajax({
              url:  "/greenhouse/crossings_list/"+$("#id_greenhouse").val(),
              success: function(data) {
                $('#listado_f1').empty().html(data);
              }
            });
        } 

        draw_croosings_ok = function(){
            $('#listado_cruces').empty()


           $.ajax({
              url:  "/greenhouse/crossings_ok/"+$("#id_greenhouse").val(),
              success: function(data) {
                $('#listado_ready').empty().html(data);
              }
            });
        } 

        draw_greenhouse()
        draw_croosings_list()
        draw_croosings_ok()

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("chartdiv", am4charts.PieChart);
        var chart2 = am4core.create("chartdiv2", am4charts.PieChart);
        var chart7 = am4core.create("chartdiv7", am4charts.PieChart);

        // Add data
        chart_total_markets = <?= json_encode($array_values_total); ?>;
        chart_count_markets = <?= json_encode($array_conteo_mecado); ?>;
        chart_count_by_markets = <?= json_encode($array_count_by_market); ?>;
        chart.data = chart_total_markets;
        chart2.data = chart_count_markets;
        chart7.data = chart_total_markets;

        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "total";
        pieSeries.dataFields.category = "market";
        pieSeries.slices.template.stroke = am4core.color("#fff");
        pieSeries.slices.template.strokeWidth = 2;
        pieSeries.slices.template.strokeOpacity = 1;

        // This creates initial animation   
        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;

        /*Grafico 2*/
        // Add and configure Series
        var pieSeries2 = chart2.series.push(new am4charts.PieSeries());
        pieSeries2.dataFields.value = "total";
        pieSeries2.dataFields.category = "market";
        pieSeries2.slices.template.stroke = am4core.color("#fff");
        pieSeries2.slices.template.strokeWidth = 2;
        pieSeries2.slices.template.strokeOpacity = 1;

        // This creates initial animation   
        pieSeries2.hiddenState.properties.opacity = 1;
        pieSeries2.hiddenState.properties.endAngle = -90;
        pieSeries2.hiddenState.properties.startAngle = -90;

        //Grafico 7
        var pieSeries7 = chart7.series.push(new am4charts.PieSeries());
        pieSeries7.dataFields.value = "avance";
        pieSeries7.dataFields.category = "market";
        pieSeries7.slices.template.stroke = am4core.color("#fff");
        pieSeries7.slices.template.strokeWidth = 2;
        pieSeries7.slices.template.strokeOpacity = 1;

        // This creates initial animation   
        pieSeries7.hiddenState.properties.opacity = 1;
        pieSeries7.hiddenState.properties.endAngle = -90;
        pieSeries7.hiddenState.properties.startAngle = -90;

        /*GAUGE*/
        am4core.useTheme(am4themes_material);
        var chart3 = am4core.create("chartdiv3", am4charts.GaugeChart);
        chart3.hiddenState.properties.opacity = 0; // this makes initial fade in effect
        chart3.innerRadius = -25;
        var axis = chart3.xAxes.push(new am4charts.ValueAxis());
        axis.min = 0;
        axis.max = 100;
        axis.strictMinMax = true;
        axis.renderer.grid.template.stroke = new am4core.InterfaceColorSet().getFor("background");
        axis.renderer.grid.template.strokeOpacity = 0.3;
        var colorSet = new am4core.ColorSet();

        var range0 = axis.axisRanges.create();
        range0.value = 0;
        range0.endValue = 50;
        range0.axisFill.fillOpacity = 1;
        range0.axisFill.fill = colorSet.getIndex(0);
        range0.axisFill.zIndex = - 1;
        var range1 = axis.axisRanges.create();
        range1.value = 50;
        range1.endValue = 80;
        range1.axisFill.fillOpacity = 1;
        range1.axisFill.fill = colorSet.getIndex(2);
        range1.axisFill.zIndex = -1;
        var range2 = axis.axisRanges.create();
        range2.value = 80;
        range2.endValue = 100;
        range2.axisFill.fillOpacity = 1;
        range2.axisFill.fill = colorSet.getIndex(4);
        range2.axisFill.zIndex = -1;
        var hand = chart3.hands.push(new am4charts.ClockHand());
        avance = <?= json_encode($avance_greenhouse); ?>;
        setTimeout(function(){hand.showValue(avance, 1000, am4core.ease.cubicOut)},2000)

        /*GAUGE 2*/
        am4core.useTheme(am4themes_material);
        var chart6 = am4core.create("chartdiv6", am4charts.GaugeChart);
        chart6.hiddenState.properties.opacity = 0; // this makes initial fade in effect
        chart6.innerRadius = -25;
        var axis = chart6.xAxes.push(new am4charts.ValueAxis());
        axis.min = 0;
        axis.max = 100;
        axis.strictMinMax = true;
        axis.renderer.grid.template.stroke = new am4core.InterfaceColorSet().getFor("background");
        axis.renderer.grid.template.strokeOpacity = 0.3;
        var colorSet = new am4core.ColorSet();

        var range0 = axis.axisRanges.create();
        range0.value = 0;
        range0.endValue = 50;
        range0.axisFill.fillOpacity = 1;
        range0.axisFill.fill = colorSet.getIndex(0);
        range0.axisFill.zIndex = - 1;
        var range1 = axis.axisRanges.create();
        range1.value = 50;
        range1.endValue = 80;
        range1.axisFill.fillOpacity = 1;
        range1.axisFill.fill = colorSet.getIndex(2);
        range1.axisFill.zIndex = -1;
        var range2 = axis.axisRanges.create();
        range2.value = 80;
        range2.endValue = 100;
        range2.axisFill.fillOpacity = 1;
        range2.axisFill.fill = colorSet.getIndex(4);
        range2.axisFill.zIndex = -1;
        var hand2 = chart6.hands.push(new am4charts.ClockHand());
        avance2 = <?= json_encode($array_info_berries['porcent']); ?>;
        setTimeout(function(){hand2.showValue(avance2, 1000, am4core.ease.cubicOut)},2000)

        // Create chart instance
        var chart4 = am4core.create("chartdiv4", am4charts.XYChart3D);
        chart4.data = chart_total_markets;

        // Create axes
        var categoryAxis = chart4.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "market";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 30;
        categoryAxis.renderer.labels.template.rotation = 15;

        var valueAxis = chart4.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Avance por Mercado Objetivo";
        valueAxis.renderer.labels.template.adapter.add("text", function(text) {
          return text;
        });

        // Create series
        var series = chart4.series.push(new am4charts.ColumnSeries3D());
        series.dataFields.valueY = "avance";
        series.dataFields.categoryX = "market";
        series.name = "Year 2017";
        series.clustered = false;
        series.columns.template.tooltipText = "Avance: [bold]{valueY}[/]";
        series.columns.template.fillOpacity = 0.9;

        var series2 = chart4.series.push(new am4charts.ColumnSeries3D());
        series2.dataFields.valueY = "total";
        series2.dataFields.categoryX = "market";
        series2.name = "Year 2018";
        series2.clustered = false;
        series2.columns.template.tooltipText = "Total: [bold]{valueY}[/]";

        //Chart Cruces Listos por Mercado
    
        var chart5 = am4core.create("chartdiv5", am4charts.XYChart3D);
        chart5.data = chart_count_by_markets;
        var categoryAxis = chart5.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "market";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 30;

        var valueAxis = chart5.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Avance Cruces Listos por Mercado Objetivo";
        valueAxis.renderer.labels.template.adapter.add("text", function(text) {
          return text;
        });

        // Setting up label rotation
        categoryAxis.renderer.labels.template.rotation = 15;

        // Create series
        var series = chart5.series.push(new am4charts.ColumnSeries3D());
        series.dataFields.valueY = "crossings_ok";
        series.dataFields.categoryX = "market";
        series.name = "Year 2017";
        series.clustered = false;
        series.columns.template.tooltipText = "Avance: [bold]{valueY}[/]";
        series.columns.template.fillOpacity = 0.9;

        var series2 = chart5.series.push(new am4charts.ColumnSeries3D());
        series2.dataFields.valueY = "total";
        series2.dataFields.categoryX = "market";
        series2.name = "Year 2018";
        series2.clustered = false;
        series2.columns.template.tooltipText = "Total: [bold]{valueY}[/]";

        $("g[aria-labelledby]").hide()

    })

    moveToPositionY = function(p,l){
        $("#modal-details").modal('hide')
        $(".tab_listado_cruces").click();

        $('html, body').animate({
                //scrollTop: $("#"+l).position().top - 600
                scrollTop: $("#"+l).offset().top - 400
              }, 1000);

        setTimeout(function(){ $('html, body').animate({
                //scrollTop: $("#"+l).position().top - 600
                scrollTop: $("#"+l).offset().top - 400
              }, 1000);
        }, 1000);

        setTimeout(function(){ 
            $("p[attr-position="+p+"]").effect( "bounce",800 ); 
        }, 2000);

        //$(window).scrollTop($("#"+l).position().top);
    }

    agregarCruce = function(id){
        Swal.fire({
            title: '¿Desea copiar la variedad ' + name + '?',
            text: "",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, copiar'
        }).then((result) => {
            if (result.value) {
                // Si el usuario elige "Si, copiar"
                $("#modal-add-cruce").modal('hide')
                setTimeout(Swal.fire({
                    title: 'Ingrese bloques separados por ","',
                    html:
                        '<input type="text" id="campoTexto" class="swal2-input" placeholder="Ingrese texto">',
                    showCancelButton: true,
                    confirmButtonText: 'Enviar',
                    cancelButtonText: 'Cancelar',

                }).then((result) => {
                    if (result.value) {
                        $("#FormAddPositions input[name=positionY]").val($("#campoTexto").val())
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
                    }
                })
                ,2000)
               
            } else {
                // Si el usuario elige "No"
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
        });
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
        $("#FormUpdatePosition select[name=flowering]").val(json.flowering).change()
        $("#FormUpdatePosition select[name=polem_volume]").val(json.polem_volume).change()
        $("#FormUpdatePosition select[name=steril_mom]").val(json.steril_mom).change()

        //Para la recarga del invernadero
        this_position =  $(element).attr("attr-position")
        this_line = $(element).attr("attr-line")
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


    //Posicion Izquierda
    showDetail= function(elem){
        var dataxx = JSON.parse($(elem).attr('attr-position'))
            tableDetail.clear().draw();
            $.each(dataxx, function(i,v){
                tableDetail.row.add([
                    v.line,
                    //(parseInt(v.positionY)%2),
                    v.positionY,
                    v.red,
                    v.redmeat,
                    v.blue,
                    '<button type="button" class="btn btn-outline-success btn-sm" onclick="moveToPositionY('+v.positionY+','+v.line+')">Ver en Invernadero</button>'
                ]).draw()

            })

            $("#modal-details").modal('show')
    }

    

    reloadCrossings = function(data){
        table_crossings.clear().draw();
        $.each(dataxx, function(i,data){
            class_btn = "";
            btn_swtch = '<i class="fas fa-random" style="cursor: pointer;z-index: 999;" onclick="switchElement('+data.id+','+data.father_id+','+data.mother_id+')"></i>'  
            btn_edit = '<a class="btn btn-outline-success btn-sm" href="/crossings/'+data.id+'/edit">Editar</a>'

            btn_delete = '{!! Form::open(["method" => "DELETE","route" => ["greenhouse.destroy", '+data.id+'],"style"=>"display:inline"]) !!}<input type="hidden" value="'+data.id+'" name="record_id">{!! Form::submit("Eliminar", ["class" => "btn btn-outline-danger btn-sm btn-delete"]) !!}{!! Form::close() !!}'

            btn_f  = '<i class="'+data.class_mother+'" style="cursor: pointer;z-index: 999;" attr-position="'+data.values_encoded_values_encoded_mother+'" onclick="showDetail(this)"></i>'
            btn_m  = '<i class="'+data.class_father+'" style="cursor: pointer;z-index: 999;" attr-position="'+data.values_encoded_values_encoded_father+'" onclick="showDetail(this)"></i>'
            if(data.btn_ok){
                btn_ok =  '{!! Form::open(["method" => "PATCH","route" => ["greenhouse.update", '+data.id+'],"style"=>"display:inline"]) !!}<input type="hidden" value="'+data.id+'" name="record_id"><input type="hidden" value="'+data.id+'" name="record_status" value="1">{!! Form::submit("OK", ["class" => "btn btn-outline-success btn-sm"]) !!}{!! Form::close() !!}'
            }else{
                btn_ok = ""
            }
            
            table_crossings.row.add([
                btn_f+" "+btn_m+" "+btn_ok,
                'T'+data.year+'-'+(parseInt(data.year)+1),
                '<b><a href="#">'+data.mothername+'</a></b>',
                btn_swtch,
                '<b><a href="#">'+data.fathername+'</a></b>',
                data.marketname,
                '<b>'+data.berries_objective?data.berries_objective:0+'</b>',   
                data.observation,
                btn_edit +" "+ btn_delete
            ]).draw()
        })
    }

    switchElement = function(varity_id, father_id, mother_id){
        $.ajax({
            url: "/greenhouse/switch/"+varity_id+"/"+father_id+"/"+mother_id,
            type: 'GET',
            dataType: 'json',
        }).done(function(dataxx) {
            draw_croosings_list()
            draw_croosings_ok()
            draw_greenhouse()

        })
    }

    savePolem = function(father_id, mother_id, status){
        $.ajax({
            url: "/greenhouse/polen/"+father_id+"/"+mother_id+"/"+status,
            type: 'GET',
            dataType: 'json',
        }).done(function(dataxx) {
            draw_croosings_list()
            draw_greenhouse()

        })
    }
</script>
@endsection
