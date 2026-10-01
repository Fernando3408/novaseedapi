<div class="row">
    <div class="col-lg-4">
        <fieldset>
            <legend>Agregar Foto</legend>
            {!! Form::open(array('class' => 'm-form','url' => '/crossings/uploadPhoto','method'=>'POST','id' => 'FormAddPhoto','files' => true)) !!}
                <input type="hidden" name="crossing" value="<?= $crossing->id;?>">
                <div class="form-group">
                    {!! Form::select('year', array(0 => 'Año 0',1 => 'Año 1',2 => 'Año 2',3 => 'Año 3',4 => 'Año 4') ,null,['class' => 'form-control required']); !!}
                </div>    
                <div class="form-group">
                    <strong>Archivo:</strong><br>
                    {!! Form::file('file',  array('placeholder' => 'Ingrese Archivo','class' => ' required')) !!}
                </div>
                <button class="btn btn-success"><i class="fa fa-save"></i> Subir foto</button>
            {!! Form::close() !!}
        </fieldset>   
    </div>
    <div class="col-lg-8">
        <fieldset>
            <legend>Listado de Fotos</legend>
            @include('crossings.show.listPhotos',['multimedias' => $crossing->multimedias])
        </fieldset>
    </div>
</div>