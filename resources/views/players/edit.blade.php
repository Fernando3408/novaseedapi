@extends('layouts.app')

@section('content')
<h3>Editar Player</h3>

<form method="POST" action="{{ route('players.update', $player) }}">
    @csrf
    @method('PUT')
    @include('players.form')
    <button class="btn btn-success">Actualizar</button>
</form>
@endsection
