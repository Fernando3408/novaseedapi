
<form id="saveCharacteristics" action="/crossings/saveCharacteristics" method="POST">
	<input type="hidden" name="crossing_id" value="<?= $crossing->id;?>">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="inBlock" value="{{ $inBlock }}">
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th width="80px">Año 0</th>
				<th width="80px">Año 1</th>
				<th width="80px">Año 2</th>
				<th width="80px">Año 3</th>
				<th width="80px">Año 4</th>
				@if(!$inBlock)
				<th width="100px">Mostrar en campo</th>
				@endif
			</tr>
		</thead>
		<tbody>
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
								<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[0][<?= $f->id;?>][value]" value="<?= $f->valor0;?>">
							@endif
						</td>
						<td>
							@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '1') !== false)
								<input type="hidden" name="feature[1][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
								<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[1][<?= $f->id;?>][value]" value="<?= $f->valor1;?>">
							@endif
						</td>
						<td>
							@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '2') !== false)
								<input type="hidden" name="feature[2][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
								<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[2][<?= $f->id;?>][value]" value="<?= $f->valor2;?>">
							@endif
						</td>
						<td>
							@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '3') !== false)
								<input type="hidden" name="feature[3][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
								<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[3][<?= $f->id;?>][value]" value="<?= $f->valor3;?>">
							@endif
						</td>
						<td>
							@if (\strpos($f->year, '0') !== false OR \strpos($f->year, '4') !== false)
								<input type="hidden" name="feature[4][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
								<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[4][<?= $f->id;?>][value]" value="<?= $f->valor4;?>">
							@endif
						</td>
						@if(!$inBlock)
						<td>
							<center>
								<input type="checkbox" name="farm[<?= $f->id;?>][farm]" value="1" <?= ($f->getShowFarm($crossing->id))?'checked="checked"':'';?>>
							</center>
						</td>
						@endif
					</tr>
				@endforeach
			@endforeach
		</tbody>
	</table>
	<div class="row">
		<div class="col-lg-12">
			<button type="submit" class="btn btn-success" style="float:right"><i class="fa fa-save"></i> Guardar características</button>
		</div>
	</div>
</form>

<style>
	.fullsize{
		margin-bottom: 0px;
	}
</style>