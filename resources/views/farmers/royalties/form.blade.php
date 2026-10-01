<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <label>Año:</label>
            {!! Form::text('year', null, array('placeholder' => 'Ingrese Año','class' => 'form-control required')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <label>Temporada:</label>
            {!! Form::text('season', null, array('placeholder' => 'Ingrese Temporada','class' => 'form-control ')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <label>Monto:</label>
            {!! Form::text('pay', null, array('placeholder' => 'Ingrese Monto','class' => 'form-control required')) !!}
        </div>
    </div>

    {!! Form::hidden('farmer_id', $farmer_id, array('placeholder' => 'Ingrese Monto','class' => 'form-control')) !!}
   
</div>


<script>
    $(document).ready(function(){
    })
</script>
