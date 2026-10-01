<div class="col-md-12">
    <button type="button" class="btn btn-primary" onclick="delete_selected()"><i class="fa fa-check"></i> Quitar</button>
</div>
<table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table_crossings_added">
    <thead>
        <tr>
            <th class="text-center"><input type="checkbox" onclick="all_delete()"></th>
            <th>Madre</th>
            <th>Padre</th>
            <th>M.Objetivo</th>
            <th>Objetivo Bayas</th>
            <th>Objetivo Cruce</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($asociate_crossing))
            @foreach($asociate_crossing as $record)
                <tr>
                    <td>
                        <center>
                            <input  class="_dtype" type="checkbox" name="_dfarm[6x1][<?= $record->id;?>][farm]" value="1" id="_dindex_{{$record->id}}" onclick="toDelete({{$record->id}})">
                        </center>
                    </td>
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
                    
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        table_crossings_added = $("#table_crossings_added").DataTable({
            bPaginate:false,
            
        })
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        all_delete = function(){
            $("._dtype").click()
        }
    })
</script>