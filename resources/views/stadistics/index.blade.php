@extends('layouts.app')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jstat@latest/dist/jstat.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@3.5.15/dist/jspdf.plugin.autotable.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<style>
    #chartdiv {
    width: 100%;
    height: 500px;
    }
</style>
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


<div class="page-title">
	<h3><span class="semi-bold">Bloques Completos Aleatorios</span></h3>
</div>
<div class="row">
    <div class="col-md-12">
		<div class="grid simple">
			<div class="grid-body">
                <div class="row">
                    <div class="autocomplete" style="width:300px;">
                        <input id="myInput" class="form-control" type="text" name="myCountry" attr-id="" placeholder="Buscador Variedades">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-block btn-warning" type="button"  id="agregar">
                                    <i class="fa fa-pen"></i> Agregar
                                </button>
                            </div>
                </div>
                <br>

                

                <table class="table" id="tableFacturacion">
						<thead>
					    	<tr>
                                <th></th>
								<th>R1</th>
                                <th>R2</th>
                                <th>R3</th>
                                <th></th>
							</tr>
						</thead>
						<tbody>
                            
                        </tbody>
					</table>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <button class="btn btn-block btn-warning" type="button" style="margin-top: 28px;" id="calc">
                                    <i class="fa fa-search"></i> Calcular
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-block btn-submit" type="button" disabled style="margin-top: 28px;" id="export">
                                    <i class="fa fa-file"></i> Exportar PDF
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-block btn-submit" type="button" disabled style="margin-top: 28px;" id="exportxls">
                                    <i class="fa fa-file"></i> Exportar Excel
                                </button>
                            </div>
                        </div>
					</div>

				<hr>
                <div id="contenedor" style="display:none">
                    <h3><span class="semi-bold">CV Ensayo</span></h3>
                        <table class="table" id="tableCVensayo">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Ensayo</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    <hr>
                    <h3><span class="semi-bold">CV Repetición</span></h3>
                        <table class="table" id="tableCVRepeticion">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Promedio</th>
                                    <th>STD</th>
                                    <th>CV</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    <hr>
                    <h3><span class="semi-bold">Resumen</span></h3>
                        <table class="table" id="tableResumen">
                            <thead>
                                <tr>
                                    <th>Grupos</th>
                                    <th>Suma</th>
                                    <th>Promedio</th>
                                    <th>Varianza</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    <hr>
                    <h3><span class="semi-bold">Análisis de Varianza</span></h3>
                        <table class="table" id="tableAnalisis" style="font-size: 15px;">
                            <thead>
                                <tr>
                                    <th>Origen de las variaciones</th>
                                    <th>Suma de cuadrados</th>
                                    <th>Grados de libertad</th>
                                    <th>Promedio de los cuadrados</th>
                                    <th style="min-width: 80px;">F</th>
                                    <th>Probabilidad</th>
                                    <th>Valor crítico para F</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    <hr>
                    <h3><span class="semi-bold">Resultados Tukey HSD - Valor crítico = <label id="critical"></label></span></h3>
                        <table class="table" id="tableTukey">
                            <thead>
                                <tr>
                                    <th>Análisis</th>
                                    <th>Q estadística</th>
                                    <th>p-valor</th>
                                    <th>Significativo</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    <hr>

                    <div id="chartdiv"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>

   

    $(document).ready(function(){

    arr_rep=[];
    arr_resumen=[];
    arr_promedios=[];
    aux=0;
    aux2=0;
    SIGNIFICANT = 5;

    tableFacturacion = $("#tableFacturacion").DataTable({
			'bLengthChange' : false,
			'filtering' : false,
			'searching' : false,
			'bPaginate': false,
			'info': false,
            'ordering': false
        })

    $("#agregar").click(function(){
        
        tableFacturacion.row.add([
			$("#myInput").val(),
			'<input type="text" name="r1" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46">',
            '<input type="text" name="r2" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46">',
            '<input type="text" name="r3" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46">',
            "<button class='btn btn-danger btn-mini' onclick='borrar_linea(this);'><i class='fa fa-trash'></i></button>"
		]).draw()
    })

    borrar_linea= function(btn){
        tableFacturacion.row($(btn).parents('tr')).remove().draw();
    }


    $("#calc").click(function(){
        $("#export").removeAttr('disabled');
        $("#exportxls").removeAttr('disabled');
        cv_ensayo();
        cv_repeticion();
        resumen();
        analisis();
        tukey();

	})

    $("#export").click(function(){
        var doc = new jsPDF();

        doc.text(15, 15, "Bloques Completos Aleatorios");
        doc.autoTable({ html: '#tableFacturacion', startY: 20, })
        doc.text("CV Ensayo", 15, doc.autoTable.previous.finalY + 10);
        doc.autoTable({ html: '#tableCVensayo',
                        startY: doc.autoTable.previous.finalY + 15, })
        doc.text("CV Repetición", 15, doc.autoTable.previous.finalY + 10);
        doc.autoTable({ html: '#tableCVRepeticion',
                        startY: doc.autoTable.previous.finalY + 15, })
        doc.text("Resumen", 15, doc.autoTable.previous.finalY + 10);
        doc.autoTable({ html: '#tableResumen',
                        startY: doc.autoTable.previous.finalY + 15, })
        doc.text("Análisis de Varianza", 15, doc.autoTable.previous.finalY + 10);
        doc.autoTable({ html: '#tableAnalisis',
            startY: doc.autoTable.previous.finalY + 15,
            styles: {overflow: 'linebreak', cellWidth: '100', font: 'arial', fontSize: 7},
         })
        doc.text("Resultados Tukey HSD", 15, doc.autoTable.previous.finalY + 10);
        doc.autoTable({ html: '#tableTukey',
                        startY: doc.autoTable.previous.finalY + 15, })



        doc.save('table1.pdf')
    })

    $("#exportxls").click(function(){
      
        var workbook = XLSX.utils.book_new();
        var tbl1= document.getElementById('tableFacturacion');
        var tbl2= document.getElementById('tableCVensayo');
        var tbl3= document.getElementById('tableCVRepeticion');
        var tbl4= document.getElementById('tableResumen');
        var tbl5= document.getElementById('tableAnalisis');
        var tbl6= document.getElementById('tableTukey');

        var wb1 = XLSX.utils.table_to_sheet(tbl1);
        XLSX.utils.book_append_sheet(workbook, wb1,'Bloques Completos Aleatorios');

        var wb2 = XLSX.utils.table_to_sheet(tbl2);
        XLSX.utils.book_append_sheet(workbook, wb2,'CV Ensayo' );

        var wb3 = XLSX.utils.table_to_sheet(tbl3);
        XLSX.utils.book_append_sheet(workbook, wb3,'CV Repetición' );

        var wb4 = XLSX.utils.table_to_sheet(tbl4);
        XLSX.utils.book_append_sheet(workbook, wb4,'Resumen' );

        var wb5 = XLSX.utils.table_to_sheet(tbl5);
        XLSX.utils.book_append_sheet(workbook, wb5,'Análisis de Varianza' );

        var wb6 = XLSX.utils.table_to_sheet(tbl6);
        XLSX.utils.book_append_sheet(workbook, wb6,'Resultados Tukey HSD' );
                  
        XLSX.writeFile(workbook,'tablas_excel.xlsx');
        
    })

    cv_ensayo = function(){
        tableCVensayo.clear().draw();
        suma=0;
        cont=0;
        varianza=0;
        document.querySelectorAll('#tableFacturacion tbody tr').forEach(function(e){
            var columnas=e.querySelectorAll("td");

            for(i=1;i<=3;i++){
                cont++;
                suma+=parseFloat(columnas[i].getElementsByTagName("input")[0].value);
            }

        });
        promedio = (suma/cont);
        document.querySelectorAll('#tableFacturacion tbody tr').forEach(function(e){
            var columnas=e.querySelectorAll("td");

            for(i=1;i<=3;i++){

                rango = Math.pow(parseFloat(columnas[i].getElementsByTagName("input")[0].value)-promedio,2);
                varianza+=rango;
            }

        });

        desviacion = Math.sqrt(varianza/cont);

        c_variacion = Math.round((desviacion/promedio)*100);

        tableCVensayo.row.add([
                "Promedio",
                precision_string(promedio)
                ]).draw()
        tableCVensayo.row.add([
                "STD",
                precision_string(desviacion)
                ]).draw()
        tableCVensayo.row.add([
                "CV",
                precision_string(c_variacion)
                ]).draw()

    }

    cv_repeticion = function(){
        tableCVRepeticion.clear().draw();

        for(i=1;i<=3;i++){
            suma=0;
            cont=0;
            varianza=0;
            document.querySelectorAll('#tableFacturacion tbody tr').forEach(function(e){
                var columnas=e.querySelectorAll("td");

                cont++;
                suma+=parseFloat(columnas[i].getElementsByTagName("input")[0].value);

            });
            promedio = (suma/cont);
            document.querySelectorAll('#tableFacturacion tbody tr').forEach(function(e){
                var columnas=e.querySelectorAll("td");

                rango = Math.pow(parseFloat(columnas[i].getElementsByTagName("input")[0].value)-promedio,2);
                varianza+=rango;

            });

            desviacion = Math.sqrt(varianza/cont);

            c_variacion = Math.round((desviacion/promedio)*100);

            promedio=Math.round(promedio);
            desviacion=Math.round(desviacion);

            tableCVRepeticion.row.add([
                "R"+(i+1),
                precision_string(promedio),
                precision_string(desviacion),
                precision_string(c_variacion)
                ]).draw()
        }

    }

    tableResumen = $('#tableResumen').DataTable({
        "bDestroy": false,
		"bInfo": false,
		"bPaginate": false,
		"searching": false,
		"bSort": false,
    });

    tableCVensayo = $('#tableCVensayo').DataTable({
        "bDestroy": false,
		"bInfo": false,
		"bPaginate": false,
		"searching": false,
		"bSort": false,
    });

    tableTukey = $('#tableTukey').DataTable({
        "bDestroy": false,
		"bInfo": false,
		"bPaginate": false,
		"searching": false,
		"bSort": false,
    });

    tableCVRepeticion = $('#tableCVRepeticion').DataTable({
        "bDestroy": false,
		"bInfo": false,
		"bPaginate": false,
		"searching": false,
		"bSort": false,
    });

    tableAnalisis = $('#tableAnalisis').DataTable({
        "bDestroy": false,
		"bInfo": false,
		"bPaginate": false,
		"searching": false,
		"bSort": false,
    });

    resumen = function(){
        tableResumen.clear().draw();
        arr_resumen=[];
        suma=0;
        varianza=0;
        document.querySelectorAll('#tableFacturacion tbody tr').forEach(function(e){
            var columnas=e.querySelectorAll("td");
            //var nombres=e.querySelectorAll("th");
            //console.log(nombres[0].textContent)
            for(i=1;i<=3;i++){
                suma+=parseFloat(columnas[i].getElementsByTagName("input")[0].value);
            }
            promedio = roundToTwo(suma/3);
            for(i=1;i<=3;i++){
                rango = Math.pow(parseFloat(columnas[i].getElementsByTagName("input")[0].value)-promedio,2);
                varianza+=rango;
            }
            arr_promedios.push(promedio)
            tableResumen.row.add([
                columnas[0].textContent,
                roundToTwo(suma),
                promedio,
                roundToTwo(varianza/2)
                ]).draw()

            let fila={
                suma:roundToTwo(suma),
                promedio:promedio,
                varianza: roundToTwo(varianza/2)

            };
            arr_resumen.push(fila);
            varianza=0;
            suma=0;

        });
        datos_grafico(arr_resumen)
        //console.log(arr_resumen)
        $("#contenedor").removeAttr("style")
    }
    analisis = function(){
        tableAnalisis.clear().draw();
        suma=0;
        cont=0;
        suma_pow=0;
        sTC=0;//suma de cuadrados Total
        sCD=0;//suma de cuadrados Dentro
        sCE=0;//suma de cuadrados Entre

        document.querySelectorAll('#tableFacturacion tbody tr').forEach(function(e){
            var columnas=e.querySelectorAll("td");

            for(i=1;i<=3;i++){
                cont++;
                suma+=parseFloat(columnas[i].getElementsByTagName("input")[0].value);
                suma_pow+=(Math.pow(parseFloat(columnas[i].getElementsByTagName("input")[0].value),2))/5;
            }

        });

        prom=suma/cont;
        k=0
        document.querySelectorAll('#tableFacturacion tbody tr').forEach(function(e){

            var columnas=e.querySelectorAll("td");
                for(i=1;i<=3;i++){
                    rango = Math.pow(parseFloat(columnas[i].getElementsByTagName("input")[0].value)-prom,2);
                    sTC+=rango;
                    sCD+= Math.pow(parseFloat(columnas[i].getElementsByTagName("input")[0].value)-arr_resumen[k]["promedio"],2);
                }
            k++;
        });

        GlD=k*(i-2);//grado de libertad Dentro
        GlT=cont-1;//grado de libertad Total
        GlE=GlT-GlD;//grado de libertad Entre

        fact_c = Math.pow(suma,2)/cont;
        sCE=(sTC-sCD);
        sctotales = suma_pow-fact_c;

        pCE=sCE/GlE;//promedio cuedrados Entre
        pCD=sCD/GlD;//promedio cuedrados Dentro

        f=pCE/pCD;
        probabilidad=Fmt(FishF(f,GlE,GlD));

        critF=precision_string(f_icdf(1-0.05,GlE,GlD))

        aux=GlD;
        aux2=pCD;


        tableAnalisis.row.add([
                "Entre grupos",
                precision_string(sCE),
                GlE,
                precision_string(pCE),
                precision_string(f),
                probabilidad,
                critF
                ]).draw()
        tableAnalisis.row.add([
                "Dentro de los grupos",
                precision_string(sCD),
                GlD,
                precision_string(pCD),
                "",
                "",
                ""
                ]).draw()
        tableAnalisis.row.add([
                "Total",
                precision_string(sTC),
                GlT,
                "",
                "",
                "",
                ""
                ]).draw()

        //console.log(f)

    }

    tukey = function(){
        matriz=[]
        resultado2=[]
        names=[]
        cont=0;

        document.querySelectorAll('#tableFacturacion tbody tr').forEach(function(e){
            var columnas=e.querySelectorAll("td");
            //var nombres=e.querySelectorAll("th");
            arr=[];
            for(i=1;i<=3;i++){
                arr.push(parseFloat(columnas[i].getElementsByTagName("input")[0].value));
            }
            matriz.push(arr)
            names.push(columnas[0].textContent)
            cont++;

        });

        critical=jStat.tukey.inv(0.95, cont, aux)

        $("#critical").html(precision_string(critical))

        for(var i=0;i<arr_promedios.length-1;i++){
            for(var j=i+1;j<arr_promedios.length;j++){
                resultado2.push(roundToTwo(jStat.qscore(arr_promedios[i],arr_promedios[j],3,3,Math.sqrt(aux2))));
            }
        }
        resultado1=jStat.tukeyhsd(matriz)
        for(var k=0;k<resultado1.length;k++){
            if(resultado2[k]>critical){
                significativo='<label style="color:green">Signifactivo</label>';
            }else{
                significativo='<label style="color:red">No Signifactivo</label>';
            }
            tableTukey.row.add([
                names[resultado1[k][0][0]]+" VS "+names[resultado1[k][0][1]],
                resultado2[k],
                precision_string(resultado1[k][1]),
                significativo
            ]).draw()
        }


    }

    function FishF(f,n1,n2) {
        var x=n2/(n1*f+n2)
        if((n1%2)==0) { return StatCom(1-x,n2,n1+n2-4,n2-2)*Math.pow(x,n2/2) }
        if((n2%2)==0){ return 1-StatCom(x,n1,n1+n2-4,n1-2)*Math.pow(1-x,n1/2) }
        var th=Math.atan(Math.sqrt(n1*f/n2)); var a=th/PiD2; var sth=Math.sin(th); var cth=Math.cos(th)
        if(n2>1) { a=a+sth*cth*StatCom(cth*cth,2,n2-3,-1)/PiD2 }
        if(n1==1) { return 1-a }
        var c=4*StatCom(sth*sth,n2+1,n1+n2-4,n2-2)*sth*Math.pow(cth,n2)/Pi
        if(n2==1) { return 1-a+c/2 }
        var k=2; while(k<=(n2-1)/2) {c=c*k/(k-.5); k=k+1 }
        return 1-a+c
    }

    function Fmt(x) {
        var v
        if(x>=0) { v=''+(x+0.0005) } else { v=''+(x-0.0005) }
        return v.substring(0,v.indexOf('.')+4)
    }

    function StatCom(q,i,j,b) {
        var zz=1; var z=zz; var k=i; while(k<=j) { zz=zz*q*k/(k-b); z=z+zz; k=k+2 }
        return z
    }

    function roundToTwo(num) {
        return +(Math.round(num + "e+3")  + "e-3");
    }





})
am5.ready(function() {
    resumen=[];
    error=[];
    function roundToTwo(num) {
        return +(Math.round(num + "e+3")  + "e-3");
    }
    datos_grafico = function(arr){
        $.each(arr,function(i,res){
            resumen[i]=roundToTwo(res['promedio']);
            error[i]=roundToTwo(Math.sqrt(res['varianza'])/1.732);
        })

        var data=[];
        linea=0;

        document.querySelectorAll('#tableFacturacion tbody tr').forEach(function(e){
            var columnas=e.querySelectorAll("td");           

            let fila={
                country: columnas[0].textContent,
                visits: resumen[linea],
                error: error[linea]
            };
            data.push(fila);           
            linea++;

        });

       /* var data = [
            {
                country: "ADARA",
                visits: resumen[0],
                error: error[0]
            },
            {
                country: "Asterix",
                visits: resumen[1],
                error: error[1]
            },
            {
                country: "KAIA",
                visits: resumen[2],
                error: error[2]
            },
            {
                country: "Karu",
                visits: resumen[3],
                error: error[3]
            },
            {
                country: "Pukara",
                visits: resumen[4],
                error: error[4]
            },
            {
                country: "TRAUKO",
                visits: resumen[5],
                error: error[5]
            }
        ];*/
        series.data.setAll(data);
        xAxis.data.setAll(data);

    }



    //console.log(error)



// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(
  am5xy.XYChart.new(root, {
    panX: true,
    panY: true,
    //wheelY: "zoomXY"
  })
);

chart.get("colors").set("step", 2);

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(
  am5xy.CategoryAxis.new(root, {
    categoryField: "country",
    renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 50 }),
    tooltip: am5.Tooltip.new(root, {})
  })
);

var yAxis = chart.yAxes.push(
  am5xy.ValueAxis.new(root, {
    extraMax: 0.9,
    extraMin: 0.9,
    renderer: am5xy.AxisRendererY.new(root, {}),
    tooltip: am5.Tooltip.new(root, {})
  })
);

// Create series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(
  am5xy.LineSeries.new(root, {
    calculateAggregates: true,
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "visits",
    categoryXField: "country",
    tooltip: am5.Tooltip.new(root, {
      labelText: "media: {valueY}\nerror: {error}"
    })
  })
);

// add error bullet
series.bullets.push(function() {
  var graphics = am5.Graphics.new(root, {
    strokeWidth: 2,
    stroke: series.get("stroke"),
    draw: function(display, target) {
      var dataItem = target.dataItem;

      var error = dataItem.dataContext.error;

      var yPosition0 = yAxis.valueToPosition(0);
      var yPosition1 = yAxis.valueToPosition(error);

      var height =
        (yAxis.get("renderer").positionToCoordinate(yPosition1) - yAxis.get("renderer").positionToCoordinate(yPosition0)) / 2;

      display.moveTo(0, -height);
      display.lineTo(0, height);

      display.moveTo(-10, -height);
      display.lineTo(10, -height);

      display.moveTo(-10, height);
      display.lineTo(10, height);
    }
  });

  return am5.Bullet.new(root, {
    dynamic: true,
    sprite: graphics
  });
});

// Add circle bullet
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/#Bullets
series.bullets.push(function() {
  var graphics = am5.Circle.new(root, {
    strokeWidth: 2,
    radius: 5,
    stroke: series.get("stroke"),
    fill: root.interfaceColors.get("background")
  });
  return am5.Bullet.new(root, {
    sprite: graphics
  });
});

// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
chart.set("cursor", am5xy.XYCursor.new(root, {
  xAxis: xAxis,
  yAxis: yAxis,
  snapToSeries: [series]
}));



// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
series.appear(1000);
chart.appear(1000, 100);

}); // end am5.ready()


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
