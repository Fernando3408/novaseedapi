@extends('layouts.app')

@section('content')

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Crear Mapeo (1 Etapa)
                </h3>
            </div>          
        </div>
    </div>
    {!! Form::open(array('class' => 'm-form','route' => 'mapouts.store','method'=>'POST','id' => 'FormCreate')) !!}
        <div class="m-portlet__body">   
            <div class="m-form__section m-form__section--first">        
                @include('mapouts.form')
            </div>
            <button type="submit" class="btn btn-primary">Crear</button>
        </div>

    {!! Form::close() !!}
</div>

<script>
    $(document).ready(function() {
        $("#FormCreate").validate()
	});
</script>




@endsection
