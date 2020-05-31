<?php

namespace App\Http\Controllers;

use App\Food\FoodBasket;
use Illuminate\Http\Request;

use App\Region;
use App\Commune;

class FoodBasketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $foodbaskets = FoodBasket::all();
        return view('food.index',compact('foodbaskets'));
    }

    public function georeferencing()
    {
        //
        return view('food.georeferencing');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        dd(env('REGION'));
        //$communes = Commune::where('region_id',1)->orderBy('name')->get();
        //$communes = Commune::where('region_id',[env('REGION')])->orderBy('name')->get();
        return view('food.create', compact('regions','communes'));
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
        $foodbasket = new FoodBasket($request->All());
        $foodbasket->save();

        session()->flash('success', 'Se recepcionó la canasta exitosamente');
        return redirect()->route('food.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Food\FoodBasket  $foodBasket
     * @return \Illuminate\Http\Response
     */
    public function show(FoodBasket $foodBasket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Food\FoodBasket  $foodBasket
     * @return \Illuminate\Http\Response
     */
    public function edit(FoodBasket $foodBasket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Food\FoodBasket  $foodBasket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoodBasket $foodBasket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Food\FoodBasket  $foodBasket
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodBasket $foodBasket)
    {
        //
    }
}
