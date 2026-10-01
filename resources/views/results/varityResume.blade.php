<ul class="nav nav-tabs nav-fill" role="tablist">
    <li class="nav-item">
        <a class="nav-link active show" data-toggle="tab" href="#resumen">Resumen</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#fathers">Padres</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#sons">Hijos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#blocks">Ensayos</a>
    </li>
</ul>
<div class="tab-content">
            <div class="tab-pane active show" id="resumen" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12">
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
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                    
                </div>
            </div>
            <div class="tab-pane" id="fathers" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12">
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
                                                                 {{ $varity->name }}
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
                                                                        <a href="/varities/{{ $t->id }}">{{ $t->code ? $t->code : $t->name}}</a>
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
            <div class="tab-pane" id="sons" role="tabpanel">
                <table class="table" style="width:100%" id="table-sons">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Madre</th>
                            <th>Padre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($childrens as $r)
                          <tr>
                            <td>{{$r["id"]}}</td>
                            <td>{{$r["name"]}}</td>
                            <td>{{@$r->motherName()}}</td>
                            <td>{{@$r->fatherName()}}</td>
                          </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="blocks" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12">
                         <table class="table" id="table-blocks">
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

        <script type="text/javascript">
    $(document).ready(function(){
        $("#table-sons").DataTable()
        $("#table-blocks").DataTable()
    })
</script>