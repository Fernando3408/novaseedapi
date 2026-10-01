@extends('layouts.app')

@section('content')

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Agregar Característica
                </h3>
            </div>          
        </div>
    </div>
    {!! Form::open(array('class' => 'm-form','route' => 'features.store','method'=>'POST','id' => 'FormCreateTypeEngine')) !!}
        <div class="m-portlet__body">   
            <div class="m-form__section m-form__section--first">        
                @include('features.form')
            </div>
            <button type="submit" class="btn btn-primary">Crear Característica</button>
        </div>

    {!! Form::close() !!}
</div>

<script>
    $(document).ready(function() {
        $("#FormCreateTypeEngine").validate()
	});
</script>


@endsection
