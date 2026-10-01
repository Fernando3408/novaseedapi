<?php
    use App\Models\Varity;
    use App\Models\Position;
?>
<script src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/custom-data-source/dom-text.js"></script>

<table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table_crossings_ready">
    <thead>
        <tr>
            <th>ID</th>
            <th>Año</th>
            <th>Temporada</th>
            <th>Madre</th>
            <th>Padre</th>
            <th>M.Objetivo</th>
            <th>Objetivo Cruce</th>
            <th>Nro Bayas</th>
            <th>Vasos</th>
            <th>Papas</th>
            <th>Clones</th>
            <th>Efectividad</th>
        </tr>
    </thead>
    <tbody> 
        @if(!empty($crossings))
            @foreach($crossings as $record)
            <?php  ?>
                <tr>
                    <td>{{ $record->id }}</td>
                    <td>{{ $record->year }}</td>
                    <td>T{{ $record->year }}-{{ $record->year+1 }}</td>
                    <td>
                        <b>
                            <a href="/varities/{{ @$record->mother->id }}">
                                {!! @$record->motherName() !!}
                            </a>
                        </b>
                    </td>
                    <td>
                        <b>
                            <a href="/varities/{{ @$record->father->id }}">
                                {!! @$record->fatherName() !!}
                            </a>
                        </b>
                    </td>
                    <td>
                        <b>
                            {{ @$record->market->name }}
                        </b>
                    </td>
                    <td>{{ $record->observation }}</td>
                    <td><input type="text" name="berries" class="berries" value="{{$record->berries}}" attr-id="{{$record->id}}" onchange="berriesUpdate(this)" style="width: 50px;"></td>
                    <td><input type="text" name="glasses" class="glasses" value="{{$record->glasses}}" attr-id="{{$record->id}}" onchange="glassesUpdate(this)" style="width: 50px;"></td>
                    <td><input type="text" name="potatoes" class="potatoes" value="{{$record->potatoes}}" attr-id="{{$record->id}}" onchange="potatoesUpdate(this)" style="width: 50px;"></td>
                    <td>{{ $record->getPositionSum() }}</td>
                    <td>{{ $record->getEfectivity() }}%</td>


                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        table_crossings_ready = $("#table_crossings_ready").DataTable({
            scrollX: true,
            bPaginate:false,
            "columnDefs": [{
                "targets": 8,
                "orderDataType": "dom-text"
            }]
        })

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $(".cancelar").click(function(event){
            event.preventDefault();
            id_cancel = $(this).attr('attr-id-cancel')
            table_crossings_ready
                .row($(this).parents('tr'))
                .remove()
                .draw();

            $.ajax({
                url: "/public/greenhouse/"+id_cancel,
                    type: 'PATCH',
                    data: {record_id: id_cancel, record_status: 0},
                    dataType: 'json',
                }).done(function(dataxx) {
                    draw_croosings_list()
                })

        })

        berriesUpdate = function(elem){
            var id = $(elem).attr("attr-id")
            var nro = $(elem).val()
            console.log(id)
            console.log(nro)

            $.ajax({
                url: "/greenhouse/berries/"+id+"/"+nro,
                type: 'GET',
                dataType: 'json',
            }).done(function(dataxx) {
                
            })
        }

        glassesUpdate = function(elem){
            var id = $(elem).attr("attr-id")
            var nro = $(elem).val()

            $.ajax({
                url: "/greenhouse/glasses/"+id+"/"+nro,
                type: 'GET',
                dataType: 'json',
            }).done(function(dataxx) {
                
            })
        }

        potatoesUpdate = function(elem){
            var id = $(elem).attr("attr-id")
            var nro = $(elem).val()

            $.ajax({
                url: "/greenhouse/potatoes/"+id+"/"+nro,
                type: 'GET',
                dataType: 'json',
            }).done(function(dataxx) {
                
            })
        }
    })

</script>