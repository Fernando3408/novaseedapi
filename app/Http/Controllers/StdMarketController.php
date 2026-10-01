<?php

namespace App\Http\Controllers;

use App\Stdmarket;
use App\Models\Varity;
use Illuminate\Http\Request;

class StdMarketController extends Controller
{
    public function index()
    {
        $stdmarket = Stdmarket::All();

        return View('stdmarket.index',compact('stdmarket'));
    }

    public function create()
    {
        $varities = Varity::All();

        return View('stdmarket.index',compact('varities'));
    }

    public function store(){
    	
    	Stdmarket::create($request->all());

        return back()->with('success','Cruce creado corectamente.');
    }
}
