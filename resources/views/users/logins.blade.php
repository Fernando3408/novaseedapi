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
                    Listado de Accesos
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        <table id="tabla_login" class="table table-striped- table-bordered table-hover table-checkable ">
            <thead>
                <tr>
                    <th>Fecha Acceso</th>
                    <th>Rut</th>
                    <th>Nombre</th>
                    <th>Tipo Usuario</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($logins))
                    @foreach($logins as $login)
                        @if($login->user->username != "zacarias" AND $login->user->username != "blumar" AND $login->user->username != "ventisqueros")
                        <tr>
                            <td>{{ $login->created_at }}</td>
                            <td><b>{{ $login->user->rut }}</b></td>
                            <td><b>{{ $login->user->name }}</b></td>
                            <td>
                                <center>
                                    @if($login->user->roles[0]->name == "admin")
                                        <span class="m-badge m-badge--success m-badge--wide">
                                            {{ $login->user->roles[0]->description }}
                                        </span>
                                    @elseif($login->user->roles[0]->name == "gerencial")
                                        <span class="m-badge m-badge--accent m-badge--wide">
                                            {{ $login->user->roles[0]->description }}
                                        </span>
                                    @elseif($login->user->roles[0]->name == "client")
                                        <span class="m-badge m-badge--info m-badge--wide">
                                            {{ $login->user->roles[0]->description }}
                                        </span>    
                                    @endif
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
<script>
    $(document).ready(function(){
        $("#tabla_login").DataTable({
            "order": [[ 0, "desc" ]]
        })
    })
</script>
@endsection
