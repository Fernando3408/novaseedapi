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
                    Ensayos
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($result))
                    @foreach($result as $record)
                        @if(!empty($record->id))
                            <tr>
                                <td>{{ @$record->id }}</td>
                                @php
                                    $name = $record->code != "" ? $record->code : $record->code;
                                @endphp
                                <td><b>{{ $name }}</b></td>
                                <td><b>{{ @$record->deleted_at }}</b></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


@endsection
