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
                    Características
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('features.create') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Crear Característica
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
            <tbody>
                @if(!empty($typefeatures))
                    @foreach($typefeatures as $tp)
                        <?php $i = 1;?>
                        <tr>
                            <td colspan="6"><h5 style="margin-top: 40px;">{{ $tp->name }}</h5></td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>Nombre</td>
                            <td>Tipo Parámetro</td>
                            <td>Años</td>
                            <td>Valores ingresados</td>
                            <td>Repeticiones</td>
                        </tr>
                        @foreach($tp->features as $record)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td><b>{{ $record->name }}</b></td>
                                <td>
                                    @if($record->type == 1)
                                        <span class="m-badge m-badge--primary m-badge--wide">
                                            Calificación
                                        </span>
                                    @elseif($record->type == 2)
                                        <span class="m-badge m-badge--warning m-badge--wide">
                                            Si/No
                                        </span>
                                    @elseif($record->type == 3)
                                        <span class="m-badge m-badge--success m-badge--wide">
                                            Tabla colores
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->year == 0)
                                        Todos
                                    @else
                                        {{ $record->year }}
                                    @endif
                                </td>
                                <td>{{ $record->values->count() }}</td>
                                </td>
                                <td>
                                    @if($record->repeat_do == 0)
                                        Sin Repeticiones
                                    @else
                                        Con Repeticiones
                                    @endif
                                </td>
                                <td>
                                    <center>
                                        <!--<a class="btn btn-outline-info btn-sm" href="{{ route('features.show',$record->id) }}">Ver</a>-->
                                        <a class="btn btn-outline-success btn-sm" href="{{ route('features.edit',$record->id) }}">Editar</a>
                                        <!--{!! Form::open(['method' => 'DELETE','route' => ['features.destroy', $record->id],'style'=>'display:inline']) !!}
                                        {!! Form::submit('Eliminar', ['class' => 'btn btn-outline-danger btn-sm btn-delete']) !!}
                                        {!! Form::close() !!}-->
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
