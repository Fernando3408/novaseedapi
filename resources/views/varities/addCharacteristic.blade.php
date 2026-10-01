
<div class="panel col-lg-12">
	<div class="row">
		<div class="col-lg-3">
	        {!! Form::select('type', $features, null , array('placeholder' => 'Seleccione Característica','class' => 'form-control ts-2  required ','onchange' => 'getDataFeature(this)')) !!}
		</div>
		<div class="col-lg-8">
			
		</div>
		<div class="col-lg-1">
			<button class="btn btn-danger" onclick="eliminarValor(this)" type="button">
				<i class="fa fa-trash"></i>
			</button>
		</div>
	</div>
	<hr>
</div>

