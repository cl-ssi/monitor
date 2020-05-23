<?php

namespace App\Http\Controllers;

use App\Lab\Exam\Covid19;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Covid19Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $exams = Covid19::search($request->input('search'))->get();
        return view('lab.exams.covid19.index', compact('exams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lab.exams.covid19.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $covid19 = new Covid19($request->All());
        $covid19->user_id = Auth::id();
        $covid19->save();

        session()->flash('info', 'La mustra ha sido ingresada: '.$covid19->id);

        return redirect()->route('lab.exams.covid19.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lab\Exam\Covid19  $covid19
     * @return \Illuminate\Http\Response
     */
    public function show(Covid19 $covid19)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lab\Exam\Covid19  $covid19
     * @return \Illuminate\Http\Response
     */
    public function edit(Covid19 $covid19)
    {
        return view('lab.exams.covid19.edit', compact('covid19'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lab\Exam\Covid19  $covid19
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Covid19 $covid19)
    {
        $covid19->fill($request->all());
        $covid19->result_at = date('Y-m-d H:i:s');
        $covid19->validator_id = Auth::id();
        $covid19->file = $request->file('file')->store('external_results');
        $covid19->save();

        session()->flash('info', 'El resultado ha sido cargado a muestra: '.$covid19->id);

        return redirect()->route('lab.exams.covid19.index');
    }

    public function reception(Request $request, Covid19 $covid19)
    {
        $covid19->fill($request->all());
        $covid19->receptor_id = Auth::id();
        $covid19->save();

        session()->flash('info', 'El examen ha sido recepcionado: '.$covid19->id);

        return redirect()->route('lab.exams.covid19.edit', $covid19);
    }

    public function addresult(Request $request, Covid19 $covid19)
    {
        $covid19->fill($request->all());
        $covid19->result_at = date('Y-m-d H:i:s');
        $covid19->validator_id = Auth::id();
        if($request->file('file')) {
            $covid19->file = $request->file('file')->storeAs('external_results',$covid19->id);
        }
        $covid19->save();

        session()->flash('info', 'El resultado ha sido cargado a muestra: '.$covid19->id);

        return redirect()->route('lab.exams.covid19.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lab\Exam\Covid19  $covid19
     * @return \Illuminate\Http\Response
     */
    public function destroy(Covid19 $covid19)
    {
        //
    }

    public function download($storage, $file) {
        return Storage::download($storage.'/'.$file, 'resultado.pdf');
    }
}
