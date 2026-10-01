@extends('layouts.exports')
@section('content')
<?php

header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
$date = date('j-m-y');
header("Content-Disposition: attachment; filename=info_nova_$date.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

?>

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded" style="overflow-x: scroll;">
	<table style="margin: 50px;">
		<thead>
			<tr>
				<th>Variedad</th>
				<th>N° Madre</th>
				<th>N° Padre</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data_chart as $k => $v)
			<tr>
				<td>{{$v['age']}}</td>
				<td>{{$v['female']}}</td>
				<td>{{$v['male']}}</td>
				<td>{{($v['male']+$v['female'])}}</td>
			</tr>
			@endforeach	
		</tbody>
	</table>

</div>
@endsection