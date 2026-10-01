<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mapout;
use App\Models\Varity;
use App\Models\Position;
use App\Models\Market;
use App\GreenHouse;

class GreenHouseController extends Controller
{

    public function index()
    {
        $greenhouse = GreenHouse::All();
        return View('greenhouse.index',compact('greenhouse'));
    }

     public function store(Request $request)
    {

        $id = GreenHouse::create($request->all());

        $b = Mapout::create([
            "lines" => 20,
            "year" => $id->name,
            "step" => 0,
            "greenhouse_id" => $id->id
        ]);


        return back()->with('success','Invernadero creado correctamente.');
    }

    public function update_greenhouse(Request $request){
        $greenhouse = GreenHouse::find($request->id);
        $greenhouse->name = $request->name_to_edit;
        $greenhouse->save();

        return back()->with('success','Invernadero editado corectamente.');
    }

    public function destroy_greenhouse(Request $request)
    {

        GreenHouse::find($_POST['to_delete'])->delete();
        Mapout::where("greenhouse_id", $_POST['to_delete'])->delete();

        return back()->with('success','Invernadero eliminado correctamente.');
    }

    public function asociate_crossing($id){
        
        $array = [];
        $data_chart = [];
        $greenhouse = GreenHouse::find($id);

        $asociate_crossing = Varity::where('greenhouse_id',$id)->orderBy("name",'desc')->get();

        foreach ($asociate_crossing as $key => $value) {

            if(!isset($array[$value->mother_id])){
                $array[$value->mother_id] = [
                    "age" => @$value->motherName(),
                    "male" => 1,
                    "female" => 0,
                    "total" => 1,
                ];
            }else{
                $array[$value->mother_id]["male"] = $array[$value->mother_id]['male']+1;
                $array[$value->mother_id]["total"] = $array[$value->mother_id]['male']+$array[$value->mother_id]["female"];
            }

            if(!isset($array[$value->father_id])){
                $array[$value->father_id] = [
                    "age" => @$value->fatherName(),
                    "male" => 0,
                    "female" => 1,
                    "total" => 1,
                ];
            }else{
                $array[$value->father_id]["female"] = $array[$value->father_id]['female']+1;
                $array[$value->mother_id]["total"] = $array[$value->mother_id]['male']+$array[$value->mother_id]["female"];
            }
        }

        foreach ($array as $k => $v) {
            array_push($data_chart, $v);
        }

        return View('greenhouse.crossings',compact('greenhouse','data_chart'));
    }

    public function export(){
        $data_chart = json_decode($_GET['data'], true);
        return View('greenhouse.elements.export',compact('data_chart'));
    }

    public function get_data_chart(Request $request){
        $array = [];
        $data_chart = [];
        $asociate_crossing = Varity::where('greenhouse_id',$request->id)->whereBetween('updated_at', [$request->desde, $request->hasta])->get();

        foreach ($asociate_crossing as $key => $value) {

            if(!isset($array[$value->mother_id])){
                $array[$value->mother_id] = [
                    "age" => @$value->motherName(),
                    "male" => 1,
                    "female" => 0,
                    "total" => 1,
                ];
            }else{
                $array[$value->mother_id]["male"] = $array[$value->mother_id]['male']+1;
                $array[$value->mother_id]["total"] = $array[$value->mother_id]['male']+$array[$value->mother_id]["female"];
            }

            if(!isset($array[$value->father_id])){
                $array[$value->father_id] = [
                    "age" => @$value->fatherName(),
                    "male" => 0,
                    "female" => 1,
                    "total" => 1,
                ];
            }else{
                $array[$value->father_id]["female"] = $array[$value->father_id]['female']+1;
                $array[$value->mother_id]["total"] = $array[$value->mother_id]['male']+$array[$value->mother_id]["female"];
            }
        }

        foreach ($array as $k => $v) {
            array_push($data_chart, $v);
        }


        return json_encode($data_chart);
    }

    public function crossings_add(){

        $crossings = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("greenhouse_id",0)->where('created_at','>', '2021-01-01 00:00:00')->get();

        return View('greenhouse.elements.crossings_add',compact('crossings'));
    }

    public function crossings_added($id){
        $asociate_crossing = Varity::where('greenhouse_id',$id)->get();
        return View('greenhouse.elements.crossings_added',compact('asociate_crossing'));
    }

    public function store_crossings(Request $request){
        if($request->arr_crossings){
            foreach ($request->arr_crossings as $key => $value) {
                $id = $value['IDVARITY'];
                $model = Varity::find($id);
                $model->greenhouse_id = $request->greenhouse_id;
                $model->save();
            }
            return json_encode(true);

        }else{
            return json_encode(false);
        }
    }

    public function delete_crossings(Request $request){
        if($request->arr_crossings){
            foreach ($request->arr_crossings as $key => $value) {
                $id = $value['IDVARITY'];
                $model = Varity::find($id);
                $model->greenhouse_id = 0;
                $model->save();
            }
            return json_encode(true);

        }else{
            return json_encode(false);
        }
    }

    public function show($id){
        $mapout = Mapout::where("greenhouse_id", $id)->first();
        $crossings = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("status", 0)->where("greenhouse_id", $id)->get();
        $crossings_ready = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("status", 1)->where("greenhouse_id", $id)->get();


        #MERCADO OBJETIVO BAYAS
        $cross_order_market = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("greenhouse_id", $id)->get()->groupBy('market_id');
        $array_values_total = [];
        $array_conteo_mecado = [];
        $array_count_by_market = [];

        foreach ($cross_order_market as $key => $value) {   
            
            $total  = $value->sum('berries_objective');
            $avance = $value->sum('berries');
            $market = $key == "" ? @Market::find(17)->name : @Market::find($key)->name;

            $res_av = @round($avance*100/$total, 2);
            is_nan($res_av) ? $res_av = 0 : "";

            $res = [
                "total"  => $total,
                "market" => $market,
                "avance" => $avance,
                "avance_por" => $res_av
            ];

            $res2_av = @round($avance*100/$total, 2);
            is_nan($res2_av) ? $res2_av = 0 : "";

            $res2 = [
                "total"  => count($value),
                "market" => $market,
                "avance" => $avance,
                "avance_por" => $res2_av
            ];

            $array_values_total[] = $res;   
            $array_conteo_mecado[] = $res2;
        }

        $total_crossings = $crossings_ready->count() + $crossings->count();
        $avance_greenhouse = 0;

        if($total_crossings > 0){
            $avance_greenhouse = intval($crossings_ready->count()*100 / $total_crossings);
        }
        
        $cross_ready_by_market = $crossings_ready->groupBy('market_id');

        $avance_berries = $crossings_ready->sum('berries');
        $berries_objective = $crossings->sum('berries_objective') + $crossings_ready->sum('berries_objective');
        $porcent_ = @round($avance_berries*100/$berries_objective, 2);
        is_nan($porcent_) ? $porcent_ = 0: "";
        $porcent = $porcent_;

        $array_info_berries = [

            "avance_berries" => $avance_berries,
            "berries_objective" => $berries_objective,
            "porcent" => $porcent

        ];

        foreach ($cross_order_market as $key => $value) {   
            $market = $key == "" ? @Market::find(17)->name : @Market::find($key)->name;
            $count_ready = 0;
            $count_total = 0;

            foreach ($value as $k => $v) {
                $count_total++;
                $v->status == 1 ? $count_ready++ : "" ;
            }

            $res = [
                "total" => $count_total,
                "crossings_ok"  => $count_ready,
                "market" => $market,
            ];

            $array_count_by_market[] = $res;   
        }


        return View('greenhouse.show',compact('mapout','crossings', 'crossings_ready','array_values_total', 'array_conteo_mecado','array_count_by_market','avance_greenhouse','cross_ready_by_market','array_info_berries','id'));
    }

    public function crossings_list($id){

        $crossings = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("status", 0)->where('greenhouse_id',$id)->get();
        $mapout = Mapout::where('greenhouse_id', $id)->first();

        return View('greenhouse.elements.crossings_list',compact('crossings', 'mapout'));
    }

    public function crossings_ok($id){

        $crossings_ready = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("status", 1)->where('greenhouse_id',$id)->get();

        return View('greenhouse.elements.crossings_ok',compact('crossings_ready'));
    }

    public function show_mobile(Mapout $mapout){
        $mapout = Mapout::find(14);
        $positions = array();
        $enabledCodify = true;
        
        if($mapout->positions->count() > 0){
            foreach($mapout->positions as $p){
                $positions[] = $p;
                if($p->step == 0){
                    $enabledCodify = false;
                }
            }
        }else{
            $enabledCodify = false;
        }

        if($mapout->step != 0){
            $enabledCodify = false;
        }

        $crossings = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("status", 0)->get();
        $crossings_ready = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("status", 1)->get();
       
        return View('greenhouse.show_mobile',compact('mapout','crossings','positions','enabledCodify', 'crossings_ready'));

    }

    public function draw_greenhouse($id){

        $mapout = Mapout::where('greenhouse_id', $id)->first();
        $positions = array();
        $enabledCodify = true;
        
        if(@count($mapout->positions) > 0){
            foreach($mapout->positions as $p){
                $positions[] = $p;
                if($p->step == 0){
                    $enabledCodify = false;
                }
            }
        }else{
            $enabledCodify = false;
        }

        if(@$mapout->step != 0){
            $enabledCodify = false;
        }

        $crossings = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("status", 0)->get();
        $crossings_ready = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("status", 1)->get();
        return View('greenhouse.elements.greenhouse',compact('mapout','crossings','positions','enabledCodify', 'crossings_ready'));

    }

     public function update(Request $request){
        
        $crossing = Varity::find($request->record_id);
        $crossing->status = $request->record_status;

        if($crossing->save()){
            return json_encode(true);    
        }else{
            return json_encode(false);
        }

        
        //return back()->with('success','Cruce actualizado corectamente.');
    }

     public function addPositions(Request $request){
        if (strpos($request->positionY, ',') !== false) {
            $d = explode(",", $request->positionY);
            foreach ($d as $key => $value) {
                $request->merge(['positionY' => $value]);
                Position::create($request->all());
            }

            dd($request->all());
        } else {
            $position = Position::create($request->all());
        }

        return json_encode(true);
        return back()
                        ->with('success','Individuo agregado correctamente.');
    }

    public function destroy($id)
    {   
        
        $varity = Varity::find($id);
        $varity->delete();

        return back()->with('success','Cruce eliminado correctamente.');
    }

    public function updatePosition(Request $request){
        $position = Position::findOrFail($request->input('id'));
        $position->observation = $request->input('observation');
        //el count se debe cambiar ya que ahora será definido por:
        //red, redmeat, blue, yellow , elongated
        $position->red = $request->input('red');
        $position->blue = $request->input('blue');
        $position->yellow = $request->input('yellow');
        $position->elongated = 0;
        $position->step = 1;

        if($request->input('flowering') != 0){
            Position::whereVarity_id($position->varity_id)->update([
                    'flowering' => $request->input('flowering')
                                    ]);
        }

        $position->flowering = $request->input('flowering');
        $position->polem_volume = $request->input('polem_volume');
        $position->steril_mom = $request->input('steril_mom');
        $position->save();

        return json_encode(true);

        return back()
            ->with('success','Individuo actualizado correctamente.');
    }

     public function deleteCodify($id){
        $id.=".00";
        Position::where('positionY',$id)->delete();

        return json_encode(true);
        return back()
        ->with('success','Posición eliminada correctamente.');
        
    }

    public function switchFathers($id,$f_id,$m_id){
        $crossing = Varity::find($id);
        $crossing->mother_id = $f_id;
        $crossing->father_id = $m_id;
        $crossing->save();

        $crossings = $this->get_crossings();

        return json_encode($crossings);
        
    }

    public function savePolen($f_id,$m_id,$r){

        $id = $f_id > 0 ? $f_id : $m_id;

        $crossing = Varity::find($id);
        $crossing->polem_save = $r;
        $crossing->save();

        $crossings = $this->get_crossings();

        return json_encode($crossings);
        
    }

    public function get_crossings(){
        $crossings = Varity::where('crossing',1)->where('typecrossing','!=',2)->where("status", 0)->get();

        foreach ($crossings as $key => $value) {
            $p1 = 0;
            $p2 = 0;            $value->mothername = @$value->motherName();
            $value->fathername = @$value->fatherName();
            $value->marketname = @$value->market->name;
            $model_position_m = Position::where('varity_id', $value->mother_id)->where('mapout_id',14);
            $model_position_f = Position::where('varity_id', $value->father_id)->where('mapout_id',14);

            $value->values_encoded_mother = json_encode($model_position_m->where('red','>','0')->get());

            $class_mother = "fa fa-times-circle";
            $class_father = "fa fa-times-circle";

            foreach ($model_position_m->get() as $k => $v) {
                if ($v->red > 0 ) {
                    $class_mother = "fa fa-check-circle show-tooltip";
                    $p1 = 1;
                }
            }

            $value->values_encoded_father = json_encode($model_position_f->where('red','>','0')->get());

            foreach ($model_position_f->get() as $key => $value) {
                if ($value->red > 0 ) {
                    $class_father = "fa fa-check-circle show-tooltip";
                    $id = $value->id;
                    $p2 = 1;
                }
            }

            if($p2 > 0 && $p1 > 0 ){
                $value->btn_ok = true;
            }

            $value->class_mother = $class_mother;
            $value->class_father = $class_father;
        }

        return $crossings;
    }

    public function save_berries($id, $nro){

        $crossing = Varity::find($id);
        $crossing->berries = $nro;
        $crossing->save();

        return json_encode(true);
    }

    public function save_glasses($id, $nro){

        $crossing = Varity::find($id);
        $crossing->glasses = $nro;
        $crossing->save();

        return json_encode(true);
    }

     public function save_potatoes($id, $nro){

        $crossing = Varity::find($id);
        $crossing->potatoes = $nro;
        $crossing->save();

        return json_encode(true);
    }

}
