<?php

namespace App\Http\Controllers;
use App\RapidTest;
use Illuminate\Http\Request;

class RapidTestController extends Controller
{
    //

    public function store(Request $request, $store)
    {
        $patient = $request->input('patient_id');

        $rapidTest = new RapidTest($request->All());
        $rapidTest->save();

        if($store == 'modal'){
            session()->flash('success', 'Se añadio correctamente el test rápido');
            return redirect()->route('patients.edit', $patient);
        }

        if($store == 'inmuno_form'){
            return redirect()->route('lab.inmuno_tests.index');
        }
    }
}

