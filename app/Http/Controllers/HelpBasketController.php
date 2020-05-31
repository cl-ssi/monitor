<?php

namespace App\Http\Controllers;

use App\Basket\HelpBasket;
use Illuminate\Http\Request;

use App\Commune;

class HelpBasketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $helpbaskets = HelpBasket::all();
        return view('help_basket.index', compact('helpbaskets'));
    }

    public function georeferencing()
    {
        //

        $data = array();

        $helpbaskets = HelpBasket::all();

        foreach ($helpbaskets as $key => $helpbasket) {
            if ($helpbasket->latitude != null and $helpbasket->longitude != null) {
                $data[$helpbasket->address . " " . $helpbasket->number . ", " . $helpbasket->commune][$helpbasket->identifier]['paciente'] = $helpbasket;
            }
        }



        return view('help_basket.georeferencing',compact('helpbaskets','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //$communes = Commune::where('region_id', 1)->orderBy('name')->get();
        $communes = Commune::where('region_id',[env('REGION')])->orderBy('name')->get();
        return view('help_basket.create', compact('communes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $helpbaket = new HelpBasket($request->All());
        $helpbaket->user_id = auth()->user()->id;
        $helpbaket->save();

        session()->flash('success', 'Se recepcionó la canasta exitosamente');
        return redirect()->route('help_basket.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Basket\HelpBasket  $helpBasket
     * @return \Illuminate\Http\Response
     */
    public function show(HelpBasket $helpBasket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Basket\HelpBasket  $helpBasket
     * @return \Illuminate\Http\Response
     */
    public function edit(HelpBasket $helpBasket)
    {
        //
        $communes = Commune::where('region_id', 1)->orderBy('name')->get();
        return view('help_basket.edit', compact('helpBasket', 'communes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Basket\HelpBasket  $helpBasket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HelpBasket $helpBasket)
    {
        //
        $helpBasket->fill($request->all());
        $helpBasket = auth()->user()->id;
        $helpBasket->save();
        session()->flash('success', 'Se actualizo los datos exitosamente');
        return redirect()->route('help_basket.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Basket\HelpBasket  $helpBasket
     * @return \Illuminate\Http\Response
     */
    public function destroy(HelpBasket $helpBasket)
    {
        //
        $helpBasket->delete();
        session()->flash('success', 'Entrega de Canaste Eliminada Exitosamente');        
        return redirect()->route('help_basket.index');
    }
}
