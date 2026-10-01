@extends('layouts.app')
@section('content')


<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Actualizar Campo</b>
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('lands.index') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Volver
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        {!! Form::model($traceability, ['method' => 'PATCH','id' => 'FormEdit','route' => ['traceability.update', $traceability->id]]) !!}
		    @include('traceability.form')
		    <button type="submit" class="btn btn-primary">Editar</button>
		{!! Form::close() !!}
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#FormEdit").validate()
        $("select[name=farmer_id]").change();
	});
</script>
@endsection
