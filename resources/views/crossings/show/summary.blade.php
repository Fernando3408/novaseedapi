@if($feature->type == 1)
	<?php 
		$varities = array();
		foreach($tree as $t){
			array_push($varities, $t->id);
		}
		$characteristics = App\Models\Characteristic::whereIn('varity_id',$varities)->where('feature_id',$feature->id)->get();
		$sum = 0;
		$nums = array();
		foreach($characteristics as $c){
			array_push($nums, $c->value);
		}
		$sum=0;
		for($i=0;$i<count($nums);$i++){
			$sum+=$nums[$i];
		}
		if($sum != 0){
			$media = $sum/count($nums);
			$sum2=0;
			for($i=0;$i<count($nums);$i++){
				$sum2+=($nums[$i]-$media)*($nums[$i]-$media);
			}
			$vari = $sum2/count($nums);
			$sq = round(sqrt($vari),2);
		}else{
			$sq = 0;
		}
		
	?>
	<tr>
	    <td>
	        @if($feature->name != $feature->namees)
	            {{ $feature->name }} / {{ $feature->namees }} 
	        @else
	            {{ $feature->namees }} 
	        @endif
	    </td>
	    <td>
	    	{{ ($sum == 0)? 0:round($sum/count($nums),2) }}
	    </td>
	    <td>
	    	{{ count($nums) }}
	    </td>
	    <td>{{ $sq }}</td>
	</tr>
@endif