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
                    Invernaderos
                </h3>
            </div>          
        </div>
    </div>

    <div class="m-portlet__body">

         {!! Form::open(array('class' => 'm-form','route' => 'greenhouse.store','method'=>'POST','id' => 'FormCreate')) !!}
                <div class="row" >
                    <div class="col-md-3">
                        <strong>Año:</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Ej. Temporada 2020','class' => 'form-control  required', 'required' => '')) !!}
                    </div>
                </div>
                 <button type="submit" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x " style="margin-bottom: 25px;">
                    Agregar Invernadero
                </button>                
            {!! Form::close() !!}

            {!! Form::open(array('class' => 'm-form','route' => ['greenhouse.update_greenhouse', 0],'method'=>'PATCH','id' => 'form_update')) !!}
                <div class="row" >
                    <div class="col-md-3">
                        <strong>Año:</strong>
                        {!! Form::text('name_to_edit', null, array('placeholder' => 'Ej. Temporada 2020','class' => 'form-control  required', 'required' => 'true', 'id' => 'name_to_edit' )) !!}
                        <strong>Nombre:</strong>
                    </div>
                </div>
                <input type="hidden" name="id" id="id">
                 <button type="submit" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x " style="margin-bottom: 25px;">
                    Actualizar Invernadero
                </button>     
                <button type="button"  onclick="store()" class="m-portlet__nav-link btn btn-outline-danger m-btn m-btn--outline-2x " style="margin-top: 5px;">
                    Cancelar
                </button>            
            {!! Form::close() !!}


        <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
            <thead>
                <tr>
                    <th>Año</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($greenhouse))
                    @foreach($greenhouse as $record)
                        <tr>
                            <td><b>{{ $record->name }}</b></td>
                            <td>
                                 <center>
                                    <a class="btn btn-outline-info btn-sm" href="{{ route('greenhouse.show',$record->id) }}">Ver</a>
                                     <a class="btn btn-outline-success btn-sm" href="#" onclick="edit('{{$record->id}}','{{$record->name}}')">Editar</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['greenhouse.destroy_greenhouse', $record->id],'style'=>'display:inline']) !!}
                                    <input type="hidden" name="to_delete" value="{{$record->id}}">
                                    {!! Form::submit('Eliminar', ['class' => 'btn btn-outline-danger btn-sm btn-delete']) !!}
                                    {!! Form::close() !!}
                                    <a class="btn btn-outline-info btn-sm" href="{{ route('greenhouse.asociate_crossing',$record->id) }}">Cruces</a>

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
