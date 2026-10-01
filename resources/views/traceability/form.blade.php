<div class="row">
    

    <div class="col-xs-6 col-sm-6 col-md-3">
        <label for="name">Agricultor</label>
        {!! Form::select('farmer_id', $farmer,null,['class' => 'form-control required','placeholder' => 'Selecciona Agricultor']); !!}
    </div>

    <div class="col-xs-6 col-sm-6 col-md-3">
        {!!Form::label('Campo')!!}
        {!!Form::select('land_id',array(), null,['class' => 'form-control required select2','placeholder' => 'Seleccione Campo'])!!}
    </div>

    <div class="col-xs-6 col-sm-6 col-md-3">
        <label for="name">Variedad</label>
        {!! Form::select('varity_id', $varity,null,['class' => 'form-control required','placeholder' => 'Selecciona Región']); !!}

    </div>

    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <label>Cantidad Ton(ha):</label>
            {!! Form::text('quantity', null, array('placeholder' => 'Ingrese Cantidad','class' => 'form-control required')) !!}
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label>Producción:</label>
            {!! Form::text('production', null, array('placeholder' => 'Ingrese Producción','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label>Categoria:</label>
            {!! Form::select('certifies_id', $certifies,null,['class' => 'form-control required','placeholder' => 'Seleccione Categoria']); !!}
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label>Proveedor:</label>
            {!! Form::text('provider', "Novaseed", array('placeholder' => 'Ingrese Proveedor','class' => 'form-control')) !!}
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
        $("select[name=farmer_id]").change(function(){
            farmer_id = $(this).val()
            if(farmer_id != ""){
                $("select[name=land_id]").html('<option value="">Seleccione Campo</option>')
                $.getJSON("/farmers/lands?farmer_id="+farmer_id, function( data ) {
                    var items = [];
                    $.each( data, function( key, val ) {
                        $("select[name=land_id]").append( "<option value='" + val.id + "'>" + val.name + "</option>" );
                    });
                    $("select[name=land_id]").removeAttr("disabled")
                });
            }
        })
    })
</script>
