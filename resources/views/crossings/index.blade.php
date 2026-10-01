@extends('layouts.app')
@section('content')
<style type="text/css">
    .pagination{
        margin-top: -15px!important;
    }
</style>
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
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('crossings.create') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Crear Cruce
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" data-toggle="tab" href="#listado_cruces">Listado de cruces</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#listado_f1">Listado de codificados (F-1)</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active show" id="listado_cruces" role="tabpanel">
                 <table class="table table-striped- table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Temporada</th>
                            <th>Madre</th>
                            <th>Padre</th>
                            <th>M.Objetivo</th>
                            <th>Etapa</th>
                            <th>Estado</th>
                            <th>Creado por</th>
                            <th>Fecha</th>
                            <th style="width:250px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($crossings))
                            @foreach($crossings as $record)
                                <tr>
                                    <td>{{ $record->id }}</td>
                                    <td>T{{ $record->year }}-{{ $record->year+1 }}</td>
                                    <td>
                                        <b>
                                            <a href="/varities/{{ @$record->mother->id }}">
                                                {!! @$record->motherName() !!}
                                            </a>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            <a href="/varities/{{ @$record->father->id }}">
                                                {!! @$record->fatherName() !!}
                                            </a>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            @if(!empty($record->marketold_id))
                                                @if($record->marketold_id == $record->market_id)
                                                    {{ @$record->market->name }}
                                                @else
                                                    Original: {{ @$record->marketold->name }}, Modificado: {{ @$record->market->name }}
                                                @endif
                                            @else
                                                {{ @$record->market->name }}
                                            @endif
                                        </b>
                                    </td>
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
                                    <td>{!! @$record->user->name !!}</td>
                                    <td>{{ @$record->created_at }}</td>
                                    <td>
                                        <center>
                                            @if($record->typecrossing == 1)
                                                <a class="btn btn-outline-info btn-sm" href="/crossings/codify/{{ $record->id }}">Codificación</a>
                                            @else
                                                <a class="btn btn-outline-info btn-sm" href="{{ route('crossings.show',$record->id) }}">Ver Info</a>
                                            @endif
                                            <a class="btn btn-outline-success btn-sm" href="{{ route('crossings.edit',$record->id) }}">Editar</a>
                                            <!--{!! Form::open(['method' => 'DELETE','route' => ['crossings.destroy', $record->id],'style'=>'display:inline']) !!}
                                            {!! Form::submit('Eliminar', ['class' => 'btn btn-outline-danger btn-sm btn-delete']) !!}
                                            {!! Form::close() !!}-->
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="listado_f1" role="tabpanel">
                <table class="table table-striped- table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Temporada</th>
                            <th>Mercado</th>
                            <th>Berries</th>
                            <th>Madre</th>
                            <th>Padre</th>
                            <th>Etapa</th>
                            <th style="width:180px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($coded))
                            @foreach($coded as $c)
                                <tr>
                                    @if(!empty($c->code))
                                        <td><b>{{ $c->code }}</b></td>

                                    @else
                                     <td><b>{{ $c->name }}</b></td>

                                    @endif
                                    <td>T{{ $c->year }}-{{ $c->year+1 }}</td>
                                    <td>
                                        <b>
                                            @if($c->marketold_id == $c->market_id)
                                                {{ @$c->market->name }}
                                            @else
                                                Original: {{ @$c->marketold->name }}, Modificado: {{ @$c->market->name }}
                                            @endif
                                        </b>
                                    </td>
                                    <td>{{ $c->berries }}</td>
                                    <td>
                                        <b>
                                            <a href="/varities/{{ @$c->mother->id }}">
                                                {!! @$c->motherName() !!}
                                            </a>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            <a href="/varities/{{ @$c->father->id }}">
                                                {!! @$c->fatherName() !!}
                                            </a>
                                        </b>
                                    </td>
                                    <td>
                                        <center>
                                            <span class="m-badge m-badge--warning m-badge--wide">Año {{ date('Y')-$c->year }}</span>
                                        </center>
                                    </td>
                                    <td>
                                        <center>
                                            <a class="btn btn-outline-info btn-sm" href="{{ route('crossings.show',$c->id) }}">Ver</a>
                                            <a class="btn btn-outline-success btn-sm" href="{{ route('crossings.edit',$c->id) }}">Editar</a>
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
