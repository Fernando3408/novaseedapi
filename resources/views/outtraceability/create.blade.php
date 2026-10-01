@extends('layouts.app')

@section('content')
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon"><i class="flaticon-add"></i></span>
                <h3 class="m-portlet__head-text">Nueva Trazabilidad Externa</h3>
            </div>
        </div>
    </div>

    <div class="m-portlet__body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0;">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {!! Form::open(['route' => 'outtraceability.store', 'method' => 'POST', 'files' => true]) !!}
            @include('outtraceability.form')
            <button type="submit" class="btn btn-primary" style="margin-top: 0px">Guardar</button>
            <a href="{{ route('outtraceability.index') }}" class="btn btn-secondary">Volver</a>
        {!! Form::close() !!}

    </div>
</div>
@endsection
