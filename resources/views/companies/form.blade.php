<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <label>Nombre:</label>
            {!! Form::text('name', null, array('placeholder' => 'Ingrese Nombre','class' => 'form-control required')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <label>Email:</label>
            {!! Form::text('mail', null, array('placeholder' => 'Ingrese Email','class' => 'form-control email')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <label>País:</label>
            {!! Form::text('country', null, array('placeholder' => 'Ingrese Teléfono','class' => 'form-control number')) !!}
        </div>
    </div>
    
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <label>Localidad:</label>
            {!! Form::text('locality', null, array('placeholder' => 'Ingrese description','class' => 'form-control ')) !!}
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <label>Contacto:</label>
            {!! Form::text('contact', null, array('placeholder' => 'Ingrese description','class' => 'form-control ')) !!}
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <label>Descripcion:</label>
            {!! Form::text('description', null, array('placeholder' => 'Ingrese description','class' => 'form-control ')) !!}
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
