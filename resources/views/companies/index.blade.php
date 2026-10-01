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
                    Empresas
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('companies.create') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Añadir Empresa
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>País</th>
                    <th>Localidad</th>
                    <th>Contacto</th>
                    <th>Descripcion</th>
                    <th>Royalty</th>
                    <th style="width:350px"></th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($Companies))
                    @foreach($Companies as $record)
                        <tr>
                            <td><b>{{ $record->name }}</b></td>
                            <td><b>{{ $record->mail }}</b></td>
                            <td><b>{{ $record->country}}</b></td>
                            <td><b>{{ $record->locality}}</b></td>
                            <td><b>{{ $record->contact }}</b></td>
                            <td><b>{{ $record->description }}</b></td>
                            @if($record->getStatusPayment() == 1 )
                            
                                <td><span style="float:right" class="m-badge m-badge--info m-badge--wide">Al día</span></td>
                            @else
                                <td><span style="float:right" class="m-badge m-badge--danger m-badge--wide">Sin Pagar</span></td>
                            @endif
                            <td>
                                <center>
                                    <a class="btn btn-outline-info btn-sm" href="{{ route('royalties.show',$record->id) }}">Royalties</a>
                                    <a class="btn btn-outline-success btn-sm" href="{{ route('companies.edit',$record->id) }}">Editar</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['companies.destroy', $record->id],'style'=>'display:inline']) !!}
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
        
    })
</script>
@endsection
