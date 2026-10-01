<?php
    use App\Models\Varity;
    $p = [];    

    for ($i=1; $i < 601; $i++) { 
        $p[$i] = [];
    }

    foreach ($positions as $key => $value) {
        $p[intval($value['positionY'])] =  $value;
    }
?>
<style type="text/css">
    .punteado{
        color: #f8b500;
        height: 0px;
        border:1px dashed;
    }

    .no-rotate-text{
        transform: rotate(360deg);
    }

    @media (max-width: 800px){
        .col-sm-1 {
            -webkit-box-flex: 0!important;
            -ms-flex: 0 0 8.33333%!important;
            flex: 0 0 8.33333%!important;
            max-width: 8.33333%!important;  
        }

        .row2{
            width: 235%!important
        }

    }
</style>

<div class="row">
    <!-- LADO IZQUIERDO-->
    <div class="col-md-6" style="zoom: 0.8">
        <div class="hv-container">
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                             @for($i = 291; $i < 301; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
                                            if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="1" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="1" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="1"><strong>LINEA 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            
            
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 290; $i > 280; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="1" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="1" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 271; $i < 281; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="2"><strong>LINEA 2</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 270; $i > 260; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 251; $i < 261; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="3" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="3" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="3"><strong>LINEA 3</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 250; $i > 240; $i--)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="3" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="3" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 231; $i < 241; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="4" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="4" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="4"><strong>LINEA 4</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 230; $i > 220; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="4" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="4" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 211; $i < 221; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="5" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="5" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="5"><strong>LINEA 5</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 210; $i > 200; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="5" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="5" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 191; $i < 201; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="6" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="6" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="6"><strong>LINEA 6</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 190; $i > 180; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="6" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="6" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 171; $i < 181; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="7" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="7" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="7"><strong>LINEA 7</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 170; $i > 160; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="7" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="7" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 151; $i < 161; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="8" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="8" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="8"><strong>LINEA 8</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 150; $i > 140; $i--)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="8" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="8" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 131; $i < 141; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="9" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="9" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="9"><strong>LINEA 9</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 130; $i > 120; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="9" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="9" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 111; $i < 121; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="10" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="10" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="10"><strong>LINEA 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 110; $i > 100; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="10" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="10" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 91; $i < 101; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="11" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="11" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="11"><strong>LINEA 11</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 90; $i > 80; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="11" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="11" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 71; $i < 81; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="12" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="12" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="12"><strong>LINEA 12</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 70; $i > 60; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="12" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="12" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 51; $i < 61; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="13" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="13" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="13"><strong>LINEA 13</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 50; $i > 40; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="13" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="13" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 31; $i < 41; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="14" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="14" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="14"><strong>LINEA 14</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                            @for($i = 30; $i > 20; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="14" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="14" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- LADO DERECHO-->
    <div class="col-md-6" style="zoom: 0.8;transform: rotate(180deg);">
        <div class="hv-container">
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                            <div class="hv-item-children">
                                @for($i = 11; $i < 21; $i++)
                                    <div class="hv-item-child" >
                                        @if(isset($p[$i]['varity_id']))
                                            <?php  
                                                $colores = "";

                                                if($p[$i]->yellow > 0){
                                                    $colores = "red";
                                                }

                                                if($p[$i]->red > 0){
                                                    $colores = "green";
                                                }
                                            ?>
                                            <p class="simple-card  no-rotate {{$colores}}" attr-line="15" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{@$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                        @else
                                            <p class="simple-card  no-rotate" attr-line="15" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row2">
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                    
                    <div class="col-md-12">
                        <hr class="punteado">
                    </div>
                    <div class="col-md-12 text-center" id="15"><strong>LINEA 15</strong></div>
                    <div class="col-md-12">
                        <hr class="punteado">
                    </div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                </div>
                <div class="hv-wrapper">
                   <div class="hv-item-child">
                        <div class="hv-item" >
                            <div class="hv-item-parent">
                                <p class="simple-card  same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>

                            <div class="hv-item-children">
                                @for($i = 10; $i > 0; $i--)
                                    <div class="hv-item-child" >
                                        @if(isset($p[$i]['varity_id']))
                                        <?php  
                                                $colores = "";

                                                if($p[$i]->yellow > 0){
                                                    $colores = "red";
                                                }

                                                if($p[$i]->red > 0){
                                                    $colores = "green";
                                                }
                                            ?>
                                        <p class="simple-card  {{$colores}}" attr-line="15" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{@$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                        
                                        @else
                                        <p class="simple-card " attr-line="15" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                            <div class="hv-item-children">
                                @for($i = 310; $i > 300; $i--)
                                
                                    <div class="hv-item-child" >
                                        @if(isset($p[$i]['varity_id']))
                                            <?php  
                                                $colores = "";

                                                if($p[$i]->yellow > 0){
                                                    $colores = "red";
                                                }

                                                if($p[$i]->red > 0){
                                                    $colores = "green";
                                                }
                                            ?>
                                            <p class="simple-card  no-rotate {{$colores}}" attr-line="16" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                        @else
                                            <p class="simple-card  no-rotate" attr-line="16" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row2">
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                    <div class="col-md-12">
                        <hr class="punteado">
                    </div>
                    <div class="col-md-12 text-center" id="16"><strong>LINEA 16</strong></div>
                    <div class="col-md-12">
                        <hr class="punteado">
                    </div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                </div>
                <div class="hv-wrapper">
                   <div class="hv-item-child">
                        <div class="hv-item">
                            <div class="hv-item-parent">
                                <p class="simple-card  same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>

                            <div class="hv-item-children">
                                 @for($i = 311; $i < 321; $i++)
                                    <div class="hv-item-child" >
                                        @if(isset($p[$i]['varity_id']))
                                        <?php  
                                                $colores = "";

                                                if($p[$i]->yellow > 0){
                                                    $colores = "red";
                                                }

                                                if($p[$i]->red > 0){
                                                    $colores = "green";
                                                }
                                            ?>
                                        <p class="simple-card  {{$colores}}" attr-line="16" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                        
                                        @else
                                        <p class="simple-card " attr-line="16" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                            <div class="hv-item-children">
                                @for($i = 330; $i > 320; $i--)
                                    <div class="hv-item-child" >
                                        @if(isset($p[$i]['varity_id']))
                                            <?php  
                                                $colores = "";

                                                if($p[$i]->yellow > 0){
                                                    $colores = "red";
                                                }

                                                if($p[$i]->red > 0){
                                                    $colores = "green";
                                                }
                                            ?>
                                            <p class="simple-card  no-rotate {{$colores}}" attr-line="17" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                        @else
                                            <p class="simple-card  no-rotate" attr-line="17" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row2">
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                    <div class="col-md-12">
                        <hr class="punteado">
                    </div>
                    <div class="col-md-12 text-center" id="17"><strong>LINEA 17</strong></div>
                    <div class="col-md-12">
                        <hr class="punteado">
                    </div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                </div>
                <div class="hv-wrapper">
                   <div class="hv-item-child">
                        <div class="hv-item">
                            <div class="hv-item-parent">
                                <p class="simple-card  same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>

                            <div class="hv-item-children">
                                 @for($i = 331; $i < 341; $i++)
                                
                                    <div class="hv-item-child" >
                                        @if(isset($p[$i]['varity_id']))
                                        <?php  
                                                $colores = "";

                                                if($p[$i]->yellow > 0){
                                                    $colores = "red";
                                                }

                                                if($p[$i]->red > 0){
                                                    $colores = "green";
                                                }
                                            ?>
                                        <p class="simple-card  {{$colores}}" attr-line="17" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                        
                                        @else
                                        <p class="simple-card " attr-line="17" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 350; $i > 340; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="18" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="18" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="18"><strong>LINEA 18</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                    <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 351; $i < 361; $i++)
                                                                        
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="18" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="18" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 370; $i > 360; $i--)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="19" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="19" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="19"><strong>LINEA 19</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item" >
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 371; $i < 381; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="19" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="19" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 390; $i > 380; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="20" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="20" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="20"><strong>LINEA 20</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 391; $i < 401; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="20" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="20" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 410; $i > 400; $i--)
                            
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="21" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="21" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="21"><strong>LINEA 21</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item" >
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 411; $i < 421; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="21" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="21" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 430; $i > 420; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="22" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="22" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="22"><strong>LINEA 22</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 431; $i < 441; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="22" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="22" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 450; $i > 440; $i--)
                            
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="23" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="23" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                 <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="23"><strong>LINEA 23</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 451; $i < 461; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="23" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="23" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 470; $i > 460; $i--)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="24" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="24" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="24"><strong>LINEA 24</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 471; $i < 481; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="24" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="24" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 490; $i > 480; $i--)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="25" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="25" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="25"><strong>LINEA 25</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 491; $i < 501; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="25" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="25" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 510; $i > 500; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="26" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="26" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="26"><strong>LINEA 26</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 511; $i < 521; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="26" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="26" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 530; $i > 520; $i--)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="27" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="27" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="27"><strong>LINEA 27</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item" >
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 531; $i < 541; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="27" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="27" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="union" style="transform: rotate(180deg);">
                <div class="hv-wrapper" >
                   <div class="hv-item-child" >
                        <div class="hv-item" style="transform: rotate(180deg);">
                            <div class="hv-item-parent">
                                <p class="simple-card same">
                                    <a href="/varities/111">111</a>
                                </p>
                            </div>
                        <div class="hv-item-children">
                            @for($i = 550; $i > 540; $i--)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="28" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="28" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="28"><strong>LINEA 28</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 551; $i < 561; $i++)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="28" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="28" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!--div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item" style="transform: rotate(180deg);">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 570; $i > 560; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="29"><strong>LINEA 29</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item" style="transform: rotate(180deg);">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 571; $i < 581; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}  </p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item" style="transform: rotate(180deg);">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 590; $i > 580; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row2">
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PD 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PD 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PD 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PD 1</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-12 text-center" id="30"><strong>LINEA 30</strong></div>
                <div class="col-md-12">
                    <hr class="punteado">
                </div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 20px"><strong> PI 10</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 9</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 12px"><strong> PI 8</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 15px"><strong> PI 7</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 6</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 5</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 4</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 3</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 2</strong></div>
                <div class="col-md-1 col-sm-1 text-center" style="margin-left: 10px"><strong> PI 1</strong></div>
            </div>
            <div class="hv-wrapper">
               <div class="hv-item-child">
                    <div class="hv-item" style="transform: rotate(180deg);">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>

                        <div class="hv-item-children">
                             @for($i = 591; $i < 601; $i++)
                            
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                    <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                    <p class="simple-card  {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card " attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}  </p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="hv-wrapper" >
               <div class="hv-item-child" style="transform: rotate(180deg);">
                    <div class="hv-item" style="transform: rotate(180deg);">
                        <div class="hv-item-parent">
                            <p class="simple-card  same">
                                <a href="/varities/111">111</a>
                            </p>
                        </div>
                        <div class="hv-item-children">
                            @for($i = 590; $i > 600; $i--)
                                <div class="hv-item-child" >
                                    @if(isset($p[$i]['varity_id']))
                                        <?php  
                                            $colores = "";

                                            if($p[$i]->yellow > 0){
                                                $colores = "red";
                                            }
if($p[$i]->red > 0){
                                                $colores = "green";
                                            }

                                            if(!@$p[$i]->crossing->father_id){
                                                $father_name = "-";
                                            }else{
                                                $father_name = $p[$i]->crossing->fatherName();
                                            }
                                        ?>
                                        <p class="simple-card  no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ @$p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{$father_name }}"> {{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{!empty(@@$p[$i]->crossing->name) ? @@$p[$i]->crossing->name : @@$p[$i]->crossing->code}}')"> </i></p>
                                    @else
                                        <p class="simple-card  no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div!-->
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        step = "<?= @$mapout->step;?>"
        $("#FormUpdatePosition").validate()
         y = 0;
         line = 0;

        $(".simple-card").click(function(e){
            if(step == 0 && $(this).attr("attr-control") == 1){
                y = $(this).attr("attr-position")
                line = $(this).attr("attr-line")
                $("#modal-add-cruce").modal('show')
            }
        }).children().click(function(e) {
          return false;
        });

        
    })
</script>