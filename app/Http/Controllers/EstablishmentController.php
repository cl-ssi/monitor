<?php

namespace App\Http\Controllers;
use App\Establishment;
use App\Commune;

use Illuminate\Http\Request;

class EstablishmentController extends Controller
{
    //
    //
    public function index()
    {
        $communes_ids = array_map('trim', explode(",", env('COMUNAS')));
        $establishments = Establishment::where('commune_id',$communes_ids)->get();
        //$communes = Commune::whereIn('id', $communes_ids)->get();
        return view('parameters.establishment.index', compact('establishments'));
    }

    public function create()
    {
        //$communes = Commune::All();
        $communes_ids = array_map('trim', explode(",", env('COMUNAS')));
        $communes = Commune::whereIn('id', $communes_ids)->get();
        return view('parameters.establishment.create',compact('communes'));
    }

    public function store(Request $request)
    {
        $establishment = new Establishment($request->All());
        $establishment->save();
        session()->flash('success', 'Se creo establecimiento exitosamente');
        return redirect()->route('parameters.establishment');
    }

    public function edit(Establishment $establishment)
    {
        //$communes = Commune::All();
        $communes_ids = array_map('trim', explode(",", env('COMUNAS')));
        $communes = Commune::whereIn('id', $communes_ids)->get();
        return view('parameters.establishment.edit', compact('establishment','communes'));
    }

    public function update(Request $request, Establishment $establishment)
    {
        //dd('acá es donde se actualiza el formulario de antes');
        $establishment->fill($request->all());
        $establishment->save();
        session()->flash('success', 'Se modificó el establecimiento exitosamente');
        return redirect()->route('parameters.establishment');
    }
}
