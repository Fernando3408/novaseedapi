<?php $childrens = App\Models\Varity::where('parent_id','!=',null)->where('father_id',$t->id)->orWhere('mother_id',$t->id)->get(); ?>
@if($childrens->count() > 0)
	<div class="hv-item-child">
        <div class="hv-item">
            <div class="hv-item-parent">
                <p class="simple-card <?= ($t->id == $crossing->id)? 'same':'';?>">
			        <a href="/crossings/{{ $t->id }}">{{ $t->code ? $t->code : $t->name}}</a>
			    </p>
            </div>
            <div class="hv-item-children">
              	@foreach($childrens as $c) 
					<div class="hv-item-child">
						<p class="simple-card">
			        		<a href="/crossings/{{ $c->id }}">{{ $c->code ? $c->code : $c->name}}</a>
					    </p>
	                </div>
             	@endforeach 
            </div>
        </div>
    </div>
@else
	<div class="hv-item-child">
	    <p class="simple-card <?= ($t->id == $crossing->id)? 'same':'';?>">
			<a href="/crossings/{{ $t->id }}">{{ $t->code ? $t->code : $t->name}}</a>
	    </p>
	</div>
@endif