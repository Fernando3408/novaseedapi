@extends('layouts.app')

@section('content')
<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list"></i>
                </span>
                <h3 class="m-portlet__head-text">Comparación Resultados</h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-checkable" id="table-compare-results">
                <thead>
                    <tr>
                        <th>ID Comparación</th>
                        <th>Nombre</th>
                        <th>Fecha creación</th>
                        <th>Última actualización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compares as $compare)
                    <tr id="compare-row-{{ $compare->id }}">
                        <td>{{ $compare->id }}</td>
                        <td>{{ $compare->nombre }}</td>
                        <td>{{ $compare->created_at }}</td>
                        <td>{{ $compare->updated_at }}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" onclick="showCompareResult({{ $compare->id }})">
                                <i class="fa fa-eye"></i> Visualizar
                            </button>
                            <a href="{{ route('results.compare-results.excel', $compare->id) }}" class="btn btn-success btn-sm">
                                <i class="fa fa-file-excel-o"></i> Imprimir
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteCompareResult({{ $compare->id }})">
                                <i class="fa fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-compare-result" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="compare-result-title">Detalle comparación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="compare-result-header" style="margin-bottom: 15px;"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="table-compare-result-detail">
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $("#table-compare-results").DataTable({
        "order": [[0, "desc"]]
    });
});

escapeCompareResult = function(value){
    return $('<div>').text(value === null || value === undefined ? '' : value).html();
}

showCompareResult = function(id){
    $.ajax({
        url: '/results/compare-results/' + id,
        type: 'GET',
        dataType: 'json'
    }).done(function(response){
        var compare = response.compare;
        var header = "<strong>ID Comparación:</strong> " + escapeCompareResult(compare.id);
        header += " &nbsp; <strong>Nombre:</strong> " + escapeCompareResult(compare.nombre);
        header += " &nbsp; <strong>Actualizado:</strong> " + escapeCompareResult(compare.updated_at);
        $("#compare-result-title").text("Comparación Resultados: " + compare.nombre);
        $("#compare-result-header").html(header);

        var thead = "<tr><th>Rank</th><th>Variedad</th><th>Padre</th><th>Madre</th><th>Obs1</th><th>Descripción</th><th>Acción</th>";
        $.each(compare.features || [], function(i, feature){
            thead += "<th>" + escapeCompareResult(feature.name) + "</th>";
        });
        thead += "</tr>";

        var tbody = "";
        $.each(compare.details || [], function(i, detail){
            tbody += "<tr>";
            tbody += "<td>" + escapeCompareResult(detail.rank) + "</td>";
            tbody += "<td>" + escapeCompareResult(detail.varity) + "</td>";
            tbody += "<td>" + escapeCompareResult(detail.father) + "</td>";
            tbody += "<td>" + escapeCompareResult(detail.mother) + "</td>";
            tbody += "<td>" + escapeCompareResult(detail.free_description) + "</td>";
            tbody += "<td>" + escapeCompareResult(detail.description) + "</td>";
            tbody += "<td>" + escapeCompareResult(detail.action) + "</td>";
            $.each(detail.features || [], function(ix, feature){
                tbody += "<td title='n: " + escapeCompareResult(feature.num) + " | Desv. STD: " + escapeCompareResult(feature.std) + "'>" + escapeCompareResult(feature.value) + "</td>";
            });
            tbody += "</tr>";
        });

        $("#table-compare-result-detail thead").html(thead);
        $("#table-compare-result-detail tbody").html(tbody);
        $("#modal-compare-result").modal('show');
    });
}

deleteCompareResult = function(id){
    Swal.fire({
        title: '¿Eliminar comparación?',
        text: 'La comparación se quitará del listado.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar'
    }).then(function(result){
        if(!result.value){ return; }
        $.ajax({
            url: '/results/compare-results/' + id,
            type: 'DELETE',
            dataType: 'json',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(){
            $("#compare-row-" + id).remove();
            Swal.fire('Eliminado', '', 'success');
        });
    });
}
</script>
@endsection
