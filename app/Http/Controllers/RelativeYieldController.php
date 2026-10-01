<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Varity;
use App\Models\Market;
use App\Relativeyield;
use App\Relativeyieldvarities;
use App\Avg_characteristic;

class RelativeYieldController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $markets = Market::pluck('name','id');
        $data = Relativeyield::all();
        
        return View('relativeyield.index',compact('markets','data'));
    }

    public function calc(Request $request){
    	$ids = explode(",", $request->ids);

    	$sum = 0;
    	$std = 0;


    	if(!empty($ids)){
    		$c = count($ids);
    		$sum = Avg_characteristic::select('PROMEDIO')->whereIn('varity_id',$ids)->where('feature_id',23)->where('locality',1)->sum('PROMEDIO');
    		$std = round($sum/$c,2);

    	}

    	return json_encode($std);

    }

    public function create()
    {
        $markets = Market::pluck('name','id');
        $varities = Varity::select('id','name')->where('name', '!=', '')->get();
        
        return View('relativeyield.create',compact('markets','varities'));
    }

     public function store(Request $request)
    {
    	
    	$relative = [
    		"market_id" => $request->market_id,
    		"name" => $request->name,
    		"std" => $request->std
    	];

        $m = Relativeyield::create($relative);
        $params = [];
        
        foreach ($request->varities as $value) {
        	$params = [
        		"relative_yield_id" => $m->id,
        		"varity_id" => $value
        	];

        	Relativeyieldvarities::create($params);
        }

        return back()->with('success','Estandard creado corectamente.');
    }

    /**
     * Vista para editar usuario
     */
    public function edit($id)
    {
        $royalty = Royalties::find($id);
        $farmer_id = $royalty->farmer_id;

        return view('relativeyield.edit',compact('royalty','farmer_id'));
    }

    /**
     * Actualiza usuario en base de datos
     */
    public function update(Request $request, $id)
    {

        Royalties::find($id)->update($request->all());

        return back()->with('success','Estandard editado corectamente.');
    }

    public function show($id = null)
    {   
        $royalties = Royalties::whereFarmerId($id)->get();
        $farmer = Farmer::find($id);

        return View('relativeyield.show', compact('royalties','farmer'));
    }

    /**
     * Elimina usuario
     */
    public function destroy($id)
    {	
    	
        Relativeyield::find($id)->delete();

        Relativeyieldvarities::where('relative_yield_id', $id)->delete();

     
        return back()->with('success','Estandard  eliminado correctamente.');
    }

}
