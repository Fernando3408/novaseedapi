@extends('layouts.app')

@section('content')

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Crear Usuario
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        {!! Form::open(array('route' => 'users.store','method'=>'POST','id' => 'FormCreateUser')) !!}
            @include('users.form')
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#FormCreateUser").validate({
            rules:{
                "password": {
                    required: true,
                },
                "password_repeat": {
                    required: true,
                    equalTo: "input[name=password]"
                }
            }
        })
	});
</script>


@endsection
