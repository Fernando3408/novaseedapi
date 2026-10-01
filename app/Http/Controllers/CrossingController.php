<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Varity;
use App\Models\Mapout;
use App\Models\Feature;
use App\Models\Market;
use App\Models\Characteristic;
use App\Models\Multimedia;
use App\Models\Observation;
use App\Models\Typefeature;
use App\Models\Community;
use App\Models\Zonaltrial;
use App\Models\Binnacle;
use App\Models\Blocksvarity;
use App\Models\Showfarm;
use App\Models\Position;
use App\Models\Block;
use Intervention\Image\Facades\Image;
use App\Colors;
use Auth;


class CrossingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //para saber si el padre o madre es una variedad o viene de un cruce, se debe preguntar por crossing == 1 (cruce) por ende muestra code y si es 0 muestra el nombre de la variedad.
        #Solo se muestran los que tienen berries
        $crossings = Varity::where('crossing',1)->where('typecrossing','!=',2)->where('berries','>', 0)->get();
        //$coded = Varity::where('crossing',1)->where('typecrossing',2)->get();
        $coded = Varity::whereNotNull('name')->orwhereNotNull('code')->get();
        return View('crossings.index',compact('crossings','coded'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //listado de variedades o cruces
        //$va = Varity::withTrashed()->get();
        $va = Varity::all();
        $varities = array();
        foreach ($va as $v) {
            if($v->crossing){
                if(!empty($v->code)){
                    $varities[$v->id] = $v->code;
                }
            }else{
                 $varities[$v->id] = $v->name;
            }
        }
        
        $markets = Market::pluck('name','id');

        $colors = Colors::all();

        $crossings = Varity::where('greenhouse_id', 10)->get();

        return View('crossings.create',compact('varities','markets','colors', 'crossings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'typecrossing' => 1,
            'marketold_id' => $request->input('market_id')
        ]);
        Varity::create($request->all());

        return redirect()->route('crossings.index')
                        ->with('success','Cruce creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Varity  $feature
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $crossing = Varity::find($id);
    	$typesfeatures = Typefeature::All();
        foreach ($typesfeatures as $t_key => $t) {
            foreach ($t->features as $key => $f) {
                $characteristic = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',0)->first();
                $typesfeatures[$t_key]->features[$key]->valor0 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',1)->first();
                $typesfeatures[$t_key]->features[$key]->valor1 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',2)->first();
                $typesfeatures[$t_key]->features[$key]->valor2 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',3)->first();
                $typesfeatures[$t_key]->features[$key]->valor3 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',4)->first();
                $typesfeatures[$t_key]->features[$key]->valor4 = (!empty($characteristic)) ? $characteristic->value:null;
            }
        }

        $crossing->codify = Varity::where('crossing',1)->where('typecrossing',2)->where('parent_id',$crossing->id)->count();
        
        if($crossing->parent_id != null){
            $tree = Varity::where('crossing',1)->where('typecrossing',2)->where('parent_id',$crossing->parent_id)->get();
        }else{
            $tree = Varity::where('crossing',1)->where('typecrossing',2)->where('parent_id',$crossing->id)->get();
        }

        return View('crossings.show',compact('crossing','features','typesfeatures','tree'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Varity  $feature
     * @return \Illuminate\Http\Response
     */
    public function codify($id)
    {
        $markets = Market::pluck('name','id');
        $crossing = Varity::find($id);
        return View('crossings.codify',compact('crossing','markets'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function edit(Varity $crossing)
    {
        //listado de variedades o cruces
        $va = Varity::All();
        $varities = array();
        foreach ($va as $v) {
            if($v->crossing){
                if(!empty($v->code)){
                    $varities[$v->id] = $v->code;
                }
            }else{
                 $varities[$v->id] = $v->name;
            }
        }
        $markets = Market::pluck('name','id');
        return view('crossings.edit',compact('crossing','varities','markets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Varity  $feature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Varity $crossing)
    {
        if($crossing->marketold_id == null){
            $request->merge([
                'marketold_id' => $request->input('market_id')
            ]);
        }
        $crossing->update($request->all());

        

        return back()
                        ->with('success','Cruce editado corectamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $varity = Varity::find($id);
        $varity->delete();

        return redirect()->route('crossings.index')
                        ->with('success','Cruce eliminado correctamente.');
    }

    public function saveCharacteristics(Request $request){
        $status = false;
        $crossing_id = (!empty($request->input('crossing_id')))? $request->input('crossing_id'):null;
        $zonaltrial_id = (!empty($request->input('zonaltrial_id')))? $request->input('zonaltrial_id'):null;
        $block_id = (!empty($request->input('block_id')))? $request->input('block_id'):null;

        $locality = !empty($request->block_id) ? Block::find($block_id)->locality:1;

        $locality_original = $locality;

        if($request->input('onlyone') == 1){
            foreach($request->input('feature') as $year => $features){
               foreach ($features as $f) {
                    $value = trim((string)($f['value'] ?? ''));

                    $feat = Feature::find($f['feature_id']);
                    $locality = $feat->localities_viewer == 0 ? 1 : $locality_original;

                    $characteristic = Characteristic::where('varity_id', $crossing_id)
                        ->where('feature_id', $f['feature_id'])
                        ->where('year', $year)
                        ->where('locality_id', $locality)
                        ->first();

                    if($value === ''){
                        if(!empty($characteristic)){
                            $characteristic->value = '';
                            $characteristic->locality_id = $locality;
                            $characteristic->block_id = $block_id;
                            $characteristic->year = $year;
                            if(isset($f['value_repeats'])){
                                $characteristic->repeats_values = $f['value_repeats'];
                            }
                            if($characteristic->save()){
                                $status = true;
                            }
                        }else{
                            $status = true;
                        }
                        continue;
                    }

                    if(empty($characteristic)){
                        $characteristic = new Characteristic();
                        $characteristic->varity_id = $crossing_id;
                        $characteristic->zonaltrial_id = $zonaltrial_id;
                        $characteristic->block_id = $block_id;
                        $characteristic->feature_id = $f['feature_id'];
                    }

                    $characteristic->value = $f['value'];
                    $characteristic->varity_id = $crossing_id;
                    $characteristic->zonaltrial_id = $zonaltrial_id;
                    $characteristic->block_id = $block_id;
                    $characteristic->feature_id = $f['feature_id'];
                    $characteristic->locality_id = $locality;
                    if(isset($f['value_repeats'])){
                        $characteristic->repeats_values = $f['value_repeats'];
                    }
                    $characteristic->year = $year;
                    if($characteristic->save()){
                        $status = true;
                    }
               }
            }
        }else{

            if(!empty($crossing_id)){
                //Characteristic::where('varity_id',$crossing_id)->delete();
            }

            if(!empty($zonaltrial_id)){
                //Characteristic::where('zonaltrial_id',$zonaltrial_id)->delete();
            }
            
            $inserts_id = [];
            foreach($request->input('feature') as $year => $features){
               foreach ($features as $f) {
                    $value = trim((string)($f['value'] ?? ''));

                    $feat = Feature::find($f['feature_id']);
                    $locality = $feat->localities_viewer == 0 ? 1 : $locality_original;

                    $c = Characteristic::where('feature_id', $f['feature_id'])
                        ->where('varity_id', $crossing_id)
                        ->where('locality_id', $locality)
                        ->where('year', $year)
                        ->first();

                    if($value === ''){
                        if(!empty($c)){
                            $c->value = '';
                            $c->locality_id = $locality;
                            $c->block_id = $block_id;
                            $c->year = $year;
                            if(isset($f['value_repeats'])){
                                $c->repeats_values = $f['value_repeats'];
                            }
                            if($c->save()){
                                $status = true;
                            }
                        }else{
                            $status = true;
                        }
                        continue;
                    }

                    if(!empty($c)){
                        $characteristic = $c;
                    }else{
                        $characteristic = new Characteristic();
                        $characteristic->varity_id = $crossing_id;
                        $characteristic->zonaltrial_id = $zonaltrial_id;
                        $characteristic->block_id = $block_id;
                        $characteristic->feature_id = $f['feature_id'];
                    }

                    $characteristic->value = $f['value'];
                    $characteristic->locality_id = $locality;
                    $characteristic->block_id = $block_id;
                    
                    if(isset($f['value_repeats'])){
                        $characteristic->repeats_values = $f['value_repeats'];
                    }
                    $characteristic->year = $year;
                    if($characteristic->save()){
                        $temp = [
                            "id_feature" => $f['feature_id'],
                            "year" => $year,
                            "id_charact" => $characteristic->id
                        ];

                        array_push($inserts_id, $temp);

                        $status = true;
                    }
               }
            }
        }

        #Variable inblock si es que viene de la vista de ensayos
        if (isset($request->inBlock)) {
            
            return json_encode(["status" => $status, "data" => $inserts_id]);
        }

    	return redirect()->back()
                        ->with('success','Características guardadas correctamente.');
    }

    public function saveCodify(Request $request){
        $save = true;
        Varity::where('parent_id',$request->input('crossing'))->delete();
        if(!empty($request->input('data'))){
            $parent = Varity::where('id',$request->input('crossing'))->first();
            $parent->market_id = $request->input('market_id');
            $parent->save();
            foreach($request->input('data') as $item){
                $crossing = new Varity();
                $crossing->mother_id = $request->input('mother_id');
                $crossing->father_id = $request->input('father_id');
                $crossing->year = $request->input('year');
                $crossing->market_id = $request->input('market_id');
                $crossing->marketold_id = $request->input('market_id');
                $crossing->code = $item['code'];
                $crossing->crossing = 1;
                $crossing->typecrossing = 2;
                $crossing->berries = $item['berries'];
                $crossing->observation = $item['observation'];
                $crossing->parent_id = $request->input('crossing');
                if(!$crossing->save()) $save = false;
            }
        }
        if($save){
            return redirect('/crossings/codify/'.$request->input('crossing'))
                        ->with('success','Codificación guardada correctamente.');
        }
    }

    public function items(){
        $crossings = Varity::where('parent_id',$_GET['crossing_id'])->with('mother')->with('father')->with('market')->get();
        return json_encode(array('data' => $crossings));
    }

    public function finishCodify($id){
        $varity = Varity::find($id);

        $varity->typecrossing = 3;

        if($varity->save()){
            return redirect('/crossings')->with('success','Codificación Terminada.');
        }else{
            return back()->with('alert','Error, no se pudo terminar codificación.');

        }
    }

    public function generateCodify(Request $request){
        //para saber si graba bien
        $save = true;
        //va a buscar el cruce padre a través de su id
        $parent = Varity::where('id',$request->input('crossing'))->first();
        //trae ultimo correlativo
        $correlativo = Varity::where('crossing',1)->where('typecrossing',2)->count() + 1;
        //genera los codificados
        for ($i = 0; $i <  $request->input('count') ; $i++) {
            if($correlativo < 10){
                $count = "00".$correlativo;
            }else if($correlativo < 100){
                $count = "0".$correlativo;
            }else{
                $count = $correlativo;
            }
            $crossing = new Varity();
            $crossing->mother_id = $parent->mother_id;
            $crossing->father_id = $parent->father_id;
            $crossing->year = $parent->year;
            $crossing->market_id = $request->input('market');
            $crossing->crossing = 1;
            $crossing->code = "NS".$parent->year."-".$count;
            $crossing->typecrossing = 2;
            $crossing->parent_id = $parent->id;
            $correlativo++;
            if(!$crossing->save()) $save = false;        
        }
        return json_encode($save);
    }

    public function deleteCodify(Request $request){
        return json_encode(Varity::where('crossing',1)->where('typecrossing',2)->where('parent_id',$request->input('crossing'))->delete());
    }

    public function uploadPhoto(Request $request){

        $ext = explode('.', $request->file->getClientOriginalName());
        $ext = end($ext);

        if(!in_array($ext, array('jpg','jpeg','png'))){
            return redirect('crossings/'.$request->input('crossing'))->with('alert','Debe agregar sólo imágenes con formato: jpg o png.'); 
        }

        if($_FILES['file']['type'] == "image/jpeg"){
            $ext = "jpg";
        }else if($_FILES['file']['type'] == "image/png"){
            $ext = "png";
        }else{
            return redirect('crossings/'.$request->input('crossing'))->with('alert','La imagen que estás tratando de subir, tiene problemas con el formato de imagen.');
        }

        $image = Image::make($request->file('file'));

        // upload and resize using Intervention Image 
        $filename = 'multimedias/foto-'.$request->input('crossing')."_".time().'.'.$ext;

        $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })
        ->save($filename, 80);    
        
        
        if($image){
            $request->merge([
                'link' => $filename,
                'varity_id' => $request->input('crossing')
            ]);

            $multimedia = Multimedia::create($request->all());


        }

        if(!empty($multimedia)){
            return redirect()->back()->with('success','Foto agregada correctamente.');
        }
    }

    public function saveObservations(Request $request){
        $save = true;
        foreach ($request->input('observations') as $year =>  $o) {
            $observation = Observation::where('varity_id',$request->input('crossing_id'))->where('year',$year)->first();
            if(!empty($observation)){
                $observation->text = $o['text'];
            }else{
                $observation = new Observation();
                $observation->text = $o['text'];
                $observation->year = $year;
                $observation->varity_id = $request->input('crossing_id');
                
            }
            if(!$observation->save()) $save = false;
        }

        return json_encode($save);
    }

    public function zona(){
        //para saber si el padre o madre es una variedad o viene de un cruce, se debe preguntar por crossing == 1 (cruce) por ende muestra code y si es 0 muestra el nombre de la variedad.
        $crossings = Varity::where('crossing',1)->where('typecrossing','!=',2)->get();
        $zonal = Zonaltrial::all();
        return View('crossings.zona.list',compact('crossings','coded','zonal'));
    }

    public function zonacreate($id){
        $communities = Community::pluck('name','id');
        $crossings = array();
        foreach (Varity::where('crossing',1)->where('typecrossing','!=',2)->get() as $crossing) {
            $mother = ($crossing->mother->crossing == 1)? $crossing->mother->code:$crossing->mother->name;
            $father = ($crossing->father->crossing == 1)? $crossing->father->code:$crossing->father->name;
            $crossings[$crossing->id] = "T".$crossing->year."-".($crossing->year+1)." ".$mother." - ".$father;
        }
        return View('crossings.zona.create',compact('crossings','id','communities'));
    }

    public function zonastore(Request $request){
        Zonaltrial::create($request->all());

        return redirect('/crossings/zona')->with('success','Ensayo de zona creado.');
    }


    public function zonaedit($id){
        $typesfeatures = Typefeature::All();
        $zonaltrial = Zonaltrial::findOrFail($id);
        foreach ($typesfeatures as $t_key => $t) {
            foreach ($t->features as $key => $f) {
                $characteristic = Characteristic::where('zonaltrial_id',$zonaltrial->id)->where('feature_id',$f->id)->where('year',0)->first();
                $typesfeatures[$t_key]->features[$key]->valor0 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('zonaltrial_id',$zonaltrial->id)->where('feature_id',$f->id)->where('year',1)->first();
                $typesfeatures[$t_key]->features[$key]->valor1 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('zonaltrial_id',$zonaltrial->id)->where('feature_id',$f->id)->where('year',2)->first();
                $typesfeatures[$t_key]->features[$key]->valor2 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('zonaltrial_id',$zonaltrial->id)->where('feature_id',$f->id)->where('year',3)->first();
                $typesfeatures[$t_key]->features[$key]->valor3 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('zonaltrial_id',$zonaltrial->id)->where('feature_id',$f->id)->where('year',4)->first();
                $typesfeatures[$t_key]->features[$key]->valor4 = (!empty($characteristic)) ? $characteristic->value:null;
            }
        }
        return View('crossings.zona.edit',compact('zonaltrial','typesfeatures'));
    }

    public function zonashow($id){
        $typesfeatures = Typefeature::All();
        $zonaltrial = Zonaltrial::findOrFail($id);
        foreach ($typesfeatures as $t_key => $t) {
            foreach ($t->features as $key => $f) {
                $characteristic = Characteristic::where('zonaltrial_id',$zonaltrial->id)->where('feature_id',$f->id)->where('year',0)->first();
                $typesfeatures[$t_key]->features[$key]->valor0 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('zonaltrial_id',$zonaltrial->id)->where('feature_id',$f->id)->where('year',1)->first();
                $typesfeatures[$t_key]->features[$key]->valor1 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('zonaltrial_id',$zonaltrial->id)->where('feature_id',$f->id)->where('year',2)->first();
                $typesfeatures[$t_key]->features[$key]->valor2 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('zonaltrial_id',$zonaltrial->id)->where('feature_id',$f->id)->where('year',3)->first();
                $typesfeatures[$t_key]->features[$key]->valor3 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('zonaltrial_id',$zonaltrial->id)->where('feature_id',$f->id)->where('year',4)->first();
                $typesfeatures[$t_key]->features[$key]->valor4 = (!empty($characteristic)) ? $characteristic->value:null;

                $characteristic = Characteristic::where('varity_id',$zonaltrial->varity_id)->where('feature_id',$f->id)->where('year',0)->first();
                $typesfeatures[$t_key]->features[$key]->crucevalor0 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('varity_id',$zonaltrial->varity_id)->where('feature_id',$f->id)->where('year',1)->first();
                $typesfeatures[$t_key]->features[$key]->crucevalor1 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('varity_id',$zonaltrial->varity_id)->where('feature_id',$f->id)->where('year',2)->first();
                $typesfeatures[$t_key]->features[$key]->crucevalor2 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('varity_id',$zonaltrial->varity_id)->where('feature_id',$f->id)->where('year',3)->first();
                $typesfeatures[$t_key]->features[$key]->crucevalor3 = (!empty($characteristic)) ? $characteristic->value:null;
                $characteristic = Characteristic::where('varity_id',$zonaltrial->varity_id)->where('feature_id',$f->id)->where('year',4)->first();
                $typesfeatures[$t_key]->features[$key]->crucevalor4 = (!empty($characteristic)) ? $characteristic->value:null;
            }
        }
        return View('crossings.zona.show',compact('zonaltrial','typesfeatures'));
    }

    public function mapout(){
        $coded = Varity::where('crossing',1)->where('typecrossing',2)->get();
        return View('crossings.mapout',compact('coded'));
    }

    public function codedJSON(Request $request){

        /*
        if($_GET['mapout_id'] == "" and $_GET['market_id'] == ""){
            $crossings = Varity::where('code','!=',null)->with('mother')->with('father')->with('market')->with('mapout')->get();    
        }else{
            if($_GET['mapout_id'] != "" and $_GET['market_id'] != ""){
                $crossings = Varity::where('market_id',$_GET['market_id'])->where('mapout_id',$_GET['mapout_id'])->where('code','!=',null)->with('mother')->with('father')->with('market')->with('mapout')->get();
            }else if($_GET['mapout_id'] != ""){
                $crossings = Varity::where('mapout_id',$_GET['mapout_id'])->where('code','!=',null)->with('mother')->with('father')->with('market')->with('mapout')->get();
            }else{
                $crossings = Varity::where('market_id',$_GET['market_id'])->where('code','!=',null)->with('mother')->with('father')->with('market')->with('mapout')->get();
            }
        }
        */

        $temp = [];

        if($_GET['mapout_id'] != ""){
            $crossings = array();
            $response = array();
            $crossings = Varity::where('mapout_id',$_GET['mapout_id'])->get();
            $res = null;
            if(count($crossings) > 0){
                if($_GET['filters']){

                    $params = array();
                    parse_str($_GET['filters'], $params);

                    foreach ($crossings as $v) {
                        if(isset($params['data'])){

                            $counter = 1;

                            if($counter > 0){
                                foreach ($params['data'] as $key => $value) {
                                    $res = $v->characteristics()->where("feature_id", $key)->where("value",">=",$value)->get();
                                    count($res) > 0 ? $counter ++ : $counter = 0;
                                }
                            }

                            if($counter > 1){
                                array_push($response,$v);
                            }
                        }
                    }

                    if(isset($params['data'])){
                        $crossings = collect($response);
                    }
                }   
            }
        }
        
        if($_GET['block_id'] != ""){
            $crossings = array();
            $response = array();
            $crossings = Blocksvarity::where('block_id',$_GET['block_id'])->with('varity')->get();

            if($_GET['filters']){

                $params = array();
                parse_str($_GET['filters'], $params);

                foreach ($crossings as $v) {
                    if(isset($v['varity'])){ # SE VALIDA MIENTRAS NO LIMPIE LA DB
                        if(isset($params['data'])){
                            foreach ($params['data'] as $key => $value) {
                                $counter = 1;
                                if($counter > 0){
                                    foreach ($params['data'] as $key => $value) {
                                        $res = $v['varity']->characteristics()->where("feature_id", $key)->where("value",">=",$value)->with('varity')->get();
                                        count($res) > 0 ? $counter ++ : $counter = 0;
                                    }
                                }
                                if($counter > 1){
                                    array_push($response,$res[0]);
                                }
                            }
                        }
                    }
                }

                if(isset($params['data'])){
                    $crossings = collect($response);
                }
            }   

            $crossings = $crossings->pluck('varity')->unique()->filter();
        }

        if(!empty($crossings)){
            foreach ($crossings as $key => $value) {
                array_push($temp, $crossings[$key]);
            }
        }else{
            $temp = Varity::all();
        }



        return json_encode(array('data' => $temp));
    }

    public function check_ancester(Request $request){

        $msj = "";

        # Se revisa si el cruzamiento ya existe
        $check = Varity::where('father_id', $request->father)->where('mother_id', $request->mother)->where('berries','>', 0)->first();

        if(empty($check)){
            $check = Varity::where('father_id', $request->mother)->where('mother_id', $request->father)->where('berries','>', 0)->first();
        }
        !empty($check) ? $msj .= " El cruzamiento que intenta realizar ya fue hecho anteriormente y tiene berries" : "";

        $check = Varity::where('father_id', $request->father)->where('mother_id', $request->mother)->first();

        if(empty($check)){
            $check = Varity::where('father_id', $request->mother)->where('mother_id', $request->father)->first();
        }
        !empty($check) ? $msj .= " El cruzamiento que intenta realizar ya fue hecho anteriormente" : "";

        # Se revisa que el padre tiene floracion escaza
        $check_polem = Position::where("varity_id", $request->father)->where('flowering', 2)->first();

        !empty($check_polem) ? $msj .= " El padre seleccionado tiene floración escaza." : "";

        # Se revisa que la madre tiene floracion escaza
        $check_polem = Position::where("varity_id", $request->mother)->where('flowering', 2)->first();
        !empty($check_polem) ? $msj .= " La madre seleccionada tiene floración escaza." : "";

        # Se revisa si el padre es esteril
        $check_fether_steril = Position::where("varity_id", $request->father)->where('polem_volume', 2)->first();
        if(!empty($check_fether_steril)){
            $msj .= " El padre seleccionada tiene polen escazo.";
        }else{
            $check_fether_steril = Position::where("varity_id", $request->father)->where('polem_volume', 3)->first();
            !empty($check_fether_steril) ? $msj .= " El padre seleccionada tiene polen esteril." : "";
        }

        # Se revisa si la madre es esteril
        $check_mother_steril = Position::where("varity_id", $request->mother)->where('steril_mom', 1)->first();
        !empty($check_mother_steril) ? $msj .= " La madre seleccionada esteril." : "";

        # Se revisa si la madre tiene polem escazo
        $check_mother_polen = Position::where("varity_id", $request->mother)->where('polem_volume','>', 1)->first();

        if(!empty($check_mother_polen)){
            $check_mother_polen->polem_volume == 2 ? $msj .= " Madre con Polen Escazo." : "";
            $check_mother_polen->polem_volume == 3 ? $msj .= " Madre con Polen Esteril." : "";
        }

        #Se revisa ancestros
        $model_mother = Varity::find($request->mother);
        $model_father = Varity::find($request->father);
        #AGREGAR VALIDACION CUANDO AMBOS SON NULL

        if($model_father->father_id == $model_mother->id || $model_father->mother_id == $model_mother->id || $model_mother->father_id == $model_father->id || $model_mother->mother_id == $model_father->id){
                $msj .= " Problema con Primera Linea Parental.";

        }

        if($model_father->father_id == $model_mother->father_id || $model_father->mother_id == $model_mother->mother_id || $model_father->mother_id == $model_mother->father_id || $model_father->father_id == $model_mother->mother_id){

            if($model_father->father_id > 0 && $model_mother->father_id > 0)  {
                $msj .= " Problema con Segunda Linea Parental, coincidencia de Padres.";
            }

            if($model_father->mother_id > 0 && $model_mother->mother_id > 0)  {
                $msj .= " Problema con Segunda Linea Parental, coincidencia de Padres.";
            }

        }

        if((@$model_father->father->father_id == @$model_mother->father->father_id) || (@$model_father->mother->mother_id == @$model_mother->mother->mother_id) || (@$model_father->mother->mother_id == @$model_mother->father->father_id) || (@$model_father->father->father_id == @$model_mother->mother->mother_id)){
            
            if(@$model_father->father->father_id > 0 && @$model_mother->father->father_id > 0)  {
                $msj .= " Problema con Tercera Linea Parental, coincidencia de Padres.i";
            }
            #dd($model_mother->mother->mother_id);
            if(@$model_father->mother->mother_id > 0  && @$model_mother->mother->mother_id > 0)  {
                $msj .= " Problema con Tercera Linea Parental, coincidencia de Padres.z";
            }
        }

        empty($msj) ? $status = false : $status = true;

        return json_encode(["status" => $status, "msj" => $msj]);
    }

    public function searchCrossings(Request   $request){

        $model = Varity::where('crossing',1)->where('typecrossing','!=',2);
        #$mapout = Mapout::select('id')->where('greenhouse_id',$request->mapout_id)->get()->toArray();
        !empty($request->glasses) ? $model->where('glasses','>=', $request->glasses) : "";
        !empty($request->berries) ? $model->where('berries','>=', $request->berries) : "";
        !empty($request->mapout_id) ? $model->where('greenhouse_id', $request->mapout_id) : "";

        $crossings = $model->get();

        return View('crossings.seaarchCrossings',compact('crossings'));
    }
}
