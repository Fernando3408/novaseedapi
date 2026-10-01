<div id="content-cruce">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Título:</strong>
                {!! Form::text('title', null, array('placeholder' => 'Ingrese título','class' => 'form-control  required')) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="form-group">
                <strong>Cruce</strong>
                {!! Form::select('varity_id', $crossings,$id,['class' => 'form-control required','placeholder' => 'Selecciona Variedad']); !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="form-group">
                <strong>Comuna</strong>
                {!! Form::select('community_id', $communities,null,['class' => 'form-control required','placeholder' => 'Selecciona Comuna']); !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        <strong>Reseña</strong>
        {!! Form::textarea('review',null,['class' => 'form-control required','placeholder' => 'Escriba reseña']); !!}
    </div>
</div>
<!--
<div class="row">
    
</div> 
-->

<script>
    $(document).ready(function(){
        $("input[name=cruce]").click(function(){
            if ($("input[name=cruce][value=1]").is(":checked")) {
                $("#content-cruce").show()
            }else{
                 $("#content-cruce").hide()
            }
        })
    })
</script>