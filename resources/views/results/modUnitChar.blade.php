<form id="saveCharacteristics" action="/blocks/saveShowFarms" method="POST">
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th width="80px" style="text-align: center;">Seleccionar</th>
			</tr>
		</thead>
		<tbody>
			@foreach($typesfeatures as $t)
				<tr>
					<td colspan="6"><b>{{ $t->name }} / {{ $t->namees }}</b></td>
					<td>
						<center>
							<input type="checkbox" onclick="selectTypes(this, {{$t->id}})"  name="{{$t->id}}" id="{{$t->id}}">
						</center>
					</td>
				</tr>

				@foreach($t->features as $f)
					<tr>
						<td>
							@if($f->name != $f->namees)
								<?php $nameHeader = $f->name; ?>
								
								{{ $f->name }} / {{ $f->namees }} 
							@else
								<?php $nameHeader = $f->namees; ?>
								{{ $f->namees }} 
							@endif
						</td>
						<td>
							<center>
								<input  class="type_{{$t->id}}" type="checkbox" name="farm[6x1][<?= $f->id;?>][farm]" value="1" id="index_{{$f->id}}" onclick="registrarCheck({{$f->id}},'{{$nameHeader}}')">
							</center>
						</td>
					</tr>
				@endforeach
			@endforeach
		</tbody>
	</table>
	<div class="row">
		<div class="col-lg-12">
			<button type="button" class="btn btn-success" style="float:right" data-dismiss="modal" aria-label="Close" onclick="resetHeaders()"><i class="fa fa-save"></i> Continuar</button>
		</div>
	</div>
</form>