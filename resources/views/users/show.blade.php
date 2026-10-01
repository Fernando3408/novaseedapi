

@extends('layouts.app')

@section('content')
    <div class="container">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Datos Empresa</a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Ubicaciones</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Servicios</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent" style="padding:20px">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <h3>Empresa: {{ $enterprise->name }}</h3>
                <h3>Rut: {{ $enterprise->rut }}</h3>
                <h3>Email: {{ $enterprise->email }}</h3>
                <h3>Creado: {{ $enterprise->created_at }}</h3>
                <h3>Estado: <?= ($enterprise->active)?'<span class="badge badge-success">Activado</span>':'<span class="badge badge-danger">Desactivado</span>' ?></h3>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                Ubicaciones 
            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                Servicios
            </div>
        </div>
    </div>
<script>
    $(document).ready(function() {
        $("#FormEditEnterprise").validate()
	});
</script>


@endsection
