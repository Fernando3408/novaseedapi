<?php  
    use App\Models\Varity;
    use App\Models\Market;
?>
@if($type_content == 1)
<div class="col-md-12" style="margin-bottom: 30px;">
    <div class="row">
        <div class="col-md-3">
            <label style="width: 100%">Filtro Variedad</label>
            <input type="text" class="form-control w-30" id="filter_varity" onkeyup="filtroql(this)">
        </div>
        <div class="col-md-3">
            <label style="width: 100%">Filtro Target</label>
            {!! Form::select('block_id', $allTarget,null,['class' => 'form-control w-30','placeholder' => 'Sin filtro', 'id' => 'filter_target']); !!}
        </div>
        <div class="col-md-6" style="margin-top: 25px;">
            <a href="#" class="m-portlet__nav-link btn btn-outline-primary m-btn m-btn--outline-2x " onclick="modificarCaracteristicas({{ $block->id }})">
                <i class="fa fa-filter"></i> Características
            </a>
            <button type="button" class="m-portlet__nav-link btn btn-warning m-btn m-btn--outline-2x " style="color: white" onclick="compare()">
                <i class="fa fa-search" style="color: white"></i> Comparar
            </button>
             <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--outline-2x" onclick="sendVarities()"><i class="fa fa-download"></i> Traspasar </button>

        </div>
    </div>
</div>

<div class="modal fade show" id="modal-caracteristicas_config" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modificación de características</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<!--background-color: lightcyan;-->
<div class="blocks width{{ $block->lines }}" >
    <?php $counter = 0; ?>
    @foreach($blocks as $k => $block_)
        <ul>
            @foreach($block_ as $i => $column)
            @php
            @endphp
                <li>
                    <h5>{{ $k }}.{{ $i }} <input name="check_id[{{@$column[1]->varity_id}}]" onclick="select_block(this)" value="{{@$column[1]->varity_id}}" type="checkbox" attr-position="{{ $k }}.{{ $i }}" /></h5>
                    <div id="main_container_{{$k}}.{{$i}}" ondrop="drop(event)" ondragover="allowDrop(event)"  style="cursor: pointer;min-height: 80px">
                        <div id="container_{{$k}}.{{$i}}" class="container_ql" draggable="true" ondragstart="drag(event)" style="min-height: 30px;" attr-position="{{ $k }}.{{ $i }}">
                            @if(!empty($column))
                                @foreach($column as $c)
                                    @if(!empty($c) and $c->varity)
                                        <?php 
                                            $counter++; 
                                            @$c->varity->code? $name = @$c->varity->code : $name = @$c->varity->name;
                                            @$c->varity->father_id !== null ? $fatherName = $c->varity->fatherName() : $fatherName = "-";
                                            @$c->varity->mother_id !== null ? $mothername = $c->varity->motherName() : $mothername = "-";
                                            @$c->varity->market->name !== null ? $market_name = $c->varity->market->name : $market_name = "-";
                                            @$observation = @$c->varity->observation !== null ? $c->varity->observation : null;
                                        ?>
                                            <a id="{{$counter}}" attr-obs="{{@$c->varity->observation}}" attr-pos="{{ $k }}.{{ $i }}" onclick='verCaracteristicas(<?php echo @$c->varity->id;?>,<?php echo @$c->id;?>,"<?php echo $c->type;?>",<?= $block->id;?>,<?= $counter;?>,"<?= @$name;?>",this,{{$c->review}},"{{$fatherName}}","{{$mothername}}", "{{@$market_name}}", "{{ $k }}.{{ $i }}")' class="name_filter name_{{strtoupper($name)}} target_filter target_{{$c->market_id}}" >

                                            <?php 
                                                $color = ""; 
                                                
                                                $c->review == 1 ? $color = "#df6403" : "";                                                        
                                                $c->review == 2 ? $color = "#ed506b" : "";                                                        
                                                $c->review == 3 ? $color = "#df6403" : "";

                                                $c->last_position == 1 ? $color = "#4867bd" : "";                                                           
                                            ?>
                                                <h6 style="background-color: {{$color}}">
                                                    <?php echo $name;?>
                                                    <br>
                                                    {{ $k }}.{{ $i }}
                                                    <b>(<?php echo $c->type;?>)</b>
                                                    @if($observation)
                                                    <i class="fa fa-comments" aria-hidden="true"></i>
                                                    @endif
                                                </h6>  
                                            </a>
                                        @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endforeach
</div>
<script type="text/javascript">
    modificarCaracteristicas = function(id){
        $.ajax({
            url: '/blocks/modCaracteristicas',
            type: 'GET',
            dataType: 'html',
            data: {block_id: id},
        }).done(function(data) {
            $("#modal-caracteristicas_config .modal-body").html(data)
            $("#modal-caracteristicas_config").modal("show")
        })
        
    }
</script>
@else
    <table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table-storage">
        <thead>
            <tr>
                <th>Código</th>
                <th>Temporada</th>
                <th>Mercado</th>
                <th>Madre</th>
                <th>Padre</th>
                <th style="width:180px">-</th>
                <th style="width:180px"></th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 0; ?>

            @foreach($codified as $k => $c)

                @if(!empty($c) and !empty($c->varity_id))
                    <?php 
                        $counter++; 
                        @$c->varity()->first()->code? $name = @$c->varity()->first()->code : $name = @$c->varity()->first()->name; 
                        $res = $c->varity()->first();
                    ?>
                    @if(!empty($res))
                        @if($name != "")
                        <?php  
                            $market = @Market::find($res->market_id)->name;
                        ?>
                            <tr>
                                <td>{{@$name}}</td>
                                <td>{{@$res->year}}</td>
                                <td>{{@$market}}</td>
                                <td>{{@$res->motherName()}}</td>
                                <td>{{@$res->fatherName()}}</td>
                                <td><a attr-obs="{{@$c->varity->observation}}" class="btn btn-outline-warning btn-sm" onclick='verCaracteristicas(<?php echo @$c->varity->id;?>,<?php echo @$c->id;?>,"<?php echo $c->type;?>",<?= $block->id;?>,{{$counter}},"<?= @$name;?>",this,{{$c->review}}, "{{@$c->varity->market->name}}")'>Características</a></td>
                                <td>
                                    <button class="btn btn-danger" onclick="deleteVarity('{{@$name}}',{{@$res->id}})" type="button"><i class="fa fa-trash"></i> Eliminar</button>
                                </td>
                                
                            </tr>
                        @endif
                        
                    @endif
                    
                @endif
            @endforeach
           
        </tbody>
    </table>
    <script>
        $(document).ready(function(){
            table_storage = $('#table-storage').DataTable()
        })
    </script>

@endif
