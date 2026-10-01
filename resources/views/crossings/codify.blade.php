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
                    Codificación (F1)
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="row">
           <div class="col-lg-3">
               Madre: <a href="/varity/{{ $crossing->mother->id }}">{!! $crossing->mother->name !!}</a>
           </div>
           <div class="col-lg-3">
               Padre: <a href="/varity/{{ $crossing->father->id }}">{!! $crossing->father->name !!}</a>
           </div>
           <div class="col-lg-2">
               Año Cruce: {{ $crossing->year }}
           </div>
        </div>
        <br>
        {!! Form::open(array('class' => 'm-form','url' => '/crossings/saveCodify','method'=>'POST','id' => 'FormCodificaciones')) !!}
            <input type="hidden" name="crossing" value="{{ $crossing->id }}">
            <input type="hidden" name="mother_id" value="{{ $crossing->mother->id }}">
            <input type="hidden" name="father_id" value="{{ $crossing->father->id }}">
            <input type="hidden" name="year" value="{{ $crossing->year }}">
            <div class="row">
                <div class="col-lg-2">
                    <div class="form-group">
                        <?= Form::select('market_id', $markets,null,['class' => 'form-control ','placeholder' => '-Select Target-','style' => '']); ?>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        {!! Form::text('count', null, array('placeholder' => 'Ingrese cantidad ','class' => 'form-control  ')) !!}
                    </div>
                </div>
                <div class="col-lg-2">
                       <button type="button"  id="add-codify" class="btn btn-success">
                           <i class="fa fa-plus"></i> Agregar para codificar
                       </button>
                </div>
                <div class="col-lg-2">
                       <button type="button"  id="delete-codify" class="btn btn-danger">
                           <i class="fa fa-trash"></i> Eliminar codificados
                       </button>
                </div>
            </div>
        <hr>
            <table class="table" id="tabla-codificacion">
                <thead>
                    <tr>
                        <th>Mother</th>
                        <th>Father</th>
                        <th>Target</th>
                        <th>Observation</th>
                        <th width="100px">Nº Berries</th>
                        <th>Code</th>
                        <!--<th></th>-->
                    </tr>
                </thead>
            </table>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar codificación</button>
            <button disabled="disabled" style="margin-top: 30px;" id="terminar_codificacion" type="button" class="btn btn-success"><i class="fa fa-arrow-right"></i> Terminar codificación</button>
        </form>
    </div>
</div>




<script>
    $(document).ready(function(){
        $("#FormCodificaciones").validate()

        let year = "<?= $crossing->year;?>"

        var increment = 1;

        $("#FormCodificaciones select[name=market_id]").val("<?= $crossing->market_id;?>")

        var table = $('#tabla-codificacion').DataTable( {
            "order": [[ 1, "desc" ]],
            "paging": false,
            "searching": false,
            processing: true,
            ajax: {
                url: '/crossings/items',
                data: function ( d ) {
                    d.crossing_id = "<?= $crossing->id;?>"
                }
            },
            columns: [
                { 
                    "data": "mother.name",
                    "render": function ( data, type, row ) {
                        $("#terminar_codificacion").removeAttr("disabled")
                        if(row.mother.crossing != 1){
                          return row.mother.name;
                        }else{
                          return row.mother.code;
                        }
                    } 
                },
                { 
                    "data": "father.name",
                    "render": function ( data, type, row ) {
                        if(row.father.crossing != 1){
                          return row.father.name;
                        }else{
                          return row.father.code;
                        }
                    } 
                },
                { 
                    "data": "market.name",
                    "render": function ( data, type, row ) {
                        return row.market.name+'<input type="hidden"  name=data['+(increment)+'][market] class="form-control" value="'+row.market.id+'"/>';
                    } 
                },
                { 
                    "data": "observation",
                    "render": function ( data, type, row ) {
                        if(row.observation == null){
                            row.observation = "";
                        }
                        return '<input type="text"  name=data['+(increment)+'][observation] class="form-control" value="'+row.observation+'"/>';
                    } 
                },
                { 
                    "data": "berries",
                    "render": function ( data, type, row ) {
                        if(row.berries == null){
                            row.berries = "";
                        }
                        return '<input type="text"  name=data['+(increment)+'][berries] class="form-control" value="'+row.berries+'"/>';
                    } 
                },
                { 
                    "data": "code",
                    "render": function ( data, type, row ) {
                        if(row.code == null){
                            row.code = "";
                        }
                        return '<input type="text"  name=data['+(increment++)+'][code] class="form-control" value="'+row.code+'"/>';
                    } 
                }/*,
                { 
                    "data": "code",
                    "render": function ( data, type, row ) {
                        if(row.code == null){
                            row.code = "";
                        }
                        return '<button onclick="delete_row(this)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    } 
                },*/
            ]
         } );

        $("#terminar_codificacion").click(function(){
            let id = "<?= $crossing->id;?>"
            Swal({
              title: '¿Está seguro que quiere terminar codificación?',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Si, seguro',
              cancelButtonText: 'Cancelar',
            }).then((result) => {
                if(result.value){
                    location.href = '/crossings/finishCodify/'+id
                }
            })
        })

        $("#delete-codify").click(function(){
            Swal({
              title: '¿Está seguro que quiere eliminar los codificados?',
              text: "Si elimina los codificados, puede que no tenga los mismos códigos de codificados.",
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Si, seguro',
              cancelButtonText: 'Cancelar',
            }).then((result) => {
                if(result.value){
                    $.ajax({
                        url: '/crossings/deleteCodify',
                        type: 'GET',
                        dataType: 'json',
                        data: {crossing: '<?= $crossing->id;?>' },
                    }).done(function(data) {
                        if(data){
                            Swal({
                              title: 'Se eliminó los codificados',
                              text: "",
                              type: 'success',
                              showCancelButton: false,
                              showConfirmButton: false,
                            })
                            table.ajax.reload()
                        }else{
                            Swal({
                              title: 'No se pudo eliminar codificados',
                              text: "",
                              type: 'error',
                              showCancelButton: false,
                              showConfirmButton: false,
                            })  
                        }
                    })
                }
            })   
        })


        $("#add-codify").click(function(){
            if($("select[name=market_id] option:selected").val() != "" &&  parseInt($("#FormCodificaciones input[name=count]").val()) > 0){
                $.ajax({
                    url: '/crossings/generateCodify',
                    type: 'GET',
                    dataType: 'json',
                    data: {crossing: '<?= $crossing->id;?>' , market: $("#FormCodificaciones select[name=market_id]").val(), count: $("#FormCodificaciones input[name=count]").val()},
                }).done(function(data) {
                    if(data){
                        table.ajax.reload()
                        Swal({
                          title: 'Se generó la codificación',
                          text: "",
                          type: 'success',
                          showCancelButton: false,
                          showConfirmButton: false,
                        })
                    }else{
                        Swal({
                          title: 'No se pudo hacer codificación',
                          text: "",
                          type: 'error',
                          showCancelButton: false,
                          showConfirmButton: false,
                        })  
                    }
                })
            }else{
                Swal({
                  title: 'Seleccione Target y cantidad de codificados',
                  text: "",
                  type: 'error',
                  showCancelButton: false,
                  showConfirmButton: false,
                })
            }
        })

        delete_row = function(element){
            table.row( $(element).parents('tr') ).remove().draw();
        }
    })
</script>

@endsection
