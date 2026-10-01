@extends('layouts.public')

@section('content')
<div class="m-login__head">
    <h3 class="m-login__title">Iniciar Sesión</h3>
</div>
<div class="container">
    <form class="m-login__form m-form" method="POST" action="{{ route('login') }}" id="FormLogin">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
            <label for="rut" class="col-md-4 control-label">Rut</label>

            <input id="rut" type="text" class="form-control required rut" name="rut" value="{{ old('rut') }}" required autofocus autocomplete="username">

            @if ($errors->has('rut'))
                <span class="help-block">
                    <strong>{{ $errors->first('rut') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Contraseña</label>

            <input id="password" type="password" class="form-control required" name="password" required autocomplete="current-password">

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

            <div class="row">
                <div class="col"></div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-block">
                        Iniciar Sesión
                    </button>
                </div>
                <div class="col"></div>
            </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        $("#FormLogin").submit(function(){
            if($("#FormLogin").valid()){
                $("#FormLogin button").html('<i class="fa fa-spinner fa-spin"></i> Iniciando Sesión')
                return true;
            }
            return false;
        })
        $("#FormLogin").validate()
    })
</script>

@endsection
