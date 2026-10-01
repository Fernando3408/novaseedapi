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
                    Mapeo
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="row">
            <div class="col-lg-3">
               <form id="form-mapout">
                   <div class="form-group">
                        <select name="year" id="" class="form-control required">
                            <option value="">Seleccione Temporada</option>
                            <option value="2018">T2018-2019</option>
                            <option value="2019">T2019-2020</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input name="blocks" type="text" class="form-control number required" placeholder="Cantidad de bloques">
                    </div>
                    <div class="form-group">
                        <input name="count" type="text" class="form-control number required" placeholder="Número posiciones bloque">
                    </div>
                    <button class="btn btn-success">Generar Mapeo</button>
                </form>
                <hr>

                
                <ul class="sortable connectedSortable" id="list">
                    @foreach($coded as $c)
                        <li class="ui-state-default">
                            {!! $c->code !!} - {{ $c->market->name }} <span></span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-9">
                <div style="overflow-x: auto;min-height: 500px">
                    <div style="width: 2000px">
                        <div class="bloque">
                            <h6>Bloque 1</h6>
                            <ul data-bloque="1" position="1" class="position sortable connectedSortable"></ul>
                            <ul data-bloque="1" position="2" class="position sortable connectedSortable"></ul>
                        </div>
                        <div class="bloque">
                            <h6>Bloque 2</h6>
                            <ul data-bloque="2" position="1" class="position sortable connectedSortable"></ul>
                            <ul data-bloque="2" position="2" class="position sortable connectedSortable"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .bloque{
        width: 270px;
        border: 1px solid #CCC;
        height: 100%;
        float: left;
        margin-right: 20px;
        border-bottom: 0px;
        border-left: 0px;
        border-right: 0px;
    }
    .bloque h6{
        width: 100%;
        background: #CCC;
        text-align: center;
        padding-top: 10px;
        padding-bottom: 10px;
        margin-bottom: 0px;
    }
    .bloque .position{
        border-left: 1px solid #CCC;
        border-right: 1px solid #CCC;
        border-bottom: 1px solid #CCC;
        padding:5px;
        min-height: 100px;
        margin-bottom: 0px;
    }

    .bloque .position li{
        background: #FFC107;
        padding: 3px 10px;
        text-align: center;
        margin-top: 3px;
        list-style: none;
    }

    #list{
        padding-left: 0px;
    }

    #list li{
        background: #009688;
        padding: 3px 10px;
        text-align: center;
        margin-top: 3px;
        list-style: none;
        color: #FFF;
        margin-bottom: 5px;
    }
</style>
<script>
    $(window).load(function(){
        
        $("#form-mapout").validate()


    })
</script>
<script>
    $( function() {
        $( ".sortable" ).sortable({
            connectWith: ".connectedSortable",
            start: function(event, ui) {
                var start_pos = ui.item.index();
                ui.item.data('start_pos', start_pos);
            },
            change: function(event, ui) {
                var start_pos = ui.item.data('start_pos');
                var index = ui.placeholder.index();
                if (start_pos < index) {
                    $('#sortable li:nth-child(' + index + ')').addClass('highlights');
                } else {
                    $('#sortable li:eq(' + (index + 1) + ')').addClass('highlights');
                }
            },
            update: function(event, ui) {
                var block = ui.item.parent().attr("data-bloque")
                var position = ui.item.parent().attr("position")
                var position_item = ui.item.index()
                console.log("bloque: "+block)
                console.log("Posición: "+position)
                console.log("Número posición: "+position_item)
                var texto = ui.item.html()
                if(typeof block != "undefined"){
                    ui.item.children("span").html("("+block+"."+position+"."+position_item+")")
                }else{
                    ui.item.children("span").html("")
                }
            }
        }).disableSelection();
    });
  </script>
@endsection
