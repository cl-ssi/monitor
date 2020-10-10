<?php

namespace App\Http\Controllers;

use App\ContactPatient;
use App\Helpers\EpivigilaApi;
use App\Tracing\Event;
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

    public function setTracingBundleWs(Event $event)
    {
        /** Si el caso no se pudo contactar (event_type_id == 6) **/
        if($event->event_type_id == 6)
            $status = 'cancelled';
        else
            $status = 'finished';

        /** Obtencion datos paciente **/
        $patient_run = $event->tracing->patient->run;
        $patient_dv = $event->tracing->patient->dv;
        $patient_rut = $patient_run . '-' . $patient_dv;
//        $response = $this->getFolioPatientWs('1', $patient_rut);

        dump('patientrut: ' . $patient_rut . ' ' . 'name'. ' ' . $event->tracing->patient->name . ' ' . $event->tracing->patient->fathers_family);

//        if($response['code'] == 1)
//            $folio = (string)$response['data']['identifier'][0]['value'];
//        else
//            dump($response['mensaje']);

        /** Obtencion datos de usuario **/
        $user_rut = auth()->user()->run . '-' . auth()->user()->dv;
        $user_name = auth()->user()->name;

        /** Obtiene inicio y fin de tracing **/
        $quarantine_start = $event->tracing->quarantine_start_at;
        $quarantine_end = $event->tracing->quarantine_end_at;

        /** Obtiene fecha inicio y fin del event_tracing**/
        $tracing_event_at = $event->event_at;

        /** Obtiene dia de seguimiento **/
        $tracing_day = $tracing_event_at->diffInDays($quarantine_start) + 1;

        /** Obtener tipo de contacto **/
        //todo definir cuales son tipo llamada y tipo visita. Que pasa con el caso no se pudo contactar, es llamada o visita.
        if ($event->event_type_id == 1) {
            $tipo_contactabilidad = 'llamada';
        }
        else
            $tipo_contactabilidad = 'visita';

        /** Obtener eventos **/
        //todo agregar segun evento de seguimiento
        $derivacionTomaMuestra = false;

        /** Obtener si es caso indice o no **/
        $index = $event->tracing->index;

        if($index == false){

            /** Obtiene paciente indice del contacto **/
            $indexPatient = ContactPatient::where('contact_id', $event->tracing->patient_id)
                ->where('index', true)->first();

            $indexPatientRut = $indexPatient->self_patient->run . '-' . $indexPatient->self_patient->dv;
            dump('Paciente indice: ' . $indexPatient->patient_id, 'Rut paciente indice: ' . $indexPatientRut);

            /** Obtiene el folio del paciente indice del contacto estrecho **/
            $response = EpivigilaApi::instance()->getFolioPatientWs('1', $indexPatientRut);
            if($response['code'] == 1)
                $folio = (string)$response['data']['identifier'][0]['value'];
            else
                dump( 'respuesta getfoliompatientws: ' . $response['mensaje']);

            /** Folio del contacto estrecho **/
            $response = EpivigilaApi::instance()->getFolioContactPatientWs('1', $patient_rut, $folio);
            if($response['code'] == 1)
                $folioContact = (string)$response['data']['identifier'][0]['value'];
            else
                dump( 'respuesta getfoliocontactpatientws: ' . $response['mensaje']);

//            dump('foliocontacto: ' . $folioContact);

        }

        /** Obtiene Establecimiento que realiza el seguimiento  **/
        $establishmentName = $event->tracing->establishment->name;
        $establishmentCode = (integer)$event->tracing->establishment->new_code_deis;

        /** Obtener sintomas del evento **/
        $symptoms = $event->symptoms;
        $symptoms = explode(',', $symptoms);

//        dump('sintomas: ' . $symptoms);
        dump($event);

        $fiebre = in_array('Fiebre', $symptoms) ? true : false;
        $tos = in_array('Tos', $symptoms) ? true : false;
        $mialgia = in_array('Mialgias', $symptoms) ? true : false;
        $odinofagia = in_array('Odinofagia', $symptoms) ? true : false;
        $anosmia = in_array('Anosmia', $symptoms) ? true : false;
        $ageusia = in_array('Ageusia', $symptoms) ? true : false;
        $dolor_toracico = in_array('Dolor toráxico', $symptoms) ? true : false;
        $diarrea = in_array('Diarrea', $symptoms) ? true : false;
        $cefalea = in_array('Cefalea', $symptoms) ? true : false;

        /** Estos sintomas no estan en bd **/ //todo que pasa con campos q no estan en bd
        $cianosis = in_array('cianosis', $symptoms) ? true : false;
        $dolor_abdominal = in_array('dolor_abdominal', $symptoms) ? true : false;
        $postracion = in_array('postracion', $symptoms) ? true : false;
        $taquipnea = in_array('taquipnea', $symptoms) ? true : false;

        //Se generan arrays de items covid exams
        $tomaDeMuestraArray = array(
            'linkId' => '2.1.1',
            'text' => 'fecha derivacion toma de muestras',
            'answer' => array(
                array(
                    'valueDate' => '2020-09-04' //todo obtener fecha derivacion
                )
            )
        );

        $derivacionSuArray = array(
            'linkId' => '2.1.2',
            'text' => 'derivacion a SU',
            'answer' => array(
                array(
                    'valueBoolean' => false //todo obtener derivacion su
                )
            )
        );

        $cumpleCuarantenaArray = array(
            'linkId' => '2.1.3',
            'text' => '¿cumple cuarentena y aislamiento?',
            'answer' => array(
                array(
                    'valueBoolean' => false //todo obtener
                )
            )
        );

        $tieneResultadoCovidArray = array(
            'linkId' => '2.1.4',
            'text' => '¿tiene resultado covid?',
            'answer' => array(
                array(
                    'valueString' => 'negativo' //todo obtener
                )
            )
        );

        //Se construye array de items
        $covidExamItemsArray = array();
        if($derivacionTomaMuestra) {array_push($covidExamItemsArray, $tomaDeMuestraArray);}
        array_push($covidExamItemsArray, $derivacionSuArray);
        array_push($covidExamItemsArray, $cumpleCuarantenaArray);
        array_push($covidExamItemsArray, $tieneResultadoCovidArray);

        //Se agrega observaciones de evento
        $observaciones = $event->details;

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
                    'status' => $status,
                    'class' => array(
                        'system' => 'apidocs.epivigila.minsal.cl/tipo-domicilio',
//                        "domicilio_particular",
//                        "domicilio_particular_caso_indice",
//                        "hospitalizacion_clinica",
//                        "hospitalizacion_domiciliaria",
//                        "residencia_sanitaria"
                        'code' => 'domicilio_particular',
                        'display' => 'Seguimiento con paciente en domicilio particular'
                    ),
                    'subject' => array(
                        'reference' => 'Patient/' . $folio
                    ),
                    'participant' => array(array(
                        'individual' => array(
                            'reference' => 'Practitioner/'. $user_rut,
                            'display' => $user_name
                        )
                    )),
                    'period' => array(
                        'start' => $quarantine_start->format('Y-m-d'),
                        'end' => $quarantine_end->format('Y-m-d')
                    ),
                    'location' => array(array(
                        'location' => array(
                            'reference' => 'seguimiento-c19',
                            'display' => 'Lugar de seguimiento'
                        ),
                        'status' => 'completed',
                        'period' => array(
                            'start' => $tracing_event_at->format('Y-m-d'),
                            'end' => $tracing_event_at->format('Y-m-d')
                        ),
                        'extension' => array(
                            array(
                                'url' => 'apidocs.epivigila.minsal.cl/dia-seguimiento-covid',
                                'valueInteger' => $tracing_day
                            ),
                            array(
                                'url' => 'apidocs.epivigila.minsal.cl/tipo-contactabilidad',
                                'valueString' => $tipo_contactabilidad
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
                                    'code' => $folioContact, //'SC50669-1085',
                                    'display' => 'folio-contacto'
                                )),
                                'contact' => array(
                                    'relationship' => array(array(
                                        'coding' => array(
                                            'system' => 'apidocs.epivigila.minsal.cl/folio-indice',
                                            'code' => $folio,
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
                                        'type' => array('text' => $user_name),
                                        'system' => 'www.registrocivil.cl/run',
                                        'value' => $user_rut
                                    )),
                            ),
                            array(
                                'resourceType' => 'Organization',
                                'id' => 'institucion-seguimiento',
                                'identifier' => array(
                                    array(
                                        'system' => 'apidocs.epivigila.minsal.cl/establecimientos-DEIS',
                                        'value' => $establishmentCode
                                )),
                                'name' => $establishmentName
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
                                            array('valueBoolean' => $cefalea)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.2',
                                        'text' => 'cianosis',
                                        'answer' => array(
                                            array('valueBoolean' => $cianosis)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.3',
                                        'text' => 'diarrea',
                                        'answer' => array(
                                            array('valueBoolean' => $diarrea)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.4',
                                        'text' => 'dolor_abdominal',
                                        'answer' => array(
                                            array('valueBoolean' => $dolor_abdominal)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.5',
                                        'text' => 'dolor_toracico',
                                        'answer' => array(
                                            array('valueBoolean' => $dolor_toracico)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.6',
                                        'text' => 'fiebre',
                                        'answer' => array(
                                            array('valueBoolean' => $fiebre)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.7',
                                        'text' => 'mialgia',
                                        'answer' => array(
                                            array('valueBoolean' => $mialgia)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.8',
                                        'text' => 'odinofagia',
                                        'answer' => array(
                                            array('valueBoolean' => $odinofagia)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.9',
                                        'text' => 'anosmia',
                                        'answer' => array(
                                            array('valueBoolean' => $anosmia)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.10',
                                        'text' => 'ageusia',
                                        'answer' => array(
                                            array('valueBoolean' => $ageusia)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.11',
                                        'text' => 'postracion',
                                        'answer' => array(
                                            array('valueBoolean' => $postracion)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.12',
                                        'text' => 'taquipnea',
                                        'answer' => array(
                                            array('valueBoolean' => $taquipnea)
                                        )
                                    ),
                                    array(
                                        'linkId' => '1.13',
                                        'text' => 'tos',
                                        'answer' => array(
                                            array('valueBoolean' => $tos)
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
                                                'valueBoolean' => $derivacionTomaMuestra,
                                                'item' => $covidExamItemsArray
                                            )
                                        )
                                    ),
                                    array(
                                        'linkId' => '2.2',
                                        'text' => 'Observacion de Seguimiento',
                                        'answer' => array(
                                            array(
                                                'valueString' => $observaciones
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
            dump($bundleJson);
            EpivigilaApi::instance()->requestApiEpivigila('POST', 'Bundle', $bundle);

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
