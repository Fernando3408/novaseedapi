@extends('layouts.app')
@section('content')


<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Actualizar Royalty</b>
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('royalties.show', $farmer_id) }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Volver
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        {!! Form::model($royalty, ['method' => 'PATCH','id' => 'FormEdit','route' => ['royalties.update', $royalty->id]]) !!}
		    @include('farmers.royalties.form')
		    <button type="submit" class="btn btn-primary">Editar</button>
		{!! Form::close() !!}
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#FormEdit").validate()
	});
</script>

@endsection
