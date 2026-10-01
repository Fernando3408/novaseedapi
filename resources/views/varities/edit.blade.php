@extends('layouts.app')
@section('content')


<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Editar Variedad</b>
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        {!! Form::model($varity, ['method' => 'PATCH','id' => 'FormEdit','route' => ['varities.update', $varity->id]]) !!}
		    @include('varities.form')
		    <button type="submit" class="btn btn-primary">Guardar Edición</button>
		{!! Form::close() !!}
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#FormEdit").validate()
	});
</script>
@endsection
