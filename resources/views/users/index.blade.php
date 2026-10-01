@extends('layouts.app',['title' => "Usuarios",'subtitle' => 'Listado'])
@section('content')
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-calendar"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Listado de Usuarios
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('users.create') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Crear Usuario
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
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($records))
                    @foreach($records as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td><b>{{ $user->name }}</b></td>
                            <td>{{ ($user->email)? $user->email:"-" }}</td>
                            <td>{{ ($user->phone)? $user->phone:"-" }}</td>
                            
                            <td>
                                <center>
                                    <a class="btn btn-outline-success btn-sm" href="{{ route('users.edit',$user->id) }}">Editar</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
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
@endsection
