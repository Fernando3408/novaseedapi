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
</script>