<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Descripción Standard:</label>
            {!! Form::text('name', null, array('placeholder' => 'Ingrese Descripción','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-3">
        <label for="name">Mercado</label>
        {!! Form::select('market_id', $markets,null,['class' => 'form-control required','placeholder' => 'Selecciona Mercado']); !!}
    </div>

    <div class="col-xs-6 col-sm-6 col-md-3">
        <label for="name">Variedades</label>
          <select class="form-control required varities" name="varities[]" id="varities_list" multiple="multiple">
            @foreach($varities as $v)
                <option value="{{@$v->id}}">{{@$v->name}}</option>  
            @endforeach
        </select>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="form-group">
            <label>Standard:</label>
            {!! Form::text('std', null, array('placeholder' => 'Cálculo Standard','class' => 'form-control', 'readonly=readonly')) !!}
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

        $(".varities").select2();


        $("#varities_list").change(function(){
            d = $("#varities_list").val()
            
            if(d != ""){
                $.getJSON("/relative_yield/calc?ids="+d, function( data ) {
                    $("input[name=std]").val(data)
                });
            }
        })
    })
</script>
