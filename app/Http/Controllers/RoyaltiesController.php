<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Royalties;
use App\Farmer;

class RoyaltiesController extends Controller
{   

    public function show($id = null)
    {   
        $royalties = Royalties::whereFarmerId($id)->get();
        $farmer = Farmer::find($id);

        return View('farmers.royalties.index', compact('royalties','farmer'));
    }
    /**
     * Vista para crear usuario
     */
    public function create($farmer_id = null)
    {	
        return View('farmers.royalties.create', compact('farmer_id'));
    }

    /**
     * Agrega nuevo usuario en base de datos
     */
    public function store(Request $request)
    {
        Royalties::create($request->all());

        return redirect()->route('royalties.show',['id' => $request->farmer_id])->with('success','Royalty agregado correctamente.');
    }


    /**
     * Vista para editar usuario
     */
    public function edit($id)
    {
        $royalty = Royalties::find($id);
        $farmer_id = $royalty->farmer_id;

        return view('farmers.royalties.edit',compact('royalty','farmer_id'));
    }

    /**
     * Actualiza usuario en base de datos
     */
    public function update(Request $request, $id)
    {

        Royalties::find($id)->update($request->all());

        return back()->with('success','Royalty editado corectamente.');
    }

    /**
     * Elimina usuario
     */
    public function destroy(Royalties $royalty)
    {
        Royalties::find($royalty->id)->delete();
     
        return back()->with('success','Royalty eliminado correctamente.');
    }
}
