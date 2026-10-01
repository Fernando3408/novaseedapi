<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Invernadero:</strong>
            {!! Form::select('greenhouse_id', $greenhouse,null,['class' => 'form-control required','placeholder' => 'Seleccione Invernadero']); !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Descripcion:</strong>
            {!! Form::text('description', null, array('placeholder' => 'T2018-2019','class' => 'form-control  required')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Año:</strong>
            {!! Form::text('year', null, array('placeholder' => '2020','class' => 'form-control  required numeric')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Lineas:</strong>
            {!! Form::text('lines', null, array('placeholder' => 'Ingrese cantidad de lineas','class' => 'form-control  required')) !!}
        </div>
    </div>
</div>