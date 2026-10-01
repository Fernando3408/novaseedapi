@extends('layouts.app')
@section('content')
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-calendar"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Importar Excel Masivo
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        {!! Form::open(array('class' => 'm-form','method'=>'POST','id' => 'FormCreateTypeEngine','enctype' => 'multipart/form-data')) !!}
            <div class="m-portlet__body">   
                <div class="m-form__section m-form__section--first">        
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <div class="form-group">
                                <strong>Archivo Excel:</strong>
                                {!! Form::file('file', null, array('placeholder' => 'Ingrese Excel','class' => 'form-control  required')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Subir Excel</button>
            </div>

        {!! Form::close() !!}
    </div>
</div>
@endsection
