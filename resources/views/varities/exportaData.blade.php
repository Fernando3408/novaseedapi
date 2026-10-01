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
<?php 
	use App\Models\Characteristic;
	use App\Models\Feature;
?>
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded" style="overflow-x: scroll;">
	<table style="margin: 50px;">
		<thead>
			<tr>
				<th>id</th>
				<th>Variedades</th>
				<th>Madre</th>
				<th>Padre</th>
				<th>Mercado</th>

				@foreach(Feature::orderBy('typefeature_id', 'asc')->get() as $k => $v)
					<th>{{$v->name}}</th>
				@endforeach
			</tr>
		</thead>
		<tbody>
			@foreach($varities as $k_ => $v_)
				<tr>
					<td>{{$v_->id}}</td>
					<td>{{$v_->name != "" ? $v_->name : $v_->code}}</td>
					<td>{{@$v_->mother->name}}</td>
					<td>{{@$v_->father->name}}</td>
					<td>{{@$v_->market->name}}</td>
					
					<?php $char = Feature::orderBy('typefeature_id', 'asc')->get(); ?>

					@foreach($char as $k_char => $v_char)
						
							<?php  
								$f = $v_->characteristics()->where('locality_id', $locality)->where('feature_id', $v_char->id)->where('value', 'not like', '0');
							?>

							@if(!empty($f->first()))
								@if(is_numeric($f->first()->value))
									<td>{{round($f->avg('value'),2)}}</td>
								@else
									<td>{{$f->first()->value}}</td>
								@endif
							@else
								<td>-</td>
							@endif
							
						
					@endforeach
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endsection