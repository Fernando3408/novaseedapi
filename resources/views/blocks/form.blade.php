<?php $lineas = array(1 => '1 linea',2 => '2 lineas',3 => '3 lineas', 4 => '4 lineas', 5 => '5 lineas',6 => '6 lineas',7 => '7 lineas',8 => '8 lineas',9 => '9 lineas',10 => '10 lineas',11 => '11 lineas',12 => '12 lineas');

    $localities = array(1 => "Pto. Varas", 2 => "Pta. Arenas", 3 => "Secano");
?>
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Descripción:</strong>
            {!! Form::text('description', null, array('placeholder' => 'Ingrese description','class' => 'form-control required ')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Año:</strong>
            {!! Form::text('year', null, array('placeholder' => 'Ingrese año','class' => 'form-control required')) !!}
        </div>
    </div>


    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Cantidad de lineas:</strong>
            {!! Form::text('lines', null, array('placeholder' => 'Ingrese cantidad','class' => 'form-control required')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4"> 
        <div class="form-group">
            <strong>Localidad:</strong>
            {!! Form::select('locality', $localities,null,['class' => 'form-control required','placeholder' => 'Selecciona Localidad']) !!}
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <strong>Latitud:</strong>
            {!! Form::text('latitude', null, array('placeholder' => 'Seleccione en el mapa un punto','class' => 'form-control required')) !!}
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <strong>Longitud:</strong>
            {!! Form::text('longitude', null, array('placeholder' => 'Seleccione en el mapa un punto','class' => 'form-control required')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div id="google_maps">
            <input id="pac-input" class="controls" type="text" placeholder="Escribe la dirección aquí">
            <div id="map" style="height: 400px;width: 100%;margin-bottom: 30px;"></div>
        </div>
    </div>
</div>

