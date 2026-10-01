<?php $roles = App\Role::pluck('description','id');?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <strong>Nombre:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Ingrese Nombre','class' => 'form-control required')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <strong>Rut:</strong>
            {!! Form::text('rut', null, array('placeholder' => 'Ingrese Rut','class' => 'form-control required rut')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Ingrese Email','class' => 'form-control email')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <strong>Teléfono:</strong>
            {!! Form::text('phone', null, array('placeholder' => 'Ingrese Teléfono','class' => 'form-control number')) !!}
        </div>
    </div>
    
    <hr style="border-top: 2px solid #ececec;    width: 97%;    margin-bottom: 25px;margin-top: 25px;">
    
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="form-group">
            <strong>Contraseña:</strong>
            <input name="password" type="password" class="form-control required" name="password"  placeholder="Ingrese Contraseña">
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="form-group">
            <strong>Repetir Contraseña:</strong>
            <input name="password_repeat" type="password" class="form-control required" name="password"  placeholder="Ingrese Contraseña">
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="form-group">
            <strong>Tipo de Usuario:</strong>
            {!! Form::select('role_id', $roles,null,['class' => 'form-control required','placeholder' => 'Seleccione Tipo de Usuario']); !!}
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
        $("select[name=enterprise_id]").change(function(){
            if($(this).val() != ""){
                $("select[name=location_id]").removeAttr("disabled")
                $.ajax({
                    url: '/locations/json/'+$("select[name=enterprise_id]").val(),
                    type: 'GET',
                    dataType: 'json',
                }).done(function(data) {
                    $("select[name=location_id]").html('<option value="" selected="selected">Seleccione Ubicación</option>')
                    if(data != null){
                        $.each(data,function(i,location){
                            $("select[name=location_id]").append('<option value="'+location.id+'">'+location.name+'</option>')
                        })
                    }
                })
            }else{
                $("select[name=location_id]").attr("disabled","disabled")
            }
        })

        $("select[name=role_id]").change(function(){
            $("select[name=location_id]").parent().parent().hide()
            $("select[name=enterprise_id]").parent().parent().hide()
            if($(this).val() == 2){
                $("select[name=enterprise_id]").parent().parent().show()
            }else if($(this).val() == 3){
                $("select[name=location_id]").parent().parent().show()
                $("select[name=enterprise_id]").parent().parent().show()
            }
        })

        
    })
</script>
