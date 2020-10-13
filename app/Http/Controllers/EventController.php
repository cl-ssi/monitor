<?php

namespace App\Http\Controllers;

use App\ContactPatient;
use App\Helpers\EpivigilaApi;
use App\Tracing\Event;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = new Event($request->All());
        $event->user_id = auth()->id();
        if($request->input('sympthoms_array')) {
            $event->symptoms = implode (", ",$request->input('sympthoms_array'));
        }
        $event->save();

        if($request->input('next_action')) {
            switch ($request->input('next_action')) {
                case 0:
                    $event->tracing->status = 0;
                    break;
                case 1:
                    $event->tracing->next_control_at = $event->event_at->add(1,'day');
                    break;
                case 2:
                    $event->tracing->next_control_at = $event->event_at->add(2,'day');
                    break;
                case 3:
                    $event->tracing->next_control_at = $event->event_at->add(3,'day');
                    break;
            }
            $event->tracing->save();
        }

        session()->flash('info', 'Evento almacenado');

        //ENVIO DE SEGUIMIENTO A EPIVIGILA
//        $response =  $this->setTracingEventBundleWs($event);
//
//        if ($response['code'] != 1)
//            session()->flash('warning', 'EPIVIGILA SEGUIMIENTO: ' . (isset($response['mensaje']) ? $response['mensaje'] : $response['message']) . '. ' . $response['detalle_mensaje']);
//
//        dump($response);

        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tracing\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tracing\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }

    public function setTracingEventBundleWs(Event $event)
    {
        dump($event);

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
        $tipo_contactabilidad = $event->contact_type;

        /** Obtener eventos  **/
        $derivacionTomaMuestra = $event->event_type_id == 2;
        $derivacionSU = $event->event_type == 7;

        /** Obtener si es caso indice o no **/
        $index = $event->tracing->index;

        /** Se construye array de identificacion dependiendo de si es caso indice o no **/
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
            else{
                dump( 'respuesta getfoliompatientws: ' . $response['mensaje']);
                return $response;
            }

            /** Folio del contacto estrecho **/
            $response = EpivigilaApi::instance()->getFolioContactPatientWs('1', $patient_rut, $folio);
            if($response['code'] == 1)
                $folioContact = (string)$response['data']['identifier'][0]['value'];
            else{
                dump( 'respuesta getfoliocontactpatientws: ' . $response['mensaje']);
                return $response;
            }

            $arrayIndentification = array(
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
            );

        }else{
            /** Obtiene paciente indice del contacto **/
            $indexPatientRut = $event->tracing->patient->run . '-' . $event->tracing->patient->dv;
            dump('Paciente indice: ' . $event->tracing->patient_id, 'Rut paciente indice: ' . $indexPatientRut);

            /** Obtiene el folio del paciente indice del contacto estrecho **/
            //todo type_id dinamico
            $response = EpivigilaApi::instance()->getFolioPatientWs('1', $indexPatientRut);
            if($response['code'] == 1)
                $folio = (string)$response['data']['identifier'][0]['value'];
            else{
                dump( 'respuesta getfoliompatientws: ' . $response['mensaje']);
                return $response;
            }

            $arrayIndentification = array(
                'resourceType' => 'Patient',
                'id' => 'seguimiento-covid',
                'identifier' => array(array(
                    'system' => 'apidocs.epivigila.minsal.cl/folio-indice',
                    'code' => $folio,
                    'display' => 'folio-indice'
                ))
            );
        }

        /** Obtiene Establecimiento que realiza el seguimiento  **/
        $establishmentName = $event->tracing->establishment->name;
        $establishmentCode = (integer)$event->tracing->establishment->new_code_deis;

        /** Obtener sintomas del evento **/
        $symptoms = $event->symptoms;
        $symptoms = explode(',', $symptoms);
        $symptoms = array_map('trim', $symptoms);

        dump($symptoms);

        $fiebre = in_array('Fiebre', $symptoms) ? true : false;
        $tos = in_array('Tos', $symptoms) ? true : false;
        $mialgia = in_array('Mialgias', $symptoms) ? true : false;
        $odinofagia = in_array('Odinofagia', $symptoms) ? true : false;
        $anosmia = in_array('Anosmia', $symptoms) ? true : false;
        $ageusia = in_array('Ageusia', $symptoms) ? true : false;
        $dolor_toracico = in_array('Dolor toráxico', $symptoms) ? true : false;
        $diarrea = in_array('Diarrea', $symptoms) ? true : false;
        $cefalea = in_array('Cefalea', $symptoms) ? true : false;
        $cianosis = in_array('Cianosis', $symptoms) ? true : false;
        $dolor_abdominal = in_array('Dolor abdominal', $symptoms) ? true : false;
        $postracion = in_array('Postracion', $symptoms) ? true : false;
        $taquipnea = in_array('Taquipnea', $symptoms) ? true : false;

        /** Obtiene fecha del evento **/
        $fechaEvento = Carbon::parse($event->event_at)->toDateString();

        //Se generan arrays de items covid exams
        $tomaDeMuestraArray = array(
            'linkId' => '2.1.1',
            'text' => 'fecha derivacion toma de muestras',
            'answer' => array(
                array(
                    'valueDate' => $fechaEvento //todo corroborar
                )
            )
        );

        $derivacionSuArray = array(
            'linkId' => '2.1.2',
            'text' => 'derivacion a SU',
            'answer' => array(
                array(
                    'valueBoolean' => $derivacionSU //todo corroborar
                )
            )
        );

        /** Obtiene cumplimiento de cuarentena **/
        $quarantineFulfilled = $event->quarantine_fulfilled == 1;
        $cumpleCuarantenaArray = array(
            'linkId' => '2.1.3',
            'text' => '¿cumple cuarentena y aislamiento?',
            'answer' => array(
                array(
                    'valueBoolean' => $quarantineFulfilled
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
        if($status == 'finished'){array_push($covidExamItemsArray, $cumpleCuarantenaArray);}
//        array_push($covidExamItemsArray, $tieneResultadoCovidArray);

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
                            $arrayIndentification,
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
            Storage::disk('public')->put('pruebaTracingBundle.json', $bundleJson);
            dump($bundleJson);
            return EpivigilaApi::instance()->requestApiEpivigila('POST', 'Bundle', $bundle);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            dump('error: ' . $decode);
            return $decode;
        }
    }

}
