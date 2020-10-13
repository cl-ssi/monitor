<?php

namespace App\Http\Controllers;

use App\ContactPatient;
use App\Establishment;
use App\Helpers\EpivigilaApi;
use App\Patient;
use App\SuspectCase;
use App\Tracing\Tracing;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContactPatientController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $search, $id)
    {
        //BANDERAS DE BUSQUEDA
        $id_patient = $id;
        $s = $search;
        $message = '';

        //BUSQUEDA DE PACIENTE
        $patients = Patient::where('id', $id)
                    ->with('demographic')
                    ->with('suspectCases')
                    ->get();

        //BUSQUEDA DE CONTACTO
        if($request->input('run') != null){
          $run = $request->input('run');
        }
        else {
          $run = '';
        }
        $contacts = Patient::where('run', $run)
                    ->orWhere('other_identification', $run)
                    ->with('demographic')
                    ->with('suspectCases')
                    ->get();
        $message = 'new contact';

        if($contacts->isEmpty()){
          $message = 'dont exist';
        }

        foreach ($contacts as $key => $contact) {
          if($contact->id == $id){
            $message = 'same patient';
          }
        }

        if($search == 'search_true'){
          //CONTACTO EXISTENTE
          $contactPatients = ContactPatient::where('patient_id', $id_patient)->get();
          foreach ($contactPatients as $key => $contactPatient) {
            foreach ($contacts as $key => $contact) {
              if($contactPatient->contact_id == $contact->id){
                  $message = 'contact already registered';
              }
            }

          }
        }

        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id',$env_communes)->orderBy('name','ASC')->get();

        return view('patients.contact.create', compact('patients', 'contacts','s', 'id_patient','request', 'message', 'establishments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // GUARDAR RELACION PACIENTE
        $contactPatient = new ContactPatient($request->All());
        $contactPatient->save();
        $contactPatientId = $contactPatient->id;

        $patient = Patient::where('id', $request->get('patient_id'))->first();

        $contactPatient = new ContactPatient($request->All());
        $contactPatient->patient_id = $request->get('contact_id');
        $contactPatient->contact_id = $request->get('patient_id');
        $contactPatient->last_contact_at =  $request->get('last_contact_at');
        $contactPatient->category = $request->get('category');
        $contactPatient->relationship = $request->get('relationship');
        $contactPatient->live_together = $request->get('live_together');
        $contactPatient->comment = $request->get('comment');
        $contactPatient->index = NULL;

        $contactPatient->save();
        $id = $request->get('patient_id');

//         SE ENVIA CONTACTO Y RELACION A API EPIVIGILA
        $contact = Patient::find($request->get('contact_id'));
        $contactPatientIndex = ContactPatient::find($contactPatientId);

        if(!$request->has('create_tracing')){
            //Caso en que tiene tracing
            $contact->tracing->establishment_id = $request->get('establishment_id');
            $contact->tracing->quarantine_start_at = $request->get('quarantine_start_at');
            $contact->tracing->quarantine_end_at = $request->get('quarantine_end_at');
            $contact->tracing->save();
//            $responseContact = $this->setContactPatientWs($contact, $contactPatientIndex);
//            $responseQuestionnaire = $this->setQuestionnairePatientWs($contactPatientIndex, $contact->tracing);

        }elseif ($request->boolean('create_tracing') == true){
            //Caso en que no tiene tracing y se desea crear uno.
            $tracing = new Tracing($request->All());
            $tracing->patient_id = $contact->id;
            $tracing->index = NULL;
            $tracing->save();
//            $responseContact = $this->setContactPatientWs($contact, $contactPatientIndex);
//            $responseQuestionnaire = $this->setQuestionnairePatientWs($contactPatientIndex, $tracing);

        }else{
            //Caso en que tiene tracing y no se desea crear uno
//            $responseContact = $this->setContactPatientWs($contact, $contactPatientIndex);
//            $responseQuestionnaire['code'] = 0;
//            $responseQuestionnaire['mensaje'] = 'No se ingresó informacion de los datos del contacto a epivigila';
        }

//        $this->showEpivigilaMessage($responseQuestionnaire, $responseContact);

        return redirect()->route('patients.edit', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ContactPatient  $contactPatient
     * @return \Illuminate\Http\Response
     */
    public function show(ContactPatient $contactPatient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ContactPatient  $contactPatient
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactPatient $contactPatient)
    {
        return view('patients.contact.edit', compact('contactPatient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContactPatient  $contactPatient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactPatient $contactPatient)
    {
        $contactPatient->fill($request->all());
        $contactPatient->save();



        return redirect()->route('patients.edit', $contactPatient->patient->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContactPatient  $contactPatient
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactPatient $contactPatient)
    {

        $patient = $contactPatient->patient_id;
        $contact = $contactPatient->contact_id;

        $contactPatient->delete();

        $contact = ContactPatient::where('contact_id', $patient)
            ->where('patient_id', $contact)
            ->delete();

        return redirect()->route('patients.edit', $patient);
    }

    /**
     * En desarrollo. Envía contacto estrecho
     * @param Patient $patient contacto estrecho
     * @param ContactPatient $indexContactPatient
     * @return array|mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setContactPatientWs(Patient $patient, ContactPatient $indexContactPatient)
    {
        if($patient->run){
            $idTypeContact = '1';
            $idContact = $patient->run. '-' . $patient->dv;
        }
        else{
            $idTypeContact = '5';
            $idContact = $patient->other_identification;
        }

        $run = $patient->run . '-' . $patient->dv;
        $family = $patient->fathers_family;
        $given_name = $patient->name;
        $mothers_family = $patient->mothers_family;
        $mobile_phone = (int)$patient->demographic->telephone;
        $home_phone = ($patient->demographic->telephone2) ? (int)$patient->demographic->telephone2 : null;
        $email = ($patient->demographic->email) ? $patient->demographic->email : null;
        $gender = $patient->gender;
        $birthday = Carbon::parse($patient->birthday)->toDateString();
        $city = $patient->demographic->city;
        $region = $patient->demographic->region->name_epivigila;
        $via = strtolower($patient->demographic->street_type);
        $direccion = $patient->demographic->address;
        $numero_residencia = $patient->demographic->number;
        $region_id = (string)$patient->demographic->region_id;
        $comuna_code_deis = $patient->demographic->commune->code_deis;

        /** code deis de comunas deben ser de 5 digitos, se agrega 0 **/
        if(strlen($comuna_code_deis) == 4){
            $comuna_code_deis = '0' . $comuna_code_deis;
        }

        //Obtiene ultimo suspect case
        $suspectCase = SuspectCase::where('patient_id', $patient->id)
            ->orderBy('id', 'desc')
            ->first();

        /** Obtiene codigo deis del establecimiento del tracing, si no tiene lo obtiene de suspect case **/
        $establecimiento_code_deis = ($patient->tracing) ?
            (int)$patient->tracing->establishment->new_code_deis : (int)$suspectCase->establishment->new_code_deis;

        /**Obtiene Paciente indice**/
        $indexPatient = Patient::select('run', 'dv')->where('id', $indexContactPatient->patient_id)->first();
        Log::channel('integracionEpivigila')->debug('Paciente indice: ' . $indexContactPatient->patient_id);
        Log::channel('integracionEpivigila')->debug('Paciente indice: ' . $indexPatient->run . '-' . $indexPatient->dv);

        $response = array();
        if ($indexContactPatient != null){
            if($indexPatient->run){
                $idType = '1';
                $idPatient = $indexPatient->run. '-' . $indexPatient->dv;
            }
            else{
                $idType = '5';
                $idPatient = $indexPatient->other_identification;
            }

            $response = EpivigilaApi::instance()->getFolioPatientWs($idType, $idPatient);

            if($response['code'] == 1){
                $folioIndice = (string)$response['data']['identifier'][0]['value'];
                Log::channel('integracionEpivigila')->debug('folio indice: ' . $folioIndice);
            }
            else{
                Log::channel('integracionEpivigila')->debug( '====ERROR==== ' . (isset($response['mensaje']) ? $response['mensaje'] : $response['message']));
                return $response;
            }
        }
        else{
            Log::channel('integracionEpivigila')->debug('====ERRORR==== ' . 'No existe paciente índice para el contacto');
            $response['code'] = 0;
            $response['mensaje'] = 'No existe paciente indice para el contacto.';
            return $response;
        }

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
//                    "domicilio_particular",
//                    "domicilio_particular_caso_indice",
//                    "hospitalizacion_clinica",
//                    "hospitalizacion_domiciliaria",
//                    "residencia_sanitaria"
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
        $patientArray = array(
            'resourceType' => 'Patient',
            'identifier' => array(
                array(
                    'type' => array(
                        'coding' => array(
                            'system' => 'apidocs.epivigila.minsal.cl/tipo-documento',
                            'code' => $idTypeContact,
                            'display' => 'run')
                    ),
                    'system' => 'www.registrocivil.cl/run',
                    'value' => $idContact
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
            'birthDate' => $birthday,
            'address' => $addressArray,

            'contact' => array(array(
                'coding' => array(
                    'system' => 'apidocs.epivigila.minsal.cl/folio-indice',
                    'code' => $folioIndice,
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

        try {
            $patientJson = json_encode($patientArray, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            Storage::disk('public')->put('pruebaContacto.json', $patientJson);
            Log::channel('integracionEpivigila')->debug($patientJson);
            return EpivigilaApi::instance()->requestApiEpivigila('POST', 'Patient', $patientArray);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            Log::channel('integracionEpivigila')->debug('====ERROR====: ' . $decode);
            return $decode;
        }
    }

    public function setQuestionnairePatientWs(ContactPatient $contactPatient, Tracing $tracing)
    {
        //Obtencion folio del indice
        $response =  EpivigilaApi::instance()->getFolioPatientWs('1', $contactPatient->self_patient->run . '-' . $contactPatient->self_patient->dv);
        if($response['code'] == 1){
            $folioIndice = (string)$response['data']['identifier'][0]['value'];
            Log::channel('integracionEpivigila')->debug('folio indice: ' . $folioIndice);
        }
        else{
            Log::channel('integracionEpivigila')->debug('======ERROR=======: ' . (isset($response['mensaje']) ? $response['mensaje'] : $response['message']));
            return $response;
        }

        //Obtiene folio del contacto
        $response = EpivigilaApi::instance()->getFolioContactPatientWs('1', $contactPatient->patient->run . '-' . $contactPatient->patient->dv, $folioIndice);
        if($response['code'] == 1)
            $folioContact = (string)$response['data']['identifier'][0]['value'];
        else{
            Log::channel('integracionEpivigila')->debug( 'respuesta getfoliocontactpatientws: ' . (isset($response['mensaje']) ? $response['mensaje'] : $response['message']));
            return $response;

        }

        Log::channel('integracionEpivigila')->debug('contacto: ' . $contactPatient->patient->run . '-' . $contactPatient->patient->dv . ' ' . $contactPatient->patient->name .  ' ' . $contactPatient->patient->fathers_family);

        //Obtencion de usuario
        $userRut = auth()->user()->run . '-' . auth()->user()->dv;
        $userName = auth()->user()->name;

        //Obtencion de establecimiento
        $establishmentCode = $tracing->establishment->new_code_deis;
        $establishmentName = $tracing->establishment->name;

        //Obtencion de relacion
        $relacion = $contactPatient->categoryEpivigila;
        $parentesco = $contactPatient->relationshipNameEpivigila;

        //Obtencion inicio cuarentena
        $inicioCuarentena = Carbon::parse($tracing->quarantine_start_at)->toDateString();

        //Datos extra dependiendo de categoria de contacto
        $arrayItems = array();

        //Data para institucional
        $arrayInstitutional = array(
            'linkId' => '1.3',
            'text' => 'Nombre institución',
            'answer' => array(
                array(
                    'valueCoding' => $contactPatient->institution
                )
            )
        );

        //Data para laboral
        $arrayOcupational = array(
            'linkId' => '1.2',
            'text' => 'Nombre empresa',
            'answer' => array(
                array(
                    'valueCoding' => $contactPatient->company_name
                )
            )
        );

        //Data para pasajero
        $arrayAirMode = array(
            array(
                'linkId' => '1.4.1',
                'text' => 'Nombre de vuelo',
                'answer' => array(
                    array(
                        'valueString' => $contactPatient->flight_name
                    )
                )
            ),
            array(
                'linkId' => '1.4.3',
                'text' => 'Detalle del pasajero',
                'answer' => array(
                    array(
                        'valueString' =>  $contactPatient->comment
                    )
                )
            )
        );

        if ($contactPatient->flight_date){
            $arrayFlightDate = array(
                'linkId' => '1.4.2',
                'text' => 'Fecha de vuelo',
                'answer' => array(
                    array(
                        'valueDate' =>  Carbon::parse($contactPatient->flight_date)->toDateString()
                    )
                )
            );
            array_push($arrayAirMode, $arrayFlightDate);
        }

        $arrayPassenger = array(
            'linkId' => '1.4',
            'text' => 'Tipo de transporte',
            'answer' => array(
                array(
                    'valueCoding' => $contactPatient->mode_of_transport
                )
            ),
            'item' => $arrayAirMode
        );

        //Data para social
        $arraySocialMeetingDate = array();
        if ($contactPatient->social_meeting_date) {
            $arraySocialMeetingDate = array(
                array(
                    'linkId' => '1.5.1',
                    'text' => 'Fecha encuentro social',
                    'answer' => array(
                        array(
                            'valueDate' => Carbon::parse($contactPatient->social_meeting_date)->toDateString()
                        )
                    )
                )
            );
        }

        $arraySocial = array(
            'linkId' => '1.5',
            'text' => 'Nombre lugar encuentro social',
            'answer' => array(
                array(
                    'valueString' => $contactPatient->social_meeting_place
                )
            ),
            'item' => $arraySocialMeetingDate
        );

        //Data para sala se espera
        $arrayWaitingRoom = array(
            'linkId' => '1.7',
            'text' => 'Nombre establecimiento sala de espera',
            'answer' => array(
                array(
                    'valueCoding' => $contactPatient->waiting_room_establishment
                )
            )
        );

        //Data para functionary
        $arrayFunctionary = array(
            'linkId' => '1.6',
            'text' => 'Profesión personal de salud',
            'answer' => array(
                array(
                    'valueCoding' => $contactPatient->functionary_profession
                )
            )
        );

        //Data para familiar
        $arrayFamily = array(
            'linkId' => '1.1',
            'text' => 'Parentesco',
            'answer' => array(
                array(
                    'valueCoding' => $parentesco
                )
            )
        );

        //Se agrega array segun corresponda
        switch($contactPatient->category){
            case 'institutional':
                array_push($arrayItems, $arrayInstitutional);
                break;
            case 'ocupational':
                array_push($arrayItems, $arrayOcupational);
                break;
            case 'passenger';
                array_push($arrayItems, $arrayPassenger);
                break;
            case 'social';
                array_push($arrayItems, $arraySocial);
                break;
            case 'waiting room';
                array_push($arrayItems, $arrayWaitingRoom);
                break;
            case 'family';
                array_push($arrayItems, $arrayFamily);
                break;
            case 'functionary';
                array_push($arrayItems, $arrayFunctionary);
                break;
        }

        //Creacion de array json para envio
        $questionnaireArray = array('resourceType' => 'QuestionnaireResponse',
            'contained' => array(
                array(
                    'resourceType' => 'Patient',
                    'id' => 'contacto',
                    'identifier' => array(
                        array('system' => 'apidocs.epivigila.minsal.cl/folio-contacto',
                            'value' => $folioContact)
                    )),
                array(
                    'resourceType' => 'Practitioner',
                    'id' => 'responsable-encuesta',
                    'identifier' => array(
                        array('system' => 'www.registrocivil.cl/run',
                            'value' => $userRut)
                    ),
                    'name' => $userName
                ),
                array(
                    'resourceType' => 'Organization',
                    'id' => 'institucion-seguimiento',
                    'identifier' => array(
                        array('system' => 'apidocs.epivigila.minsal.cl/establecimientos-DEIS',
                            'value' => $establishmentCode)
                    ),
                    'name' => $establishmentName
                )
            ),
            'status' => 'completed',
            'subject' => array(
                'reference' => '#encuesta-c19'
            ),
            'item' => array(
                array(
                    'linkId' => '1',
                    'text' => 'Relación con el caso',
                    'answer' => array(
                        array(
                            'valueCoding' => $relacion
                        )
                    ),
                    'item' => $arrayItems
                ),
                array(
                    'linkId' => '2',
                    'text' => 'Fecha de inicio de cuarentena',
                    'answer' => array(
                        array(
                            'valueDate' => $inicioCuarentena
                        )
                    )
                )
            ),
            'request' => array(
                'method' => 'POST',
                'url' => 'QuestionnaireResponse'
            )
        );


        try {
            $bundleJson = json_encode($questionnaireArray, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            Storage::disk('public')->put('pruebaQuestionnaire.json', $bundleJson);
            Log::channel('integracionEpivigila')->debug($bundleJson);
            return EpivigilaApi::instance()->requestApiEpivigila('POST', 'QuestionnaireResponse', $questionnaireArray);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            Log::channel('integracionEpivigila')->debug('=======ERROR=====: ' . $decode);
            return $decode;
        }

    }

    /** Muestra mensajes desde API Epivigila
     * @param array $responseQuestionnaire
     * @param array $responseContact
     */
    private function showEpivigilaMessage(array $responseQuestionnaire, array $responseContact): void
    {
        if ($responseQuestionnaire['code'] == 1) {
            session()->flash('info', 'EPIVIGILA SEGUIMIENTO: ' . (isset($responseQuestionnaire['mensaje']) ? $responseQuestionnaire['mensaje'] : $responseQuestionnaire['message']) . '. ' . $responseQuestionnaire['detalle_mensaje']);
        } else {
            session()->flash('warning', 'EPIVIGILA SEGUIMIENTO: ' . (isset($responseQuestionnaire['mensaje']) ? $responseQuestionnaire['mensaje'] : $responseQuestionnaire['message']) . '. ' . $responseQuestionnaire['detalle_mensaje']);
        }

        if ($responseContact['code'] == 1) {
            session()->flash('info', 'EPIVIGILA CONTACTO: ' . (isset($responseContact['mensaje']) ? $responseContact['mensaje'] : $responseContact['message']) . '. ' . $responseContact['detalle_mensaje']);
        } else {
            session()->flash('warning', 'EPIVIGILA CONTACTO: ' . (isset($responseContact['mensaje']) ? $responseContact['mensaje'] : $responseContact['message']) . '. ' . $responseContact['detalle_mensaje']);
        }
    }

}
