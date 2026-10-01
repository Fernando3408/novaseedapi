@extends('layouts.app')
@section('content')
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Ensayos
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('blocks.create') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Crear Ensayo
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Description</th>
                    <th>Lines</th>
                    <th>Year</th>
                    <th>Eliminado</th>
                    <th style="width:400px"></th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($blocks))
                    @foreach($blocks as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td><b>{{ $record->description }}</b></td>
                            <td><b>{{ $record->lines }}</b></td>
                            <td><b>{{ $record->year }}</b></td>
                            <td><b>{{ $record->deleted_at }}</b></td>
                            <td>
                                <center>
                                    <a class="btn btn-info btn-sm" href="{{ route('blocks.show',$record->id) }}">Ver</a>
                                    <a class="btn btn-outline-warning btn-sm" onclick="modificarCaracteristicas({{ $record->id }})">Modificar Características</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['blocks.destroy', $record->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Eliminar', ['class' => 'btn btn-outline-danger btn-sm btn-delete']) !!}
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

<div class="modal fade show" id="modal-caracteristicas" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modificación de características</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque earum repudiandae distinctio, nemo at, iure rem labore maxime deleniti libero autem perferendis, incidunt provident maiores! Optio veritatis unde iure quia.
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        $(".table").DataTable();

        modificarCaracteristicas = function(id){
            $.ajax({
                url: '/blocks/modCaracteristicas',
                type: 'GET',
                dataType: 'html',
                data: {block_id: id},
            }).done(function(data) {
                $("#modal-caracteristicas .modal-body").html(data)
                $("#modal-caracteristicas").modal("show")
            })
            
        }
    })
</script>
@endsection
