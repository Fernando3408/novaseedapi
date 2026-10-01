<div class="form-group mb-2">
    <label>Nombre</label>
    <input type="text" name="name" class="form-control"
        value="{{ old('name', $player->name ?? '') }}" required>
</div>

<div class="form-group mb-2">
    <label>Grupo</label>
    <input type="number" name="group_p" class="form-control"
        value="{{ old('group_p', $player->group_p ?? 0) }}" required>
</div>

<div class="form-group mb-2">
    <label>ID Externo</label>
    <input type="number" name="id_externo" class="form-control"
        value="{{ old('id_externo', $player->id_externo ?? '') }}">
</div>

<div class="form-group mb-3">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="1" {{ old('status', $player->status ?? 1) == 1 ? 'selected' : '' }}>
            Activo
        </option>
        <option value="2" {{ old('status', $player->status ?? 1) == 2 ? 'selected' : '' }}>
            Suspendido
        </option>
    </select>
</div>
