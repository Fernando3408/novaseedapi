<input type="hidden" name="crossing" value="1">
<div id="content-cruce">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4">
            <div class="form-group">
                <strong>Mercado Objetivo</strong>
                {!! Form::select('market_id', $markets,null,['class' => 'form-control required','placeholder' => 'Selecciona Mercado']); !!}
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-4">
            <div class="form-group">
                <strong>Berries:</strong>
                {!! Form::text('berries', null, array('placeholder' => 'Cantidad de Berries','class' => 'form-control  required')) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4">
            <div class="form-group">
                <strong>Observación:</strong>
                {!! Form::text('observation', null, array('placeholder' => '','class' => 'form-control  ')) !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("select").select2();
    })
</script>