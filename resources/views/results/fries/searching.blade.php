<?php 
use App\Models\Characteristic;
use App\Models\Feature;
use App\Models\Varity;
?>
<table class="table" style="margin-top: 20px">
	<thead>
		<tr>
			<th></th>
			<th>ID</th>
			<th>Nombre</th>
			<th>Año</th>
			@foreach($config_features as $k => $v)
				<?php 
					$name = Feature::find($k)->code;
				?>
				<th>{{$name}}</th>
				<th>Pond. {{$name}}</th>
			@endforeach
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@if(!empty($varities))
			<?php $already_shown = []; ?>
			@foreach($varities as $key => $value)
				<?php  
					// Evitar repetidos
					if (in_array($value, $already_shown)) continue;
					$already_shown[] = $value;

					$model = Varity::find($value);
					$name_varity = @$model->name.";".@$model->mother->name." ; ".@$model->father->name."";
				?>

				@if(!empty($model))
					<tr>
						<td>
							<input name="check_id[{{@$model->id}}]" onclick="select_block(this)" attr-id="{{@$model->id}}" attr-name="{{@$name_varity}}" type="checkbox">
						</td>
						<td>{{@$model->id}}</td>		
						<td>{{@$model->name ? $model->name : $model->code}}</td>
						<td>{{ @$model->year }}</td>
						@foreach($config_features as $k => $v)
							<td ><a href="#/" onclick="getDetailInfo({{$k}},{{@$model->id}})">{{@round($varity_data[$value][$k]['promedio'], 0)}}</a></td>
							<td ><a href="#/" onclick="getDetailInfo({{$k}},{{@$model->id}})">{{@round($varity_data[$value][$k]['ponderado'], 2)}}</a></td>
						@endforeach
						<td>
							<a href="/varities/{{ @$model->id }}" class="btn btn-primary btn-sm">Ver</a>
						</td>
						<td>
							<button class="btn btn-danger" onclick='deleteVarity("{{$model->name}}", {{$model->id}})' type="button">
								<i class="fa fa-trash"></i> Eliminar
							</button>
						</td>
					</tr>
				@endif
			@endforeach
		@endif
	</tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(){
	 	$(".table").DataTable({
	 		"bPaginate": false
	 	});
	});
            
    deleteVarity = function(name, id_to_delete){
        Swal.fire({
          title: '¿Quieres eliminar '+name+' ?',
          text: "Se va a eliminar, no habrá vuelta atrás.",
          type: 'error',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, eliminar'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/blocks/deleteCodify/"+id_to_delete,
              type: 'GET',
              dataType: 'json',
            }).done(function(data) {
                make_show_view()
            })
          }
        })
    }
</script>