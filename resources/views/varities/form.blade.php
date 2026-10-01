<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Nombre:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Ingrese nombre variedad','class' => 'form-control required ')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Año:</strong>
            {!! Form::text('year', null, array('placeholder' => 'Ingrese Año de cruce','class' => 'form-control  required')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4">
        <div class="form-group">
            <strong>Destino Productivo</strong>
            {!! Form::select('market_id', $markets,null,['class' => 'form-control required','placeholder' => 'Selecciona Destino Productivo']); !!}
        </div>
    </div>
</div>

<fieldset>
    <legend>Arbol Geneológico</legend>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-5">
            <div class="form-group">
                <strong>Madre</strong>
                {!! Form::select('mother_id', $varities,null,['class' => 'form-control ','placeholder' => 'Selecciona Variedad']); !!}
            </div>
        </div>
        <div class="col-lg-1">
            <button type="button" class="btn btn-warning find-btn" style="margin-top: 19px"><i class="fa fa-search"></i></button>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5">
            <div class="form-group">
                <strong>Padre</strong>
                {!! Form::select('father_id', $varities,null,['class' => 'form-control ','placeholder' => 'Selecciona Variedad']); !!}
            </div>
        </div>
        <div class="col-lg-1">
            <button type="button"  class="btn btn-warning find-btn" style="margin-top: 19px"><i class="fa fa-search"></i></button>
        </div>
    </div>
</fieldset>


<script>
    $(document).ready(function(){
        $("select").select2();
        
    })
</script>