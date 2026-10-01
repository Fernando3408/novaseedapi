<div class="row">

    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="form-group">
            {!! Form::label('agreement_year', 'Año del convenio') !!}
            {!! Form::number('agreement_year', null, ['class' => 'form-control required', 'min' => 1900, 'max' => 2100]) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="form-group">
            {!! Form::label('country', 'País') !!}
            {!! Form::text('country', null, ['class' => 'form-control required', 'maxlength' => 100]) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            {!! Form::label('client', 'Cliente') !!}
            {!! Form::text('client', null, ['class' => 'form-control required', 'maxlength' => 150, 'placeholder' => 'Ingrese cliente']) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        {!! Form::label('varities', 'Variedades (múltiple)') !!}
        <select name="varities[]" class="form-control select2" multiple required>
          @foreach($varity as $v)
            <option value="{{ $v->id }}"
              @if(!empty($selected) && in_array($v->id, $selected)) selected @endif
            >
              {!! $v->name !!}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            {!! Form::label('import_permit', 'Permiso de Importación (archivo)') !!}
            {!! Form::file('import_permit', ['class' => 'form-control']) !!}
            @if(!empty($row) && !empty($row->import_permit))
                <small class="form-text text-muted" style="display:block;margin-top:6px;">
                    Archivo actual:
                    <a href="{{ asset($row->import_permit) }}" target="_blank">Ver permiso</a>
                </small>
            @endif
            <small class="form-text text-muted">Formatos: PDF/JPG/PNG. Máx 5MB.</small>
        </div>
    </div>

</div>

<script>
    $(document).ready(function(){
        $('.select2').select2({
            width: '100%',
            escapeMarkup: function (markup) { return markup; } // para que se vea el icono
          });
    });
</script>
