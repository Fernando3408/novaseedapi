<?php
use App\Models\Varity;
?>


 <ul class="nav nav-tabs nav-fill" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#listado_f1">Resultados</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#listado_ready">Padres</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active show" id="listado_f1" role="tabpanel">
    	<table class="table" style="margin-top: 20px">
			<thead>
				<tr>
<th>
    <input type="checkbox" id="select-all-results" onclick="toggle_all_results(this)">
</th>
					<th>ID</th>
					<th>Nombre</th>
					<th>Año</th>
					@foreach($config_features as $k => $v)
						<?php $fries_list = [48,49,50,51]; ?>

						<th>{!! $feature_names[$k] ?? \App\Models\Feature::find($k)->name !!}</th>

						@if(in_array($k, $fries_list))
							<th>Ponderado</th>
						@endif
					@endforeach
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!empty($varities))
					@foreach($varities as $key => $value)
						<?php
							$model = $result_models[$value] ?? Varity::withTrashed()->find($value);
							$name =	@$model->name ? @$model->name : @$model->code;
							$name_varity = @$name.";".@$model->motherName()." ; ".@$model->fatherName()."";

						?>

						@if(!empty($model))
								<tr>
									
<td><input name="check_id[{{@$model->id}}]" class="result-select-check" onclick="select_block(this)" attr-id="{{@$model->id}}" attr-name="{{@$name_varity}}" type="checkbox"></td>
									<td>{{@$model->id}}</td>		
									<td>{!!@$model->name ? @$model->name : @$model->code!!}</td>
									<td>{{ @$model->year }}</td>
									@foreach($config_features as $k => $v)
										@if(is_numeric(@$varity_data[$value][$k]['promedio']))
											<td ><a href="#/" onclick="getDetailInfo({{$k}},{{@$model->id}})">{{@round($varity_data[$value][$k]['promedio'], 0)}}</a></td>

											@if(in_array($k, $fries_list))
												<td ><a href="#/" onclick="getDetailInfo({{$k}},{{@$model->id}})">{{@round($varity_data[$value][$k]['ponderado'], 2)}}</a></td>
											@endif
										@else
										<td ><a href="#/" onclick="getDetailInfo({{$k}},{{@$model->id}})">

										{{@$varity_data[$value][$k]['promedio'] ? strtoupper(@$varity_data[$value][$k]['promedio']): "-"}}</a></td>
										@endif
									@endforeach
											
									<td><a href="/varities/{{ @$model->id }}?locality={{$locality_id}}" class="btn btn-primary btn-sm">Ver</a></td>
									<td><button class="btn btn-danger" onclick='deleteVarity("{{$model->name}}", {{$model->id}})' type="button"><i class="fa fa-trash"></i> Eliminar</button></td>
								</tr>
						@endif
					@endforeach
				@endif
			</tbody>
		</table>
    </div>
    <div class="tab-pane " id="listado_ready" role="tabpanel">
        <table class="table" style="margin-top: 20px">
			<thead>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Nombre</th>
					<th>Año</th>
					<th>Padres</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!empty($fathers_list))
					@foreach($fathers_list as $key => $value)
                        <?php
                        $model = $parent_models[$key] ?? Varity::withTrashed()->find($key);
                        $name = !empty($model) ? (@$model->name ? @$model->name : @$model->code) : "#".$key;
                        $name_varity = !empty($model) ? @$name.";".@$model->motherName()." ; ".@$model->fatherName()."" : "#".$key."; - ; -";
                        ?>

						@if(!empty($model))
								<tr>
									
									<td><input name="check_id[{{@$model->id}}]" onclick="select_block(this)" attr-id="{{@$model->id}}" attr-name="{{@$name_varity}}" type="checkbox"></td>
									<td>{{@$model->id}}</td>		
                        <td>{!!@$name!!}</td>
									<td>{{ @$model->year }}</td>
									<td>{{@$value[0]}}</td>
									<td><a href="/varities/{{ @$model->id }}" class="btn btn-primary btn-sm">Ver</a></td>
								</tr>
						@endif
					@endforeach
				@endif
				
			</tbody>
		</table>
    </div>
</div>

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
$(".table").not("#table-compare-beta").DataTable({
"bPaginate": false
});
});

toggle_all_results = function(element){
    var checked = $(element).is(':checked');
    $(".result-select-check").each(function(){
        $(this).prop('checked', checked);
    });
    sync_selected_varities_from_grid();
}
            
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
