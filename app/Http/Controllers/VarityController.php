<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Varity;
use App\Models\Feature;
use App\Models\Characteristic;
use App\Models\Multimedia;
use App\Models\Typefeature;
use App\Models\Value;
use App\Models\Market;
use App\Models\Blocksvarity;
use App\Models\Binnacle;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str as Str;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Avg_characteristic;
use App\Fries_avg_characteristics;
    


class VarityController extends Controller
{
protected function normalizeSelectedValues($value)
{
if (!is_array($value)) {
return [];
}

        return array_values(array_unique(array_map(function ($item) {
            return is_string($item) ? trim($item) : $item;
}, array_values($value))));
}

protected function normalizeBlockIds($value)
{
if (!is_array($value)) {
$value = [$value];
}

return array_values(array_unique(array_filter(array_map(function ($item) {
return (int) $item;
}, $value))));
}

protected function areAllSelectedValuesNumeric(array $values)
    {
        if (empty($values)) {
            return false;
        }

        foreach ($values as $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $varities = Varity::where('name', '!=', '')
                ->orWhere('code', '!=', '')
                ->get();
        $delete_final_boss = DB::table('varities AS v')
                            ->leftJoin('blocksvarities AS bv', 'v.id', '=', 'bv.varity_id')
                            ->whereNotIn('v.id', function ($query) {
                                $query->select('varity_id')
                                    ->from('characteristics')
                                    ->where('year', 1);
                            })
                            ->whereNull('bv.varity_id')
                            ->where(function ($query) {
                                $query->whereRaw('v.id != v.father_id')
                                    ->orWhereRaw('v.id != v.mother_id');
                            })
                            // ->whereNotNull('v.name')
                            ->count();
        /*
        $delete_final_boss = [];
        $varities = DB::table('varities AS v')
                        ->select([
                            'v.*',
                            // ¿Está en un ensayo?
                            DB::raw('CASE WHEN EXISTS (
                                SELECT 1
                                  FROM blocksvarities bv
                                 WHERE bv.varity_id = v.id
                            ) THEN 1 ELSE 0 END AS in_ensayo'),
                            // ¿Tiene características (año 1)?
                            DB::raw('(
                                SELECT COUNT(*) 
                                  FROM characteristics c
                                 WHERE c.varity_id = v.id
                                   AND c.year <= 1
                            ) AS characteristics_count'),
                            // ¿Tiene hijos en crossings?
                            DB::raw('(
                                SELECT COUNT(*) 
                                  FROM varities cr
                                 WHERE crossing = 1
                                    AND (cr.father_id = v.id
                                    OR cr.mother_id = v.id)
                            ) AS crossings_children'),
                            // ¿Tiene hijos en la misma tabla varities?
                            DB::raw('(
                                SELECT COUNT(*) 
                                  FROM varities child
                                 WHERE child.father_id = v.id
                                    OR child.mother_id = v.id
                            ) AS variety_children'),
                        ])

                        /*
                            bv.block_id IN (22,25,27,28,29,30,32,35,37,38,39,41,42,43,44,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113) and
                        
                        // Filtro: **no** debe estar en un ensayo
                        ->whereRaw('NOT EXISTS (
                            SELECT 1
                              FROM blocksvarities bv
                             WHERE bv.varity_id = v.id and bv.block_id in (114,115,116,117,118,119,120,121,122,123)
                        )')->whereNotNull('name')
                        ->orwhereNotNull('code')
                        ->whereNull('deleted_at')
                        ->get();
            #dd($varities);*/

        return View('varities.index',compact('varities','delete_final_boss'));
    }

    public function index_papelera()
    {
        // $varities = Varity::where('name', '!=', '')->get();
        $varities = Varity::onlyTrashed()->orderBy('deleted_at','desc')->get();
        // $varities = Varity::whereYear('created_at','>','2018')->onlyTrashed()->get();
        //$varities = Varity::where('crossing',0)->get();
        

        return View('varities.index_papelera',compact('varities'));
    }

    public function delete_final_boss(){
        $delete_final_boss = DB::table('varities AS v')
                            ->leftJoin('blocksvarities AS bv', 'v.id', '=', 'bv.varity_id')
                            ->whereNotIn('v.id', function ($query) {
                                $query->select('varity_id')
                                    ->from('characteristics')
                                    ->where('year', 1);
                            })
                            ->whereNull('bv.varity_id')
                            ->where(function ($query) {
                                $query->whereRaw('v.id != v.father_id')
                                    ->orWhereRaw('v.id != v.mother_id');
                            })
                            // ->whereNotNull('v.name')
                            ->delete();

        return json_encode($delete_final_boss);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $markets = Market::pluck('name','id');

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
        return View('varities.create',compact('varities','markets'));
    }
    public function check_to_delete(Request $request){
        $msj = "";
        $sons = Varity::orWhere('father_id', $request->id)->orWhere('mother_id', $request->id)->get();
        $data = Characteristic::where('year',1)->where('varity_id', $request->id)->get();
        $blocks = Blocksvarity::where('varity_id', $request->id)->get();

        count($sons) > 1 ? $msj .= "Variedad tiene Descendencia" : "";
        count($data) > 1 ? $msj .= " Variedad tiene Datos en Año 1" : "";
        count($blocks) > 1 ? $msj .= " Variedad fue utilizada en ensayo" : "";

        $msj != "" ? $status = true : $status = false;

        return json_encode(["status" => $status, "msj" => $msj]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $existingVarity = Varity::where('slug', Str::slug($request->input('name')))->first();

        if($existingVarity){
            return redirect()->route('varities.index')->with('alert','Ya existe una variedad con ese nombre.');
        }
        $request->merge([
            'slug' => $request->input('name'),
            'marketold_id' => $request->input('market_id')
        ]);


        Varity::create($request->all());

        return redirect()->route('varities.index')
                        ->with('success','Característica creada correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Varity  $feature
     * @return \Illuminate\Http\Response
     */
    public function show($varity)
    {   
        /*
        if($varity->crossing == 1){
            return redirect('/crossings/'.$varity->id);
        }*/
$varity = Varity::withTrashed()->find($varity);
        $typesfeatures = Typefeature::where('status', 1)->get();
        $locality = isset($_GET['locality']) ? $_GET['locality'] : 1;

        foreach ($typesfeatures as $t_key => $t) {
            $m = $t->features()->where('status',1)->orderBy('id', 'asc')->get();

            foreach ($m as $key => $f) {

                $c = Characteristic::select('value')->where('varity_id',$varity->id)->where('feature_id',$f->id)->where('value','!=', '')->where('value', 'not like', '0')->where('locality_id', $locality)->get();

                if(!empty($c) && $c->count() > 0){
                    if(is_numeric($c[0]->value)){

                        if($f->id == 16 || $f->id == 18){
                            #$typesfeatures[$t_key]->features[$key]->promedio = $c[0]->value;
                            foreach ($typesfeatures[$t_key]->features as $key_ => $val) {
                                if($val->id == $f->id){
                                    $typesfeatures[$t_key]->features[$key_]->promedio = $c[0]->value;
                                    $typesfeatures[$t_key]->features[$key_]->std = $this->stats_standard_deviation($c);
                                }
                            }
                        }else{
                            foreach ($typesfeatures[$t_key]->features as $key_ => $val) {
                                if($val->id == $f->id){
                                    $typesfeatures[$t_key]->features[$key_]->promedio = (int)$c->avg('value');
                                    $typesfeatures[$t_key]->features[$key_]->std = @$this->stats_standard_deviation($c);
                                }
                            }
                        }
                        
                    }else{
                        $typesfeatures[$t_key]->features[$key]->promedio = @$c->first()->value;
                        $typesfeatures[$t_key]->features[$key]->std = "-";
                    }
                }
            }
        }

        $childrens = Varity::where('mother_id',$varity->id)->orWhere('father_id',$varity->id)->whereNull('deleted_at')->get();

        $tree = Varity::whereIn('mother_id',[$varity->father_id, $varity->mother_id])->whereIn('father_id',[$varity->father_id, $varity->mother_id])->get();

        $count_childrens = count($childrens);
        $nietos = 0;
        if($count_childrens > 0){
            foreach ($childrens as $key => $value) {
                $count = Varity::whereNotNull('name')->where('mother_id',$value->id)->orWhere('father_id',$value->id)->count();
                $nietos += $count;
            }
        }

        $blocks = Blocksvarity::where('varity_id', $varity->id)->get();
        $ancesters = [];
        #dd("hijos:".$count_childrens."--- Nietos:".$nietos);

        return View('varities.show',compact('varity','typesfeatures','tree', 'ancesters', 'childrens', 'blocks'));
    }



##### PARA PROBAR A
    public function ancesters ($id, $max_level, $actual_level){
        /*
        $tree[$actual_level] = [];
        dd($tree);

        while ($actual_level < $max_level) {
            $actual_level++;
            $varity = Varity::where('mother_id',$varity->id)->orWhere('father_id',$varity->id)->get();

        }
        */
        $varity = Varity::find($id); #PERSONA -> PADRE - MADRE LVL 1

        $father = $varity->father_id; #RUT PADRE -> LVL 2
        $mother = $varity->mother_id; #RUT MADRE


        $ancesters = $this->search_ancesters($mother, $father, 4, 3);

        return $ancesters;
    }

    public function search_ancesters ($id_mother,$id_father, $max_level, $actual_level){
        $parents = [];
       
        while ($actual_level < $max_level) {
            $actual_level++;

            $nodo1 = Varity::find($id_mother);
            $nodo2 = Varity::find($id_father);
            /*
            $parents["nodo1"] = $this->search_ancesters($nodo1->mother_id, $nodo1->father_id, $max_level, $actual_level);
            $parents["nodo2"] = $this->search_ancesters($nodo2->mother_id, $nodo2->father_id, $max_level, $actual_level);
            */
            $parents["nodo1"] = ["mother_id" => @$nodo1->mother_id,"father_id" => @$nodo1->father_id];
            $parents["nodo2"] = ["mother_id" => @$nodo2->mother_id,"father_id" => @$nodo2->father_id];

        }

       return $parents;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function edit(Varity $varity)
    {
        $markets = Market::pluck('name','id');
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
        return view('varities.edit',compact('varity','markets','varities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Varity  $feature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Varity $varity)
    {

        if($varity->marketold_id == null){
            $request->merge([
                'marketold_id' => $request->input('market_id')
            ]);
        }

        $varity->update($request->all());

        return redirect()->route('varities.index')
                        ->with('success','Variedad editada corectamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function destroy(Varity $varity)
    {
        $varity->delete();
        return redirect()->route('varities.index')
                        ->with('success','Característica eliminada correctamente.');
    }

    public function restore($id){
        $varity = Varity::withTrashed()->find($id); 
        $varity->restore();
        return redirect()->route('varities.papelera')
                        ->with('success','Variedad restaurada correctamente.');
    }

    public function saveCharacteristics(Request $request){
        Characteristic::where('varity_id',$request->input('crossing_id'))->delete();

        foreach($request->input('feature') as $year => $features){
           foreach ($features as $f) {
               if(!empty($f['value'])){
                    $characteristic = new Characteristic();
                    $characteristic->value = $f['value'];
                    $characteristic->varity_id = $request->input('crossing_id');
                    $characteristic->feature_id = $f['feature_id'];
                    $characteristic->year = $year;
                    $characteristic->save();
                }
           }
        }

        return redirect('varities/'.$request->input('crossing_id'))
                        ->with('success','Características guardadas correctamente.');
    }

    public function addFeature(){

        if(isset($_GET['fries'])){
            $features = Feature::OrderBy('typefeature_id')->whereIn('id', [48,49,50,51])->pluck('name','id');    
        }else{
            $features = Feature::OrderBy('typefeature_id')->pluck('name','id');    
        }
        
        return View('varities.addCharacteristic',compact('features'));
    }

    public function addValues(){
        $feature = Feature::where('id',$_GET['feature'])->first();
        $values = Value::where('feature_id',$_GET['feature'])->get();
        return View('varities.addValues',compact('feature','values'));
    }
 
    public function searchHomeLegacy(Request   $request){
        $varities =  null;
        if( !function_exists('array_key_last') ) {
            function array_key_last(array $array) {
                if( !empty($array) ) return key(array_slice($array, -1, 1, true));
            }
        }
        
        $key_last = array_key_last($request->data);
        $keys_ = [];
        $varity_array = [];
        $varity_array_2 = [];
        $varity_list = [];
        $fathers_list = [];
        $mothers_list = [];
        $fries_list = [48,49,50,51];
        $locality_id = isset($request->locality_id) ? $request->locality_id : 1;
        $locality_original = isset($request->locality_id) ? $request->locality_id : 1;

        #busqueda por ensayo:
        if($request->block && !isset($request->only_childrens)){
            $block_varity = Varity::withTrashed()->whereHas('blocksvarity', function (Builder $query) use($request) {
                                    $query->whereBlock_id($request->block);
                                })->get();

            foreach ($block_varity as $key_ => $value_) {
                $varity_array[$value_->id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                $varity_list[] = $value_->id;
                !empty($value_->father_id) ? $fathers_list[$value_->father_id][] = $value_->father_id : "";
                !empty($value_->mother_id) ? $mothers_list[$value_->mother_id][] = $value_->mother_id : "";
            }
        }

        #busqueda por hijos
        if(isset($request->only_childrens)){

            if($request->block){
                $varity_list = array();
                $varity_array = array();
                $fathers_list = [];
                $mothers_list = [];
            }

            $childrens = Varity::withTrashed()->where('father_id', $request->varities_list)->orWhere('mother_id', $request->varities_list)->get();

            foreach ($childrens as $key_ => $value_) {
                if(!empty($value_->name) || !empty($value_->code)){
                    $varity_array[$value_->id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                    $varity_list[] = $value_->id;
                }
            }
        }


        foreach ($request->data ?? [] as $key => $value) {

           $f = Feature::find($key)->localities_viewer;

           $locality_id = $f == 0 ? 1 : $locality_original;

            if(is_array($value)){
                # RANGOS
                if(isset($request->operator[$key]) && $request->operator[$key] == "-"){
                    $pos = strpos($value, ";");
                    if($pos !== false){
                        $pos = explode(";", $value);
                        $max = $pos[1];
                        $min = $pos[0];
                    }else{
                        #Valores normales
                        $max = array_keys($value, max($value))[0];
                        $min = array_keys($value, min($value))[0];
                        is_numeric($min) ? (float) $min :  "";
                        is_numeric($max) ? (float) $max :  "";
                    }
                }else{
                    $max = array_keys($value, max($value))[0];
                    $min = array_keys($value, min($value))[0];
                    is_numeric($min) ? (float) $min :  "";
                    is_numeric($max) ? (float) $max :  "";
                }
            }else{
                # RANGOS
                $pos = strpos($value, ";");

                if($pos !== false){
                    $pos = explode(";", $value);
                    $max = $pos[1];
                    $min = $pos[0];
                }else{
                    #Valores normales
                    $max = null;
                    $min = $value;
                }
            }

            #Orden al imprimir los datos en la vista
            $keys_[$key] = $max;

            #Segunda iteracion, excepto cuando viene un ensayo seleccionado

            if(!empty($varity_array)){
                $max == null ? $max = 0 : '';

                if( is_numeric($max) && is_numeric($min) && $max == 0 && $min == 0){

                    $varities = Varity::whereNotNull('name')->whereIn('id',$varity_list)->whereDoesntHave('characteristics', function (Builder $query) use($key,$min,$max, $value, $request) {
                        $query->where("feature_id", $key);
                    })->get();

                    $llave= $key;
                    $varity_array = [];
                    $varity_list = [];
                    $fathers_list = [];
                    $mothers_list = [];

                    foreach ($varities as $key_ => $value_) {
                        $varity_array[$value_->id][$llave]["promedio"] = 0;
                        $varity_list[] = $value_->id;
                        !empty($value_->father_id) ? $fathers_list[$value_->father_id][] = $value_->father_id : "";
                        !empty($value_->mother_id) ? $fathers_list[$value_->mother_id][] = $value_->mother_id : "";
                    }


                    break;

                }else if( is_numeric($max) && is_numeric($min) && $max > 0 && $max != $min){


                    $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->whereBetween("PROMEDIO",[$min, $max])->whereIn('varity_id',$varity_list)->whereNull('deleted_at');
                }else{



                    #VALOR UNICO CON OPERADOR LOGICO
                    if(isset($request->operator[$key])){
                        if($min > 0){
                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)
                                                        ->where("PROMEDIO", $request->operator[$key],(int)$min)
                                                        ->whereIn('varity_id',$varity_list)
                                                        ->whereNull('deleted_at');;
                        }else{
                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->where("PROMEDIO", $request->operator[$key], 0)->whereIn('varity_id',$varity_list)
                            ->whereNull('deleted_at');
                        }
                    }else{
                        #VALOR UNICO NUMERICO
                        if(is_numeric($min)){
                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)
                                         ->where("PROMEDIO",(int)$min)->whereIn('varity_id',$varity_list);
                        }

                        # STRING
                        if(is_string($min)){

                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->where("PROMEDIO",'like',$min)->whereIn('varity_id',$varity_list)->whereNull('deleted_at');

                            if($max != null  and  $max != $min){
                                $varities->where("feature_id", $key)->orWhere("PROMEDIO",'like',$max)->whereIn('varity_id',$varity_list);
                            }
                        }
                    }
                }

                #Resultados
                $varity_list = [];
                $fathers_list = [];
                $mothers_list = [];

                foreach ($varities->get() as $key_ => $value_) {
                    if($value_->feature_id == $key){
                        $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                        $varity_list[] = $value_->varity_id;
                        !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                        !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";
                    }
                }

            }else{

                if(is_numeric($max) && is_numeric($min) && $max == 0 && $min == 0){
                    $varities = Varity::whereNotNull('name')->whereDoesntHave('characteristics', function (Builder $query) use($key,$min,$max, $value, $request) {
                        $query->where("feature_id", $key);
                    })->get();

                    foreach ($varities as $key_ => $value_) {
                        $varity_array[$value_->id][$key]["promedio"] = 0;
                        $varity_array[$value_->id][$key]["ponderado"] = 0;
                        $varity_list[] = $value_->id;
                        !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                        !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";
                    }
                }else if(is_numeric($max) && is_numeric($min) && $max > 0 && $max != $min){

                    if(!in_array($key, $fries_list)){
                        $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->whereBetween("PROMEDIO",[$min, $max])->whereNull('deleted_at');
                        $varities = $varities->get();
                        foreach ($varities as $key_ => $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                            $varity_list[] = $value_->varity_id;
                            !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                            !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";
                        }
                    }else{
                        $varities = Fries_avg_characteristics::where("feature_id", $key)->whereBetween("PROMEDIO",[$min, $max]);
                        $varities = $varities->get();
                         foreach ($varities as $key_ => $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                            $varity_array[$value_->varity_id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                            $varity_list[] = $value_->varity_id;
                            !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                            !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";
                        }
                    } 
                }else{

                    #VALOR UNICO CON OPERADOR LOGICO
                    if(isset($request->operator[$key])){
                        if($min > 0){
                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)
                                     ->where("PROMEDIO", $request->operator[$key],(int)$min)->whereNull('deleted_at');
                        }else{
                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->where("PROMEDIO", $request->operator[$key], 0)->whereNull('deleted_at');
                        }
                    }else{
                        #VALOR UNICO NUMERICO
                        if(is_numeric($min)){
                            if(!in_array($key, $fries_list)){
                               $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)
                                         ->where("PROMEDIO",(int)$min)->whereNull('deleted_at');
                            }else{
                                $varities = Fries_avg_characteristics::where("feature_id", $key)->whereBetween("PROMEDIO",[$min, $max]);
                            }
                        }

                        # STRING
                        if(is_string($min)){
                            $min = str_replace("'", "", $min);
                            $max = str_replace("'", "", $max);

                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->where("PROMEDIO",'like',$min)->whereNull('deleted_at');

                            if($max != null  and  $max != $min){
                                $varities->orWhere("PROMEDIO",'like',$max);
                            }
                        }
                    }

                    $varities = $varities->get();

                    if(!in_array($key, $fries_list)){
                        foreach ($varities as $key_ => $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                            $varity_list[] = $value_->varity_id;
                            !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                            !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";

                        }
                    }else{
                         foreach ($varities as $key_ => $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                            $varity_array[$value_->varity_id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                            $varity_list[] = $value_->varity_id;
                            !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                            !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";
                        }
                    }
                }
            }
        }

        $filteredVarities = Varity::with(['father', 'mother'])->whereIn('id', array_values(array_unique($varity_list)))->get();
        $fathers_list = [];
        $mothers_list = [];
        foreach ($filteredVarities as $filteredVarity) {
            if(!empty($filteredVarity->father_id)){
                $fathers_list[$filteredVarity->father_id][] = $filteredVarity->father_id;
            }
            if(!empty($filteredVarity->mother_id)){
                $mothers_list[$filteredVarity->mother_id][] = $filteredVarity->mother_id;
            }
        }

        $varities = $varity_list;
        $varity_data = $varity_array;
        $config_features = $keys_;
        $fries = $request->fries;

        #$fathers_list = array_merge($fathers_list, $mothers_list);
        #dd($fathers_list);
        return View('varities.searchHome',compact('varities','varity_data','config_features', 'fries','mothers_list', 'fathers_list','locality_id'));
    }

    public function searchHome(Request $request){
        $varity_array = [];
        $varity_list = [];
        $fries_list = [48,49,50,51];
        $locality_id = isset($request->locality_id) ? $request->locality_id : 1;
        $locality_original = $locality_id;
        $keys_ = [];
        $varities = null;
        $fathers_list = [];
        $mothers_list = [];

        if( !function_exists('array_key_last') ) {
            function array_key_last(array $array) {
                if( !empty($array) ) return key(array_slice($array, -1, 1, true));
            }
        }

$featureIds = collect($request->data ?? [])->keys()->map(fn ($id) => (int) $id)->all();
$featuresById = Feature::whereIn('id', $featureIds)->get()->keyBy('id');
$blockIds = $this->normalizeBlockIds($request->input('block', []));

if(!empty($blockIds) && !isset($request->only_childrens)){
$block_varity = Varity::withTrashed()->whereHas('blocksvarity', function (Builder $query) use($blockIds) {
$query->whereIn('block_id', $blockIds);
})->get();

            foreach ($block_varity as $value_) {
                $varity_array[$value_->id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                $varity_list[] = $value_->id;
            }
        }

if(isset($request->only_childrens)){
if(!empty($blockIds)){
$varity_list = [];
$varity_array = [];
                $fathers_list = [];
                $mothers_list = [];
            }

            $childrens = Varity::withTrashed()->where('father_id', $request->varities_list)->orWhere('mother_id', $request->varities_list)->get();
            foreach ($childrens as $value_) {
            if(!empty($value_->name) || !empty($value_->code)){
                    $varity_array[$value_->id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                    $varity_list[] = $value_->id;
                }
            }
        }

        foreach ($request->data as $key => $value) {
            $feature = $featuresById->get((int) $key);
            if(!$feature){
                continue;
            }

            $locality_id = $feature->localities_viewer == 0 ? 1 : $locality_original;
            $selectedValues = $this->normalizeSelectedValues($value);
            $isDiscreteTextSelection = is_array($value)
                && !empty($selectedValues)
                && !$this->areAllSelectedValuesNumeric($selectedValues)
                && !isset($request->operator[$key]);

            if(is_array($value)){
                if(isset($request->operator[$key]) && $request->operator[$key] == "-"){
                    $pos = strpos($value, ";");
                    if($pos !== false){
                        $pos = explode(";", $value);
                        $max = $pos[1];
                        $min = $pos[0];
                    }else{
                        $max = array_keys($value, max($value))[0];
                        $min = array_keys($value, min($value))[0];
                    }
                }else{
                    $max = array_keys($value, max($value))[0];
                    $min = array_keys($value, min($value))[0];
                }
            }else{
                $pos = strpos($value, ";");
                if($pos !== false){
                    $pos = explode(";", $value);
                    $max = $pos[1];
                    $min = $pos[0];
                }else{
                    $max = null;
                    $min = $value;
                }
            }

            $keys_[$key] = $isDiscreteTextSelection ? implode(', ', $selectedValues) : $max;

            if(!empty($varity_array)){
                $max == null ? $max = 0 : '';

                $query = Avg_characteristic::where('locality', $locality_id)->where('feature_id', $key)->whereNull('deleted_at');

                if(is_numeric($max) && is_numeric($min) && $max == 0 && $min == 0){
                    $varities = Varity::whereNotNull('name')->whereIn('id',$varity_list)->whereDoesntHave('characteristics', function (Builder $query) use($key) {
                        $query->where('feature_id', $key);
                    })->get();

                    $varity_list = [];

                    foreach ($varities as $value_) {
                        $varity_array[$value_->id][$key]["promedio"] = 0;
                        $varity_list[] = $value_->id;
                    }
                    break;
                }else if(is_numeric($max) && is_numeric($min) && $max > 0 && $max != $min){
                    $varities = $query->whereBetween('PROMEDIO',[$min, $max])->whereIn('varity_id',$varity_list);
                }else{
                    if(isset($request->operator[$key])){
                        $varities = $query->where('PROMEDIO', $request->operator[$key], (int)$min)->whereIn('varity_id',$varity_list);
                    }else{
                        if(is_numeric($min)){
                            $varities = $query->where('PROMEDIO',(int)$min)->whereIn('varity_id',$varity_list);
                        }
                        if(is_string($min)){
                            if($isDiscreteTextSelection){
                                $varities = $query->whereIn('PROMEDIO', $selectedValues)->whereIn('varity_id',$varity_list);
                            }else{
                                $varities = $query->where('PROMEDIO','like',$min)->whereIn('varity_id',$varity_list);
                            }
                        }
                    }
                }

                $varity_list = [];

                foreach ($varities->get() as $value_) {
                    if($value_->feature_id == $key){
                        $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                        $varity_list[] = $value_->varity_id;
                    }
                }
            }else{
                $query = Avg_characteristic::where('locality', $locality_id)->where('feature_id', $key)->whereNull('deleted_at');

                if(is_numeric($max) && is_numeric($min) && $max == 0 && $min == 0){
                    $varities = Varity::whereNotNull('name')->whereDoesntHave('characteristics', function (Builder $query) use($key) {
                        $query->where('feature_id', $key);
                    })->get();
                    foreach ($varities as $value_) {
                        $varity_array[$value_->id][$key]["promedio"] = 0;
                        $varity_array[$value_->id][$key]["ponderado"] = 0;
                        $varity_list[] = $value_->id;
                    }
                }else if(is_numeric($max) && is_numeric($min) && $max > 0 && $max != $min){
                    if(!in_array($key, $fries_list)){
                        $varities = $query->whereBetween('PROMEDIO',[$min, $max])->get();
                        foreach ($varities as $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                            $varity_list[] = $value_->varity_id;
                        }
                    }else{
                        $varities = Fries_avg_characteristics::where('feature_id', $key)->whereBetween('PROMEDIO',[$min, $max])->get();
                        foreach ($varities as $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                            $varity_array[$value_->varity_id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                            $varity_list[] = $value_->varity_id;
                        }
                    }
                }else{
                    if(isset($request->operator[$key])){
                        $varities = $query->where('PROMEDIO', $request->operator[$key], (int)$min);
                    }else{
                        if(is_numeric($min)){
                            $varities = in_array($key, $fries_list)
                                ? Fries_avg_characteristics::where('feature_id', $key)->whereBetween('PROMEDIO',[$min, $max])
                                : $query->where('PROMEDIO',(int)$min);
                        }elseif(is_string($min)){
                            if($isDiscreteTextSelection){
                                $selectedValues = array_map(function ($selectedValue) {
                                    return str_replace("'", "", $selectedValue);
                                }, $selectedValues);
                                $varities = $query->whereIn('PROMEDIO', $selectedValues);
                            }else{
                                $min = str_replace("'", "", $min);
                                $max = str_replace("'", "", $max);
                                $varities = $query->where('PROMEDIO','like',$min);
                                if($max != null && $max != $min){
                                    $varities->orWhere('PROMEDIO','like',$max);
                                }
                            }
                        }
                    }

                    foreach ($varities->get() as $value_) {
                        $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                        if(in_array($key, $fries_list)){
                            $varity_array[$value_->varity_id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                        }
                        $varity_list[] = $value_->varity_id;
                    }
                }
            }
        }

        $varities = array_values(array_unique($varity_list));
        $varity_data = $varity_array;
        $config_features = $keys_;
        $fries = $request->fries;
        $feature_names = $featuresById->pluck('name', 'id');

        $result_models = Varity::withTrashed()
            ->with(['father', 'mother'])
            ->whereIn('id', $varities)
            ->get()
            ->keyBy('id');

        $parent_counts = [];
        foreach ($result_models as $result_model) {
            if(!empty($result_model->father_id)){
                $parent_counts[$result_model->father_id] = ($parent_counts[$result_model->father_id] ?? 0) + 1;
            }
            if(!empty($result_model->mother_id)){
                $mothers_list[$result_model->mother_id] = ($mothers_list[$result_model->mother_id] ?? 0) + 1;
            }
        }

        $fathers_list = [];
        foreach ($parent_counts as $parent_id => $count) {
            $fathers_list[$parent_id] = [$count];
        }

        $parent_models = Varity::withTrashed()
            ->with(['father', 'mother'])
            ->whereIn('id', array_keys($parent_counts))
            ->get()
            ->keyBy('id');

        return View('varities.searchHome',compact(
            'varities',
            'varity_data',
            'config_features',
            'fries',
            'mothers_list',
            'fathers_list',
            'locality_id',
            'feature_names',
            'result_models',
            'parent_models'
        ));
    }

     public function searchHomeFries(Request   $request){

        $varities =  null;
        
        if( !function_exists('array_key_last') ) {
            function array_key_last(array $array) {
                if( !empty($array) ) return key(array_slice($array, -1, 1, true));
            }
        }

        $key_last = array_key_last($request->data);
        $keys_ = [];
        $varity_array = [];
        $varity_array_2 = [];
        $varity_list = [];

        #busqueda por ensayo:
        if($request->block && !isset($request->only_childrens)){

            $block_varity = Varity::withTrashed()->whereHas('blocksvarity', function (Builder $query) use($request) {
                                    $query->whereBlock_id($request->block);
                                })->get();

            foreach ($block_varity as $key_ => $value_) {
                $varity_array[$value_->id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                $varity_array[$value_->id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                $varity_list[] = $value_->id;
            }
        }

        #busqueda por hijos
        if(isset($request->only_childrens)){

            $childrens = Varity::withTrashed()->where('father_id', $request->varities_list)->orWhere('mother_id', $request->varities_list)->get();

            foreach ($childrens as $key_ => $value_) {
            if(!empty($value_->name) || !empty($value_->code)){
                    $varity_array[$value_->id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                    $varity_array[$value_->id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                    $varity_list[] = $value_->id;
                }
            }
        }

        foreach ($request->data as $key => $value) {

            if(is_array($value)){
                # RANGOS
                if(isset($request->operator[$key]) && $request->operator[$key] == "-"){
                    $pos = strpos($value, ";");
                    if($pos !== false){
                        $pos = explode(";", $value);
                        $max = $pos[1];
                        $min = $pos[0];
                    }else{
                        #Valores normales
                        $max = array_keys($value, max($value))[0];
                        $min = array_keys($value, min($value))[0];
                        is_numeric($min) ? (float) $min :  "";
                        is_numeric($max) ? (float) $max :  "";
                    }
                }else{
                    $max = array_keys($value, max($value))[0];
                    $min = array_keys($value, min($value))[0];
                    is_numeric($min) ? (float) $min :  "";
                    is_numeric($max) ? (float) $max :  "";
                }
            }else{
                # RANGOS
                $pos = strpos($value, ";");

                if($pos !== false){
                    $pos = explode(";", $value);
                    $max = $pos[1];
                    $min = $pos[0];
                }else{
                    #Valores normales
                    $max = null;
                    $min = $value;
                }
            }

            #Orden al imprimir los datos en la vista
            $keys_[$key] = $max;

            #Segunda iteracion, excepto cuando viene un ensayo seleccionado
            if(!empty($varity_array)){
                $max == null ? $max = 0 : '';
                if( is_numeric($max) && is_numeric($min) && $max == 0 && $min == 0){
                    $varities = Varity::whereNotNull('name')->whereIn('id',$varity_list)->whereDoesntHave('characteristics', function (Builder $query) use($key,$min,$max, $value, $request) {
                        $query->where("feature_id", $key);
                    })->get();

                    $llave= $key;
                    $varity_array = [];
                    $varity_list = [];

                    foreach ($varities as $key_ => $value_) {
                        $varity_array[$value_->id][$llave]["promedio"] = 0;
                        $varity_list[] = $value_->id;
                    }


                    break;

                }else if( is_numeric($max) && is_numeric($min) && $max > 0 && $max != $min){
                    $varities = Fries_avg_characteristics::where("feature_id", $key)->whereBetween("PROMEDIO",[$min, $max])->whereIn('varity_id',$varity_list);

                }else{

                    #VALOR UNICO CON OPERADOR LOGICO
                    if(isset($request->operator[$key])){
                        if($min > 0){
                            $varities = Fries_avg_characteristics::where("feature_id", $key)
                                                        ->where("PROMEDIO", $request->operator[$key],(int)$min)
                                                        ->whereIn('varity_id',$varity_list);
                        }else{
                            $varities = Fries_avg_characteristics::where("feature_id", $key)->where("PROMEDIO", $request->operator[$key], 0)->whereIn('varity_id',$varity_list);
                        }
                    }else{
                        #VALOR UNICO NUMERICO
                        if(is_numeric($min)){
                            $varities = Fries_avg_characteristics::where("feature_id", $key)
                                         ->where("PROMEDIO",(int)$min)->whereIn('varity_id',$varity_list);
                        }

                        # STRING
                        if(is_string($min)){

                            $varities = Fries_avg_characteristics::where("feature_id", $key)->where("PROMEDIO",'like',$min)->whereIn('varity_id',$varity_list);

                            if($max != null  and  $max != $min){
                                $varities->where("feature_id", $key)->orWhere("PROMEDIO",'like',$max)->whereIn('varity_id',$varity_list);
                            }
                        }
                    }
                }

                #Resultados
                $varity_list = [];

                foreach ($varities->get() as $key_ => $value_) {
                    if($value_->feature_id == $key){
                        $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                        $varity_array[$value_->varity_id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                        $varity_list[] = $value_->varity_id;
                    }
                }

            }else{

                if(is_numeric($max) && is_numeric($min) && $max == 0 && $min == 0){
                    $varities = Varity::whereNotNull('name')->whereDoesntHave('characteristics', function (Builder $query) use($key,$min,$max, $value, $request) {
                        $query->where("feature_id", $key);
                    })->get();

                    foreach ($varities as $key_ => $value_) {
                        $varity_array[$value_->id][$key]["promedio"] = 0;
                        $varity_list[] = $value_->id;
                    }
                }else if(is_numeric($max) && is_numeric($min) && $max > 0 && $max != $min){
                    $varities = Fries_avg_characteristics::where("feature_id", $key)->whereBetween("PROMEDIO",[$min, $max]);
                    $varities = $varities->get();

                    foreach ($varities as $key_ => $value_) {
                        $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                        $varity_array[$value_->varity_id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                        $varity_list[] = $value_->varity_id;
                    }
                }else{

                    #VALOR UNICO CON OPERADOR LOGICO
                    if(isset($request->operator[$key])){
                        if($min > 0){
                            $varities = Fries_avg_characteristics::where("feature_id", $key)
                                     ->where("PROMEDIO", $request->operator[$key],(int)$min);
                        }else{
                            $varities = Fries_avg_characteristics::where("feature_id", $key)->where("PROMEDIO", $request->operator[$key], 0);
                        }
                    }else{
                        #VALOR UNICO NUMERICO
                        if(is_numeric($min)){
                            $varities = Fries_avg_characteristics::where("feature_id", $key)
                                         ->where("PROMEDIO",(int)$min);
                        }

                        # STRING
                        if(is_string($min)){
                            $min = str_replace("'", "", $min);
                            $max = str_replace("'", "", $max);

                            $varities = Fries_avg_characteristics::where("feature_id", $key)->where("PROMEDIO",'like',$min);

                            if($max != null  and  $max != $min){
                                $varities->orWhere("PROMEDIO",'like',$max);
                            }
                        }
                    }

                    $varities = $varities->get();

                    foreach ($varities as $key_ => $value_) {

                        $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                        $varity_array[$value_->varity_id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                        $varity_list[] = $value_->varity_id;
                    }
                }
            }
        }

        $varities = $varity_list;
        $varity_data = $varity_array;
        $config_features = $keys_;
        $fries = $request->fries;

        return View('results.fries.searching',compact('varities','varity_data','config_features', 'fries'));
    }

    public function check_db(){

        $varities = Varity::all();
        $typefeatures = Typefeature::all();

        return View('varities.exportaData',compact('varities', 'typefeatures'));

    }

    public function searchHomeCrossing(Request   $request){

      $varities =  null;
        
        if( !function_exists('array_key_last') ) {
            function array_key_last(array $array) {
                if( !empty($array) ) return key(array_slice($array, -1, 1, true));
            }
        }

        $key_last = array_key_last($request->data);
        $keys_ = [];
        $varity_array = [];
        $varity_array_2 = [];
        $varity_list = [];
        $fathers_list = [];
        $mothers_list = [];
        $fries_list = [48,49,50,51];
        $locality_id = isset($request->locality_id) ? $request->locality_id : 1;
        $locality_original = isset($request->locality_id) ? $request->locality_id : 1;

        #busqueda por ensayo:
        if($request->block && !isset($request->only_childrens)){
            $block_varity = Varity::withTrashed()->whereHas('blocksvarity', function (Builder $query) use($request) {
                                    $query->whereBlock_id($request->block);
                                })->get();

            foreach ($block_varity as $key_ => $value_) {
                $varity_array[$value_->id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                $varity_list[] = $value_->id;
                !empty($value_->father_id) ? $fathers_list[$value_->father_id][] = $value_->father_id : "";
                !empty($value_->mother_id) ? $mothers_list[$value_->mother_id][] = $value_->mother_id : "";
            }
        }

        #busqueda por hijos
        if(isset($request->only_childrens)){

            if($request->block){
                $varity_list = array();
                $varity_array = array();
                $fathers_list = [];
                $mothers_list = [];
            }

            $childrens = Varity::withTrashed()->where('father_id', $request->varities_list)->orWhere('mother_id', $request->varities_list)->get();

            foreach ($childrens as $key_ => $value_) {
                if(!empty($value_->name)){
                    $varity_array[$value_->id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                    $varity_list[] = $value_->id;
                }
            }
        }


        foreach ($request->data as $key => $value) {

           $f = Feature::find($key)->localities_viewer;

           $locality_id = $f == 0 ? 1 : $locality_original;
           /*
           if($key == 18){
            dd($locality_id);
           }
           */

            if(is_array($value)){
                # RANGOS
                if(isset($request->operator[$key]) && $request->operator[$key] == "-"){
                    $pos = strpos($value, ";");
                    if($pos !== false){
                        $pos = explode(";", $value);
                        $max = $pos[1];
                        $min = $pos[0];
                    }else{
                        #Valores normales
                        $max = array_keys($value, max($value))[0];
                        $min = array_keys($value, min($value))[0];
                        is_numeric($min) ? (float) $min :  "";
                        is_numeric($max) ? (float) $max :  "";
                    }
                }else{
                    $max = array_keys($value, max($value))[0];
                    $min = array_keys($value, min($value))[0];
                    is_numeric($min) ? (float) $min :  "";
                    is_numeric($max) ? (float) $max :  "";
                }
            }else{
                # RANGOS
                $pos = strpos($value, ";");

                if($pos !== false){
                    $pos = explode(";", $value);
                    $max = $pos[1];
                    $min = $pos[0];
                }else{
                    #Valores normales
                    $max = null;
                    $min = $value;
                }
            }

            #Orden al imprimir los datos en la vista
            $keys_[$key] = $max;

            #Segunda iteracion, excepto cuando viene un ensayo seleccionado

            if(!empty($varity_array)){
                $max == null ? $max = 0 : '';

                if( is_numeric($max) && is_numeric($min) && $max == 0 && $min == 0){

                    $varities = Varity::whereNotNull('name')->whereIn('id',$varity_list)->whereDoesntHave('characteristics', function (Builder $query) use($key,$min,$max, $value, $request) {
                        $query->where("feature_id", $key);
                    })->get();

                    $llave= $key;
                    $varity_array = [];
                    $varity_list = [];
                    $fathers_list = [];
                    $mothers_list = [];

                    foreach ($varities as $key_ => $value_) {
                        $varity_array[$value_->id][$llave]["promedio"] = 0;
                        $varity_list[] = $value_->id;
                        !empty($value_->father_id) ? $fathers_list[$value_->father_id][] = $value_->father_id : "";
                        !empty($value_->mother_id) ? $fathers_list[$value_->mother_id][] = $value_->mother_id : "";
                    }


                    break;

                }else if( is_numeric($max) && is_numeric($min) && $max > 0 && $max != $min){


                    $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->whereBetween("PROMEDIO",[$min, $max])->whereIn('varity_id',$varity_list);
                }else{



                    #VALOR UNICO CON OPERADOR LOGICO
                    if(isset($request->operator[$key])){
                        if($min > 0){
                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)
                                                        ->where("PROMEDIO", $request->operator[$key],(int)$min)
                                                        ->whereIn('varity_id',$varity_list);
                        }else{
                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->where("PROMEDIO", $request->operator[$key], 0)->whereIn('varity_id',$varity_list);
                        }
                    }else{
                        #VALOR UNICO NUMERICO
                        if(is_numeric($min)){
                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)
                                         ->where("PROMEDIO",(int)$min)->whereIn('varity_id',$varity_list);
                        }

                        # STRING
                        if(is_string($min)){

                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->where("PROMEDIO",'like',$min)->whereIn('varity_id',$varity_list);

                            if($max != null  and  $max != $min){
                                $varities->where("feature_id", $key)->orWhere("PROMEDIO",'like',$max)->whereIn('varity_id',$varity_list);
                            }
                        }
                    }
                }

                #Resultados
                $varity_list = [];
                $fathers_list = [];
                $mothers_list = [];

                foreach ($varities->get() as $key_ => $value_) {
                    if($value_->feature_id == $key){
                        $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                        $varity_list[] = $value_->varity_id;
                        !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                        !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";
                    }
                }

            }else{

                if(is_numeric($max) && is_numeric($min) && $max == 0 && $min == 0){
                    $varities = Varity::whereNotNull('name')->whereDoesntHave('characteristics', function (Builder $query) use($key,$min,$max, $value, $request) {
                        $query->where("feature_id", $key);
                    })->get();

                    foreach ($varities as $key_ => $value_) {
                        $varity_array[$value_->id][$key]["promedio"] = 0;
                        $varity_array[$value_->id][$key]["ponderado"] = 0;
                        $varity_list[] = $value_->id;
                        !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                        !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";
                    }
                }else if(is_numeric($max) && is_numeric($min) && $max > 0 && $max != $min){

                    if(!in_array($key, $fries_list)){
                        $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->whereBetween("PROMEDIO",[$min, $max]);
                        $varities = $varities->get();
                        foreach ($varities as $key_ => $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                            $varity_list[] = $value_->varity_id;
                            !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                            !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";
                        }
                    }else{
                        $varities = Fries_avg_characteristics::where("feature_id", $key)->whereBetween("PROMEDIO",[$min, $max]);
                        $varities = $varities->get();
                         foreach ($varities as $key_ => $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                            $varity_array[$value_->varity_id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                            $varity_list[] = $value_->varity_id;
                            !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                            !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";
                        }
                    } 
                }else{

                    #VALOR UNICO CON OPERADOR LOGICO
                    if(isset($request->operator[$key])){
                        if($min > 0){
                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)
                                     ->where("PROMEDIO", $request->operator[$key],(int)$min);
                        }else{
                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->where("PROMEDIO", $request->operator[$key], 0);
                        }
                    }else{
                        #VALOR UNICO NUMERICO
                        if(is_numeric($min)){
                            if(!in_array($key, $fries_list)){
                               $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)
                                         ->where("PROMEDIO",(int)$min);
                            }else{
                                $varities = Fries_avg_characteristics::where("feature_id", $key)->whereBetween("PROMEDIO",[$min, $max]);
                            }
                        }

                        # STRING
                        if(is_string($min)){
                            $min = str_replace("'", "", $min);
                            $max = str_replace("'", "", $max);

                            $varities = Avg_characteristic::where('locality', $locality_id)->where("feature_id", $key)->where("PROMEDIO",'like',$min);

                            if($max != null  and  $max != $min){
                                $varities->orWhere("PROMEDIO",'like',$max);
                            }
                        }
                    }

                    $varities = $varities->get();

                    if(!in_array($key, $fries_list)){
                        foreach ($varities as $key_ => $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                            $varity_list[] = $value_->varity_id;
                            !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                            !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";

                        }
                    }else{
                         foreach ($varities as $key_ => $value_) {
                            $varity_array[$value_->varity_id][$value_->feature_id]["promedio"] = $value_->PROMEDIO;
                            $varity_array[$value_->varity_id][$value_->feature_id]["ponderado"] = $value_->ponderado;
                            $varity_list[] = $value_->varity_id;
                            !empty($value_->father_id) ? (isset($fathers_list[$value_->father_id]) ? $fathers_list[$value_->father_id][0]++ : $fathers_list[$value_->father_id][] = 1) : "";
                            !empty($value_->mother_id) ? (isset($fathers_list[$value_->mother_id]) ? $fathers_list[$value_->mother_id][0]++ : $fathers_list[$value_->mother_id][] = 1) : "";
                        }
                    }
                }
            }
        }

        $varities = $varity_list;
        $varity_data = $varity_array;
        $config_features = $keys_;
        $fries = $request->fries;

        return View('crossings.searchHome',compact('varities','varity_data','config_features','fries'));
    }

    public function uploadPhoto(Request $request){

        $ext = explode('.', $request->file->getClientOriginalName());
        $ext = end($ext);

        if(!in_array($ext, array('jpg','jpeg','png'))){
            return redirect('varities/'.$request->input('varity'))->with('alert','Debe agregar sólo imágenes con formato: jpg o png.'); 
        }

        if($_FILES['file']['type'] == "image/jpeg"){
            $ext = "jpg";
        }else if($_FILES['file']['type'] == "image/png"){
            $ext = "png";
        }else{
            return redirect('varities/'.$request->input('varity'))->with('alert','La imagen que estás tratando de subir, tiene problemas con el formato de imagen.');
        }

        $image = Image::make($request->file('file'));

        // upload and resize using Intervention Image 
        $filename = 'multimedias/foto-'.$request->input('varity')."_".time().'.'.$ext;

        $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })
        ->save($filename, 80);    
        
        if($image){
            $request->merge([
                'link' => $filename,
                'varity_id' => $request->input('varity')
            ]);

            $multimedia = Multimedia::create($request->all());
        }

        if(!empty($multimedia)){
            return redirect('varities/'.$request->input('varity'))->with('success','Foto agregada correctamente.');
        }
    }

    function stats_standard_deviation( $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        
        $mean = $a->sum('value') / $n;

        $carry = 0.0;

        foreach ($a as $val) {
            $d = ((double) $val->value) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
           --$n;
        }
        return round(sqrt($carry / $n), 2);
    }

    
}
