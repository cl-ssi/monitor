<?php

namespace App\Http\Controllers;

use App\SuspectCase;
use App\Patient;
use App\Demographic;
//use App\Log;
use App\Region;
use App\Commune;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $patients = Patient::search($request->input('search'))
                        ->with('demographic')
                        ->with('suspectCases')
                        ->with('contactPatient')
                        ->orderBy('name')
                        ->paginate(250);
        return view('patients.index', compact('patients','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $patient = new Patient($request->All());
        $patient->save();

        //$log = new Log();
        //$log->old = $patient;
        //$log->new = $patient;
        //$log->save();

        return redirect()->route('patients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        // dd($patient);
        $regions = Region::orderBy('id','ASC')->get();
        $communes = Commune::orderBy('id','ASC')->get();
        return view('patients.edit',compact('patient', 'regions', 'communes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        //$logPatient = new Log();
        //$logPatient->old = clone $patient;

        $patient->fill($request->all());
        $patient->save();

        //$logPatient->new = $patient;
        //$logPatient->save();

        //$logDemographic = new Log();
        if($patient->demographic) {
            //$logDemographic->old = clone $patient->demographic;

            $patient->demographic->fill($request->all());
            $patient->demographic->save();

            //$logDemographic->new = $patient->demographic;
            //$logDemographic->save();
        }
        else {

            if ($request->address != null | $request->address != null | $request->deparment != null |
                    $request->town != null | $request->latitude != null | $request->longitude != null |
                    $request->email != null | $request->telephone != null | $request->number != null |
                    $request->region != null | $request->commune != null) {

                $demographic = new Demographic($request->All());
                $demographic->patient_id = $patient->id;
                $demographic->save();

                //$logDemographic->new = $demographic;
                //$logDemographic->save();
            }
        }
        //$logDemographic->save();

        return redirect()->route('patients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //$log = new Log();
        //$log->old = clone $patient;
        //$log->new = $patient->setAttribute('patient','delete');
        //$log->save();

        $patient->delete();

        session()->flash('success', 'Paciente Eliminado exitosamente');

        return redirect()->route('patients.index');
    }

    public function positives(Request $request)
    {
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2','positive');
        })->with('demographic')->get();

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

        return view('patients.positives', compact('patients'));
    }


    public function getPatient($rut)
    {
        return Patient::where('run',$rut)->orWhere('other_identification',$rut)->first();
    }

    public function georeferencing()
    {
        $date = \Carbon\Carbon::today()->subDays(21);
        // $users = User::where('created_at', '>=', $date)->get();
        // $suspectCases = SuspectCase::latest('id')->get();
        $suspectCases = SuspectCase::where('pscr_sars_cov_2_at', '>=', $date)
                                   ->where('pscr_sars_cov_2', 'positive')
                                   ->whereHas('patient', function ($q) {
                                        $q->whereNotIn('status',['Hospitalizado UTI','Hospitalizado UCI','Hospitalizado Básico','Hospitalizado Crítico','Residencia Sanitaria','Fallecido','Alta']);
                                    })
                                   ->get();

        $data = array();
        foreach ($suspectCases as $key => $case) {
          if ($case->pscr_sars_cov_2 == 'positive'){// || $case->pscr_sars_cov_2 == 'pending') {
              // FIX , pendiente ver que pasó que hay un caso sin paciente asociado
              if($case->patient) {
                  if ($case->patient->demographic != null) {
                    $data[$case->patient->demographic->address . " " . $case->patient->demographic->number . ", " . $case->patient->demographic->commune][$case->patient->run]['paciente']=$case;
                  }
              }
          }
        }
        // dd($data);
        // foreach ($data as $key1 => $data1) {
        //   foreach ($data1 as $key2 => $data2) {
        //     foreach ($data2 as $key3 => $data3) {
        //       print_r($data3->patient->demographic->latitude);
        //     }
        //   }
        // }

        return view('patients.georeferencing.georeferencing', compact('suspectCases','data'));
    }

    public function export()
    {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=pacientes.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $filas = Patient::all();

        $columnas = array(
            'ID',
            'RUN',
            'DV',
            'Otro Doc',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Genero',
            'Fecha Nacimiento',
            'Positivo',
            'Coumuna',
            'Direccion'
        );

        $callback = function() use ($filas, $columnas)
        {
            $file = fopen('php://output', 'w');
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            fputcsv($file, $columnas,';');

            foreach($filas as $fila) {
                fputcsv($file, array(
                    $fila->id,
                    $fila->run,
                    $fila->dv,
                    $fila->other_identification,
                    $fila->name,
                    $fila->fathers_family,
                    $fila->mothers_family,
                    $fila->gender,
                    ($fila->birthday)?$fila->birthday->format('d-m-Y'):'',
                    ($fila->suspectCases->where('pscr_sars_cov_2','positive')->first())?'Si':'No',
                    ($fila->demographic)?$fila->demographic->commune:'',
                    ($fila->demographic)?$fila->demographic->address.' '.$fila->demographic->number:''
                ),';');
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Descarga archivo csv de pacientes covid positivos con georeferencia.
     * @return StreamedResponse
     */
    public function exportPositives(){
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=pacientes_covid_positivo.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $filas = Patient::positivesList();

        $columnas = array(
            'ID',
            'RUN',
            'DV',
            'Otro Doc',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Genero',
            'Fecha Nacimiento',
            'Comuna',
            'Direccion',
            'Latitud',
            'Longitud'
        );

        $callback = function() use ($filas, $columnas)
        {
            $file = fopen('php://output', 'w');
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            fputcsv($file, $columnas,';');

            foreach($filas as $fila) {
                fputcsv($file, array(
                    $fila->id,
                    $fila->run,
                    $fila->dv,
                    $fila->other_identification,
                    $fila->name,
                    $fila->fathers_family,
                    $fila->mothers_family,
                    $fila->gender,
                    ($fila->birthday)?$fila->birthday->format('d-m-Y'):'',
                    ($fila->demographic)?$fila->demographic->commune:'',
                    ($fila->demographic)?$fila->demographic->address.' '.$fila->demographic->number:'',
                    ($fila->demographic)?$fila->demographic->latitude:'',
                    ($fila->demographic)?$fila->demographic->longitude:''

                ),';');
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);

    }
}
