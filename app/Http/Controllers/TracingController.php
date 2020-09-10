<?php

namespace App\Http\Controllers;

use App\Tracing\Tracing;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\Patient;
use Carbon\Carbon;
use App\EstablishmentUser;
use App\Commune;
use App\Establishment;
use App\SuspectCase;
use Illuminate\Support\Facades\Storage;


class TracingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function carToIndex()
    {
        $audits = \OwenIt\Auditing\Models\Audit::where('auditable_type','App\Tracing\Tracing')
            ->orderBy('created_at', 'desc')->get();
        // $audits = \OwenIt\Auditing\Models\Audit::where('auditable_type','App\Tracing\Tracing')
        // ->where('old_values->index', 0)
        // ->where('old_values->index', 1)
        // ->pluck('created_at')->first();

        // dd($audits);


        return view('patients.tracing.cartoindex', ['audits' => $audits]);
    }

    public function withoutTracing(Request $request)
    {
        //
        $patients = Patient::search($request->input('search'))->doesntHave('tracing')->whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2','positive')
              ->where('pcr_sars_cov_2_at', '>=', now()->subDays(14));
        })->paginate(200);
        return view('patients.tracing.withouttracing', compact('patients', 'request'));
    }

    public function notificationsReport()
    {
        $tracings = Tracing::whereNotNull('notification_at')
                            ->orderBy('notification_at')
                            ->whereIn('establishment_id',auth()->user()->establishments->pluck('id'))
                            ->paginate(100);
                            //->get();
        //dd($tracings);
        return view('patients.tracing.notifications_report', compact('tracings'));
    }


    public function indexByCommune()
    {
        if (auth()->user()->establishments->count() == 0) {
            session()->flash('info', 'Usuario no tiene establecimientos asociados.');
            return  redirect()->back();
        }

        $patients = Patient::whereHas('demographic', function ($q) {
            $q->whereIn('commune_id', auth()->user()->communes());
        })
            ->whereHas('tracing', function ($q) {
                $q->where('status', 1)
                    ->orderBy('next_control_at');
            })
            ->where(function ($q) {
                $q->whereNotIn('status', [
                    'Fallecido',
                    //'Alta',
                    'Residencia Sanitaria',
                    'Hospitalizado Básico',
                    'Hospitalizado Medio',
                    'Hospitalizado UCI',
                    'Hospitalizado UTI',
                    'Hospitalizado UCI (Ventilador)'
                ])
                    ->orWhereNull('status');
            })
            ->with('tracing')
            ->with('demographic')
            ->get()
            ->sortBy(function ($q) {
                return $q->tracing->next_control_at;
            })
            ->all();
        //dd($patients);

        return view('patients.tracing.index', compact('patients'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByEstablishment()
    {
        $patients = Patient::whereHas('tracing', function ($q) {
            $q->whereIn('establishment_id', auth()->user()->establishments->pluck('id')->toArray())
                ->where('status', 1)
                ->orderBy('next_control_at');
        })
            ->where(function ($q) {
                $q->whereNotIn('status', [
                    'Fallecido',
                    'Alta',
                    'Residencia Sanitaria',
                    'Hospitalizado Básico',
                    'Hospitalizado Medio',
                    'Hospitalizado UCI',
                    'Hospitalizado UTI',
                    'Hospitalizado UCI (Ventilador)'
                ])
                    ->orWhereNull('status');
            })
            ->with('tracing')
            ->with('demographic')
            ->get()
            ->sortBy(function ($q) {
                return $q->tracing->next_control_at;
            })
            ->all();

        return view('patients.tracing.index', compact('patients'));
    }



    public function mapByEstablishment()
    {
        $patients = Patient::whereHas('tracing', function ($q) {
            $q->whereIn('establishment_id', auth()->user()->establishments->pluck('id')->toArray())
                ->where('status', 1)
                ->orderBy('next_control_at');
        })
            ->where(function ($q) {
                $q->whereNotIn('status', [
                    'Fallecido',
                    'Alta',
                    'Residencia Sanitaria',
                    'Hospitalizado Básico',
                    'Hospitalizado Medio',
                    'Hospitalizado UCI',
                    'Hospitalizado UTI',
                    'Hospitalizado UCI (Ventilador)'
                ])
                    ->orWhereNull('status');
            })
            ->with('tracing')
            ->with('demographic')
            ->get()
            ->sortBy(function ($q) {
                return $q->tracing->next_control_at;
            })
            ->all();

        $establishments = Establishment::whereIn('id', auth()->user()->establishments)->distinct('name')->get();

        return view('patients.tracing.mapbyestablishment', compact('patients', 'establishments'));
    }

    public function tracingCompleted()
    {
        $patients = Patient::whereHas('demographic', function ($q) {
            $q->whereIn('commune_id', auth()->user()->communes());
        })
            ->whereHas('tracing', function ($q) {
                $q->where('status', 0)
                    ->orderBy('next_control_at');
            })
            ->where(function ($q) {
                $q->whereNotIn('status', [
                    'Fallecido'
                ])
                    ->orWhereNull('status');
            })
            ->with('tracing')
            ->with('demographic')
            ->get()
            ->sortBy(function ($q) {
                return $q->tracing->next_control_at;
            })
            ->all();

        $titulo = 'Fin de Seguimiento';
        return view('patients.tracing.completed', compact('patients', 'titulo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mapByCommune()
    {
        if (auth()->user()->establishments->count() == 0) {
            session()->flash('info', 'Usuario no tiene establecimientos asociados.');
            return  redirect()->back();
        }

        $patients = Patient::whereHas('demographic', function ($q) {
            $q->whereIn('commune_id', auth()->user()->communes());
        })
            ->whereHas('tracing', function ($q) {
                $q->where('status', 1)
                    ->orderBy('next_control_at');
            })
            ->where(function ($q) {
                $q->whereNotIn('status', [
                    'Fallecido',
                    'Alta',
                    'Residencia Sanitaria',
                    'Hospitalizado Básico',
                    'Hospitalizado Medio',
                    'Hospitalizado UCI',
                    'Hospitalizado UTI',
                    'Hospitalizado UCI (Ventilador)'
                ])
                    ->orWhereNull('status');
            })
            ->with('tracing')
            ->with('demographic')
            ->get()
            ->sortBy(function ($q) {
                return $q->tracing->next_control_at;
            })
            ->all();

        $communes = Commune::whereIn('id', auth()->user()->communes())->distinct('name')->get();



        return view('patients.tracing.mapbycommune', compact('patients', 'communes'));
    }

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
        $patient = Patient::find($request->patient_id);

        if ($patient->tracing) {
            $tracing = $patient->tracing;
            $tracing->fill($request->all());
            $tracing->save();
            session()->flash('info', 'Los datos de seguimiento se actualizaron');
        } else {
            $tracing = new tracing($request->All());
            $tracing->user_id = auth()->id();
            //$tracing->status = 1;
            $tracing->next_control_at = Carbon::now();
            $tracing->quarantine_start_at = Carbon::now();
            $tracing->quarantine_end_at = Carbon::now()->add(13, 'days');
            $tracing->save();
        }
        return redirect()->back();
    }

    public function reportByCommune(Request $request)
    {


        if ($request->has('date')) {
            $date = $request->get('date');
        } else {
            $date = Carbon::now();
        }

        // ----------------------- crear arreglo ------------------------------
        $communes = Commune::where('region_id', env('REGION'))->orderBy('name')->get();
        foreach ($communes as $key => $commune) {
            $report[$commune->id]['Comuna'] = $commune->name;
            $report[$commune->id]['positives'] = 0;
            $report[$commune->id]['car'] = 0;
            $report[$commune->id]['curso'] = 0;
            $report[$commune->id]['terminado'] = 0;
        }


        $patients = Patient::whereHas('suspectCases', function ($q) use ($date) {
            $q->where('pcr_sars_cov_2', 'positive')
                ->whereDate('pcr_sars_cov_2_at', $date);
        })
            ->whereHas('demographic', function ($q) {
                $q->where('region_id', env('REGION'));
            })
            ->get();


        foreach($patients as $patient){

            $report[$patient->demographic->commune_id]['positives'] += 1;

            foreach ($patient->contactPatient as $contact) {
                if ($contact->patient_id == $patient->id) {
                    // dd($contact);
                    $report[$patient->demographic->commune_id]['car'] += 1;
                }
            }




            if($patient->tracing){
                if($patient->tracing->status == 1){
                $report[$patient->demographic->commune_id]['curso'] += 1;
                }
                if ($patient->tracing->status == null or $patient->tracing->status == 0) {
                    $report[$patient->demographic->commune_id]['terminado'] += 1;
                }



            }
        }

        //dd($report);


        $communes_ids = array_map('trim', explode(",", env('COMUNAS')));
        $communes = Commune::whereIn('id', $communes_ids)->get();

        return view('patients.tracing.reportbycommune', compact('request', 'communes', 'patients'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tracing\Tracing  $tracing
     * @return \Illuminate\Http\Response
     */
    public function show(Tracing $tracing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tracing\Tracing  $tracing
     * @return \Illuminate\Http\Response
     */
    public function edit(Tracing $tracing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tracing\Tracing  $tracing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tracing $tracing)
    {
        $tracing->fill($request->all());
        $tracing->save();

        session()->flash('info', 'Los datos de seguimiento fueron guardados');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tracing\Tracing  $tracing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tracing $tracing)
    {
        //
    }

    public function migrate()
    {
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->with('suspectCases')->get();

        foreach ($patients as $patient) {
            if (
                $patient->demographic->commune->id == 8 or
                $patient->demographic->commune->id == 9 or
                $patient->demographic->commune->id == 10 or
                $patient->demographic->commune->id == 11
            ) {
                $suspectCase                = $patient->suspectCases->where('pcr_sars_cov_2', 'positive')->first();
                $tracing                    = new Tracing();
                $tracing->patient_id        = $suspectCase->patient_id;
                $tracing->user_id           = ($suspectCase->user_id === 0) ? null : $suspectCase->user_id;
                $tracing->index             = 1;
                $tracing->establishment_id  = ($suspectCase->establishment_id === 0) ? 4002 : $suspectCase->establishment_id;
                $tracing->functionary       = $suspectCase->functionary;
                $tracing->gestation         = $suspectCase->gestation;
                $tracing->gestation_week    = $suspectCase->gestation_week;
                $tracing->next_control_at   = $suspectCase->pcr_sars_cov_2_at;
                $tracing->quarantine_start_at = ($suspectCase->symptoms_at) ?
                    $suspectCase->symptoms_at :
                    $suspectCase->pcr_sars_cov_2_at;
                $tracing->quarantine_end_at = $tracing->quarantine_start_at->add(14, 'days');
                $tracing->observations      = $suspectCase->observation;
                $tracing->notification_at   = $suspectCase->notification_at;
                $tracing->notification_mechanism = $suspectCase->notification_mechanism;
                $tracing->discharged_at     = $suspectCase->discharged_at;
                $tracing->symptoms_start_at = $suspectCase->symptoms_at;
                switch ($suspectCase->symptoms) {
                    case 'Si':
                        $tracing->symptoms = 1;
                        break;
                    case 'No':
                        $tracing->symptoms = 0;
                        break;
                    default:
                        $tracing->symptoms = null;
                        break;
                }

                if ($suspectCase->patient->status == 'Fallecido') {
                    $tracing->status = 0;
                } else {
                    $tracing->status = ($tracing->quarantine_start_at < Carbon::now()->sub(14, 'days')) ? 0 : 1;
                }

                $tracing->save();
                echo $patient->name . '<br>';
            }
        }
    }

    public function quarantineCheck(Request $request)
    {
        $fechaActual = Carbon::now()->toDateString();
        $run = $request->run;

        $isQuarantined = Tracing::whereHas('patient', function ($q) use ($run) {
            $q->where('run', $run);
        })
            ->where('quarantine_start_at', '<=', $fechaActual)
            ->where(function ($q) use ($fechaActual) {
                $q->where('quarantine_end_at', '>=', $fechaActual)
                    ->orWhereNull('quarantine_end_at');
            })->exists();

        return view('patients.tracing.quarantine_check', compact('isQuarantined', 'run'));
    }


    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tracing\Tracing  $tracing
     * @return \Illuminate\Http\Response
     */
    public function withoutEvents()
    {
        $tracingsWithoutEvents =
            Tracing::where('quarantine_start_at','<=',now())
                   ->where('quarantine_end_at','>=',now())
                   ->where('status',1)
                   ->whereDoesntHave('events')
                   ->orderBy('quarantine_start_at')
                   ->get();
        //dd($tracingsWithoutEvents);

        return view('patients.tracing.without_events', compact('tracingsWithoutEvents'));
    }

    /**
     * En desarrollo. Obtiene folio-indice del paciente.
     * @throws GuzzleException
     */
    public function getFolioPatientWs(String $type_id, String $id)
    {
        $guzzle = new \GuzzleHttp\Client();

        try {

            $response = $guzzle->post(env('TOKEN_ENDPOINT'), [
               'form_params' => [
                   'grant_type' => 'client_credentials',
                   'client_id' => env('CLIENT_ID'),
                   'client_secret' => env('CLIENT_SECRET'),
               ],
            ]);

            $accessToken = json_decode((string) $response->getBody(), true)['access_token'];

            $client = new \GuzzleHttp\Client(['base_uri' => env('BASE_ENDPOINT')]);
            $headers = [
                'Authorization' => 'Bearer ' . $accessToken,
                'x-api-key' => env('X_API_KEY'),
            ];

            $response2 = $client->request('GET', 'Patient/' . $type_id . '/' . $id, [
                'headers' => $headers
            ]);

            /** test_parameters: type_id=1, $id=17353836-7 **/
            dd(json_decode((string) $response2->getBody(), true));

//            $response = ['status' => 1, 'msg' => 'OK'];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            dd($decode);
//            $response = ['status' => 0, 'msg' => $decode->error];
        }

    }

    /**
     * En desarrollo. Envía paciente contacto estrecho
     */
    public function setPatientWs(Patient $patient){

        $run = $patient->run . '-' . $patient->dv;
        $family = $patient->fathers_family;
        $given_name = $patient->name;
        $mothers_family = $patient->mothers_family;
        $mobile_phone = (int)$patient->demographic->telephone;
        $home_phone = ($patient->demographic->telephone2) ? (int)$patient->demographic->telephone2 : null;
        $email = ($patient->demographic->email) ? $patient->demographic->email : null;
        $gender = $patient->gender;
        //todo verificar si city esta estandarizado
        $city = $patient->demographic->city;
        //todo agregar columna de homologacion
        $region = $patient->demographic->region->name;
        $via = strtolower($patient->demographic->street_type);
        $direccion = $patient->demographic->address;
        $numero_residencia = $patient->demographic->number;
        $region_id = (string)$patient->demographic->region_id;
        $comuna_code_deis = $patient->demographic->commune->code_deis;

        /** code deis de comunas deben ser de 5 digitos **/
        if(strlen($comuna_code_deis) == 4){
            $comuna_code_deis = '0' . $comuna_code_deis;
        }

        //todo obtener suspect case por parametro?, por ahora se obtiene el ultimo caso
        $suspectCase = SuspectCase::where('patient_id', $patient->id)
            ->orderBy('id', 'desc')
            ->first();

        $establecimiento_code_deis = ($suspectCase->establishment->new_code_deis) ?
            (int)$suspectCase->establishment->new_code_deis : null;

        /** TELECOM ARRAY **/
        $mobile_phone_array = array(
            'system' => 'phone',
            'use' => 'mobile',
            'value' => $mobile_phone
        );

        $home_phone_array = array(
            'system' => 'phone',
            'use' => 'home',
            'value' => $home_phone
        );

        $email_array = array(
            'system' => 'email',
            'value' => $email,
            'use' => 'home'
        );

        $telecomArray = array();
        array_push($telecomArray, $mobile_phone_array);
        if($home_phone){array_push($telecomArray, $home_phone_array);}
        if($email){array_push($telecomArray, $email_array);}

        /** ADDRESS ARRAY **/

        $addressArray = array(
            'state' => $region,
            'country' => 'CL',
            'extension' => array(
                array(
                    'url' => 'apidocs.epivigila.minsal.cl/tipo-direccion',
//                        todo cambiar a una de las opciones
                    'valueString' => 'domicilio_particular'
                ),
                array(
                    'url' => 'apidocs.epivigila.minsal.cl/via',
                    'valueString' => $via
                ),
                array(
                    'url' => 'apidocs.epivigila.minsal.cl/direccion',
                    'valueString' => $direccion
                ),
                array(
                    'url' => 'apidocs.epivigila.minsal.cl/numero-residencia',
                    'valueString' => $numero_residencia
                ),
                array(
                    'url' => 'apidocs.epivigila.minsal.cl/comuna',
                    'valueCode' => $comuna_code_deis
                ),
                array(
                    'url' => 'apidocs.epivigila.minsal.cl/region',
                    'valueCode' => $region_id
                ))
        );

        if($city){
            $addressArray = array('city' => $city) + $addressArray;
        }

        /** JSON **/

        $patient = array(
            'resourceType' => 'Patient',
            'identifier' => array(
                array(
                    'type' => array(
                        'coding' => array(
                            'system' => 'apidocs.epivigila.minsal.cl/tipo-documento',
                            'code' => 1,
                            'display' => 'run')
                    ),
                    'system' => 'www.registrocivil.cl/run',
                    'value' => $run
                )),
            'name' => array(
                'family' => $family,
                'given' => array($given_name),
                'extension' => array(
                    array(
                        'url' => 'www.hl7.org/fhir/extension-humanname-mothers-family.json.html',
                        'valueString' => $mothers_family
                    ))
            ),
            'telecom' => $telecomArray,
            'gender' => $gender,
            'birthDate' => '1992-10-27',
            'address' => $addressArray,

            'contact' => array(array(
                'coding' => array(
                    'system' => 'apidocs.epivigila.minsal.cl/folio-indice',
                    'code' => '50669',
                    'display' => 'folio-indice'
                )
            )),

            'managingOrganization' => array(
                'identifier' => array(array(
                    'system' => 'apidocs.epivigila.minsal.cl/establecimientos-DEIS',
                    'value' => $establecimiento_code_deis
                )),
                'name' => 'string'
            )

        );

        /** template **/
//        $patient = array(
//            'resourceType' => 'Patient',
//            'identifier' => array(
//                array(
//                    'type' => array(
//                        'coding' => array(
//                            'system' => 'apidocs.epivigila.minsal.cl/tipo-documento',
//                            'code' => 1,
//                            'display' => 'run')
//                    ),
//                    'system' => 'www.registrocivil.cl/run',
//                    'value' => '18314540-1'
//                )),
//            'name' => array(
//                'family' => 'Christiansen',
//                'given' => array('Rodrigo Iván'),
//                'extension' => array(
//                    array(
//                        'url' => 'www.hl7.org/fhir/extension-humanname-mothers-family.json.html',
//                        'valueString' => 'Gonzalez'
//                    ))
//            ),
//            'telecom' => array(
//                array(
//                    'system' => 'phone',
//                    'use' => 'mobile',
//                    'value' => 123456789
//                ),
//                array(
//                    'system' => 'phone',
//                    'use' => 'home',
//                    'value' => 584679
//                ),
//                array(
//                    'system' => 'email',
//                    'value' => 'rodrigo.christiansen@hjnc.cl',
//                    'use' => 'home'
//                )),
//            'gender' => 'male',
//            'birthDate' => '1992-10-27',
//            'address' => array(
//                'city' => 'Arica',
//                'state' => 'De Arica y Parinacota',
//                'country' => 'CL',
//                'extension' => array(
//                    array(
//                        'url' => 'apidocs.epivigila.minsal.cl/tipo-direccion',
//                        'valueString' => 'domicilio_particular'
//                ),
//                    array(
//                        'url' => 'apidocs.epivigila.minsal.cl/via',
//                        'valueString' => 'calle'
//                    ),
//                    array(
//                        'url' => 'apidocs.epivigila.minsal.cl/direccion',
//                        'valueString' => 'garona'
//                    ),
//                    array(
//                        'url' => 'apidocs.epivigila.minsal.cl/numero-residencia',
//                        'valueString' => '598'
//                    ),
//                    array(
//                        'url' => 'apidocs.epivigila.minsal.cl/comuna',
//                        'valueCode' => '15101'
//                    ),
//                    array(
//                        'url' => 'apidocs.epivigila.minsal.cl/region',
//                        'valueCode' => '15'
//                    ))
//            ),
//
//            'contact' => array(array(
//                'coding' => array(
//                    'system' => 'apidocs.epivigila.minsal.cl/folio-indice',
//                    'code' => '50669',
//                    'display' => 'folio-indice'
//                )
//            )),
//
//            'managingOrganization' => array(
//                'identifier' => array(array(
//                    'system' => 'apidocs.epivigila.minsal.cl/establecimientos-DEIS',
//                    'value' => 101100
//                )),
//                'name' => 'string'
//            )
//
//        );

        try {
            $patientJson = json_encode($patient, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            Storage::disk('public')->put('prueba.json', $patientJson);
            dd($patientJson);

//            $response = ['status' => 1, 'msg' => 'OK'];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            dd('error: ' + $decode);

//            $response = ['status' => 0, 'msg' => $decode->error];
        }
    }

    public function setBundleContactWs(){

        $bundle = array(
            'resourceType' => "Bundle",
            'id' => 'bundle-seguimiento',
            'type' => 'collection',
            'entry' => array(array(
                'resource' => array(
                    'resourceType' => 'Encounter',
                    'id' => 'seguimiento-c19',
                    'text' => array(
                        'status' => 'generated',
                        'div' => '<div>Seguimiento Contacto</div>'
                    ),
                    'contained' => array(array(
                        'resourceType' => 'Location',
                        'id' => 'seguimiento',
                        'description' => 'Casa',
                        'mode' => 'kind'
                    )),
                    'status' => 'finished',
                    'class' => array(
                        'system' => 'apidocs.epivigila.minsal.cl/tipo-domicilio',
                        'code' => 'domicilio_particular',
                        'display' => 'Seguimiento con paciente en domicilio particular'
                    ),
                    'subject' => array(
                        'reference' => 'Patient/50669'
                    ),
                    'participant' => array(array(
                        'individual' => array(
                            'reference' => 'Practitioner/15000018-1',
                            'display' => 'Alfredo Figueroa Seguel'
                        )
                    )),
                    'period' => array(
                        'start' => '2020-09-04',
                        'end' => '2020-09-17'
                    ),
                    'location' => array(array(
                        'location' => array(
                            'reference' => 'seguimiento-c19',
                            'display' => 'Lugar de seguimiento'
                        ),
                        'status' => 'completed',
                        'period' => array(
                            'start' => '2020-09-04',
                            'end' => '2020-09-04'
                        ),
                        'extension' => array(
                            array(
                                'url' => 'apidocs.epivigila.minsal.cl/dia-seguimiento-covid',
                                'valueInteger' => 1
                            ),
                            array(
                                'url' => 'apidocs.epivigila.minsal.cl/tipo-contactabilidad',
                                'valueString' => 'llamada'
                            )
                        )
                    ))
                ),
                'request' => array(
                    'method' => 'POST',
                    'url' => 'Encounter'
                )
            ),
                array(
                    'resource' => array(
                        'resourceType' => 'QuestionnaireResponse',
                        'contained' => array(
                            array(
                                'resourceType' => 'Patient',
                                'id' => 'seguimiento-covid',
                                'identifier' => array(array(
                                    'system' => 'apidocs.epivigila.minsal.cl/folio-contacto',
                                    'code' => 'SC50669-1085',
                                    'display' => 'folio-contacto'
                                )),
                                'contact' => array(
                                    'relationship' => array(array(
                                        'coding' => array(
                                            'system' => 'apidocs.epivigila.minsal.cl/folio-indice',
                                            'code' => '50669',
                                            'display' => 'folio-indice'
                                        )
                                    ))
                                ),
                            ),
                            array(
                                'resourceType' => 'Practitioner',
                                'id' => 'responsable-encuesta-seguimiento',
                                'identifier' => array(
                                    array(
                                        'type' => array('text' => 'Rodrigo Baeza Galaz'),
                                        'system' => 'www.registrocivil.cl/run',
                                        'value' => '15840868-6'
                                    )),
                            ),
                            array(
                                'resourceType' => 'Organization',
                                'id' => 'institucion-seguimiento',
                                'identifier' => array(
                                    array(
                                        'system' => 'apidocs.epivigila.minsal.cl/establecimientos-DEIS',
                                        'value' => 101100
                                )),
                                'name' => 'Hospital Dr. Juan Noé Crevani'
                            )
                        ),
                        'status' => 'completed',
                        'subject' => array(
                            'reference' => 'seguimiento-c19'
                        ),
                        'encounter' => array(
                            'reference' => 'Encounter/seguimiento-c19'
                        ),
                        'item' => array(
                            array(
                                'linkId' => '1',
                                'text' => '¿Ha presentado alguno de los siguientes síntomas?',
                                'item' => array(

                                    array(
                                        'linkId' => '1.1',
                                        'text' => 'cefalea',
                                        'answer' => array(
                                            array('valueBoolean' => false)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.2',
                                        'text' => 'cianosis',
                                        'answer' => array(
                                            array('valueBoolean' => false)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.3',
                                        'text' => 'diarrea',
                                        'answer' => array(
                                            array('valueBoolean' => false)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.4',
                                        'text' => 'dolor_abdominal',
                                        'answer' => array(
                                            array('valueBoolean' => true)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.5',
                                        'text' => 'dolor_toracico',
                                        'answer' => array(
                                            array('valueBoolean' => false)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.6',
                                        'text' => 'fiebre',
                                        'answer' => array(
                                            array('valueBoolean' => false)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.7',
                                        'text' => 'mialgia',
                                        'answer' => array(
                                            array('valueBoolean' => false)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.8',
                                        'text' => 'odinofagia',
                                        'answer' => array(
                                            array('valueBoolean' => true)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.9',
                                        'text' => 'anosmia',
                                        'answer' => array(
                                            array('valueBoolean' => true)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.10',
                                        'text' => 'ageusia',
                                        'answer' => array(
                                            array('valueBoolean' => false)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.11',
                                        'text' => 'postracion',
                                        'answer' => array(
                                            array('valueBoolean' => false)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.12',
                                        'text' => 'taquipnea',
                                        'answer' => array(
                                            array('valueBoolean' => false)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.13',
                                        'text' => 'tos',
                                        'answer' => array(
                                            array('valueBoolean' => false)
                                        )
                                    )
                                )
                            ),
                            array(
                                'linkId' => '2',
                                'text' => 'respecto a examen covid-19',
                                'item' => array(
                                    array(
                                        'linkId' => '2.1',
                                        'text' => 'fue derivado para realizarse el examen?',
                                        'answer' => array(
                                            array(
                                                'valueBoolean' => false,
                                                'item' => array(
                                                    array(
                                                        'linkId' => '2.1.1',
                                                        'text' => 'fecha derivacion toma de muestras',
                                                        'answer' => array(
                                                            array(
                                                                'valueDate' => '2020-09-04'
                                                            )
                                                        )
                                                    ),
                                                    array(
                                                        'linkId' => '2.1.2',
                                                        'text' => 'derivacion a SU',
                                                        'answer' => array(
                                                            array(
                                                                'valueBoolean' => false
                                                            )
                                                        )
                                                    ),
                                                    array(
                                                        'linkId' => '2.1.3',
                                                        'text' => '¿cumple cuarentena y aislamiento?',
                                                        'answer' => array(
                                                            array(
                                                                'valueBoolean' => false
                                                            )
                                                        )
                                                    ),
                                                    array(
                                                        'linkId' => '2.1.4',
                                                        'text' => '¿tiene resultado covid?',
                                                        'answer' => array(
                                                            array(
                                                                'valueString' => 'negativo'
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    ),
                                    array(
                                        'linkId' => '2.2',
                                        'text' => 'Observacion de Seguimiento',
                                        'answer' => array(
                                            array(
                                                'valueString' => 'Sin observaciones'
                                            )
                                        )
                                    )
                                )
                            )

                        )

                    ),
                    'request' => array(
                        'method' => 'POST',
                        'url' => 'QuestionnaireResponse'
                    )
                )

            )
        );


        try {
            $bundleJson = json_encode($bundle, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            Storage::disk('public')->put('prueba.json', $bundleJson);
            dd($bundleJson);

//            $response = ['status' => 1, 'msg' => 'OK'];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            dd('error: ' + $decode);

//            $response = ['status' => 0, 'msg' => $decode->error];
        }
    }

}
