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
                    Rendimientos Promedio por Mercado
                </h3>
            </div>          
        </div>
         <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('std_market.create') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Crear Rendimiento promedio
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
                    <th>Mercado</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($stdmarket))
                    @foreach($stdmarket as $record)
                        <tr>
                            <td><b>{{ $record->name }}</b></td>
                            <td><b>{{ $record->market()->name }}</b></td>
                            <td><b>{{ $record->value }}</b></td>
                            <td>
                                 <center>
                                     <a class="btn btn-outline-success btn-sm" href="#" onclick="edit('{{$record->id}}','{{$record->name}}')">Editar</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['greenhouse.destroy_greenhouse', $record->id],'style'=>'display:inline']) !!}
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
        $(".table").DataTable()

        $("#form_update").hide()

        edit = function(a,b){
            $("#form_update").show()
            $("#FormCreate").hide()
            $("#name_to_edit").val(b)
            $("#id").val(a)
        }

        store = function(){
            $("#form_update").hide()
            $("#FormCreate").show()
        }


    });
</script>
@endsection
