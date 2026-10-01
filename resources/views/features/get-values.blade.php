@if(sizeof($feature->values) > 0)
	@foreach($feature->values as $key => $v)
	<?php $key++;?>
		<div class="col-lg-3">
			<h6>Nombre Valor {{ $key }}</h6>
			<div class="form-group">
			    {!! Form::hidden('values['.$key.'][id]', $v->id) !!}
			    {!! Form::text('values['.$key.'][name]', $v->name, array('placeholder' => 'Ingrese nombre','class' => 'form-control  required')) !!}
			</div>
		</div>
		<div class="col-lg-3">
			<h6>Valor {{ $key }}</h6>
		    <div class="form-group">
		    	{!! Form::text('values['.$key.'][value]', $v->value, array('placeholder' => 'Ingrese valor','class' => 'form-control   required')) !!}
		    </div>
		</div>
	@endforeach		
@endif