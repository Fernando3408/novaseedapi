<?php $inBlock = true;?>
<form id="saveCharacteristics" action="/blocks/saveShowFarms" method="POST">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="inBlock" value="{{ $inBlock }}">
	<input type="hidden" name="block_id" value="{{ $block->id }}">
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th width="80px" style="text-align: center;">6x1</th>
				<th width="80px" style="text-align: center;">12x1</th>
				<th width="80px" style="text-align: center;">12x2</th>
				<th width="80px" style="text-align: center;">12x3</th>
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
						</td>
						<td>
							<center>
								<input <?= ($f->getShowFarm($block->id,'6x1'))?'checked="checked"':'';?> type="checkbox" name="farm[6x1][<?= $f->id;?>][farm]" >
							</center>
						</td>
						<td>
							<center>
								<input <?= ($f->getShowFarm($block->id,'12x1'))?'checked="checked"':'';?> type="checkbox" name="farm[12x1][<?= $f->id;?>][farm]" >
							</center>
						</td>
						<td>
							<center>
								<input <?= ($f->getShowFarm($block->id,'12x2'))?'checked="checked"':'';?> type="checkbox" name="farm[12x2][<?= $f->id;?>][farm]" >
							</center>
						</td>
						<td>
							<center>
								<input <?= ($f->getShowFarm($block->id,'12x3'))?'checked="checked"':'';?> type="checkbox" name="farm[12x3][<?= $f->id;?>][farm]" >
							</center>
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