<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use App\Models\Mapout;
use App\Models\Varity;
use App\Models\Market;
use App\Models\Blocksvarity;
use App\Models\Typefeature;
use App\Models\Characteristic;
use App\Models\Showfarm;
use App\Models\Feature;
use App\Locality;
use App\fries_characteristics;
use App\ModelCalibres;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class BlockController extends Controller
{
    /**
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Vista de tipos de motor
     */
    public function index()
    {
        $blocks = Block::All();
        $localities = Locality::pluck('name','id');

        return View('blocks.index',compact('blocks', 'localities'));
    }


    public function drops($id)
    {   
        $result = Varity::withTrashed()->where('block_deleted', $id)->get();
        
        return View('blocks.drops',compact('result'));
    }
    /**
     * Vista para crear tipo de motor
     */
    public function create()
    {
        $localities = Locality::pluck('name','id');
        return View('blocks.create', compact('localities'));
    }

    public function trash()
    {
        $blocks = Block::onlyTrashed()->get();

        return View('blocks.trash', compact('blocks'));
    }

    public function show($id_block){

        $rows = 100;
        $block = Block::withTrashed()->find($id_block);
        $markets = Market::pluck('name','id');

        if(Mapout::count() > 0){
            foreach (Mapout::All() as $m) {
                $mapouts[$m->id] = "Nº".$m->id." ".$m->year;
            }    
        }else{
            $mapouts = array();
        }

        if($block->limit == 0) $block->limit = 6;

        $blocks = array();

        for($j = 1; $j <= $rows; $j++){
            for($i = 1; $i <= $block->lines; $i++){
                $blocks[$j][$i] = array();
                for ($k = 1; $k <= $block->limit; $k++) {
                    $blocks[$j][$i][$k] = array();   
                }
            }       
        }
        
        $codified = Blocksvarity::where('block_id',$block->id)->get();
        if($codified->count() > 0){
            foreach ($codified as $c) {
                $position = explode('.', $c->position);
                $blocks[$position[0]][$position[1]][$position[2]] = $c;
            }
        }

        $allBlocks = Block::pluck('description','id');
        $allTarget = Market::pluck('name','id');
        $locality_id = $block->locality > 0 ? $block->locality : 1; 
        if(isset($_GET['type_content'])){
            $type_content = $_GET['type_content'];
            return View('blocks.modules.content',compact('block','mapouts','blocks','markets','allBlocks','allTarget','type_content','codified','locality_id'));
        }


        return View('blocks.show',compact('block','mapouts','blocks','markets','allBlocks','allTarget','locality_id'));
    }

    public function show_trash($id_block){

        $rows = 100;

        $block = Block::withTrashed()->find($id_block);

        $markets = Market::pluck('name','id');
        
        if(Mapout::count() > 0){
            foreach (Mapout::All() as $m) {
                $mapouts[$m->id] = "Nº".$m->id." ".$m->year;
            }    
        }else{
            $mapouts = array();
        }

        if($block->limit == 0) $block->limit = 6;

        $blocks = array();

        for($j = 1; $j <= $rows; $j++){
            for($i = 1; $i <= $block->lines; $i++){
                $blocks[$j][$i] = array();
                for ($k = 1; $k <= $block->limit; $k++) {
                    $blocks[$j][$i][$k] = array();   
                }
            }       
        }
        
        $codified = Blocksvarity::where('block_id',$block->id)->get();
        if($codified->count() > 0){
            foreach ($codified as $c) {
                $position = explode('.', $c->position);
                $blocks[$position[0]][$position[1]][$position[2]] = $c;
            }
        }

        $allBlocks = Block::withTrashed()->pluck('description','id');
        $allTarget = Market::pluck('name','id');

        if(isset($_GET['type_content'])){
            $type_content = $_GET['type_content'];
            return View('blocks.modules.content',compact('block','mapouts','blocks','markets','allBlocks','allTarget','type_content','codified'));
        }

        return View('blocks.show',compact('block','mapouts','blocks','markets','allBlocks','allTarget'));
    }

    /**
     * Registra un nuevo tipo de motor en base de datos
     */
    public function store(Request $request)
    {
        $block = Block::create($request->all());

        return redirect()->route('blocks.index')
                        ->with('success','Bloque creado correctamente.');
    }


    /**
     * Vista de editar tipo de motor
     */
    public function edit($id)
    {
        $block = Block::find($id);
        return view('blocks.edit',compact('block'));
    }

    /**
     * Actualiza un tipo de motor en base de datos
     */
    public function update(Request $request, $id)
    {

        Block::find($id)->update($request->all());

        return redirect()->route('blocks.index')
                        ->with('success','bloque editado corectamente.');
    }

    /**
     * Elimina un tipo de motor siemrpe y cuando no existan motores asociados.
     */
    public function destroy($id)
    {   

        $block = Block::withTrashed()->find($id);

        if($block->deleted_at){
            $block->forceDelete();
        }else{
            $block->delete();
        }

        return redirect()->back()->with('success','Ensayo eliminado correctamente.');
    }

    public function codify(){

        $rows = 100;
        asort($_GET['codificados'], 1);
        $block = Block::withTrashed()->findOrFail($_GET['block_id']);

        if($block->limit == 0) $block->limit = 6;

        //1.- Foreach para generar un arreglo con posiciones
        $bloques = array();
        for($j = 1; $j <= $rows; $j++){
            for($i = 1; $i <= $block->lines; $i++){
                for ($k = 1; $k <= $block->limit; $k++) {
                    $bloques[$j][$i][$k] = $j.".".$i.".".$k;   
                }
            }       
        }


        //2.- Va a buscar los codificados ya asociados al bloque.
        $coded = Blocksvarity::where('block_id',$_GET['block_id'])->get();

        $lastType = null;
        $lastPosition = null;
        if($coded->count() > 0){
            foreach ($coded as $c) {
                $position = explode('.', $c->position);
                unset($bloques[$position[0]][$position[1]]);
                if(!empty($c->type)){
                    $lastType = $c->type;
                }
                $lastPosition = $position[0];
            }     
        }

        $block = Block::withTrashed()->findOrFail($_GET['block_id']);

        if($_GET['type'] != $lastType){
            if(!empty($lastPosition)){
                foreach ($bloques[$lastPosition] as $b) {
                    $blockvarity = new Blocksvarity();
                    $blockvarity->varity_id = null;
                    $blockvarity->block_id = $block->id;
                    $blockvarity->position = $b[1];
                    $blockvarity->type = $lastType;
                    $blockvarity->save();
                    $position = explode('.', $blockvarity->position);
                    unset($bloques[$position[0]][$position[1]]);
                }
            }
        }

        //3.- Foreach para generar un listado para posiciones disponibles
        $blocks = array();
        foreach ($bloques as $item) {
            if(!empty($item)){
                foreach ($item as $column) {
                    foreach ($column as $col) {
                       $blocks[] = $col; 
                    }
                }
            }
        }


        //4 se intertan los nuevos codificados asociando al listado de posiciones disponibles con un incremental.
        if(!empty($_GET['codificados'])){
            $count = 0;
            foreach ($_GET['codificados'] as $id => $value) {
                $varity = Varity::findOrFail($id);
                $blockvarity = new Blocksvarity();
                $blockvarity->varity_id = $varity->id;
                $blockvarity->block_id = $block->id;
                $blockvarity->position = $blocks[$count];
                $blockvarity->type = $_GET['type'];
                $blockvarity->save();
                $count++;
            }
            return redirect()->route('blocks.show',$_GET['block_id'])
                        ->with('success','Se agregaron los codificados.');
        }else{
            return redirect()->route('blocks.show',$_GET['block_id'])
                        ->with('alert','No agrego ningún codificado que asociar.');
        }
    }

    public function getCaracteristicas(){

        $crossing = Varity::find($_GET['crossing']);
        $block = Block::withTrashed()->find($_GET['block_id']);
        $type = $_GET['type'];
        $typesfeatures = Typefeature::All();
        $locality = $block->locality;
        $locality_original = $locality;

        foreach ($typesfeatures as $t_key => $t) {
            $count = 0;
            foreach ($t->features as $key => $f) {
                
                if(Showfarm::where('block_id',@$block->id)->where('feature_id',@$f->id)->where('type',@$type)->count() > 0){
                    $count++;
                }

               if(Showfarm::where('block_id',$block->id)->where('feature_id',@$f->id)->first()){

                $locality = $f->localities_viewer == 0? 1 : $locality_original; 

                #AÑO 0
                @$characteristic = Characteristic::where('locality_id', $locality)->where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',0) ->orderBy('created_at', 'desc')->first();

                @$typesfeatures[$t_key]->features[$key]->valor0 = (!empty($characteristic)) ? $characteristic->value:null;
                @$typesfeatures[$t_key]->features[$key]->value_repeats_0 = (!empty($characteristic)) ? @$characteristic->repeats_values:null;
                #AÑO 1
                @$characteristic = Characteristic::where('locality_id', $locality)->where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',1) ->orderBy('created_at', 'desc')->first();
                @$typesfeatures[$t_key]->features[$key]->valor1 = (!empty($characteristic)) ? $characteristic->value:null;
                @$typesfeatures[$t_key]->features[$key]->value_repeats_1 = (!empty($characteristic)) ? @$characteristic->repeats_values:null;
                #AÑO 2
                @$characteristic = Characteristic::where('locality_id', $locality)->where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',2) ->orderBy('created_at', 'desc')->first();
                @$typesfeatures[$t_key]->features[$key]->valor2 = (!empty($characteristic)) ? $characteristic->value:null;
                @$typesfeatures[$t_key]->features[$key]->value_repeats_2 = (!empty($characteristic)) ? @$characteristic->repeats_values:null;
                #AÑO 3
                @$characteristic = Characteristic::where('locality_id', $locality)->where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',3) ->orderBy('created_at', 'desc')->first();
                @$typesfeatures[$t_key]->features[$key]->valor3 = (!empty($characteristic)) ? $characteristic->value:null;
                @$typesfeatures[$t_key]->features[$key]->value_repeats_3 = (!empty($characteristic)) ? @$characteristic->repeats_values:null;
                #AÑO 4
                @$characteristic = Characteristic::where('locality_id', $locality)->where('varity_id',$crossing->id)->where('feature_id',$f->id)->where('year',4) ->orderBy('created_at', 'desc')->first();
                @$typesfeatures[$t_key]->features[$key]->valor4 = (!empty($characteristic)) ? $characteristic->value:null;   
                @$typesfeatures[$t_key]->features[$key]->value_repeats_4 = (!empty($characteristic)) ? @$characteristic->repeats_values:null;
                } else {
                    unset($t->features[$key]);
                }
            }
           
            if($count == 0){
                unset($typesfeatures[$t_key]);
            }
        }

        if(isset($_GET['position_block']) && $_GET['position_block'] != ""){
            $model = Blocksvarity::where('last_position','!=', "")->where("block_id", $_GET['block_id'])->get();
            if(count($model) > 0 ){
                foreach ($model as $key => $value) {
                    $value->last_position = "";
                    $value->save();
                }
            }

            $last_position = Blocksvarity::where('position',$_GET['position_block'].".1")->where("block_id", $_GET['block_id'])->first();
            $last_position->last_position = 1;
            $last_position->save();
        }


        return View('blocks.getCaracteristicas',compact('crossing','typesfeatures','block','type'));
    }

    public function getFotos(){
        $crossing = Varity::findOrFail($_GET['crossing']);
        return View('blocks.getFotos',compact('crossing'));
    }

    public function saveObservation(){

        $blockvarity = Blocksvarity::where('block_id',$_GET['blockvarity_id'])->where('varity_id', $_GET['id_varity_obs'])->first();
        $blockvarity->observation = $_GET['observation'];

        $varity = Varity::whereId($_GET['id_varity_obs'])->first();
        $obs = $varity->observation." | ".$_GET['observation'];
        $varity->observation = $obs;
        $varity->save();

        if($blockvarity->save()){
            return json_encode(true);
        }
        return json_encode(false);
    }

    public function modCaracteristicas(){
        $block = Block::withTrashed()->findOrFail($_GET['block_id']);
        $typesfeatures = Typefeature::All();
        return View('blocks.modCaracteristicas',compact('typesfeatures','block'));
    }

    public function saveShowFarms(Request $request){
        if(!empty($request->input('farm'))){
            Showfarm::where('block_id',$_POST['block_id'])->delete();
            if(!empty($request->input('farm'))){
                

                foreach ($request->input('farm') as $type => $features) {
                    foreach ($features as $feature_id => $f) {
                        $showFarm = new Showfarm();
                        $showFarm->block_id = $_POST['block_id'];
                        $showFarm->feature_id = $feature_id;
                        $showFarm->type = $type;
                        $showFarm->save();
                    }
                    
                }
            }    
        }
        return redirect()->back()
                        ->with('success','Características modificadas correctamente.');
    }

     public function addFeature(){
        $features = Feature::pluck('name','id');
        return View('blocks.addCharacteristic',compact('features'));
    }

    public function getDataFries(Request $request){
        if(isset($request->year) && $request->year >= 0){
            $data = fries_characteristics::where('varity_id',$request->varity)
                                  ->where('feature',$request->feature)
                                  ->where('year',$request->year)
                                  ->orderBy('created_at', 'desc')
                                  ->first();
        }else{

            $response = [];
            $temp = [];
            $dataChart = [];

            $factores = [
                #"enero" => 0,
                #"febrero" => 0,
                "marzo" => 0.071,
                "abril" => 0.077,
                "mayo" => 0.084,
                "junio" => 0.090,
                "julio" => 0.097,
                "agosto" => 0.103,
                "septiembre" => 0.110,
                "octubre" => 0.116,
                "noviembre" => 0.123,
            ];

            $data = fries_characteristics::where('varity_id',$request->varity)
                                  ->where('feature',$request->feature)
                                  ->get()->toArray();

            foreach ($data as $key => $value) {
                $total_ponderado = 0;

                foreach ($factores as $k => $v) {
                    $col = $k."_ponderado";
                    $value[$col] = $value[$k] * $v;
                    $total_ponderado += $value[$col];

                    #Para grafico, si no existe el indice lo creo
                    if(!isset($temp[$k]['value'])){
                        $temp[$k]['value'] = 0;
                        $temp[$k]['counter'] = 0;
                    }

                    #Seteo el valor ponderado a la columna y lo sumo
                    $temp[$k]['value'] += $value[$k];

                    #Si el valor es mayor a 0 aumento contador para promediar luego
                    if($value[$col] > 0 ){
                        $temp[$k]['counter'] ++;
                    }
                    
                }

                $value['total_ponderado'] = $total_ponderado;

                $response[$value['year']][] = $value;
            }
            
            foreach ($temp as $kx => $vx) {

                $valuex = $vx['value'] > 0 ? $vx['value']/$vx['counter'] : 0;
                $dataChart[] = ["country"=> ucfirst($kx), "value" => $valuex];
            }

            return View('results.fries.detail_fries',compact('response', 'factores', 'dataChart'));

        }

        if(!empty($data)){
            return json_encode(["status" => true, "data" => $data]);
        }else{
            return json_encode(["status" => false, "data" => $data]);
        }
    }

    public function saveDataFries(Request $request){
        $status = false;

        if (!empty($request->params)) {
            $model  = fries_characteristics::where('varity_id', $request->params[0]['varity'])
                                            ->where('year',$request->params[0]['year'])
                                            ->where('feature',$request->params[0]['feature'])
                                            #->where('block_id',$request->params[0]['block_id'])
                                            #->where('locality_id',$request->params[0]['locality'])
                                            ->first();

            if(empty($model)){
                $model = new fries_characteristics();
            }

            foreach ($request->params as $key => $value) {
                $model->characteristic_id = $value['feature'];
                $model->enero = $value['Enero'];
                $model->febrero = $value['Febrero'];
                $model->marzo = $value['Marzo'];
                $model->abril = $value['Abril'];
                $model->mayo = $value['Mayo'];
                $model->junio = $value['Junio'];
                $model->julio = $value['Julio'];
                $model->agosto = $value['Agosto'];
                $model->septiembre = $value['Septiembre'];
                $model->octubre = $value['Octubre'];
                $model->noviembre = $value['Noviembre'];
                $model->diciembre = $value['Diciembre'];
                $model->varity_id = $value['varity'];
                $model->year = $value['year'];
                $model->feature = $value['feature'];
                #$model->block_id = $value['block_id'];
                #$model->locality_id = $value['locality'];
                $model->save();
            }
        }

        return json_encode(true);

    }

    public function updateDataFries(Request $request){
            $status =  false;

        if($request->option == 1){
            fries_characteristics::find($request->id_data)->delete();
            $status =  true;
        }

        return json_encode(array("status" => $status));
    }

    public function print(Block $block){

        $rows = 100;

        $markets = Market::pluck('name','id');
        if(Mapout::count() > 0){
            foreach (Mapout::All() as $m) {
                $mapouts[$m->id] = "Nº".$m->id." ".$m->year;
            }    
        }else{
            $mapouts = array();
        }
        

        if($block->limit == 0) $block->limit = 6;
        $blocks = array();
        for($j = 1; $j <= $rows; $j++){
            for($i = 1; $i <= $block->lines; $i++){
                $blocks[$j][$i] = array();
                for ($k = 1; $k <= $block->limit; $k++) {
                    $blocks[$j][$i][$k] = array();   
                }
            }       
        }
        
        $codified = Blocksvarity::where('block_id',$block->id)->get();
        if($codified->count() > 0){
            foreach ($codified as $c) {
                $position = explode('.', $c->position);
                $blocks[$position[0]][$position[1]][$position[2]] = $c;
            }
        }

        $allBlocks = Block::withTrashed()->pluck('description','id');
        

        return View('blocks.show',compact('block','mapouts','blocks','markets','allBlocks'));
    }

    public function move_position(Request $request){

        $request->position_drag .=".1";
        $request->position_drop .=".1";
        $drag = Blocksvarity::where('position',$request->position_drag)->where('block_id',$request->block)->first();
        $drag_position = $drag->position;
        $drop = Blocksvarity::where('position',$request->position_drop)->where('block_id',$request->block)->first();
        if(!empty($drop)){
            $drop_position = $drop->position;
            $drag->position = $drop_position;
            $drag->save();

            $drop->position = $drag_position;
            $drop->save();
        }else{
            $drag->position = $request->position_drop;
            $drag->save();
        }

        return json_encode(true);
    }


    public function info_to_compare(Request $request){
        $data    = [];
        $headers = [];

        foreach ($request->selected_p as $k => $v) {
            $model = Varity::where('id', $v["IDVARITY"])->first();
            //caracteristicas
            $char = Showfarm::where('block_id',$request->block)->get();
            $model->mother_id? $mother_name =  @$model->mother()->name : $mother_name = "-";
            $model->father_id? $father_name =  @$model->father()->name : $father_name = "-";
            $model->market_id? $target_name =  @$model->market()->name : $target_name = "-";
            $model->name? $name =  @$model->name : $name = "-";

            $temp = [
                        "mother" => $mother_name,
                        "father" => $father_name,
                        "target" => $target_name,
                        "name"   => $name,
                        "varity_id" => $v["IDVARITY"]
                    ];

            foreach ($char as $k_ => $v_) {


                $feature = Feature::whereId($v_->feature_id)->first();

                if(!isset($headers[@$feature->id])){
                    $headers[@$feature->id] = @$feature->name;
                }

                $value_ = Characteristic::where('feature_id', @$v_->feature_id)
                                        ->where('varity_id', @$v["IDVARITY"])
                                        ->first();

                $temp[@$feature->id] = @$value_->value;
            }

            array_push($data, $temp);
        }

        return json_encode(["headers" => $headers, "data" => $data]);
    }

    public function send_to_blocks(Request $request){

        $rows = 100;

        $block = Block::withTrashed()->findOrFail($request->destiny);

        if($block->limit == 0) $block->limit = 6;

        //1.- Foreach para generar un arreglo con posiciones
        $bloques = array();
        for($j = 1; $j <= $rows; $j++){
            for($i = 1; $i <= $block->lines; $i++){
                for ($k = 1; $k <= $block->limit; $k++) {
                    $bloques[$j][$i][$k] = $j.".".$i.".".$k;   
                }
            }       
        }

        //2.- Va a buscar los codificados ya asociados al bloque.
        $coded = Blocksvarity::where('block_id',$request->destiny)->get();

        $lastType = null;
        $lastPosition = null;
        if($coded->count() > 0){
            foreach ($coded as $c) {
                $position = explode('.', $c->position);
                unset($bloques[$position[0]][$position[1]]);
                if(!empty($c->type)){
                    $lastType = $c->type;
                }
                $lastPosition = $position[0];
            }     
        }

        $block = Block::withTrashed()->findOrFail($request->destiny);

        //3.- Foreach para generar un listado para posiciones disponibles
        $blocks = array();
        foreach ($bloques as $item) {
            if(!empty($item)){
                foreach ($item as $column) {
                    foreach ($column as $col) {
                       $blocks[] = $col; 
                    }
                }
            }
        }

        //4 se intertan los nuevos codificados asociando al listado de posiciones disponibles con un incremental.
        if(!empty($request->selected_p)){
            $count = 0;
            foreach ($request->selected_p as $id => $value) {
                $varity = Blocksvarity::where('varity_id',$value["IDVARITY"])->where('block_id', $request->block)->first();
                $blockvarity = new Blocksvarity();
                $blockvarity->varity_id = $varity->varity_id;
                $blockvarity->block_id = $block->id;
                $blockvarity->position = $blocks[$count];
                $blockvarity->type = $varity->type;
                $blockvarity->save();
                $count++;

                DB::table('characteristics')->where('varity_id',$value["IDVARITY"])->where('block_id', $request->block)->update(array('block_id' => $request->destiny));


            }
            $status = true;
        }else{
            $status = false;
        }

        return json_encode(["status" => $status]);
    }

    public function deleteCodify($id){
        Varity::whereId($id)->delete();
        Blocksvarity::whereVarity_id($id)->delete();

        return json_encode(true);        
    }

    public function getDataChar(Request $request){
        if( $request->feature == 147 || $request->feature == 148 ){

            $request->feature == 147 ? $feature = 130 : $feature = 131;
            
            $locality = Block::find($request->block)->locality;


            $ton = Characteristic::whereVarity_id($request->varity_id)
                                #->where('year',$request->year)
                                ->where('feature_id',23)
                                ->where('locality_id',$locality)
                                #->first();
                                ->avg('value');
            
            $var  = Characteristic::whereVarity_id($request->varity_id)
                                ->whereBlock_id($request->block)
                                ->where('year',$request->year)
                                ->where('locality_id',$locality)
                                ->where('feature_id',$feature)
                                ->first();

            $drymatter =  Characteristic::whereVarity_id($request->varity_id)
                                ->where('locality_id',$locality)
                                ->where('feature_id',47)
                                ->avg('value');

            return json_encode(array("feature" => $request->feature, "ton" => $ton, "find" => $var, "drymatter" => $drymatter));
        }

        $potatoes = Characteristic::whereVarity_id($request->varity_id)
                                ->whereBlock_id($request->block)
                                ->where('year',$request->year)
                                ->where('feature_id',123)
                                ->first();

        return json_encode($potatoes);
    }

    public function reorder($id){
        $arr_ = [];
        $result = [];
        $data = Blocksvarity::whereBlock_id($id)->get();

        foreach ($data as $key => $value) {
            $exist = Varity::find($value->varity_id);


            if(!empty($value->varity_id) && $exist != null){
                $temp = [
                    "varity_id" => $value->varity_id,
                    "observation" => $value->observation,
                    "state" => $value->state,
                    "review" => $value->review,
                    "type" => $value->type,

                ];
                array_push($arr_, $temp);
            }
        }

        $lines = Block::withTrashed()->whereId($id)->first()->lines;
        $bloques = array();
        for($j = 1; $j <= 100; $j++){
            for($i = 1; $i <= $lines; $i++){
                for ($k = 1; $k <= 1; $k++) {
                    $bloques[$j][$i][$k] = $j.".".$i.".".$k;   
                }
            }       
        }

        $blocks = array();
        foreach ($bloques as $item) {
            if(!empty($item)){
                foreach ($item as $column) {
                    foreach ($column as $col) {
                       $blocks[] = $col; 
                    }
                }
            }
        }
        
        $count = 0;
        foreach ($data as $id => $value) {
            $exist = Varity::find($value->varity_id);
            if(!empty($value->varity_id) && $exist != null){
                $value->position = $blocks[$count];
                $value->save();
                $count++;
            }else{
                $value->delete();
            }
            
        }   

        return json_encode(true);
    }

    public function discard(Request $request){
        foreach ($request->selected_p as $k => $v) {
            $model = Blocksvarity::where('block_id',$request->block)->where('varity_id', $v['IDVARITY'])->first();
            $model->delete();

            #$var = Varity::find($v['IDVARITY'])->delete();
        }

        return json_encode(true);
    }

    public function check_childrens(Request $request){
        $result = array();
        foreach ($request->selected_p as $k => $v) {
            $childrens = Varity::where('mother_id', $v['IDVARITY'])->orWhere('father_id', $v['IDVARITY'])->get();

            if(!empty($childrens)){
                foreach ($childrens as $key => $value) {

                    $father_name = "";

                    if($value->father_id == $v['IDVARITY']){
                        $father_name = $value->fatherName();
                    }

                    if($value->mother_id == $v['IDVARITY']){
                        $father_name = $value->motherName();
                    }

                    $temp[$v['IDVARITY']] = array(
                        "children_name" => !empty($value->name) ? $value->code : $value->code,
                        "childen_id" => $value->id,
                        "father_name" => $father_name,
                    );

                    array_push($result, $temp);
                }
            }
        }

        return json_encode($result);
    }

    public function drop(Request $request){
        foreach ($request->selected_p as $k => $v) {
            $var = Varity::withTrashed()->find($v['IDVARITY']);
            $var->block_deleted = $request->block;
            $var->save();

            $model = Blocksvarity::where('block_id',$request->block)->where('varity_id', $v['IDVARITY'])->first();
            $model->delete();

            if($var->deleted_at){
                //$var->forceDelete();
            }else{
                $var->delete();
            }
        }

        return json_encode(true);
    }

    public function export($id){

        $locality = Block::find($id)->locality; 

        $varities = Varity::whereHas('blocksvarity', function (Builder $query) use($id) {
                        $query->where('block_id', $id);
                    })->get();

        $typefeatures = Typefeature::all();

        return View('varities.exportaData',compact('varities', 'typefeatures', 'locality'));
    }

    public function review(Request $request){

        #dd($request->selected_p);
        foreach ($request->selected_p as $k => $v) {
            $pos = $v['POSITION'].".1";
            $model = Blocksvarity::where('block_id',$request->block)->where('varity_id', $v['IDVARITY'])->where('position', $pos)->first();
            $model->review = $request->review;
            $model->save();
        }

        return json_encode(true);
    }

     public function mark_offline(Request $request){

        $model = Block::where('id', $request->block)->first();
        $model->offline = $request->input('value');
        $model->save();

        return json_encode(true);
    }

    
    public function saveCalibres(Request $request){

        $success = false;

        if(!empty($request->calibres)){

            $model = ModelCalibres::where('block_id', $request->block_id)
                                    ->where('varity_id', $request->varity)
                                    ->where('year', $request->year)
                                    ->where('repeat_nro', $request->repeat_nro)
                                    ->delete();

            foreach ($request->calibres as $key => $value) {

                $data = array(
                    "feature_id" => $value['feature_id'],
                    "year" => $request->year,
                    "value_calib" => $value['elem_val'],
                    "repeat_nro" => $request->repeat_nro,
                    "block_id" => $request->block_id,
                    "varity_id" => $request->varity
                );

                try {
                    ModelCalibres::create($data);
                    $success = true;
                } catch (\Exception $e) {
                    $success = false; // Set success to false if an exception occurs
                }
            }
        }

        return json_encode($success);
    }
}
