
<?php 
	$childrens = App\Models\Varity::withTrashed()->where('father_id',$t->id)->orWhere('mother_id',$t->id)->whereNotNull('name')->get();
?>

@if($childrens->count() > 0)
	<div class="hv-item-child">
        <div class="hv-item">
            <div class="hv-item-parent">
                <p class="simple-card <?= ($t->id == $crossing->id)? 'same':'';?>">
			        <a href="/varities/{{ $t->id }}">{!! $t->code ? $t->code : $t->name!!}</a>
			    </p>
            </div>
            <div class="hv-item-children">
              	@foreach($childrens as $c) 
					@if(!empty($c->name))
						<div class="hv-item-child">
		                    <p class="simple-card"> 
			        			<a href="/varities/{{ $c->id }}">{!! $c->code ? $c->code : $c->name !!}</a>
		                    </p>
		                </div>
					@endif
             	@endforeach 
            </div>
        </div>
    </div>
@else
	@if(!empty($t->name))
		<div class="hv-item-child">
		    <p class="simple-card <?= ($t->id == $crossing->id)? 'same':'';?>">
				<a href="/varities/{{ $t->id }}">{!! $t->name ? $t->name : $t->code!!}</a>
		    </p>
		</div>
	@endif
@endif
