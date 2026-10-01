<?php 
$tabla1=Array();
$tabla2=Array();
$i=0;
$valida1=false;
$validatabla=false;
$mitad=0;
if (!empty($sample) && count($sample->resultados)>1) {
	$mitad= count($sample->resultados)/2;
	$part=array_chunk($sample->resultados,(int)$mitad);
	$tabla1=$part[0];
	$tabla2=$part[1];
	$validatabla=true;
}
if (count($sample->resultados)==1) {
	$valida1=true;
}

?>
<table class="analisis">
	<tbody>
		<tr>
			<th style="width: 160px">CARACTERISTICA</th>
			<th style="width: 30px">PROM</th>
			<th style="width: 30px">STD</th>
			<th>DESCRIPTION</th>
		</tr>	
	</tbody>
	@if($valida1)
	@foreach($sample->resultados as $data)
	@if (strlen($data->TYPE)!=0)
			<tr>
				<td ><strong>{{$data->TYPE}}</strong></td>
					<td></td>
					<td></td>
					<td></td>
			</tr>
			@endif
			<tr>
				<td>{{$data->NAME}}</td>
				<td>{{$data->PROM}}</td>
				<td class="value">{{$data->STD}}</td>
				<td>{{$data->VAL}}</td>
			</tr>
		@endforeach
	@endif
	@if(!empty($sample))
		@foreach($tabla1 as $data)
		@if (strlen($data->TYPE)!=0)
			<tr>
				<td ><strong>{{$data->TYPE}}</strong></td>
					<td></td>
					<td></td>
					<td></td>
			</tr>
			@endif
			<tr>
				<td>{{$data->NAME}}</td>
				<td>{{$data->PROM}}</td>
				<td class="value">{{$data->STD}}</td>
				<td>{{$data->VAL}}</td>
			</tr>
		@endforeach
	@endif
</table>
<table class="analisis2">
	<tbody>
		<tr>
			<th style="width: 160px">CARACTERISTICA</th>
			<th style="width: 30px">PROM</th>
			<th style="width: 30px">STD</th>
			<th>DESCRIPTION</th>
		</tr>	
	</tbody>
	@if($validatabla)
		@foreach($tabla2 as $data)
		@if (strlen($data->TYPE)!=0)
			<tr>
				<td ><strong>{{$data->TYPE}}</strong></td>
					<td></td>
					<td></td>
					<td></td>
			</tr>
			@endif
			<tr>
				<td>{{$data->NAME}}</td>
				<td>{{$data->PROM}}</td>
				<td class="value">{{$data->STD}}</td>
				<td>{{$data->VAL}}</td>
			</tr>
		@endforeach
	@endif
</table>


<div class="clear"></div>

