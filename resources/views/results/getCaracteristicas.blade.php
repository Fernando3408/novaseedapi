
<form id="saveCharacteristics" action="/crossings/saveCharacteristics" method="POST">
	<input type="hidden" name="crossing_id" value="<?= @$crossing->id;?>">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="inBlock" value="true">
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th width="80px">Año 0</th>
				<th width="80px">Año 1</th>
				<th width="80px">Año 2</th>
				<th width="80px">Año 3</th>
				<th width="80px">Año 4</th>
			</tr>
		</thead>
		<tbody>

			@if(isset($f))
				<tr>
					<td colspan="6"><b>{{ $typesfeatures->name }} / {{ $typesfeatures->namees }}</b></td>
				</tr>
				<tr>
					<td>
						@if($f->name != $f->namees)
							{{ $f->name }} / {{ $f->namees }} 
						@else
							{{ $f->namees }} 
						@endif
						<span style="float:right" class="m-badge m-badge--info m-badge--wide">
							@if($f->type == 1)
								Calificación
							@elseif($f->type == 2)
								Si/No
							@elseif($f->type == 3)
								Valor
							@elseif($f->type == 4)
								Texto
							@elseif($f->type == 5)
								Matriz
							@endif
						</span>
					</td>
					<td>
						@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '0') !== false)
							<input type="hidden" name="feature[0][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
							<input type="hidden" name="feature[0][<?= $f->id;?>][value_repeats]" value="<?= @$f->value_repeats_0;?>">
							<input <?= ($f->type == 1)? 'maxlength="4"':'';?> type="text" class="fullsize" name="feature[0][<?= $f->id;?>][value]" value="<?= $valor0;?>" attr-fries="{{$f->frying_test}}" attr-type="{{$f->repeat_do}}" attr-name="feature[0][<?= $f->id;?>]" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id'],0}})">
						@endif
					</td>
					<td>
						@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '1') !== false)
							<input type="hidden" name="feature[1][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
							<input type="hidden" name="feature[1][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_1;?>">
							<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[1][<?= $f->id;?>][value]" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id'],1}})" attr-name="feature[1][<?= $f->id;?>]" value="<?= $valor1;?>">
						@endif
					</td>
					<td>
						@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '2') !== false)
							<input type="hidden" name="feature[2][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
							<input type="hidden" name="feature[2][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_2;?>">
							<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[2][<?= $f->id;?>][value]" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id'],2}})" attr-name="feature[2][<?= $f->id;?>]" value="<?= $valor2;?>">
						@endif
					</td>
					<td>
						@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '3') !== false)
							<input type="hidden" name="feature[3][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
							<input type="hidden" name="feature[3][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_3;?>">
							<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[3][<?= $f->id;?>][value]" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id'],3}})" attr-name="feature[3][<?= $f->id;?>]" value="<?= $valor3;?>">
						@endif
					</td>
					<td>
						@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '4') !== false)
							<input type="hidden" name="feature[4][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
							<input type="hidden" name="feature[4][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_4;?>">
							<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onclick="insertRepeat(this,{{$f['id']}},{{$crossing['id'],4}})" name="feature[4][<?= $f->id;?>][value]" attr-name="feature[4][<?= $f->id;?>]" value="<?= $valor4;?>">
						@endif
					</td>
					
				</tr>
				<input type="hidde" name="onlyone" value="1">
			@else
				<input type="hidde" name="onlyone" value="2">
				@foreach($typesfeatures as $t)
					<tr>
						<td colspan="6"><b>{{ $t->name }} / {{ $t->namees }}</b></td>
					</tr>
					@foreach($t->features as $f)
						<tr>
							<td>
								@if($f->name != $f->namees)
									{{ $f->name }} / {{ $f->namees }} 
								@else
									{{ $f->namees }} 
								@endif
								<span style="float:right" class="m-badge m-badge--info m-badge--wide">
									@if($f->type == 1)
										Calificación
									@elseif($f->type == 2)
										Si/No
									@elseif($f->type == 3)
										Valor
									@elseif($f->type == 4)
										Texto
									@elseif($f->type == 5)
										Matriz
									@endif
								</span>
							</td>
							<td>
								@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '0') !== false)
									<input type="hidden" name="feature[0][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
									<input type="hidden" name="feature[0][<?= $f->id;?>][value_repeats]" value="<?= @$f->value_repeats_0;?>">
									<input <?= ($f->type == 1)? 'maxlength="4"':'';?> type="text" class="fullsize" name="feature[0][<?= $f->id;?>][value]" value="<?= $f->valor0;?>" attr-fries="{{$f->frying_test}}" attr-type="{{$f->repeat_do}}" attr-name="feature[0][<?= $f->id;?>]" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id'],0}})">
								@endif
							</td>
							<td>
								@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '1') !== false)
									<input type="hidden" name="feature[1][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
									<input type="hidden" name="feature[1][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_1;?>">
									<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[1][<?= $f->id;?>][value]" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id'],1}})" attr-name="feature[1][<?= $f->id;?>]" value="<?= $f->valor1;?>">
								@endif
							</td>
							<td>
								@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '2') !== false)
									<input type="hidden" name="feature[2][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
									<input type="hidden" name="feature[2][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_2;?>">
									<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[2][<?= $f->id;?>][value]" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id'],2}})" attr-name="feature[2][<?= $f->id;?>]" value="<?= $f->valor2;?>">
								@endif
							</td>
							<td>
								@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '3') !== false)
									<input type="hidden" name="feature[3][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
									<input type="hidden" name="feature[3][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_3;?>">
									<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[3][<?= $f->id;?>][value]" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id'],3}})" attr-name="feature[3][<?= $f->id;?>]" value="<?= $f->valor3;?>">
								@endif
							</td>
							<td>
								@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '4') !== false)
									<input type="hidden" name="feature[4][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
									<input type="hidden" name="feature[4][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_4;?>">
									<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onclick="insertRepeat(this,{{$f['id']}},{{$crossing['id'],4}})" name="feature[4][<?= $f->id;?>][value]" attr-name="feature[4][<?= $f->id;?>]" value="<?= $f->valor4;?>">
								@endif
							</td>
							
						</tr>
					@endforeach
				@endforeach
			@endif
		</tbody>
	</table>
	<div class="row">
		<div class="col-lg-12">
			<button type="button" class="btn btn-success" style="float:right" onclick="saveForm()"><i class="fa fa-save"></i> Siguiente</button>
		</div>
	</div>
</form>
<script>
    $(document).ready(function(){

    	/*
    	$("#saveCharacteristics").click(function(e){
    		e.preventDefault()
    		saveForm()
    	})
    	*/

    	saveForm = function(){
    		var form = $("#saveCharacteristics")
    		var url = form.attr('action');

    		$.ajax({
	           url: url,
            	dataType: 'json',
	           data: form.serialize(), // serializes the form's elements.
	           type: "POST",
	           success: function(data){
	           	if(data.status){
	       			Swal.fire(
		                'Información Guardada correctamente',
	            	)

	            	make_table()
	           		
	           	}else{
	           		Swal.fire(
                    'Ocurrio un Error al guardar los datos',
                    'error'
                	)	
	           	}
	           }
	        });
    	}
    });
</script>

<style>
	.fullsize{
		margin-bottom: 0px;
	}
</style>