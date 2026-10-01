<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;


class CompanyController extends Controller
{
        public function index()
    {
        $Companies = Companies::All();
        return View('companies.index',compact('Companies'));
    }

    /**
     * Vista para crear usuario
     */
    public function create()
    {	

        return View('companies.create');
    }

    /**
     * Agrega nuevo usuario en base de datos
     */
    public function store(Request $request)
    {
        
        Companies::create($request->all());

        return back()->with('success','Agricultor creado correctamente.');
    }


    /**
     * Vista para editar usuario
     */
    public function edit($id)
    {
        $Companies = Companies::find($id);
        $region = Region::All();
        $communities = Community::All();
        $provinces = Province::All();

        return view('companies.edit',compact('region','Companies', 'communities', 'provinces'));
    }

    /**
     * Actualiza usuario en base de datos
     */
    public function update(Request $request, $id)
    {

        Companies::find($id)->update($request->all());

        return back()->with('success','Empresa editado corectamente.');
    }

    /**
     * Elimina usuario
     */
    public function destroy(Companies $Companies)
    {
        Companies::find($farmer->id)->delete();
     
        return back()->with('success','Empresa eliminado correctamente.');
    }
}
