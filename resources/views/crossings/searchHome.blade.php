<?php 
	use App\Models\Characteristic;
	use App\Models\Feature;
	use App\Models\Varity;
?>

<table class="table" style="margin-top: 20px">
	<thead>
		<tr>
			<th></th>
			<!--th>ID</th-->
			<th>Nombre</th>
			<th>Año</th>
			@foreach($config_features as $k => $v)
				<?php 
					$name = Feature::find($k)->name;
				?>
				<th>{{$name}}</th>
			@endforeach
			<th></th>
			
		</tr>
	</thead>
	<tbody>
		@if(!empty($varities))
			@foreach($varities as $key => $value)
				<tr>
					<?php  
						$model = Varity::withTrashed()->find($value);
						$name =	@$model->name ? @$model->name : @$model->code;
						$name_varity = @$name.";".@$model->motherName()." ; ".@$model->fatherName()."";

					?>
					<td><input name="check_id[{{$model->id}}]" onclick="select_block(this)" attr-id="{{$model->id}}" attr-name="{{$name_varity}}" type="checkbox"></td>
					<!--td>{{$model->id}}</td-->		
					<td>{{@$name}}</td>
					
					<td>{{ @$model->year }}</td>
					@foreach($config_features as $k => $v)
						@if(is_numeric(@$varity_data[$value][$k]['promedio']))
							<th>{{@round($varity_data[$value][$k]['promedio'], 0)}}</th>
						@else
							<th>{{@strtoupper($varity_data[$value][$k]['promedio'])}}</th>
						@endif
					@endforeach
							
					<td>
						<a href="#" class="btn btn-primary btn-sm" onclick="seleccionar({{$model->id}})">Seleccionar</a>
					</td>
					
				</tr>
			@endforeach
		@else
			<tr>
				<td>No se encontraron Datos</td>
			</tr>
		@endif
	</tbody>
</table>
<div class="modal show" id="modal-compare" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header"><
                <h5 class="modal-title" id="exampleModalLabel">Comparación Resultados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="stripe row-border order-column" id="table_datos_compare" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Code</th>
                            <th>Ensayo</th>
                            <th>Año Variedad</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
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
