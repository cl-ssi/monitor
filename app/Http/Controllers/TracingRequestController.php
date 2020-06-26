<?php

namespace App\Http\Controllers;

use App\Tracing\TracingRequest;
use App\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TracingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function social_index()
    {
        $tracing_request = TracingRequest::whereHas('tracing', function($q) {
                $q->where('status',1)
                ->whereIn('establishment_id', auth()->user()->establishments->pluck('id'));
            })->get();

        $request_types = TracingRequest::select('request_type_id')
        ->whereHas('tracing', function($q) {
                $q->where('status',1)
                ->whereIn('establishment_id', auth()->user()->establishments->pluck('id'));
            })
        ->groupBy('request_type_id')
        ->get();

        return view('patients.tracing.social_index', compact('request_types', 'tracing_request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tracing_request = new TracingRequest($request->All());
        $tracing_request->user_id = auth()->id();

        $tracing_request->save();

        session()->flash('info', 'Estimado Usuario: Su solicitud fue correctamente almacenada.');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TracingRequest  $tracing_request
     * @return \Illuminate\Http\Response
     */
    public function show(TracingRequest $tracing_request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TracingRequest  $tracing_request
     * @return \Illuminate\Http\Response
     */
    public function edit(TracingRequest $tracing_request)
    {
        //
    }

    public function request_complete(TracingRequest $tracing_request)
    {
        return view('patients.tracing.social_request_complete', compact('tracing_request'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TracingRequest  $tracing_request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TracingRequest $tracing_request)
    {

    }

    public function request_complete_update(Request $request, TracingRequest $tracing_request)
    {
        $date_request_complete = Carbon::now();
        $tracing_request->fill($request->all());

        $tracing_request->request_complete_at = $date_request_complete;
        $tracing_request->user_complete_request_id = auth()->id();

        $tracing_request->save();

        session()->flash('info', 'Estimado Usuario: la solicitud fue correctamente actualizada.');

        return redirect()->action('TracingRequestController@social_index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TracingRequest  $tracing_request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }
}
