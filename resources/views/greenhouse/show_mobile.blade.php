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
@extends('layouts.app')
@section('content')
<style>

    .green{
        background-color: green!important;

    }

    .red{
        background-color: red!important;

    }


    .blue{
        background-color: blue!important;
    }

    .button {
      margin: 0 0 0 5px;
      text-indent: -9999px;
      cursor: pointer;
      width: 29px;
      height: 29px;
      float: left;
      text-align: center;
      background: url(/img/increments/buttons.png) no-repeat;
    }
    .dec {
      background-position: 0 -29px;
    }

    .numbers-row{
        position: relative;
        margin-bottom: 10px;
        width: 100%;
    }

    .inc.button{
        position: absolute;
        top: 31px;
        right: 6px;
    }
    .dec.button{
        position: absolute;
        top: 31px;
        right: 40px;
    }

    #mapout-first-step{
        transform: rotate(90deg);

    }

    #mapout-first-step li h6{
        transform: rotate(270deg);
    }

    #mapout-first-step li{
        min-width: 59px;
        margin-left: 0px;
        margin-right:0px;
        background-size: 95%;
        background-repeat: no-repeat;
    }

    #mapout-first-step li:nth-child(even){
       background: url('../img/arrow-down.png')!important;
       background-position-x: 50%!important;
        background-size: 95%!important;
        background-repeat: no-repeat!important;
    }


    #mapout-first-step li h6{
        height: 30px;
        width:65%;
        font-size: 09px;
        margin-left: 10px;
        margin-top: 5px;
        
    }

    #mapout-first-step {
        padding-left: 0px;
        min-height: 640px;
    }
    .simple-card.same {
        background: #e36f00;
        display: none;
        width: 42px;
    }
    .hv-wrapper .hv-item .hv-item-parent:after {
        position: absolute;
        content: '';
        width: 2px;
        height: 25px;
        bottom: 0;
        left: 50%;
        background-color: rgba(93, 93, 93, 0.7);
        transform: translateY(100%);
        display: none;
    }   

    .no-rotate{
        transform: rotate(180deg);
    }
    .simple-card {
        background: #df6403;
        width: 43px;
    }
    .fa-times-circle{
        cursor: pointer;
    }
</style>
<div class="row">
    <!-- LADO IZQUIERDO-->
    <div class="col-md-6 col-sm-12" style="zoom: 0.8">
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
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
    <div class="col-md-6 col-sm-12" style="zoom: 0.8">
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
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}  </p>
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
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
                                        ?>
                                    <p class="simple-card {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    
                                    @else
                                    <p class="simple-card" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}  </p>
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
                                        ?>
                                        <p class="simple-card no-rotate {{$colores}}" attr-line="2" attr-position="{{$i}}" attr-control="0" onclick="modificarCruce(this)" data-id="{{ $p[$i]->id }}" data-mother="{{ $p[$i]->crossing->name }}" json="{{ json_encode($p[$i]) }}" data-father="{{ $p[$i]->crossing->fatherName() }}"> {{@$p[$i]->crossing->name}} <i class="fa fa-times-circle" onclick="delete_position({{$i}},'{{@$p[$i]->crossing->name}}')"> </i></p>
                                    @else
                                        <p class="simple-card no-rotate" attr-line="2" attr-position="{{$i}}" attr-control="1"> {{$i}}</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modal-add-cruce" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Cruce</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
                <div class="modal-body">
                    {!! Form::open(array('class' => 'm-form','route' => 'greenhouse.addPositions','method'=>'POST','id' => 'FormAddPositions')) !!}
                            <input type="hidden" name="mapout_id" value="{{ $mapout->id }}">
                            <input type="hidden" name="positionY">
                            <input type="hidden" name="line">
                            <input type="hidden" name="varity_id">
                        </form>

                    <table class="table table-striped- table-bordered table-hover table-checkable datatables_p">
                        <thead>
                            <tr>
                                
                                <th>Temporada</th>
                                <th>Id</th>
                                <th>Variedad</th>
                                <th>M.Objetivo</th>
                                <th>Etapa</th>
                                <th>Estado</th>
                                <th style="width:350px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                $list_new = [];
                            ?>

                            @if(!empty($crossings))
                                @foreach($crossings as $r)
                                    
                                        <?php 
                                            $record = Varity::where("id",@$r->mother->id)->first();
                                            $list_new[] = @$record->id;
                                            $record = Varity::where("id",@$r->father->id)->first();
                                            $list_new[] = @$record->id;
                                        ?>
                                @endforeach
                                @foreach(array_unique($list_new) as $r)
                                    <?php 
                                        $record = Varity::where("id",$r)->first();
                                        $list_new[] = @$record->id;
                                    ?>
                                    <tr>
                                        <td>T{{ @$record->year }}-{{ @$record->year+1 }}</td>
                                        <td>{{ @$record->id }}</td>
                                        <td>
                                            <b>
                                                <a href="/varities/{{ @$record->id }}">
                                                    {{ @$record->name }}
                                                </a>
                                            </b>
                                        </td>
                                       
                                        <td>
                                            <b>
                                                @if(!empty($record->marketold_id))
                                                    @if($record->marketold_id == $record->market_id)
                                                        {{ $record->market->name }}
                                                    @else
                                                        Original: {{ $record->marketold->name }}, Modificado: {{ $record->market->name }}
                                                    @endif
                                                @endif
                                            </b>
                                        </td>
                                        <td>
                                            <center>
                                                <span class="m-badge m-badge--warning m-badge--wide">Año {{ date('Y')-@$record->year }}</span>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                @if(@$record->typecrossing == 1)
                                                    <span class="m-badge m-badge--info m-badge--wide">Por codificar</span>
                                                @else
                                                    <span class="m-badge m-badge--success m-badge--wide">Codificado</span>
                                                @endif
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <a class="btn btn-outline-info btn-sm" onclick="agregarCruce({{ @$record->id }})">Seleccionar</a>
                                            </center>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade show" id="modal-update-position" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modificación Cruce</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            {!! Form::open(array('class' => 'm-form','route' => 'greenhouse.updatePosition','method'=>'POST','id' => 'FormUpdatePosition')) !!}
                <div class="modal-body">
                    <input type="hidden" name="id" >
                    <!--<div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <strong>
                                    ¿Qué tipo de ensayo quiere asignar al cruce?
                                </strong>
                                {!! Form::select('type', array(0 => 'No califica', '6x1' => '6x1','12x2' => '12x2','12x3' => '12x3'),null, array('placeholder' => 'Seleccione ensayo','class' => 'form-control  required')) !!}
                            </div>
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Flor</label>
                                <input type="text" name="red" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Polem</label>
                                <input type="text" name="redmeat" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Bayas</label>
                                <input type="text" name="blue" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Nº Abortos</label>
                                <input type="text" name="yellow" class="form-control" value="0">
                            </div>
                        </div>
                        <!--div class="col-lg-6">
                            <div class="numbers-row">
                                <label for="name">Alargada</label>
                                <input type="text" name="elongated" class="form-control" value="0">
                            </div>
                        </div-->
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <strong>Observación:</strong>
                                {!! Form::textarea('observation', null, array('placeholder' => 'Ingrese Observación','class' => 'form-control ')) !!}
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    @if($mapout->step == 0)
                    <button type="submit" class="btn btn-primary" style="margin-top: 0px;"><i class="fa fa-save"></i> Actualizar</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        step = "<?= $mapout->step;?>"
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

    agregarCruce = function(id){
        $("#FormAddPositions input[name=positionY]").val(y)
        $("#FormAddPositions input[name=line]").val(line)
        $("#FormAddPositions input[name=varity_id]").val(id)
        $.ajax({
          url: $("#FormAddPositions").attr('action'),
          type: 'POST',
          dataType: 'json',
          data: $("#FormAddPositions").serialize(),
        }).done(function(data) {
            $("#modal-add-cruce").modal('hide')
            draw_greenhouse()
        })
        //$("#FormAddPositions").submit()
    }

    modificarCruce = function(element){
        if(step != 0){
            $("#FormUpdatePosition button[type=submit]").hide()
            $(".dec.button").hide()
            $(".inc.button").hide()
            $("#FormUpdatePosition input,#FormUpdatePosition textarea").attr("disabled","disabled")
        }else{
            $("#FormUpdatePosition button[type=submit]").show()
            $(".dec.button").show()
            $(".inc.button").show()
            $("#FormUpdatePosition input,#FormUpdatePosition textarea").removeAttr("disabled")
        }
        var json = JSON.parse($(element).attr("json"))
        var id = $(element).attr("data-id")
        var mother = $(element).attr("data-mother")
        var father = $(element).attr("data-father")
        $("#modal-update-position").modal("show")
        $("#modal-update-position h5").html("Actualizar Información de: <b>"+mother+"")
        $("#FormUpdatePosition input[name=id]").val(id)
        $("#FormUpdatePosition textarea[name=observation]").val(json.observation)
        $("#FormUpdatePosition select[name=type]").val(json.count)
        $("#FormUpdatePosition input[name=red]").val(json.red)
        $("#FormUpdatePosition input[name=redmeat]").val(json.redmeat)
        $("#FormUpdatePosition input[name=blue]").val(json.blue)
        $("#FormUpdatePosition input[name=yellow]").val(json.yellow)
        $("#FormUpdatePosition input[name=elongated]").val(json.elongated)
        return false;
        
    }

    delete_position = function(id,name){
        Swal.fire({
          title: '¿Quieres eliminar '+name+' ?',
          text: "Se va a eliminar, no habrá vuelta atrás.",
          type: 'error',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, anular'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/greenhouse/deleteCodify/"+id,
              type: 'GET',
              dataType: 'json',
            }).done(function(data) {
                draw_greenhouse()
            })
          }
        })
    }  
</script>
@endsection
