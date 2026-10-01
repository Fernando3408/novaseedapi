
	<div class="col-lg-3" style="margin-top: 15px;">
	    {!! Form::select('type', $features, null , array('placeholder' => 'Seleccione Característica','class' => 'form-control  required','onchange' => 'getDataFeature(this)')) !!} 
	</div>
	<div class="col-lg-1" style="margin-top: 15px;">
		<button class="btn btn-danger" onclick="eliminarValor(this)" type="button">
			<i class="fa fa-trash"></i>
		</button>
	</div>
	<div class="col-lg-8" style="margin-top: 15px">
	</div>
	


