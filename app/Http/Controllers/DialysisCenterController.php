<?php

namespace App\Http\Controllers;

use App\Dialysis\DialysisCenter;
use App\Commune;
use Illuminate\Http\Request;

class DialysisCenterController extends Controller
{
    //

    public function index()
    {
        $dialysiscenters = DialysisCenter::All();
        return view('parameters.dialysis_center.index', compact('dialysiscenters'));
    }


    public function create()
    {   
        $communes = Commune::All();
        return view('parameters.dialysis_center.create',compact('communes'));
    }


    
    public function store(Request $request)
    {
        $dialysiscenters = new  DialysisCenter($request->All());
        $dialysiscenters->save();
        session()->flash('success', '¡Centro de Dialisis Creado Exitosamente!');
        return redirect()->route('parameters.dialysis_center');
    }


    public function edit(DialysisCenter $dialysis_center)
    {
        
        $communes = Commune::All();
        return view('parameters.dialysis_center.edit', compact('dialysis_center','communes'));
    }

    public function update(Request $request, DialysisCenter $dialysis_center)
    {
      $dialysis_center->fill($request->all());
      $dialysis_center->save();
      session()->flash('success', '¡Centro de Dialisis Actualizado Exitosamente!');
      return redirect()->route('parameters.dialysis_center');
    }
}
