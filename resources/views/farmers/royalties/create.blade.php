@extends('layouts.app')

@section('content')

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Añadir Royalty
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        {!! Form::open(array('route' => 'royalties.store','method'=>'POST','id' => 'FormCreateFarmer')) !!}
            @include('farmers.royalties.form')
            <button type="submit" class="btn btn-primary">Añadir Royalty</button>
        {!! Form::close() !!}
    </div>
</div>


@endsection
