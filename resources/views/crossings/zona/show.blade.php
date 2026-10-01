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
                    Ensayo Zona #{{ $zonaltrial->id }}
                </h3>
            </div>          
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('varities.index') }}" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x ">
                        Volver
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="row">
            <div class="col-lg-6">
                <fieldset>
                    <legend>Resumen Cruce Original</legend>
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>AVG</th>
                                <th>Nº</th>
                                <th>STD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($typesfeatures as $t)
                                <tr>
                                    <td colspan="6"><b>{{ $t->name }} / {{ $t->namees }}</b></td>
                                </tr>
                                @foreach($t->features as $f)
                                    <?php $avg_count = 0;?>
                                    <?php if($f->crucevalor0 != "") $avg_count++;?>
                                    <?php if($f->crucevalor1 != "") $avg_count++;?>
                                    <?php if($f->crucevalor2 != "") $avg_count++;?>
                                    <?php if($f->crucevalor3 != "") $avg_count++;?>
                                    <?php if($f->crucevalor4 != "") $avg_count++;?>
                                    <?php $avg = $f->crucevalor0 + $f->crucevalor1 + $f->crucevalor2 + $f->crucevalor3 + $f->crucevalor4;?>
                                    <tr>
                                        <td>
                                            @if($f->name != $f->namees)
                                                {{ $f->name }} / {{ $f->namees }} 
                                            @else
                                                {{ $f->namees }} 
                                            @endif
                                        </td>
                                        <td>
                                            @if($avg != "")
                                                {{ $avg/$avg_count }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($avg != "")
                                                {{ $avg/$avg_count }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($avg != "")
                                                {{ $avg/$avg_count }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <div class="col-lg-6">
                <fieldset>
                    <legend>Resumen Ensayo en {{ $zonaltrial->community->name }}</legend>
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>AVG</th>
                                <th>Nº</th>
                                <th>STD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($typesfeatures as $t)
                                <tr>
                                    <td colspan="6"><b>{{ $t->name }} / {{ $t->namees }}</b></td>
                                </tr>
                                @foreach($t->features as $f)
                                    <?php $avg_count = 0;?>
                                    <?php if($f->valor0 != "") $avg_count++;?>
                                    <?php if($f->valor1 != "") $avg_count++;?>
                                    <?php if($f->valor2 != "") $avg_count++;?>
                                    <?php if($f->valor3 != "") $avg_count++;?>
                                    <?php if($f->valor4 != "") $avg_count++;?>
                                    <?php $avg = $f->valor0 + $f->valor1 + $f->valor2 + $f->valor3 + $f->valor4;?>
                                    <tr>
                                        <td>
                                            @if($f->name != $f->namees)
                                                {{ $f->name }} / {{ $f->namees }} 
                                            @else
                                                {{ $f->namees }} 
                                            @endif
                                        </td>
                                        <td>
                                            @if($avg != "")
                                                {{ $avg/$avg_count }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($avg != "")
                                                {{ $avg/$avg_count }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($avg != "")
                                                {{ $avg/$avg_count }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
    </div>
</div>
@endsection
