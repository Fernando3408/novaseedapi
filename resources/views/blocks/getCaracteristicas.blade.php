
<form id="saveCharacteristics" action="/crossings/saveCharacteristics" method="POST">
	<input type="hidden" name="crossing_id" value="<?= @$crossing->id;?>">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="block_id" value="{{ $block->id }}">
	<input type="hidden" name="inBlock" value="true">
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th width="80px" class="anho_0 anho">Año 0</th>
				<th width="80px" class="anho_1 anho">Año 1</th>
				<th width="80px" class="anho_2 anho">Año 2</th>
				<th width="80px" class="anho_3 anho">Año 3</th>
				<th width="80px" class="anho_4 anho">Año 4</th>
			</tr>
		</thead>
		<tbody>
			@foreach($typesfeatures as $t)
				<tr>
					<td colspan="6"><b>{{ $t->name }} / {{ $t->namees }}</b></td>
				</tr>
				@foreach($t->features as $f)
					<tr>
						<!-- Validacion para agregar el boton de calculo de calibres me basta que 1 aparezca-->

						<?php  
							if($f->id > 25 && $f->id < 31){
								$calibres = true;
							}
						?>
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
						<td class="anho_0 anho">

							@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '0') !== false)
								<input type="hidden" name="feature[0][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
								<input type="hidden" name="feature[0][<?= $f->id;?>][value_repeats]" value="<?= @$f->value_repeats_0;?>">

								<?php $name = @$crossing->code ? @$crossing->code : @$crossing->name; ?>

								<input <?= ($f->type == 1)? 'maxlength="4"':'';?> type="text" class="fullsize" name="feature[0][<?= $f->id;?>][value]" value="<?= $f->valor0;?>" attr-fries="{{$f->frying_test}}" attr-type="{{$f->repeat_do}}" attr-name="feature[0][<?= $f->id;?>]" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id']}},0,'{{$name}}')" attr-year="0" attr-id-feature="{{$f['id']}}">
							@endif
						</td>
						<td class="anho_1 anho">
							@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '1') !== false)
								<input type="hidden" name="feature[1][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
								<input type="hidden" name="feature[1][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_1;?>">
								<input <?= ($f->type == 1)? 'maxlength="4"':'';?> type="text" class="fullsize" name="feature[1][<?= $f->id;?>][value]" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id']}},1,'{{$name}}')" attr-name="feature[1][<?= $f->id;?>]" value="<?= $f->valor1;?>" attr-year="1" attr-id-feature="{{$f['id']}}">
							@endif
						</td>
						<td class="anho_2 anho">
							@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '2') !== false)
								<input type="hidden" name="feature[2][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
								<input type="hidden" name="feature[2][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_2;?>">
								<input <?= ($f->type == 1)? 'maxlength="4"':'';?> type="text" class="fullsize" name="feature[2][<?= $f->id;?>][value]" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id']}},2,'{{$name}}')" attr-name="feature[2][<?= $f->id;?>]" value="<?= $f->valor2;?>" attr-year="2">
							@endif
						</td>
						<td class="anho_3 anho">
							@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '3') !== false)
								<input type="hidden" name="feature[3][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
								<input type="hidden" name="feature[3][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_3;?>">
								<input <?= ($f->type == 1)? 'maxlength="4"':'';?> type="text" class="fullsize" name="feature[3][<?= $f->id;?>][value]" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onfocus="insertRepeat(this,{{$f['id']}},{{$crossing['id']}},3,'{{$name}}')" attr-name="feature[3][<?= $f->id;?>]" value="<?= $f->valor3;?>" attr-year="3" attr-id-feature="{{$f['id']}}">
							@endif
						</td>
						<td class="anho_4 anho">
							@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '4') !== false)
								<input type="hidden" name="feature[4][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
								<input type="hidden" name="feature[4][<?= $f->id;?>][value_repeats]" value="<?=@$f->value_repeats_4;?>">
								<input <?= ($f->type == 1)? 'maxlength="4"':'';?> type="text" class="fullsize" attr-type="{{$f->repeat_do}}" attr-fries="{{$f->frying_test}}" onclick="insertRepeat(this,{{$f['id']}},{{$crossing['id']}},4,'{{$name}}')" name="feature[4][<?= $f->id;?>][value]" attr-name="feature[4][<?= $f->id;?>]" value="<?= $f->valor4;?>" attr-year="4" attr-id-feature="{{$f['id']}}">
							@endif
						</td>
						
					</tr>
				@endforeach
				<?php  
					if(isset($calibres)){
						if($calibres){
							$calibres = false;
							?>
							<button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--outline-2x" onclick="calculoCalibres(1,1)">Calcular Calibres</button>
							<?php 
						}
					}
				?>		
			@endforeach
			<button type="button" class="btn btn-success" style="float:right;margin-right: 10px;" onclick="saveForm()"><i class="fa fa-save"></i> Siguiente</button>
		</tbody>
	</table>
	<div class="row">
		<div class="col-lg-12">
			<button type="button" class="btn btn-success" style="float:right;margin-right: 10px;" onclick="saveForm()"><i class="fa fa-save"></i> Siguiente</button>
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

    	//$("#selectView").change();
    	$(".fullsize").change(function(){
            feature_id = $(this).attr('attr-id-feature');
            elem_val   = this.value;
            year = $(this).attr('attr-year');
            
            if(feature_id > 25 && feature_id < 31){
            	values_calibres(feature_id, year, elem_val,1)
            }
        })


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

	           		if($("#type_content").val() == 2){
	           			Swal.fire(
		                'Información Guardada correctamente',
	            		)


	           			return true;
	           		}

	           		if(global_process_fries && data.data.length > 0){
					    siguiente = parseInt(global_idactua)+1
				        $("#"+siguiente).click()
	           		}else{
				    	siguiente = parseInt(global_idactua)+1
	           			$("#"+siguiente).click()
	           		}
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