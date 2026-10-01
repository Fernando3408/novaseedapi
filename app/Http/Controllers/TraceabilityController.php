<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\Region;
use App\Models\Province;
use App\Models\Varity;
use App\Land;
use App\Farmer;
use App\Traceability;
use App\Certifies;

class TraceabilityController extends Controller
{
    public function index()
    {
        $farmer = Traceability::All();
        return View('traceability.index',compact('farmer'));
    }

    /**
     * Vista para crear usuario
     */
    public function create()
    {	

        $farmer = Farmer::pluck('name','id');
        $certifies = Certifies::pluck('name','id');
        $varity = Varity::where('name',"!=", "")->pluck('name','id');

        return View('traceability.create', compact('farmer','varity','certifies'));
    }

    /**
     * Agrega nuevo usuario en base de datos
     */
    public function store(Request $request)
    {
        
        Traceability::create($request->all());

        return back()->with('success','Trazabilidad creada correctamente.');
    }


    /**
     * Vista para editar usuario
     */
    public function edit($id)
    {
        $traceability = Traceability::find($id);
        $varity = Varity::where('name',"!=", "")->pluck('name','id');
        $farmer = Farmer::where('name',"!=", "")->pluck('name','id');
        $certifies = Certifies::pluck('name','id');

        return view('traceability.edit',compact('traceability','farmer', 'varity', 'certifies'));
    }

    /**
     * Actualiza usuario en base de datos
     */
    public function update(Request $request, $id)
    {

        Traceability::find($id)->update($request->all());

        return back()->with('success','Trazabilidad editada corectamente.');
    }

    /**
     * Elimina usuario
     */
    public function destroy(Traceability $traceability)
    {
        Traceability::find($traceability->id)->delete();
     
        return back()->with('success','Trazabilidad eliminado correctamente.');
    }
}
