@extends('layouts.app')
@section('content')
@php
use App\Models\Varity;
@endphp
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-calendar"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Variedades En papelera
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('varities.create') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Crear Variedad
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table-cross">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Madre</th>
                    <th>Padre</th>
                    <th>Destino Productivo</th>
                    <th>Fecha El.</th>
                    <th>Hijos</th>
                    <th style="width:350px"></th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($varities))
                    @foreach($varities as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td><b>{!! !empty($record->name) ?  $record->name : $record->code !!}</b></td>
                            <td><b>{!! @$record->motherName() !!}</b></td>
                            <td><b>{!! @$record->fatherName() !!}</b></td>
                            <td>
                                <b>
                                    @if($record->marketold_id == $record->market_id)
                                        {{ @$record->market->name }}
                                    @else
                                        Original: {{ @$record->marketold->name }}, Modificado: {{ @$record->market->name }}
                                    @endif
                                </b>
                            </td>
                            <td><b>{{ @$record->deleted_at }}</b></td>
                            @php
                                $hijos = Varity::orWhere('mother_id',$record->id)->orWhere('father_id', $record->id)->count();
                            @endphp
                            <td>{{$hijos}}</td>
                            <td>
                                <center>
                                   <a class="btn btn-outline-info btn-sm" href="{{ route('varities.show',$record->id) }}">Ver</a>
                                    <a class="btn btn-outline-success btn-sm " href="{{ route('varities.edit',$record->id) }}">Editar</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['varities.destroy', $record->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Eliminar', ['class' => 'btn btn-outline-danger btn-sm btn-delete']) !!}
                                    {!! Form::close() !!}
                                    {!! Form::open(['method' => 'POST','route' => ['varities.restore', $record->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Recuperar', ['class' => 'btn btn-outline-success btn-sm btn-success']) !!}
                                    {!! Form::close() !!}
                                </center>
                            </td>

                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".table").DataTable({
            ordering: false
        })
        $(".btn-delete").click(function(){
                    var element = $(this)
                    swal(
                        {
                            title:"¿Está seguro que quiere eliminar?",
                            text:"Si elimina el registro este desaparecera del sistema y no podrá volver atrás.",
                            type:"warning",
                            showCancelButton:!0,
                            confirmButtonText:"Si, eliminar",
                            cancelButtonText:"No, cancelar",
                            reverseButtons:!0
                        }
                    ).then(function(e){
                        if(e.value){
                           $(element).parent().submit()
                        }else{
                            return false;
                        }
                    })
                    return false;
                })

    });
</script>
@endsection
