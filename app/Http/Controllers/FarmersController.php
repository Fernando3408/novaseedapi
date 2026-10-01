<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Farmer;
use App\Land;
use App\Royalties;
use App\Models\Community;
use App\Models\Region;
use App\Models\Province;

class FarmersController extends Controller
{
    public function index()
    {
        $farmer = Farmer::All();
        return View('farmers.index',compact('farmer'));
    }

    /**
     * Vista para crear usuario
     */
    public function create()
    {	

        $region = Region::All();

        return View('farmers.create', compact('region'));
    }

    /**
     * Agrega nuevo usuario en base de datos
     */
    public function store(Request $request)
    {
        
        Farmer::create($request->all());

        return back()->with('success','Agricultor creado correctamente.');
    }


    /**
     * Vista para editar usuario
     */
    public function edit($id)
    {
        $farmer = Farmer::find($id);
        $region = Region::All();
        $communities = Community::All();
        $provinces = Province::All();

        return view('farmers.edit',compact('region','farmer', 'communities', 'provinces'));
    }

    /**
     * Actualiza usuario en base de datos
     */
    public function update(Request $request, $id)
    {

        Farmer::find($id)->update($request->all());

        return back()->with('success','Usuario editado corectamente.');
    }

    /**
     * Elimina usuario
     */
    public function destroy(Farmer $farmer)
    {
        Farmer::find($farmer->id)->delete();
     
        return back()->with('success','Usuario eliminado correctamente.');
    }

    public function getProvinces(){
        $region_id = $_GET["region_id"];
        $provinces = Province::where('region_id','=',$region_id)->get();
        return json_encode($provinces);
    }

    public function getCommunities(){
        if(isset($_GET["province_id"])){
            $province_id = $_GET["province_id"];
            $communities = Community::where('province_id','=',$province_id)->get();
        }else{
            $communities = Community::where('country_id','=', $_GET['country_id'])->get();
        }
        
        return json_encode($communities);
    }

     public function getLands(){
        $farmer_id = $_GET["farmer_id"];
        $lands = Land::where('farmer_id',$farmer_id)->get();
        return json_encode($lands);
    }

    
}
