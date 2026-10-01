<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title></title>
	</head>
	<button type="button" id="buscar" class="btn btn-primary" style="margin-top: 23px;float: right; margin-right: 10px;" onclick="imprimir()"><i class="fa fa-check"></i> Imprimir PDF</button>
	<body>
		<div class="reporte">
			<div class="header">
	<div class="logo">
		<img src="/img/logo.png" style="width: 100%">
	</div>
</div>

<style type="text/css">
	.logo{
		float: left;
		width: 130px;
		text-align: left;
		font-size: 10px;
	}

	.logo img{
		width: 130px;
		height: 80px;
	}
	.logo h5{
		margin: 0;
		padding: 0;
		text-align: left;
	}
</style>
			<div class="clear"></div>
			@foreach($data as $k => $value)
				<h4>INFORME VARIEDAD {{strtoupper($value['code'])}}</h4>
				<div class="metodo">
					<div class="metodo">
						<textarea style="width: 720px;height: 300px;" onfocusout="cambioarregloql({{$k}},1,this,null)">
						</textarea>
					</div>
				</div>
				@if(isset($value['multimedia']))
					@foreach($value['multimedia'] as $key => $v)
						<img src="/{{$v['link']}}" class="picks" height="100px" width="100px" style="margin: auto;">
					@endforeach
				@endif
				<div class="clear"></div>
				<div style="margin-top: 30px;margin-bottom: 30px;">
					<table class="analisis">
						<tbody>
							<tr>
								<th style="width: 150px">Caracteristica</th>
								<th>PROM</th>
								<th>STD</th>
								<th>DESCRIPTION</th>
							</tr>	
						</tbody>
						@if(!empty($value))
							@foreach($value['resultados'] as $z => $s)
							@if (strlen($s["TYPE"])!=0)
							<tr>
								<td><input type="text" name="typef" value="{{$s['TYPE']}}" ></td>
									<td></td>
									<td></td>
							</tr>
							@endif
								<tr>
									
									<td><input type="text" name="name" value="{{$s['NAME']}}" onchange="cambioarregloql({{$k}},2,this,{{$z}})" elemento="NAME"></td>
									<td><input type="text" name="prom" value="{{$s['PROM']}}" onchange="cambioarregloql({{$k}},2,this,{{$z}})" elemento="PROM"></td>
									<td><input type="text" name="std" value="{{$s['STD']}}" onchange="cambioarregloql({{$k}},2,this,{{$z}})" elemento="STD"></td>
									<td><input type="text" name="val" value="{{$s['VAL']}}" onchange="cambioarregloql({{$k}},2,this,{{$z}})" elemento="VAL"></td>
								</tr>
								
							@endforeach
						@endif
					</table>
					<div class="clear"></div>
				</div>
			@endforeach
		</div>
	</body>
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
			background-color: #c2d69b;
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
			border:1px solid #c2d69b;
			height: 68px;
		}

		.grafica th{
			background-color: #c2d69b;
			color: #000;
			padding: 1px 10px
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
			width: 350px;
			margin-right: 5px;
		}

		table.analisis.x2.right{
			width: 350px;
			margin: 0;
			margin-left: 5px;
		}

		table.analisis{
			border-width: 0px;
		    border-style: solid;
		    border-spacing: 0;
		    margin-top: 25px;
		    width: 710px;
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
			background-color: #c2d69b;
			color: #000;
		}

		table.analisis td.value{
			font-weight: 900;
		}

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
			border:1px solid #c2d69b;
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
			background-color: #c2d69b;
			color: #000;
			font-weight: 900;
			padding: 6px;
		}

		table.medidas{
			margin-top: 20px;
		}
	</style>
	<script type="text/javascript">
		arregloql = {!! json_encode($data) !!}

		cambioarregloql = function(padre,tipo,elem,hijo = null){

			//agrega OBS
			if(tipo == 1){
				arregloql[padre]['obs'] = $(elem).val()
			}else{
				arregloql[padre]['resultados'][hijo][$(elem).attr('elemento')] = $(elem).val()
			}
		}		

		imprimir = function(){
	        var req = new XMLHttpRequest()
            req.open('POST', '/results/report', true)
            req.responseType = "blob"
           
            var formData = new FormData()
            formData.append("data", JSON.stringify(arregloql))
            formData.append("_token", "{{ csrf_token() }}")
            
            req.onload = function (event) {
                var blob = req.response
                var link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                
                link.download = "informe_{{date('now')}}.pdf"
                link.click()
            }

            req.send(formData)
		}

	</script>
</html>