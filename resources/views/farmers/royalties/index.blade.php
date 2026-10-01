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
                    Royalties {{$farmer->name}}
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('farmers.index') }}" class="m-portlet__nav-link btn btn-outline-danger m-btn m-btn--outline-2x " style="margin-right: 10px">Volver</a>
                    <a href="/royalties/create/{{$farmer->id}}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x " >
                        Añadir Royalty
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
            <thead>
                <tr>
                    <th>Año</th>
                    <th>Temporada</th>
                    <th>Valor</th>
                    <th style="width:350px"></th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($royalties))
                    @foreach($royalties as $record)
                        <tr>
                            <td><b>{{ $record->year }}</b></td>
                            <td><b>{{ $record->season }}</b></td>
                            <td><b>$ {{ number_format($record->pay, 0, ',', '.') }}</b></td>
                            <td>
                                <center>
                                    <a class="btn btn-outline-success btn-sm" href="{{ route('royalties.edit',$record->id) }}">Editar</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['royalties.destroy', $record->id],'style'=>'display:inline']) !!}
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
