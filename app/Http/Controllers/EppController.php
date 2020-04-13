<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pharmacies\Dispatch;
use Carbon\Carbon;

class EppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $establishment = $request->establishment;
      //dd($establishment);
      if($from = $request->has('from')){
        $from = $request->get('from'). ' 00:00:00';
        $to = $request->get('to'). ' 23:59:59';
      }else{
        $from = Carbon::now()->firstOfMonth();
        $to = Carbon::now()->lastOfMonth();
      }

      $dispatchs = Dispatch::whereBetween('date',[$from,$to])
                           ->when($establishment, function ($query, $establishment) {
                                return $query->where('establishment', $establishment);
                           })
                           ->orderBy('date','DESC')
                           ->get();
      $pharmacies = Dispatch::select('establishment')->distinct('establishment')->get();
      //dd($pharmacies);
      return view('epp.index', compact('dispatchs','from','to','pharmacies','establishment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
