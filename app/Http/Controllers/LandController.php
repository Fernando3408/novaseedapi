<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\Region;
use App\Models\Province;
use App\Land;
use App\Farmer;

class LandController extends Controller
{
      public function index()
    {
        $farmer = Land::All();
        return View('lands.index',compact('farmer'));
    }

    /**
     * Vista para crear usuario
     */
    public function create()
    {	

        $region = Region::pluck('name','id');
        $farmer = Farmer::pluck('name','id');

        return View('lands.create', compact('region', 'farmer'));
    }

    /**
     * Agrega nuevo usuario en base de datos
     */
    public function store(Request $request)
    {
        
        Land::create($request->all());

        return back()->with('success','Agricultor creado correctamente.');
    }


    /**
     * Vista para editar usuario
     */
    public function edit($id)
    {
        $land = Land::find($id);
        $farmer = Farmer::pluck('name','id');
        $region = Region::pluck('name','id');
        $communities = Community::pluck('name','id');
        $provinces = Province::pluck('name','id');

        return view('lands.edit',compact('region','farmer', 'communities', 'provinces','land'));
    }

    /**
     * Actualiza usuario en base de datos
     */
    public function update(Request $request, $id)
    {

        Land::find($id)->update($request->all());

        return back()->with('success','Campo editado corectamente.');
    }

    /**
     * Elimina usuario
     */
    public function destroy(Land $land)
    {		
        Land::find($land->id)->delete();
     
        return back()->with('success','Campo eliminado correctamente.');
    }

    
}
