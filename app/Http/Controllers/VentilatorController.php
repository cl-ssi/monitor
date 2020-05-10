<?php

namespace App\Http\Controllers;

use App\Ventilator;
use App\Patient;
use Illuminate\Http\Request;

class VentilatorController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ventilator  $ventilator
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $ventilator = Ventilator::first();

        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2','positive');
        })->with('suspectCases')->with('demographic')->get();

        $patients = $patients->whereNotIn('demographic.region',
                    [
                    'Arica y Parinacota',
                    'Antofagasta',
                    'Atacama',
                    'Coquimbo',
                    'Valparaíso',
                    'Región del Libertador Gral. Bernardo O’Higgins',
                    'Región del Maule',
                    'Región del Biobío',
                    'Región de la Araucanía',
                    'Región de Los Ríos',
                    'Región de Los Lagos',
                    'Región Aisén del Gral. Carlos Ibáñez del Campo',
                    'Región de Magallanes y de la Antártica Chilena',
                    'Región Metropolitana de Santiago',
                    'Región de Ñuble']);
        $ct_covid = $patients->where('status', 'Hospitalizado UCI')->count();

        return view('parameters.ventilators.edit', compact('ventilator','ct_covid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ventilator  $ventilator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $ventilator = Ventilator::first();
        $ventilator->fill($request->all());
        $ventilator->save();

        session()->flash('info', 'Los datos fueron guardaros correctamente');

        return redirect()->back();
    }

}
