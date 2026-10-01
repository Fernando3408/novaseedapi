@extends('layouts.app')
@section('content')


<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Editar Mapeo</b>
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        {!! Form::model($mapout, ['method' => 'PATCH','id' => 'FormEditTypeEngine','route' => ['mapouts.update', $mapout->id]]) !!}
		    @include('mapouts.form')
		    <button type="submit" class="btn btn-primary">Editar</button>
		{!! Form::close() !!}
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#FormEditTypeEngine").validate()
        
        
	});
</script>
@endsection
