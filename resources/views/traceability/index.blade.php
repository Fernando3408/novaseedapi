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
                    Trazabilidad Productiva
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('traceability.create') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Añadir Traza
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
            <thead>
                <tr>
                    <th>Agricultor</th>
                    <th>Campo</th>
                    <th>Variedad</th>
                    <th>Cantidad</th>
                    <th>Produccion</th>
                    <th>Categoria</th>
                    <th>Proveedor</th>
                    <th style="width:350px"></th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($farmer))
                    @foreach($farmer as $record)
                        <tr>
                            <td><b>{{ $record->farmer->name }}</b></td>
                            <td><b>{{ $record->land->name }}</b></td>
                            <td><b>{!! @$record->varity->name !!}</b></td>
                            <td><b>{{ $record->quantity }}</b></td>
                            <td><b>{{ $record->production }}</b></td>
                            <td><b>{{ $record->tubers }}</b></td>
                            <td><b>{{ $record->provider }}</b></td>
                            <td>
                                <center>
                                    <a class="btn btn-outline-success btn-sm" href="{{ route('traceability.edit',$record->id) }}">Editar</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['traceability.destroy', $record->id],'style'=>'display:inline']) !!}
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
<script type="text/javascript">
    $(document).ready(function(){
        $(".table").DataTable({
          dom: 'Bfrtip',
          buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                         columns: [ 0,1,2,3,4,5]
                    }
                }]
        })
    })
</script>
@endsection
