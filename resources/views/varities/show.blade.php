@extends('layouts.app')
@section('content')
<style type="text/css">
    .ancester{
        padding: 0 5px!important;

    }

   .hv-item-parent-ancester {
        margin-bottom: 40px!important;
        position: relative;
        display: flex;
        justify-content: center;
    }

    .hv-item-children-ancester{
        max-height: 70px!important;
    }

    .right-hv:after {
        transform: translateY(100%)rotate(27deg)!important;
        height: 35px!important
    }

    .left-hv:after {
        transform: translateY(100%)rotate(-27deg)!important;
        height: 35px!important
    }

    .parents{
        margin-right: 40px!important;
    }

    .hv-wrapper .hv-item .parent-soon:after {
        display: none!important;
    }

</style>
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-calendar"></i>
                </span>
                <div class="row">
                    <h3 class="m-portlet__head-text">
                        {!! !empty($varity->name) ? $varity->name : $varity->code !!} | Año: {{ $varity->year }} | ( {!!$varity->motherName()!!} x {!!$varity->fatherName()!!} )
                    </h3>
                </div>
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
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" data-toggle="tab" href="#resumen">Resumen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tree">Árbol Genealógico</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#caracteristicas">Ingreso de Características</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#fotos">Fotos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#blocks">Ensayos</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active show" id="resumen" role="tabpanel">
                <div class="row">
                    <div class="col-lg-6">
                        <fieldset>
                            <legend>Resumen Características</legend>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>AVG</th>
                                        <th>STD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($typesfeatures as $t)
                                        <tr>
                                            <td colspan="6"><b>{{ $t->name }} / {{ $t->namees }}</b></td>
                                        </tr>
                                        <?php  
                                            $m = $t->features;
                                        ?>
                                        @foreach($m as $f)
                                            
                                            <tr>
                                                <td>
                                                    @if($f->name != $f->namees)
                                                        {{ $f->name }} / {{ $f->namees }} 
                                                    @else
                                                        {{ $f->namees }} 
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$f->promedio}}
                                                </td>
                                                <td>{{$f->std}}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                    <div class="col-lg-6">
                        <fieldset>
                            <legend>Árbol Geneológico Padres</legend>
                            <div class="hv-container">
                                <div class="hv-wrapper">
                                <!-- ABUELOS -->
                                    <div class="hv-item">
                                        <div class="hv-item-children hv-item-children-ancester ">

                                            <div class="hv-item-parent hv-item-parent-ancester left-hv">
                                                @if(@$varity->mother->mother->id)
                                                    <div class="hv-item-child ancester">
                                                        <p class="simple-card">
                                                            <a href="<?= "/varities/".@$varity->mother->mother->id;?>">
                                                                <?= (@$varity->mother->mother->crossing == 1)? @$varity->mother->mother->code:@$varity->mother->mother->name;?>    
                                                            </a>
                                                        </p>
                                                    </div>
                                                @else
                                                    <div class="hv-item-child ancester">
                                                        <p class="simple-card">
                                                            <a href="#">
                                                                Sin Datos
                                                            </a>
                                                        </p>
                                                    </div>
                                                @endif
                                                @if(@$varity->mother->father->id)
                                                    <div class="hv-item-child ancester">
                                                        <p class="simple-card ">
                                                            <a href="<?= "/varities/".@$varity->father->id;?>">
                                                                <?= (@$varity->mother->father->crossing == 1)? @$varity->mother->father->code:@$varity->mother->father->name;?>    
                                                            </a>
                                                        </p>
                                                    </div>
                                                 @else
                                                    <div class="hv-item-child ancester">
                                                        <p class="simple-card">
                                                            <a href="#">
                                                                Sin Datos
                                                            </a>
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="hv-item-parent hv-item-parent-ancester right-hv">
                                                @if(@$varity->father->mother->id)
                                                    <div class="hv-item-child ancester">
                                                        <p class="simple-card">
                                                            <a href="<?= "/varities/".@$varity->father->mother->id;?>">
                                                                <?= (@$varity->father->mother->crossing == 1)? @$varity->father->mother->code:@$varity->father->mother->name;?>    
                                                            </a>
                                                        </p>
                                                    </div>
                                                 @else
                                                    <div class="hv-item-child ancester">
                                                        <p class="simple-card">
                                                            <a href="#">
                                                                Sin Datos
                                                            </a>
                                                        </p>
                                                    </div>
                                                @endif
                                                @if(@$varity->father->father->id)
                                                    <div class="hv-item-child ancester">
                                                        <p class="simple-card ">
                                                            <a href="<?= "/varities/".@$varity->father->father->id;?>">
                                                                <?= (@$varity->father->father->crossing == 1)? @$varity->father->father->code:@$varity->father->father->name;?>    
                                                            </a>
                                                        </p>
                                                    </div>
                                                 @else
                                                    <div class="hv-item-child ancester">
                                                        <p class="simple-card">
                                                            <a href="#">
                                                                Sin Datos
                                                            </a>
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- PADRES -->
                                        <div class="hv-item-parent">
                                            @if($varity->mother_id)
                                                <div class="hv-item-child parents">
                                                    <p class="simple-card <?= (@$varity->mother->id == @$varity->id)? 'same':'';?>">
                                                        <a href="<?= "/varities/".@$varity->mother->id;?>">
                                                            <?= (@$varity->mother->crossing == 1)? @$varity->mother->code:@$varity->mother->name;?>    
                                                        </a>
                                                    </p>
                                                </div>
                                            @else
                                                <div class="hv-item-child ancester">
                                                    <p class="simple-card">
                                                        <a href="#">
                                                            Sin Datos
                                                        </a>
                                                    </p>
                                                </div>
                                            @endif
                                            @if($varity->father_id)
                                            <div class="hv-item-child">
                                                <p class="simple-card <?= (@$varity->father->id == @$varity->id)? 'same':'';?>">
                                                    <a href="<?= "/varities/".@$varity->father->id;?>">
                                                        <?= (@$varity->father->crossing == 1)? @$varity->father->code:@$varity->father->name;?>    
                                                    </a>
                                                </p>
                                            </div>
                                            @else
                                                 <div class="hv-item-child ancester">
                                                    <p class="simple-card">
                                                        <a href="#">
                                                            Sin Datos
                                                        </a>
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        @if(!$varity->father_id && !$varity->mother_id)
                                            <div class="hv-item">
                                                <div class="hv-item-parent parent-soon">
                                                     <div class="hv-item-child">
                                                         <p class="simple-card  same">
                                                             <a href="/varities/{{ $varity->id }}">
                                                                 {!! $varity->name !!}
                                                             </a>
                                                         </p>
                                                     </div>
                                                 </div>
                                            </div>
                                        @else
                                            <!-- HIJOS -->
                                            <div class="hv-item-children">
                                                @foreach($tree as $t)
                                                    @if($t->id == $varity->id)
                                                        <div class="hv-item-child">
                                                            <div class="hv-item">
                                                                <div class="hv-item-parent parent-soon">
                                                                    <p class="simple-card <?= ($t->id == $varity->id)? 'same':'';?>">
                                                                        <a href="/varities/{{ $t->id }}">{!! $t->code ? $t->code : $t->name!!}</a>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tree" role="tabpanel">
                
                <div class="col-lg-12">
                        <fieldset>
                            <legend>Árbol Geneológico Hijos</legend>
                            <div class="hv-container">
                                <div class="hv-wrapper">

                                    <!-- Key component -->
                                    <div class="hv-item">

                                        <div class="hv-item-parent">

                                            <div class="hv-item-child">
                                                <p class="simple-card  same">
                                                    <a href="/varities/{{ $varity->id }}">
                                                        {!! $varity->name !!}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="hv-item-children">
                                            @foreach($childrens as $t)
                                                @include('varities.tree',['item' => $t,'crossing' => $varity])
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </fieldset>
                </div>
            </div>
            <div class="tab-pane" id="caracteristicas" role="tabpanel">
                @include('varities.show.characteristics',['crossing' => $varity])
            </div>
            <div class="tab-pane" id="fotos" role="tabpanel">
                <div class="row">
                    <div class="col-lg-4">
                        <fieldset>
                            <legend>Agregar Foto</legend>
                            {!! Form::open(array('class' => 'm-form','url' => '/varities/uploadPhoto','method'=>'POST','id' => 'FormAddPhoto','files' => true)) !!}
                                <input type="hidden" name="varity" value="<?= $varity->id;?>">
                                <input type="hidden" name="year" value="4">
                                <div class="form-group">
                                    <strong>Archivo:</strong><br>
                                    {!! Form::file('file',  array('placeholder' => 'Ingrese Archivo','class' => ' required')) !!}
                                </div>
                                <button class="btn btn-success"><i class="fa fa-save"></i> Subir foto</button>
                            {!! Form::close() !!}
                        </fieldset>   
                    </div>
                    <div class="col-lg-8">
                        <fieldset>
                            <legend>Listado de Fotos</legend>
                            @include('varities.show.listPhotos',['multimedias' => $varity->multimedias])
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="blocks" role="tabpanel">
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                         <table class="table">
                            <thead>
                                <tr>
                                    <th>DESCRIPCION</th>
                                    <th>AÑO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($blocks as $b)
                                @if(isset($b->block))
                                    <tr>
                                        <td ><b>{{ @$b->block->description }}</b></td>
                                        <td ><b>{{ @$b->block->year }}</b></td>
                                        <td ><b><a class="btn btn-outline-info btn-sm" href="{{ route('blocks.show',$b->block_id) }}">Ver</a></b></td>
                                    </tr>
                                @endif
                                    

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
