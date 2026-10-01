<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title></title>
	</head>
	<body>
	<?php 
	$contador=1;
	$aux=0;
	
	?>
		<div class="reporte">
			@include('elements.pdf.header')
			<div class="clear"></div>
			@foreach($data as $k => $value)
				@if($aux!=0)
				@include('elements.pdf.headerlogo')
				@endif
				<h4>VARIEDAD {{strtoupper($value->code)}}</h4>
				
				<div class="metodo">
					@if(isset($value->multimedia))
						@foreach($value->multimedia as $key => $v)
							<img src="{{public_path()}}/{{$v->link}}" class="picks" height="150px" width="150px" style="margin-bottom: 30px;margin-top: 30px;">
						@endforeach
					@endif
				</div>
				<div class="clear"></div>
				
				<div style="margin-top: 30px;">
					@include('elements.pdf.results_simple',['sample' => $value])			
				</div>
				@if(isset($value->obs))
					<div class="metodo">
						@include('elements.pdf.description',['value' => $value])
					</div>
				@endif
				
				
				@if(count($data)!=$contador)
				<div class="page-break"></div>
				@endif
				<?php $contador++;
				$aux++;
				?>
			@endforeach
		</div>
	</body>
	<style>
		.page-break {
			page-break-after: always;
		}
	</style>
	<style type="text/css">
		.table {
			display: table;
		}
		.tr {
			display: table-row;
		}
		.td {
			display: table-cell;
		}
		body{
			/*-webkit-print-color-adjust:exact;*/
			margin-right: 20px;
			width: 710px;
		}
		.reporte{
			margin: 0;
			padding: 0;
			font-size: 11px;
			font-family: sans-serif;
			width: 700px;
		}

		.reporte h4,.reporte h5{
			width: 100%;
			text-align: center;
			font-size: 17px;
		}

		.reporte hr{
			width: 700px;
			height: 1px;
			background-color: #e06404;
			border:none;
		}

		p.title{
			margin: 0;
		}

		.clear::after { 
			content: "";
			display: block; 
			clear: both;
		}

		

		.page-break {
		    page-break-after: always;
		}

		.grafica {
			float: left;
			border-spacing: 0;
			margin: 5px;
			margin-left: 0;
			margin-top: 0;
			border:1px solid #e06404;
			height: 68px;
		}

		.grafica th{
			background-color: #e06404;
			color: white;
			padding: 1px 10px;
			height:20px;
			font-size: 9px;
		}

		.grafica th b{
			font-size: 13px;
		}

		.grafica th small{
			font-size: 8px;
		}

		.grafica th.right{
			text-align: right;
		}

		.grafica img{
			width: 230px;
			position: relative;
		}
		table.analisis.x2{
			width: 175px;
			margin-right: 5px;
		}

		table.analisis.x2.right{
			width: 175px;
			margin: 0;
			margin-left: 5px;
		}

		table.analisis{
			border-width: 0px;
		    border-style: solid;
		    border-spacing: 0;
		    margin-top: 25px;
		    width: 350px;
		    z-index: 1;	
		    float: left;
		    margin: 0px;
		    margin-right: 10px;
		}

		table.analisis th.ref{
			text-align: center;
		}
		table.analisis td,table.analisis th{
			border-bottom-width: 0px;
	    	border-bottom-style: solid;
	    	text-align: left;
	    	
		}
		table.analisis th{
			background-color: #e06404;
			color: white;
		}

		table.analisis td.value{
			font-weight: 400;
		}
      /* inicio del cagazo*/

	  table.analisis2.x2{
			width: 150px;
			margin-right: 5px;
		}

		table.analisis2.x2.right{
			width: 150px;
			margin: 0;
			margin-left: 5px;
		}

		table.analisis2{
			border-width: 0px;
		    border-style: solid;
		    border-spacing: 0;
		    margin-top: 25px;
		    width: 350px;
		    z-index: 1;	
		    float: right;
		    margin: 0px;
		    margin-right: 10px;
		}

		table.analisis2 th.ref{
			text-align: center;
		}
		table.analisis2 td,table.analisis2 th{
			border-bottom-width: 0px;
	    	border-bottom-style: solid;
	    	text-align: left;
	    	
		}
		table.analisis2 th{
			background-color: #e06404;
			color: white;
		}

		table.analisis2 td.value{
			font-weight: 400;
		}
		/* fin del cagazo */

		table.img{
			margin: 0px 0 -3px 0;
			z-index:0;	
		}
		div.informe{
			width: 710px;
			position: absolute;
		    bottom: 100px;
		}

		div.informe .metodo{
			width: 350px;
			border:1px solid #e06404;
			padding: 5px;
			display: block;
		}

		div.informe .metodo p{ 
			font-size: 7pt;
			text-align: justify;
			width: 50%;
		}

		.firma{
			float: left;
			width: 600px;
			height: 80px;
		}

		div.informe .pie {
		    width: 710px;
		}

		div.informe .pie p{
		    font-size: 7pt;
		    width: 450px;
		    float: left;
		    margin-top: 90px;
		}

		.pie img.acreditadores{
			width: 60px;
			float: left;
			margin-top: 80px;
		}

		div.informe .firma_foto{
			width: 100px;
			float: left;
		}

		div.informe .firma_foto img {
		    height: 120px;
		    margin-top: 20px;
		    float:left;
		}
		table.medidas td{
			background-color: #e06404;
			color: white;
			font-weight: 900;
			padding: 6px;
		}

		table.medidas{
			margin-top: 20px;
		}

		
	</style>
</html>