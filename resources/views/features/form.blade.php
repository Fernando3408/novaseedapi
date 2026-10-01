<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Nombre:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Ingrese característica','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Familia:</strong>
        {!! Form::select('typefeature_id', $typefeatures, null , array('placeholder' => 'Seleccione para familia','class' => 'form-control  required')) !!} 
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Visualización Localidad:</strong>
            {!! Form::select('localities_viewer', array(0 => 'Común a Todas las localidades', 1 => 'Solo por Localidad' ), null , array('placeholder' => 'Seleccione Tipo de Visualización','class' => 'form-control  required')) !!}
        </div>
    </div>
    <input type="hidden" name="year" value="0">
</div>
<hr>
<span class="m-section__sub">
    Valores
</span>
<div class="row">
    <div class="col-lg-4">
        {!! Form::select('type', array(1 => 'Calificación', 2 => 'Si o no',3 => 'Valor' ), null , array('placeholder' => 'Seleccione Tipo de valor','class' => 'form-control  required')) !!} 
    </div>
    <div class="col-lg-4">
        {!! Form::select('repeat_do', array(0 => 'Sin Repeticiones', 1 => 'Con Repeticiones' ), null , array('placeholder' => 'Seleccione para habilitar Repeticiones','class' => 'form-control  required')) !!} 
    </div>
    <div class="col-lg-2">
        <button type="button" id="new-value" class="btn btn-success"><i class="fa fa-plus"></i> Agregar otro valor</button>
    </div>
</div>     
<br>

<div class="row" id="elements-value">
    
</div>
<!--
<div class="row">
    
</div> 
-->

<script>
    $(document).ready(function(){

        var values = 1;

        $("select[name=type]").change(function(){
            $("#elements-value").html("")
            if($(this).val() == 1 || $(this).val() == 2){
                $("#new-value").removeAttr("disabled")
            }else{
                $("#new-value").attr("disabled","disabled")
            }
        })

        $("#new-value").click(function(){
            
            if($("select[name=type]").val() == 1 || $("select[name=type]").val() == 2 && values <= 2){
                $.ajax({
                    url: '/features/add-value',
                    type: 'GET',
                    dataType: 'html',
                    data: {id: values++},
                }).done(function(data) {
                    $("#elements-value").append(data)
                })
            }
            
        })
    })
</script>
