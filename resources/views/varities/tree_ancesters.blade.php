<?php 
	$t = App\Models\Varity::withTrashed()->find($t);
	if(!empty($t)){
		$childrens = App\Models\Varity::withTrashed()->where('father_id',$t->id)->orWhere('mother_id',$t->id)->where('name','!=',"")->get(); 
	}else{
		$childrens = [];
	}
?>

@if(!empty($childrens))
		<div class="hv-item-child">
	        <div class="hv-item">
	            <div class="hv-item-parent">
	                <p class="simple-card <?= ($t->id == $crossing->id)? 'same':'';?>">
				        <a href="/varities/{{ $t->id }}">{!! $t->code ? $t->code : $t->name !!}</a>
				    </p>
	            </div>
	            <div class="hv-item-children">
	              	@foreach($childrens as $c) 
						<div class="hv-item-child">
		                    <p class="simple-card"> {!! $c->code ? $c->code : $c->name!!}</p>
		                </div>
	             	@endforeach 
	            </div>
	        </div>
	    </div>
	<!--div class="hv-item-child">
	    <p class="simple-card <?= ($t->id == $crossing->id)? 'same':'';?>">
	        <a href="/varities/{{ $t->id }}">{{ $t->code ? $t->code : $t->name}}</a>
	    </p>
	</div-->
@endif
