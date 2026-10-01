<!-- Styles -->
<style>


</style>



<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

var chart = am4core.create("chartdiv", am4charts.XYChart);
chart.hiddenState.properties.opacity = 0; // this makes initial fade in effect

chart.data = {!! json_encode($dataChart) !!};


var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.dataFields.category = "country";
categoryAxis.renderer.minGridDistance = 40;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

var series = chart.series.push(new am4charts.CurvedColumnSeries());
series.dataFields.categoryX = "country";
series.dataFields.valueY = "value";
series.tooltipText = "{valueY.value}"
series.columns.template.strokeOpacity = 0;
series.columns.template.tension = 1;

series.columns.template.fillOpacity = 0.75;

var hoverState = series.columns.template.states.create("hover");
hoverState.properties.fillOpacity = 1;
hoverState.properties.tension = 0.8;

chart.cursor = new am4charts.XYCursor();

// Add distinctive colors for each column using adapter
series.columns.template.adapter.add("fill", function(fill, target) {
  return chart.colors.getIndex(target.dataItem.index);
});

chart.scrollbarX = new am4core.Scrollbar();
chart.scrollbarY = new am4core.Scrollbar();

}); // end am4core.ready()
</script>
<div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample6">

  @if(!empty($response))
    @foreach($response as $key => $record)
        <div class="card">
          <div class="card-header" id="headingOne{{$key}}">
            <div class="card-title" data-toggle="collapse" data-target="#collapseOne{{$key}}">
              <i class="flaticon-pie-chart-1"></i> Año {{$key}}
            </div>
          </div>
          <div id="collapseOne{{$key}}" class="collapse show" data-parent="#accordionExample6">
            <div class="card-body">
              <table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table_detail_{{$key}}">
                <thead>
                  <tr>
                    @foreach($factores as $k => $v)
                      <th>{{substr("$k", 0, 3)}}</th>
                    @endforeach
                    <th>Resultado</th>
                    <th>-</th>
                    <th>-</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($record as $r)
                      <tr>
                        @foreach($factores as $k => $v)
                            <td><input type="text" value="{{$r[$k]}}" id="{{$r['id']}}_{{$k}}"></td>
                        @endforeach
                        <td>{{$r["total_ponderado"]}}</td>
                        <td>
                          <button type="button" class="btn btn-outline-success" onclick="updateFries({{$key}}, {{$r['varity_id']}}, {{$r['id']}})">
                            <i class="fa fa-save" style="color: green"></i>
                          </button>
                        </td>
                        <td>
                          <button type="button" class="btn btn-outline-danger" onclick="updateFries({{$key}}, {{$r['varity_id']}}, {{$r['id']}},1)">
                            <i class="fa fa-trash" style="color: red"></i>
                          </button>
                        </td>
                        
                      </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
    @endforeach
@endif

<!-- HTML -->
<div id="chartdiv"></div>