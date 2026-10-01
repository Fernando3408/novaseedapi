@extends('layouts.app')
@section('content')


<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Editar Usuario &nbsp;<b>({{ $user->name }})</b>
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        {!! Form::model($user, ['method' => 'PATCH','id' => 'FormEditUser','route' => ['users.update', $user->id]]) !!}
		    @include('users.form')
		    <button type="submit" class="btn btn-primary">Editar Usuario</button>
		{!! Form::close() !!}
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#FormEditUser").validate({
            rules:{
                "password_repeat": {
                    equalTo: "input[name=password]"
                }
            }
        })
        $("#FormEditUser input[name=password]").removeClass("required")
        $("#FormEditUser input[name=password_repeat]").removeClass("required")
        $("#FormEditUser select[name=role_id]").val("<?= $role_id;?>").trigger("change")
        $("#FormEditUser select[name=enterprise_id]").val("<?= $user->enterprise_id;?>").trigger("change")
        setTimeout(function(){ 
            $("#FormEditUser select[name=location_id]").val("<?= $user->location_id;?>").trigger("change") 
        }, 200);
	});
</script>
@endsection
