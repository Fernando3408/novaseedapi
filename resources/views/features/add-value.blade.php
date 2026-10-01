<div class="col-lg-3">
	<h6>Nombre Valor {{ $id }}</h6>
	<div class="form-group">
	    {!! Form::text('values['.$id.'][name]', null, array('placeholder' => 'Ingrese nombre','class' => 'form-control  required')) !!}
	</div>
</div>
<div class="col-lg-3">
	<h6>Valor {{ $id }}</h6>
    <div class="form-group">
    	{!! Form::text('values['.$id.'][value]', $id, array('placeholder' => 'Ingrese valor','class' => 'form-control   required')) !!}
    </div>
</div>