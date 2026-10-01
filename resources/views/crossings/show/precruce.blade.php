<div class="row">
	<div class="col-lg-3">
		<div class="item">
			<h6>Temporada {{ $crossing->id }}</h6>
			<h5>T{{ $crossing->year }}-{{ $crossing->year+1 }}</h5>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="item">
			<h6>Madre</h6>
			<h5><a href="/varity/{{ $crossing->mother->id }}"><?= ($crossing->mother->crossing == 1)? $crossing->mother->code:$crossing->mother->name;?></a></h5>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="item">
			<h6>Padre</h6>
			<h5><a href="/varity/{{ $crossing->father->id }}"><?= ($crossing->father->crossing == 1)? $crossing->father->code:$crossing->father->name;?></a></h5>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="item">
			<h6>Etapa</h6>
			<span class="m-badge m-badge--warning m-badge--wide">Año {{ date('Y')-$crossing->year }}</span>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="item">
			<h6>Estado</h6>
			@if($crossing->typecrossing == 1)
                <span class="m-badge m-badge--info m-badge--wide">Por codificar</span>
            @else
                <span class="m-badge m-badge--success m-badge--wide">Codificado</span>
            @endif
		</div>
	</div>
	<div class="col-lg-3">
		<div class="item">
			<h6>Creado por</h6>
			<h5>{{ $crossing->user->name }}</h5>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="item">
			<h6>Nº Codificados</h6>
			<h5>{{ $crossing->codify }}</h5>
		</div>
	</div>
    <div class="col-lg-3">
        <div class="item">
            <h6>Mercado Objetivo</h6>
            <p>{{ $crossing->market->name }}</p>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="item">
            <h6>Objetivo del cruce</h6>
            <p>{{ $crossing->observation }}</p>
        </div>
    </div>
</div>


<hr>
<br>
<div class="row">
    <div class="col-lg-6">
        <fieldset>
            <legend>Resumen Características</legend>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>AVG</th>
                        <th>Nº</th>
                        <th>STD</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($typesfeatures as $t)
                        <tr>
                            <td colspan="6"><b>{{ $t->name }} / {{ $t->namees }}</b></td>
                        </tr>
                        @foreach($t->features as $f)
                            
                            <!--  Función para formulas de numeros - promedio - desviación -->
                            @include('crossings.show.summary',['feature' => $f,'tree' => $tree])     
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </fieldset>
    </div>
    <div class="col-lg-6">
        <fieldset>
            <legend>Árbol Geneológico</legend>
            <div class="hv-container">
                <div class="hv-wrapper">

                    <!-- Key component -->
                    <div class="hv-item">

                        <div class="hv-item-parent">

                            <div class="hv-item-child">
                                <p class="simple-card">
                                    <a href="<?= ($crossing->mother->crossing == 1)? "/crossings/".$crossing->mother->id:"/varities/".$crossing->mother->id;?>">
                                        {{ $crossing->motherName() }}    
                                    </a>
                                </p>
                            </div>

                            <div class="hv-item-child">
                                <p class="simple-card">
                                    <a href="<?= ($crossing->father->crossing == 1)? "/crossings/".$crossing->father->id:"/varities/".$crossing->father->id;?>">
                                        {{ $crossing->fatherName() }}  
                                    </a>
                                </p>
                            </div>

                        </div>

                        <div class="hv-item-children">

                            <!--<div class="hv-item-child">
                                <div class="hv-item">

                                    <div class="hv-item-parent">
                                        <p class="simple-card"> Parent </p>
                                    </div>

                                    <div class="hv-item-children">

                                        <div class="hv-item-child">
                                            <p class="simple-card"> Child 1 </p>
                                        </div>

                                    </div>

                                </div>
                            </div>-->

                            @foreach($tree as $t)
                                <div class="hv-item-child">
                                    <p class="simple-card <?= ($t->id == $crossing->id)? 'same':'';?>">
                                        <a href="/crossings/{{ $t->id }}">{{ $t->code }}</a>
                                    </p>
                                </div>
                            @endforeach

                        </div>

                    </div>

                </div>
            </div>
        </fieldset>
    </div>
</div>


<script>
    $(document).ready(function(){
        $("#FormAddPhoto").validate({
            rules: {
                file: {
                  required: true,
                  extension: "jpg|jpeg|png"
                }
            },
            messages: {
                file: {
                  extension: "Debe ingresar sólo multimedias con formato: jpg, png."
                }
            }
        })
    })
</script>