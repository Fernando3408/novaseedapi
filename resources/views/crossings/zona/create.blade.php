@extends('layouts.app')

@section('content')

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Agregar Ensayo Zona
                </h3>
            </div>          
        </div>
    </div>
    {!! Form::open(array('class' => 'm-form','route' => 'crossings.zonacreate','method'=>'POST','id' => 'FormCreate')) !!}
        <input type="hidden" name="user_id" value="<?= Auth::user()->id;?>">
        <div class="m-portlet__body">   
            <div class="m-form__section m-form__section--first">        
                @include('crossings.zona.form')
            </div>
            <button type="submit" class="btn btn-primary">Crear Ensayo</button>
        </div>

    {!! Form::close() !!}
</div>

<script>
    $(document).ready(function() {
        $("#FormCreate").validate()
	});
</script>


@endsection
