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
                    Mapeos
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('mapouts.create') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Crear mapeo
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
                    <th>Invernadero</th>
                    <th>Descripcion</th>
                    <th>Año</th>
                    <th>Lineas</th>
                    <th>Fase</th>
                    <th>Fech. Creación</th>
                    <th style="width:350px"></th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($mapouts))
                    @foreach($mapouts as $record)
                        @if($record->id != 14)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td><b>{{ @$record->greenhouse->name }}</b></td>
                            <td><b>{{ $record->description }}</b></td>
                            <td><b>{{ $record->year }}</b></td>
                            <td><b>{{ $record->lines }}</b></td>
                            <td>
                                @if($record->step == 0)
                                    <span class="m-badge m-badge--warning m-badge--wide">Primera Etapa</span>
                                @elseif($record->step == 1)
                                    <span class="m-badge m-badge--warning m-badge--wide">
                                        Ya codificado
                                    </span>
                                @endif
                            </td>
                            <td><b>{{ $record->created_at }}</b></td>
                            <td>
                                <center>
                                    <a class="btn btn-outline-info btn-sm" href="{{ route('mapouts.show',$record->id) }}">Ver</a>
                                    <a class="btn btn-outline-success btn-sm" href="{{ route('mapouts.edit',$record->id) }}">Editar</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['mapouts.destroy', $record->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Eliminar', ['class' => 'btn btn-outline-danger btn-sm btn-delete']) !!}
                                    {!! Form::close() !!}
                                </center>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        
    })

    
</script>
@endsection
