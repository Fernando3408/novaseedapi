
<div class="m-form__group form-group">
	<div class="m-checkbox-inline">
		@if($feature->type == 1)

			<div class="m-radio-inline" style="margin-top: 8px">
				
				@if(!is_numeric(@$values[0]->value))
					<label class="m-radio type-1">
						<input type="checkbox" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}][0]" value="0">0
						<span></span>
					</label>
					@foreach($values as $k => $v)
						<label class="m-radio type-1">
							<input type="checkbox" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}][{{@$v->value}}]" value="{{ @$v->value }}">{{ @$v->value }}
							<span></span>
						</label>
					@endforeach
				@else


				<!-- PECTOBACTERIUM - BRUISING -->
				@if($feature->id == 71 || $feature->id == 125 || $feature->id == 80)
					@for ($i = 0; $i <= 100; $i += 10) 
						<label class="m-radio type-2">
							<input type="checkbox" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}][{{$i}}]" value="{{ $i }}"> {{ $i }} {{ @$v->name }}
							<span></span>
						</label>
					@endfor
				@else

					@if($feature->id == 46) 
						<label class="m-radio type-3">
							<input type="checkbox" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}][0]" value="0">0
							<span></span>
						</label>
						@foreach($values as $k => $v)
							<label class="m-radio type-3">
								<input type="checkbox" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}][{{ @$v->value}}]" value="{{ @$v->value }}">{{ @$v->name }}
								<span></span>
							</label>
						@endforeach

					@elseif($values->first()->value < 3) 
						<label class="m-radio type-3">
							<input type="checkbox" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}][0]" value="0">0
							<span></span>
						</label>
						@foreach($values as $k => $v)
							<label class="m-radio type-3">
								<input type="checkbox" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}][{{ @$v->value}}]" value="{{ @$v->value }}">{{ @$v->name }}
								<span></span>
							</label>
						@endforeach
					
					@else
						<label class="m-radio type-4">
							<input type="checkbox" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}][0]" value="0"> 0 {{ @$v->name }}
							<span></span>
						</label>
						@for ($i = 3; $i <= 9; $i++) 
							<label class="m-radio type-4">
								<input type="checkbox" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}][{{$i}}]" value="{{ $i }}"> {{ $i }} {{ @$v->name }}
								<span></span>
							</label>
						@endfor
					@endif
				@endif
			@endif
			</div>
			<ul style="padding-left: 0px;">
				@foreach($values as $v)
					<li style="float:left;list-style: none;margin-right: 20px;">{{ $v->value }} - {{ $v->name }}</li>
				@endforeach		
			</ul>
		@endif
		@if($feature->type == 2)
			<div class="m-radio-inline">
				<label class="m-radio">
					<input checked="checked" attr-name="{{ $feature->name }}" type="radio" name="data[{{ $feature->id }}]['si']" value="1"> Si
					<span></span>
				</label>
				<label class="m-radio">
					<input type="radio" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}]['no']" value="0"> No
					<span></span>
				</label>
			</div>
		@endif
		@if($feature->type == 3)
		
			<div class="form-group">
	        	
				<select style="margin-right: 30px;" name="operator[{{ $feature->id }}]">
	        		<option value="=">==</option>
	        		<option value="!=">!=</option>
	        		<option value=">">></option>
	        		<option value="<"><</option>
	        		<option value="-">[value1 ; value2]</option>
	        	</select>
				<input type="text" class="form-control required special-width" attr-name="{{ $feature->name }}" name="data[{{ $feature->id }}]" style="width:200px" placeholder="Ingrese valor">
				@if($feature->id == 16 || $feature->id == 18)
					<i class="fa fa-info-circle" style="color: #0404ff94; font-size: 25px;cursor:pointer;margin-left: 20px;" onclick="showColors()"></i>
				@endif
			</div>
		@endif
	</div>
</div>