<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <label>Nombre:</label>
            {!! Form::text('name', null, array('placeholder' => 'Ingrese Nombre','class' => 'form-control required')) !!}
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-3">
        <label for="name">Agricultor</label>
        {!! Form::select('farmer_id', $farmer,null,['class' => 'form-control required','placeholder' => 'Selecciona Región']); !!}
    </div>
    <div class="col-md-6 col-sm-6"></div>

    <div class="col-xs-6 col-sm-6 col-md-3">
        <label for="name">Región</label>
        {!! Form::select('region_id', $region,null,['class' => 'form-control required','placeholder' => 'Selecciona Región']); !!}

    </div>

    <div class="col-xs-6 col-sm-6 col-md-3">
        {!!Form::label('Provincia')!!}
        {!!Form::select('province_id',array(), null,['class' => 'form-control required select2 provinces','placeholder' => 'Seleccione Provincia'])!!}
    </div>
    <div class="col-xs-6 col-sm-6 col-md-3">
        {!!Form::label('Comuna')!!}
        {!!Form::select('community_id',array(), null,['class' => 'form-control required select2','placeholder' => 'Seleccione Comuna'])!!}
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label>Latitud:</label>
            {!! Form::text('latitude', null, array('placeholder' => 'Seleccione en el mapa un punto','class' => 'form-control', 'readonly' => 'readonly')) !!}
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label>Longitud:</label>
            {!! Form::text('longitude', null, array('placeholder' => 'Seleccione en el mapa un punto','class' => 'form-control', 'readonly' => 'readonly')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div id="google_maps">
            <input id="pac-input" class="controls" type="text" placeholder="Escribe la dirección aquí">
            <div id="map" style="height: 400px;width: 100%;margin-bottom: 30px;"></div>
        </div>
    </div>
</div>
    <!--
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Body:</strong>
            {!! Form::textarea('body', null, array('placeholder' => 'Body','class' => 'form-control','style'=>'height:150px')) !!}
        </div>
    </div>-->

<script>
    $(document).ready(function(){
        $("select[name=region_id]").change(function(){
            region_id = $(this).val()
            if(region_id != ""){
                $("select[name=province_id]").html('<option value="">Seleccione Provincia</option>')
                $("select[name=community_id]").html('<option value="">Seleccione Ciudad</option>')
                $.getJSON("/farmers/provinces?region_id="+region_id, function( data ) {
                    var items = [];
                    $.each( data, function( key, val ) {
                        $("select[name=province_id]").append( "<option value='" + val.id + "'>" + val.name + "</option>" );
                    });
                    $("select[name=province_id]").removeAttr("disabled")
                    
                });
            }
        })

        $("select[name=province_id]").change(function(){
            province_id = $(this).val()
            if(province_id != ""){
                $("select[name=community_id]").html('<option value="">Seleccione Ciudad</option>')
                $.getJSON( "/farmers/communities?province_id="+province_id, function( data ) {
                    var items = [];
                    $.each( data, function( key, val ) {
                        $("select[name=community_id]").append( "<option value='" + val.id + "'>" + val.name + "</option>" );
                        $("select[name=community_id_dynamic]").append( "<option value='" + val.id + "'>" + val.name + "</option>" );
                    });
                    $("select[name=community_id]").removeAttr("disabled")
                });
            }
        })

        $("select[name=community_id]").change(function(){
            var text = $("select[name=community_id] option:selected").text()
            $("#pac-input").val(text).change()
        })
    })
</script>
