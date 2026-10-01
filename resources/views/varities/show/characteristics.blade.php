
<form id="saveCharacteristics" action="saveCharacteristics" method="POST">
	<input type="hidden" name="crossing_id" value="<?= $crossing->id;?>">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th width="100px">Característica</th>
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
							<input type="hidden" name="feature[0][<?= $f->id;?>][feature_id]" value="<?= $f->id;?>">
							<input <?= ($f->type == 1)? 'maxlength="1"':'';?> type="text" class="fullsize" name="feature[0][<?= $f->id;?>][value]" value="<?= $f->valor0;?>">
						</td>
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