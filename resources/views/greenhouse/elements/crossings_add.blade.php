<div class="col-md-12">
    <button type="button" class="btn btn-primary" onclick="seleccionar()"><i class="fa fa-check"></i> Seleccionar</button>
</div>


<table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table_crossings" style="width: 100%">
    <thead>
        <tr>
            <th> <input  type="checkbox" onclick="select_all_new()"></th>
            <th>id</th>
            <th>Madre</th>
            <th>Padre</th>
            <th>M.Objetivo</th>
            <th>Objetivo Bayas</th>
            <th>Objetivo Cruce</th>
            <th>Fecha Creación</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($crossings))
            @foreach($crossings as $record)
                <tr>
                    <td>
                        <center>
                            <input  class="type_to_add" type="checkbox" name="farm[6x1][<?= $record->id;?>][farm]" value="1" id="index_{{$record->id}}" onclick="registrarCheck({{$record->id}})">
                        </center>
                    </td>
                    <td>{{ $record->id }}</td>
                    <td>
                        <b><a href="/varities/{{ @$record->mother->id }}">{{ @$record->motherName() }}</a></b>
                    </td>
                    <td>
                        <b><a href="/varities/{{ @$record->father->id }}">{{ @$record->fatherName() }}</a></b>
                    </td>
                    <td>
                        <b>{{ @$record->market->name }}</b>
                    </td>
                   
                    <td>{{ $record->berries_objective }}</td>
                    <td>{{ $record->observation }}</td>
                     <td>
                        <b>{{ @$record->created_at }}</b>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        table_crossings = $("#table_crossings").DataTable({
            bPaginate:false,
            
        })
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        select_all_new = function(){
            $(".type_to_add").click()
        }
    })
</script>