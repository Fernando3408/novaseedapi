<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mapout;
use App\Models\Varity;
use App\Models\Position;
use App\GreenHouse;

class MapoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mapouts = Mapout::orderBy('id','desc')->get();

        return View('mapouts.index',compact('mapouts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $greenhouse = Greenhouse::pluck('name','id');
        return View('mapouts.create', compact('greenhouse'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mapout = Mapout::create($request->all());

        return back()->with('success','Mapeo creado correctamente.');
    }

     /**
     * Vista de editar tipo de motor
     */
    public function edit($id)
    {
        $mapout = Mapout::find($id);
        $greenhouse = Greenhouse::pluck('name','id');
        return view('mapouts.edit',compact('mapout', 'greenhouse'));
    }

    /**
     * Actualiza un tipo de motor en base de datos
     */
    public function update(Request $request, $id)
    {

        Mapout::find($id)->update($request->all());

        return back()->with('success','Mapeo editado corectamente.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Mapout $mapout)
    {
        $positions = array();
        $enabledCodify = true;
        if($mapout->positions->count() > 0){
            foreach($mapout->positions as $p){
                $positions[$p->line][] = $p;
                if($p->step == 0){
                    $enabledCodify = true;
                }
            }
        }else{
            $enabledCodify = false;
        }

        if($mapout->step != 0){
            $enabledCodify = false;
        }


        $crossings = Varity::where('crossing',1)->where('greenhouse_id', @$mapout->greenhouse_id)->where('typecrossing','!=',2)->get();

        return View('mapouts.show',compact('mapout','crossings','positions','enabledCodify'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mapout $mapout)
    {

        //Se cambio eliminando el mapout_id de variedad y solo relacionando posición con variedad asociada.
        //$varities = Varity::where('mapout_id',$mapout->id)->update(array('mapout_id' => null,'position' => null));

        $varities = Position::where('mapout_id',$mapout->id)->delete();
        
        Mapout::find($mapout->id)->delete();


        return redirect()->route('mapouts.index')
                        ->with('success','Mapeo elimado correctamente.');
    }

    public function addPositions(Request $request){
        $position = Position::create($request->all());

        return redirect()->route('mapouts.show',$request->input('mapout_id'))
                        ->with('success','Individuo agregado correctamente.');
    }

    public function updatePosition(Request $request){
        $position = Position::findOrFail($request->input('id'));
        $mapout = Mapout::find($position->mapout_id);

        $new_sum = $request->input('red')+$request->input('blue')+$request->input('redmeat')+$request->input('yellow')+$request->input('elongated');
        $sum_pos = $position->red + $position->redmeat + $position->blue + $position->yellow + $position->elongated;

        if($new_sum > $sum_pos && $position->step == 1){
            $limit = $new_sum - $sum_pos;
            $correlativo = Varity::where('crossing',1)->where('typecrossing',2)->count() + 1;

            for ($i = 0; $i < $limit; $i++) {

                if($correlativo < 10){
                    $count = "00".$correlativo;
                }else if($correlativo < 100){
                    $count = "0".$correlativo;
                }else{
                    $count = $correlativo;
                }

                $crossing = new Varity();
                $crossing->mother_id = $position->crossing->mother_id;
                $crossing->father_id = $position->crossing->father_id;
                $crossing->year = $position->crossing->year;
                $crossing->marketold_id = $position->crossing->market_id;
                $crossing->market_id = $position->crossing->market_id;
                $crossing->crossing = 1;
                $crossing->code = "NS".$mapout->year."-".$count;
                $crossing->typecrossing = 2;
                $crossing->parent_id = $position->crossing->id;
                $crossing->mapout_id = $position->mapout_id;
                $crossing->berries = 0;
                $correlativo++;
                $crossing->save();
            }
        }

        $position->observation = $request->input('observation');
        //el count se debe cambiar ya que ahora será definido por:
        //red, redmeat, blue, yellow , elongated
        $position->red = $request->input('red');
        $position->redmeat = $request->input('redmeat');
        $position->blue = $request->input('blue');
        $position->yellow = $request->input('yellow');
        $position->elongated = $request->input('elongated');
        $position->step = 1;
        $position->save();




        return redirect()->route('mapouts.show',$position->mapout_id)
            ->with('success','Individuo actualizado correctamente.');
    }


    public function generateCodify($id){
        $mapout = Mapout::findOrFail($id);

        $correlativo = Varity::where('crossing',1)->where('typecrossing',2)->count() + 1;

        $save = true;

        if(!empty($mapout->positions->count() > 0)){
            foreach($mapout->positions as $pos){
                $limit = $pos->red + $pos->redmeat + $pos->blue + $pos->yellow + $pos->elongated;
                //$berries = explode('x', $pos->count)[0];
                $berries = 0;
                //$limit = explode('x', $pos->count)[1];
                for ($i = 0; $i < $limit; $i++) {
                    if($correlativo < 10){
                        $count = "00".$correlativo;
                    }else if($correlativo < 100){
                        $count = "0".$correlativo;
                    }else{
                        $count = $correlativo;
                    }
                    $crossing = new Varity();
                    $crossing->mother_id = $pos->crossing->mother_id;
                    $crossing->father_id = $pos->crossing->father_id;
                    $crossing->year = $pos->crossing->year;
                    $crossing->marketold_id = $pos->crossing->market_id;
                    $crossing->market_id = $pos->crossing->market_id;
                    $crossing->crossing = 1;
                    $crossing->code = "NS".$mapout->year."-".$count;
                    $crossing->typecrossing = 2;
                    $crossing->parent_id = $pos->crossing->id;
                    $crossing->mapout_id = $mapout->id;
                    $crossing->berries = $berries;
                    $correlativo++;
                    if(!$crossing->save()) $save = false; 
                }
            }
            if($save){
                $mapout->step = 1;
                $mapout->save();
            }
        }

        return redirect()->route('mapouts.show',$mapout->id)
            ->with('success','Codificación realizada correctamente.');
    }

    public function deleteCodify($id){
        $mapout = Mapout::findOrFail($id);

        if(isset($_GET['varity_id'])){
            $m = Position::find($_GET['varity_id']);
            $m->delete();
            return redirect()->route('mapouts.show',$mapout->id)
                ->with('success','Codificado eliminado correctamente.'); 
        }else{
            if(!empty($mapout)){
                Varity::where('mapout_id',$mapout->id)->delete();
                $mapout->step = 0;
                $mapout->save();
                return redirect()->route('mapouts.show',$mapout->id)
                ->with('success','Codificación anulada correctamente.');        
            }
        }
    }

}
