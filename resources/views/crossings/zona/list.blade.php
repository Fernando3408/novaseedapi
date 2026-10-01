@extends('layouts.app')
@section('content')
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-calendar"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Cruces
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" data-toggle="tab" href="#listado_cruces">Listado de cruces</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#listado_f1">Ensayos de Zona</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active show" id="listado_cruces" role="tabpanel">
                 <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Temporada</th>
                            <th>Madre</th>
                            <th>Padre</th>
                            <th>Etapa</th>
                            <th>Estado</th>
                            <th>Creado por</th>
                            <th style="width:350px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($crossings))
                            @foreach($crossings as $record)
                                <tr>
                                    <td>{{ $record->id }}</td>
                                    <td>T{{ $record->year }}-{{ $record->year+1 }}</td>
                                    <td><b><a href="/varity/{{ $record->mother->id }}"><?= ($record->mother->crossing == 1)? $record->mother->code:$record->mother->name;?></a></b></td>
                                    <td><b><a href="/varity/{{ $record->father->id }}"><?= ($record->father->crossing == 1)? $record->father->code:$record->father->name;?></a></b></td>
                                    <td>
                                        <center>
                                            <span class="m-badge m-badge--warning m-badge--wide">Año {{ date('Y')-$record->year }}</span>
                                        </center>
                                    </td>
                                    <td>
                                        <center>
                                            @if($record->typecrossing == 1)
                                                <span class="m-badge m-badge--info m-badge--wide">Por codificar</span>
                                            @else
                                                <span class="m-badge m-badge--success m-badge--wide">Codificado</span>
                                            @endif
                                        </center>
                                    </td>
                                    <td>{{ $record->user->name }}</td>
                                    <td>
                                        <center>
                                            <a  href="/crossings/zonacreate/{{ $record->id }}" class="btn btn-outline-info btn-sm">Crear Ensayo Zona</a>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="listado_f1" role="tabpanel">
                <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
                    <thead>
                        <tr>
                            <th>Correlativo</th>
                            <th>Título</th>
                            <th>Madre</th>
                            <th>Padre</th>
                            <th>Comuna</th>
                            <th ></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($zonal))
                            @foreach($zonal as $z)
                                <tr>
                                    <td><b>{{ $z->id }}</b></td>
                                    <td>{{ $z->title }}</td>
                                    <td><b><a href="/varity/{{ $record->mother->id }}"><?= ($record->mother->crossing == 1)? $record->mother->code:$record->mother->name;?></a></b></td>
                                    <td><b><a href="/varity/{{ $record->father->id }}"><?= ($record->father->crossing == 1)? $record->father->code:$record->father->name;?></a></b></td>
                                    <td>{{ $z->community->name }}</td>
                                    <td>
                                        <center>
                                            <a class="btn btn-outline-warning btn-sm" href="{{ route('crossings.show',$z->varity_id) }}">Ver Cruce Original</a>
                                            <a class="btn btn-outline-info btn-sm" href="{{ route('crossings.zonashow',$z->id) }}">Ver Ensayo</a>
                                            <a class="btn btn-outline-success btn-sm" href="{{ route('crossings.zonaedit',$z->id) }}">Modificar Características</a>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(window).load(function(){
        // Disable search and ordering by default
        $.extend( $.fn.dataTable.defaults, {
            "searching": false
        } );
        
    })
</script>
@endsection
