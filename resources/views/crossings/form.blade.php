<input type="hidden" name="crossing" value="1">
<div id="content-cruce">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="form-group">
                <strong>Madre</strong>
                {!! Form::select('mother_id', $varities,null,['class' => 'form-control required','placeholder' => 'Selecciona Variedad', 'id' => 'select_mother']); !!}
            </div>
        </div>
        <div class="col-lg-1">
            <button type="button" class="btn btn-warning find-btn" attr-selector="mother" style="margin-top: 19px"><i class="fa fa-search"></i></button>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="form-group">
                <strong>Padre</strong>
                {!! Form::select('father_id', $varities,null,['class' => 'form-control required','placeholder' => 'Selecciona Variedad', 'id' => 'select_father']); !!}
            </div>
        </div>
        
        <div class="col-lg-1">
            <button type="button"  class="btn btn-warning find-btn"  attr-selector="father" style="margin-top: 19px"><i class="fa fa-search"></i></button>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-2">
            <div class="form-group">
                <strong>Mercado Objetivo</strong>
                {!! Form::select('market_id', $markets,null,['class' => 'form-control required','placeholder' => 'Selecciona Mercado']); !!}
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2">
            <div class="form-group">
                <strong>Año:</strong>
                {!! Form::text('year', null, array('placeholder' => 'Ingrese Año de cruce','class' => 'form-control ')) !!}
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-3">
            <div class="form-group">
                <strong>Berries:</strong>
                {!! Form::text('berries_objective', null, array('placeholder' => 'Ingrese objetivo Berries','class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-9">
            <div class="form-group">
                <strong>Objetivo del cruce:</strong>
                {!! Form::textarea('observation', null, array('placeholder' => '','class' => 'form-control')) !!}
            </div>
        </div>
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

        $("select").select2();
    })
</script>