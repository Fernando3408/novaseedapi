<?php

namespace App\Imports;

use App\Models\Varity;
use App\Models\Feature;
use App\Models\Characteristic;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class VaritiesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $features = array();
        $indexes  = array();
        $temp    = array();

        dd($rows);

        foreach ($rows as $key => $row) {
            if ($key == 0) {
                foreach ($row as $key_s => $value) {
                    if($value != " "){
                        $temp[] = $value;

                        $v = str_replace(",", ".", $value);
                        $v = (float)$v;
                        $v = round($v, 2);

                        $indexes[$value] = [
                            "id_feature" => $v,
                            "value" => "",
                            "year" => "5",
                            "type" => 2,
                            "block_id" => 46
                        ];
                    }
                }
            }
            
            if($key > 0){
                $id = 0;

                foreach ($row as $key_r => $r) {

                    if($key_r == 0){

                        $v = Varity::where('name','LIKE','%'.$r.'%')->count();

                        if($v == 0){
                            Varity::Create([
                                "name" => $r
                            ]);

                            $res = Varity::latest()->first();
                            $id = $res->id;
                        }else{
                             $res = Varity::where('name','LIKE','%'.$r.'%')->first();
                             $id= $res->id;
                        }

                        $index = $id;
                        $features[$index] = [];

                    }else if($key_r > 0) {
                        @$indexes[@$temp[$key_r]]["value"] = $r;

                    }
                }
                array_push($features[$index], $indexes);

            }

        }

        foreach ($features as $key => $value) {

            foreach ($value[0] as $k => $v) {
                $v['value'] == "" ? $v['value'] = 0 : "";

                if($v['id_feature'] != 'varity_name'){
                    $char = new Characteristic;
                    $char->varity_id = $key;
                    $char->feature_id = $v['id_feature'];
                    $char->year  = $v['year'];
                    $char->value = $v['value'];
                    $char->block_id = $v['block_id'];
                    $char->save();
                    
                    /*
                    $model = Characteristic::whereVarity_id($key);
                    if($model){
                        $model->delete();
                    }
                    */
                }
            }
        }

        return json_encode($features);
        
        die();
    }
}
