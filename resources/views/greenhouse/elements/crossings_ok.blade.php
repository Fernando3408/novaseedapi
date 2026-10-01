<?php
    use App\Models\Varity;
    use App\Models\Position;
?>
<script src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/custom-data-source/dom-text.js"></script>
<h1 class="text-center">Nro Vasos: <strong id="text_glass">0</strong> | Nro Bayas:  <strong id="text_berries">0</strong>| Nro Papas:  <strong id="text_potatoes">0</strong></h1>

<table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table_crossings_ready">
    <thead>
        <tr>
            <th></th>
            <th>Fecha Término</th>
            <th>Madre</th>
            <th>Padre</th>
            <th>M.Objetivo</th>
            <th>Objetivo Bayas</th>
            <th>Objetivo Cruce</th>
            <th>Nro Bayas</th>
            <th>Vasos</th>
            <th>Papas</th>
        </tr>
    </thead>
    <tbody> 
        @if(!empty($crossings_ready))
            @foreach($crossings_ready as $record)
                <tr>
                    <td>
                    {!! Form::open(['method' => 'PATCH','route' => ['greenhouse.update', $record->id],'style'=>'display:inline']) !!}
                    <input type="hidden" name="record_id" value="{{@$record->id}}">
                    <input type="hidden" name="record_status" value="0">
                    
                    <button type="button" class="btn btn-outline-success btn-sm cancelar" attr-id-cancel="{{$record->id}}" >Cancelar</button>
                    {!! Form::close() !!}
                    </td>
                    <td>{{ $record->updated_at }}</td>
                    <td>
                        <b>
                            <a href="/varities/{{ @$record->mother->id }}">
                                {{ @$record->motherName() }}
                            </a>
                        </b>
                    </td>
                    <td>
                        <b>
                            <a href="/varities/{{ @$record->father->id }}">
                                {{ @$record->fatherName() }}
                            </a>
                        </b>
                    </td>
                    <td>
                        <b>
                            {{ @$record->market->name }}
                        </b>
                    </td>
                    <td>{{ $record->berries_objective }}</td>
                    <td>{{ $record->observation }}</td>
                    <td><input type="text" style="width: 50px;" name="berries" class="berries" value="{{$record->berries}}" attr-id="{{$record->id}}" onchange="berriesUpdate(this)"></td>
                    <td><input type="text" style="width: 50px;" name="glasses" class="glasses" value="{{$record->glasses}}" attr-id="{{$record->id}}" onchange="glassesUpdate(this)"></td>
                    <td><input type="text" style="width: 50px;" name="potatoes" class="potatoes" value="{{$record->potatoes}}" attr-id="{{$record->id}}" onchange="potatoesUpdate(this)"></td>

                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script type="text/javascript">
    function sumBerriesGlasses() {
        // Seleccionar todos los inputs con la clase 'berries'
        const berriesInputs = document.querySelectorAll('input.berries');
        const glassesInputs = document.querySelectorAll('input.glasses');
        const potatoesInputs = document.querySelectorAll('input.potatoes');

        // Inicializar la suma
        let sumBerries = 0;
        let sumGlasses = 0;
        let sumPotatoes = 0;

        // Recorrer cada input y sumar los valores
        berriesInputs.forEach(input => {
            const value = parseFloat(input.value); // Convertir a número
            if (!isNaN(value)) { // Asegurar que sea un número válido
                sumBerries += value;
            }
        });

        glassesInputs.forEach(input => {
            const value = parseFloat(input.value); // Convertir a número
            if (!isNaN(value)) { // Asegurar que sea un número válido
                sumGlasses += value;
            }
        });

        potatoesInputs.forEach(input => {
            const value = parseFloat(input.value); // Convertir a número
            if (!isNaN(value)) { // Asegurar que sea un número válido
                sumPotatoes += value;
            }
        });

        // Mostrar el resultado
        $("#text_glass").empty().html(sumGlasses)
        $("#text_berries").empty().html(sumBerries)
        $("#text_potatoes").empty().html(sumPotatoes)
    }

    $(document).ready(function(){
        table_crossings_ready = $("#table_crossings_ready").DataTable({
            scrollX: true,
            bPaginate:false,
            "columnDefs": [{
                "targets": [7, 8, 9],
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
                sumBerriesGlasses()
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
                sumBerriesGlasses()
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
                sumBerriesGlasses()
            })
        }

        sumBerriesGlasses()
    })

</script>