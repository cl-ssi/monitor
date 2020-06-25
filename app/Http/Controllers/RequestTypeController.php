<?php

namespace App\Http\Controllers;

use App\Tracing\RequestType;
use Illuminate\Http\Request;

class RequestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request_types = RequestType::orderby('name', 'ASC')
            ->get();

        return view('parameters.request_type.index', compact('request_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('parameters.request_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request_types = new RequestType($request->All());
        $request_types->save();
        session()->flash('success', '¡Tipo de solicitud creado con éxito!');
        return redirect()->route('parameters.request_type');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\Response
     */
    public function show(RequestType $requestType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestType $requestType)
    {
        return view('parameters.request_type.edit', compact('requestType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestType $requestType)
    {
      $requestType->fill($request->all());
      $requestType->save();
      session()->flash('success', '¡Tipo de solicitud modificada exitosamente!');
      return redirect()->route('parameters.request_type');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestType $requestType)
    {
        //
    }
}
