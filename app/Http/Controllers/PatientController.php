<?php

namespace App\Http\Controllers;

use App\Country;
use App\SuspectCase;
use App\Patient;
use App\Demographic;
use App\Region;
use App\Commune;
use App\Establishment;
use App\Tracing\EventType;
use App\Tracing\RequestType;
use App\Tracing\Symptom;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Establishment $establishment)
    {
        $patients = Patient::search($request->input('search'))
                              ->with('demographic')
                              ->with('suspectCases')
                              ->with('contactPatient')
                              ->orderBy('name')
                              ->paginate(250);

        return view('patients.index', compact('patients','request','establishment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $regions = Region::orderBy('id','ASC')->get();
        $communes = Commune::orderBy('id','ASC')->get();
        $countries = Country::select('name')->orderBy('id', 'ASC')->get();

        return view('patients.create', compact('regions', 'communes', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'run' => 'nullable|unique:patients'
        ],
        [
            'run.unique' => 'Este rut ya está registrado.'
        ]);

        $patient = new Patient($request->All());
        $patient->save();

        $demographic = new Demographic($request->All());
        $demographic->patient_id = $patient->id;
        $demographic->save();

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
        // obtiene linea de tiempo
        $timeline = array();
        foreach ($patient->audits as $key => $audit) {
            // print_r($audit->created_at->format('Y-m-d H:m'));
            // print_r($audit->old_values);
            // print_r($audit->new_values);
            // print_r("***<br />");
            if ($key == 0) {
                if (array_key_exists('status',$audit->old_values)) {
                    $timeline[$audit->created_at->format('Y-m-d H:m')] = 'Paciente creado - ' . $audit->new_values['status'];
                }else{
                    $timeline[$audit->created_at->format('Y-m-d H:m')] = 'Paciente creado';
                }

            }else{
                if (array_key_exists('status',$audit->new_values)) {
                    if ($audit->new_values['status'] == NULL || $audit->new_values['status'] == '') {
                        $timeline[$audit->created_at->format('Y-m-d H:m')] = 'Ambulatorio';
                    }else{
                        $timeline[$audit->created_at->format('Y-m-d H:m')] = $audit->new_values['status'];
                    }
                }
            }
        }
        // dd($timeline);

        $regions = Region::orderBy('id','ASC')->get();
        $communes = Commune::orderBy('id','ASC')->get();
        $countries = Country::select('name')->orderBy('id', 'ASC')->get();
        $event_types = EventType::all();
        $request_types = RequestType::all();
        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id',$env_communes)->orderBy('name','ASC')->get();
        $symptoms = Symptom::All();
        return view('patients.edit',compact('patient', 'regions', 'communes','event_types', 'request_types','establishments','symptoms', 'countries', 'timeline'));
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
            $q->where('pcr_sars_cov_2','positive');
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
        return Patient::where('run',$rut)->first();
    }

    public function getPatientOtherIdentification($other_identification)
    {
        return Patient::where('other_identification',$other_identification)->first();
    }

    public function georeferencing()
    {
        $date = \Carbon\Carbon::today()->subDays(30);
        // $users = User::where('created_at', '>=', $date)->get();
        // $suspectCases = SuspectCase::latest('id')->get();
        $suspectCases = SuspectCase::
        where('pcr_sars_cov_2_at', '>=', $date)->
                                   where('pcr_sars_cov_2', 'positive')
                                ->whereHas('patient', function ($q) {
                                        $q->whereIn('status',['Ambulatorio',''])
                                          ->OrWhereNULL('status');
                                    })

                                   ->get();

        $data = array();
        foreach ($suspectCases as $key => $case) {
          if ($case->pcr_sars_cov_2 == 'positive'){// || $case->pcr_sars_cov_2 == 'pending') {
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
                    ($fila->suspectCases->where('pcr_sars_cov_2','positive')->first())?'Si':'No',
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

    public function inResidence()
    {
        if(auth()->user()->establishments->count() == 0){
            session()->flash('info', 'Usuario no tiene establecimientos asociados.');
            return redirect()->back();
        }

        $patients = Patient::whereHas('demographic', function($q) {
            $q->whereIn('commune_id', auth()->user()->communes());
        })
            ->where(function ($q) {
                $q->where('status','Residencia Sanitaria');
            })
            ->with('tracing')
            ->with('demographic')
            ->get();

        $titulo = 'En residencia';
        return view('patients.tracing.index', compact('patients', 'titulo'));
    }

}
