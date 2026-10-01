<?php 
	$observations = array();
	if(!empty($crossing->observations)){
		foreach($crossing->observations as $o){
			$observations[$o->year] = $o->text;
		}
	}
?>
<form id="formObservations">
	<input type="hidden" name="crossing_id" value="<?= $crossing->id;?>">
    <div class="row">
    	<div class="col-lg-6">
    		<div class="form-group">
	    		<label for="observations[0][text]">Observación Año 0</label>
	    		<textarea name="observations[0][text]" style="height:100px" cols="30" rows="10" class="form-control"><?= (isset($observations[0]))?$observations[0]:"";?>
	    		</textarea>
	    	</div>
    	</div>
    	<div class="col-lg-6">
			<div class="form-group">
	    		<label for="observations[1][text]">Observación Año 1</label>
	    		<textarea name="observations[1][text]" style="height:100px" cols="30" rows="10" class="form-control"><?= (isset($observations[1]))?$observations[1]:"";?></textarea>
	    	</div>
    	</div>
    	<div class="col-lg-6">
    		<div class="form-group">
	    		<label for="observations[2][text]">Observación Año 2</label>
	    		<textarea name="observations[2][text]" style="height:100px" cols="30" rows="10" class="form-control"><?= (isset($observations[2]))?$observations[2]:"";?></textarea>
	    	</div>
    	</div>
    	<div class="col-lg-6">
    		 <div class="form-group">
	    		<label for="observations[3][text]">Observación Año 3</label>
	    		<textarea name="observations[3][text]" style="height:100px" cols="30" rows="10" class="form-control"><?= (isset($observations[3]))?$observations[3]:"";?></textarea>
	    	</div>
	        
    	</div>
    	<div class="col-lg-6">
    		<div class="form-group">
	    		<label for="observations[4][text]">Observación Año 4</label>
	    		<textarea name="observations[4][text]" style="height:100px" cols="30" rows="10" class="form-control"><?= (isset($observations[4]))?$observations[4]:"";?></textarea>
	    	</div>
    	</div>
    </div>
    <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Guardar Observaciones</button>
</form>


<script>
	$("#formObservations").submit(function(){
		$.ajax({
			url: '/crossings/saveObservations',
			type: 'GET',
			dataType: 'json',
			data: $("#formObservations").serialize(),
		}).done(function(data) {
			if(data){
				Swal({
                  title: 'Observaciones guardadas correctamente.',
                  text: "",
                  type: 'success',
                  showCancelButton: false,
                  showConfirmButton: false,
                })
			}else{
				Swal({
                  title: 'Error, no se pudo guardar las observaciones.',
                  text: "",
                  type: 'error',
                  showCancelButton: false,
                  showConfirmButton: false,
                }) 
			}
		})
		
		return false;
	})
</script>