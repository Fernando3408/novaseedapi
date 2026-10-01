@extends('layouts.app')

@section('content')
<a href="{{ route('players.create') }}" class="btn btn-primary mb-3">
    Nuevo Player
</a>

<table class="table table-bordered" id="players-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Grupo</th>
            <th>ID Externo</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($players as $player)
        <tr data-id="{{ $player->id }}">
            <td>{{ $player->id }}</td>
            <td>{{ $player->name }}</td>

            <td>
                <input type="number"
                       class="form-control form-control-sm editable"
                       data-field="group_p"
                       value="{{ $player->group_p }}"
                       data-original="{{ $player->group_p }}">
            </td>

            <td>
                <input type="number"
                       class="form-control form-control-sm editable"
                       data-field="id_externo"
                       value="{{ $player->id_externo }}"
                       data-original="{{ $player->id_externo }}">
            </td>

            <td>
                <select class="form-control form-control-sm editable"
                        data-field="status"
                        data-original="{{ $player->status }}">
                    <option value="1" {{ $player->status == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="2" {{ $player->status == 2 ? 'selected' : '' }}>Suspendido</option>
                </select>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
$(document).ready(function () {

    const table = $('#players-table').DataTable({
        pageLength: 25,
        stateSave: true, // 🔥 guarda búsqueda y página
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_",
            info: "Mostrando _START_ a _END_ de _TOTAL_",
            paginate: {
                next: "→",
                previous: "←"
            }
        }
    });

    let debounceTimer = null;

    $('#players-table').on('change', '.editable', function () {

        const input = $(this);
        const row = input.closest('tr');
        const playerId = row.data('id');

        if (input.val() == input.data('original')) {
            return;
        }

        const payload = {
            _token: '{{ csrf_token() }}'
        };

        row.find('.editable').each(function () {
            payload[$(this).data('field')] = $(this).val();
        });

        row.addClass('table-warning');

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {

            $.ajax({
                url: `/players/${playerId}/quick-update`,
                method: 'POST',
                data: payload,
                success: function () {
                    row.removeClass('table-warning')
                       .addClass('table-success');

                    row.find('.editable').each(function () {
                        $(this).data('original', $(this).val());
                    });

                    setTimeout(() => {
                        row.removeClass('table-success');
                    }, 600);
                },
                error: function () {
                    row.removeClass('table-warning')
                       .addClass('table-danger');
                }
            });

        }, 300);
    });

});
</script>


@endsection
