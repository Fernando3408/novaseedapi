@extends('layouts.app')

@section('content')
<h3>Nuevo Player</h3>

<form method="POST" action="{{ route('players.store') }}">
    @csrf
    @include('players.form')
    <button class="btn btn-success">Guardar</button>
</form>
@endsection
