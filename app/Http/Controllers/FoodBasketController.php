<?php

namespace App\Http\Controllers;

use App\Food\FoodBasket;
use Illuminate\Http\Request;

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

        return view('food.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('food.create');
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
