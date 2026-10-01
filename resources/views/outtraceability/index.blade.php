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
                    Trazabilidad Externa (Out)
                </h3>
            </div>
        </div>

        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('outtraceability.create') }}" class="btn btn-primary btn-sm">
                        <i class="la la-plus"></i> Nuevo
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="m-portlet__body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped- table-bordered table-hover table-checkable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Año convenio</th>
                    <th>País</th>
                    <th>Variedad</th>
                    <th>Cliente</th>
                    <th>Permiso</th>
                    <th style="width:250px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ $r->agreement_year }}</td>
                        <td>{{ $r->country }}</td>
                        <td>
                          @foreach($r->varities as $v)
                            <span class="badge badge-secondary">{!! $v->name !!}</span>
                          @endforeach
                        </td>
                        <td>{{ $r->client }}</td>
                        <td>
                          @if(!empty($r->import_permit))
                            <a href="{{ asset($r->import_permit) }}" target="_blank"><i class="la la-edit"></i>Ver</a>
                          @else
                            -
                          @endif
                        </td>

                        <td>
                            <center>
                                <a href="{{ route('outtraceability.edit', $r->id) }}" class="btn btn-sm btn-outline-warning">
                                   Editar
                                </a>
                                {!! Form::open(['method' => 'DELETE','route' => ['outtraceability.destroy', $r->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Eliminar', ['class' => 'btn btn-outline-danger btn-sm btn-delete']) !!}
                                {!! Form::close() !!}>
                            </center>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $(".table").DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: 'Excel',
                exportOptions: { columns: [0,1,2,3,4,5] }
            }]
        });
    });
</script>
@endsection
