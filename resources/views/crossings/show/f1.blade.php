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

</style>

<ul class="nav nav-tabs nav-fill" role="tablist">
    <li class="nav-item">
        <a class="nav-link active show" data-toggle="tab" href="#resumen">Resumen</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#caracteristicas">Ingreso de Características</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#fotos">Fotos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#observaciones">Observaciones</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active show" id="resumen" role="tabpanel">


        <div class="row">
            <div class="col-lg-4">
                <h6>Padre: {{ @$crossing->father->name }} <b>{{ @$crossing->father->code }}</b> </h6>
            </div>
            <div class="col-lg-4">
                <h6>Madre: {{ @$crossing->mother->name }} <b>{{ @$crossing->mother->code }}</b></h6>
            </div>
            <div class="col-lg-4">
                <h6>Temporada: T{{ $crossing->year }}-{{ $crossing->year+1 }}</h6>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-6">
                <fieldset>
                    <legend>Resumen Características</legend>
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>AVG</th>
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
                                    <?php @$avg = $f->valor0 + $f->valor1 + $f->valor2 + $f->valor3 + $f->valor4;?>
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
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <div class="col-lg-6">
                <fieldset>
                    <legend>Árbol Geneológico</legend>
                    <div class="hv-container">
                        <div class="hv-wrapper">

                            <!-- ABUELOS -->
                            <div class="hv-item">
                                <div class="hv-item-children hv-item-children-ancester ">
                                    <div class="hv-item-parent hv-item-parent-ancester left-hv">
                                        @if(@$crossing->mother->mother->id)
                                            <div class="hv-item-child ancester">
                                                <p class="simple-card">
                                                    <a href="<?= (@$crossing->mother->mother->crossing == 1)? "/crossings/".@$crossing->mother->mother->id:"/crossings/".@$crossing->mother->mother->id;?>">
                                                        <?= (@$crossing->mother->mother->crossing == 1)? @$crossing->mother->mother->code:@$crossing->mother->mother->name;?>    
                                                    </a>
                                                </p>
                                            </div>
                                        @endif
                                        @if(@$crossing->mother->father->id)
                                            <div class="hv-item-child ancester">
                                                <p class="simple-card ">
                                                    <a href="<?= (@$crossing->father->crossing == 1)? "/crossings/".@$crossing->father->id:"/crossings/".@$crossing->father->id;?>">
                                                        <?= (@$crossing->mother->father->crossing == 1)? @$crossing->mother->father->code:@$crossing->mother->father->name;?>    
                                                    </a>
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="hv-item-parent hv-item-parent-ancester right-hv">
                                        @if(@$crossing->mother->mother->id)
                                            <div class="hv-item-child ancester">
                                                <p class="simple-card">
                                                    <a href="<?= (@$crossing->mother->mother->crossing == 1)? "/crossings/".@$crossing->mother->mother->id:"/crossings/".@$crossing->mother->mother->id;?>">
                                                        <?= (@$crossing->mother->mother->crossing == 1)? @$crossing->mother->mother->code:@$crossing->mother->mother->name;?>    
                                                    </a>
                                                </p>
                                            </div>
                                        @endif
                                        @if(@$crossing->mother->father->id)
                                            <div class="hv-item-child ancester">
                                                <p class="simple-card ">
                                                    <a href="<?= (@$crossing->father->crossing == 1)? "/crossings/".@$crossing->father->id:"/crossings/".@$crossing->father->id;?>">
                                                        <?= (@$crossing->mother->father->crossing == 1)? @$crossing->mother->father->code:@$crossing->mother->father->name;?>    
                                                    </a>
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- PADRES -->
                                <div class="hv-item-parent">
                                    <div class="hv-item-child parents">
                                        <p class="simple-card <?= (@$crossing->mother->id == @$crossing->id)? 'same':'';?>">
                                            <a href="<?= (@$crossing->mother->crossing == 1)? "/crossings/".@$crossing->mother->id:"/crossings/".@$crossing->mother->id;?>">
                                                <?= (@$crossing->mother->crossing == 1)? @$crossing->mother->code:@$crossing->mother->name;?>    
                                            </a>
                                        </p>
                                    </div>
                                    <div class="hv-item-child">
                                        <p class="simple-card <?= (@$crossing->father->id == @$crossing->id)? 'same':'';?>">
                                            <a href="<?= (@$crossing->father->crossing == 1)? "/crossings/".@$crossing->father->id:"/crossings/".@$crossing->father->id;?>">
                                                <?= (@$crossing->father->crossing == 1)? @$crossing->father->code:@$crossing->father->name;?>    
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- HIJOS -->

                                <div class="hv-item-children">
                                    @foreach($tree as $t)
                                        @include('crossings.show.treeitem',['item' => $t,'crossing' => $crossing])
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="caracteristicas" role="tabpanel">
        @include('crossings.show.characteristics',['crossing' => $crossing,'inBlock' => false])
    </div>
    <div class="tab-pane" id="fotos" role="tabpanel">
        <div class="row">
            <div class="col-lg-4">
                <fieldset>
                    <legend>Agregar Foto</legend>
                    {!! Form::open(array('class' => 'm-form','url' => '/crossings/uploadPhoto','method'=>'POST','id' => 'FormAddPhoto','files' => true)) !!}
                        <input type="hidden" name="crossing" value="<?= $crossing->id;?>">
                        <div class="form-group">
                            {!! Form::select('year', array(0 => 'Año 0',1 => 'Año 1',2 => 'Año 2',3 => 'Año 3',4 => 'Año 4') ,null,['class' => 'form-control required','placeholder' => 'Selecciona Año']); !!}
                        </div>    
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
                    @include('crossings.show.listPhotos',['multimedias' => $crossing->multimedias])
                </fieldset>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="observaciones" role="tabpanel">
        @include('crossings.show.observations',['crossing' => $crossing])
    </div>

</div>


<script>
    $(document).ready(function(){
        $("#FormAddPhoto").validate({
            rules: {
                file: {
                  required: true,
                  extension: "jpg|jpeg|png"
                }
            },
            messages: {
                file: {
                  extension: "Debe ingresar sólo multimedias con formato: jpg, png."
                }
            }
        })
    })
</script>