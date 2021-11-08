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
use Illuminate\Support\Facades\File;

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
        $patients = array();        
        if (isset($request->search)) {
            $patients = Patient::search($request->input('search'))
                ->with('demographic')
                ->with('suspectCases')
                ->with('contactPatient')
                ->orderBy('name')
                ->paginate(250);
        } 

        return view('patients.index', compact('patients', 'request', 'establishment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $regions = Region::orderBy('id', 'ASC')->get();
        $communes = Commune::orderBy('id', 'ASC')->get();
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

        $validatedData = $request->validate(
            [
                'run' => 'nullable|unique:patients'
            ],
            [
                'run.unique' => 'Este rut ya está registrado.'
            ]
        );

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
                if (array_key_exists('status', $audit->old_values)) {
                    $timeline[$audit->created_at->format('Y-m-d H:m')] = 'Paciente creado - ' . $audit->new_values['status'];
                } else {
                    $timeline[$audit->created_at->format('Y-m-d H:m')] = 'Paciente creado';
                }
            } else {
                if (array_key_exists('status', $audit->new_values)) {
                    if ($audit->new_values['status'] == NULL || $audit->new_values['status'] == '') {
                        $timeline[$audit->created_at->format('Y-m-d H:m')] = 'Ambulatorio';
                    } else {
                        $timeline[$audit->created_at->format('Y-m-d H:m')] = $audit->new_values['status'];
                    }
                }
            }
        }
        // dd($timeline);

        $regions = Region::orderBy('id', 'ASC')->get();
        $communes = Commune::orderBy('id', 'ASC')->get();
        $countries = Country::select('name')->orderBy('id', 'ASC')->get();
        $event_types = EventType::all();
        $request_types = RequestType::all();
        $env_communes = array_map('trim', explode(",", env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id', $env_communes)->orderBy('name', 'ASC')->get();
        $symptoms = Symptom::All();
        return view('patients.edit', compact('patient', 'regions', 'communes', 'event_types', 'request_types', 'establishments', 'symptoms', 'countries', 'timeline'));
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
        if ($patient->demographic) {
            //$logDemographic->old = clone $patient->demographic;

            $patient->demographic->fill($request->all());
            $patient->demographic->save();

            //$logDemographic->new = $patient->demographic;
            //$logDemographic->save();
        } else {

            if (
                $request->address != null | $request->address != null | $request->deparment != null |
                $request->town != null | $request->latitude != null | $request->longitude != null |
                $request->email != null | $request->telephone != null | $request->number != null |
                $request->region != null | $request->commune != null
            ) {

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
            $q->where('pcr_sars_cov_2', 'positive');
        })->with('demographic')->get();

        $patients = $patients->whereNotIn(
            'demographic.region',
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
                'Región de Ñuble'
            ]
        );

        return view('patients.positives', compact('patients'));
    }


    public function getPatient($rut)
    {
        return Patient::where('run', $rut)->first();
    }

    public function getPatientOtherIdentification($other_identification)
    {
        return Patient::where('other_identification', $other_identification)->first();
    }

    public function georeferencing()
    {
        $date = \Carbon\Carbon::today()->subDays(30);
        // $users = User::where('created_at', '>=', $date)->get();
        // $suspectCases = SuspectCase::latest('id')->get();
        $suspectCases = SuspectCase::where('pcr_sars_cov_2_at', '>=', $date)->where('pcr_sars_cov_2', 'positive')
            ->whereHas('patient', function ($q) {
                $q->whereIn('status', ['Ambulatorio', ''])
                    ->OrWhereNULL('status');
            })

            ->get();

        $data = array();
        foreach ($suspectCases as $key => $case) {
            if ($case->pcr_sars_cov_2 == 'positive') { // || $case->pcr_sars_cov_2 == 'pending') {
                // FIX , pendiente ver que pasó que hay un caso sin paciente asociado
                if ($case->patient) {
                    if ($case->patient->demographic != null) {
                        $data[$case->patient->demographic->address . " " . $case->patient->demographic->number . ", " . $case->patient->demographic->commune][$case->patient->run]['paciente'] = $case;
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

        return view('patients.georeferencing.georeferencing', compact('suspectCases', 'data'));
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

        $callback = function () use ($filas, $columnas) {
            $file = fopen('php://output', 'w');
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columnas, ';');

            foreach ($filas as $fila) {
                fputcsv($file, array(
                    $fila->id,
                    $fila->run,
                    $fila->dv,
                    $fila->other_identification,
                    $fila->name,
                    $fila->fathers_family,
                    $fila->mothers_family,
                    $fila->gender,
                    ($fila->birthday) ? $fila->birthday->format('d-m-Y') : '',
                    ($fila->suspectCases->where('pcr_sars_cov_2', 'positive')->first()) ? 'Si' : 'No',
                    ($fila->demographic) ? $fila->demographic->commune : '',
                    ($fila->demographic) ? $fila->demographic->address . ' ' . $fila->demographic->number : ''
                ), ';');
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Descarga archivo csv de pacientes covid positivos con georeferencia.
     * @return StreamedResponse
     */
    public function exportPositives()
    {
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

        $callback = function () use ($filas, $columnas) {
            $file = fopen('php://output', 'w');
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columnas, ';');

            foreach ($filas as $fila) {
                fputcsv($file, array(
                    $fila->id,
                    $fila->run,
                    $fila->dv,
                    $fila->other_identification,
                    $fila->name,
                    $fila->fathers_family,
                    $fila->mothers_family,
                    $fila->gender,
                    ($fila->birthday) ? $fila->birthday->format('d-m-Y') : '',
                    ($fila->demographic) ? $fila->demographic->commune : '',
                    ($fila->demographic) ? $fila->demographic->address . ' ' . $fila->demographic->number : '',
                    ($fila->demographic) ? $fila->demographic->latitude : '',
                    ($fila->demographic) ? $fila->demographic->longitude : ''

                ), ';');
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function inResidence()
    {
        if (auth()->user()->establishments->count() == 0) {
            session()->flash('info', 'Usuario no tiene establecimientos asociados.');
            return redirect()->back();
        }

        $patients = Patient::whereHas('demographic', function ($q) {
            $q->whereIn('commune_id', auth()->user()->communes());
        })
            ->where(function ($q) {
                $q->where('status', 'Residencia Sanitaria');
            })
            ->with('tracing')
            ->with('demographic')
            ->get();

        $titulo = 'En residencia';
        return view('patients.tracing.index', compact('patients', 'titulo'));
    }


    public function json_to_vars($arr, $d = 1, $ka = null)
    {
        if ($d == 1) echo "<pre>";
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                if ($d == 1) {
                    $ka = array($k);
                } else {
                    if ($d <= count($ka)) {
                        array_pop($ka);
                    }
                    $ka[] = $k;
                }
                if (is_array($v)) {
                    $this->json_to_vars($v, $d + 1, $ka);
                } else {
                    foreach ($ka as $key => $x) {
                        if ($key === 0)  echo '$fhir';
                        if (is_int($x)) {
                            echo "[" . $x . "]";
                        } else {
                            echo "['" . $x . "']";
                        }
                    }
                    echo " = '" . $v . "';\n";
                }
            }
        }
        if ($d == 1) echo "</pre>";
    }

    public function fhir(Patient $patient)
    {
        $fhir = json_decode(File::get(base_path() . '/app/Fhir/bundle.fhir.json'), true);

        return $this->json_to_vars($fhir);
        die();


        $fhir['identifier'][0]['value'] = $patient->run . '-' . $patient->dv;
        $fhir['name'][0]['text'] = $patient->fullName;
        $fhir['name'][0]['family'] = $patient->fathers_family . ' ' . $patient->mothers_family;
        $fhir['name'][0]['_family']['extension'][0]['valueString'] = $patient->fathers_family;
        $fhir['name'][0]['_family']['extension'][1]['valueString'] = $patient->mothers_family;
        $fhir['name'][0]['given'] = explode(' ', $patient->name);

        $fhir['telecom'][0]['system'] = 'phone';
        $fhir['telecom'][0]['value'] = $patient->demographic->telephone;
        $fhir['telecom'][0]['use'] = 'mobile';

        $fhir['telecom'][1]['system'] = 'email';
        $fhir['telecom'][1]['value'] = $patient->demographic->email;

        $fhir['gender'] = $patient->gender;
        $fhir['birthDate'] = $patient->birthday->format('Y-m-d');

        if ($patient->deceased_at) {
            $fhir['deceasedBoolean'] = true;
        } else {
            $fhir['deceasedBoolean'] = false;
        }

        $fhir['address'][0]['use'] = 'home';
        $fhir['address'][0]['text'] = $patient->demographic->street_type . ' ' . $patient->demographic->address . ' ' . $patient->demographic->number . ' ' . $patient->demographic->department . ', ' . $patient->demographic->commune->name . ', ' . $patient->demographic->region->name . ', Chile';
        $fhir['address'][0]['line'][0] = $patient->demographic->street_type . ' ' . $patient->demographic->address . ' ' . $patient->demographic->number . ' ' . $patient->demographic->department;
        $fhir['address'][0]['_line'][0]['extension'][0]['valueString'] = $patient->demographic->street_type;
        $fhir['address'][0]['_line'][0]['extension'][1]['valueString'] = $patient->demographic->address;
        $fhir['address'][0]['_line'][0]['extension'][2]['valueString'] = $patient->demographic->number;
        $fhir['address'][0]['_line'][0]['extension'][3]['valueString'] = $patient->demographic->department;
        $fhir['address'][0]['state'] = $patient->demographic->region->name;
        $fhir['address'][0]['district'] = $patient->demographic->commune->name;
        $fhir['address'][0]['city'] = $patient->demographic->city;
        $fhir['address'][0]['country'] = 'Chile';
        $fhir['address'][0]['extension'][0]['extension'][0]['valueDecimal'] = $patient->demographic->latitude;
        $fhir['address'][0]['extension'][0]['extension'][1]['valueDecimal'] = $patient->demographic->longitude;



        /*

            [nationality] => Chile
            [region_id] => 1
            [commune_id] => 5
            [city] => 
            [suburb] => 
            [latitude] => -20.21422510
            [longitude] => -70.13451010
            [telephone] => 932509396
            [telephone2] => 
            [email] => 
            [workplace] => 
            [patient_id] => 65
            [created_at] => 2020-03-25 04:41:56
            [updated_at] => 2020-08-21 19:34:53
            [deleted_at] =>  */

        //echo '<pre>';
        //print_r(json_encode($fhir));
        return response()->json($fhir);
    }
}
