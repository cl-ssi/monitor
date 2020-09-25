<?php

namespace App\Http\Controllers;

use App\ContactPatient;
use App\Helpers\EpivigilaApi;
use App\Patient;
use App\SuspectCase;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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


        return view('patients.contact.create', compact('patients', 'contacts','s', 'id_patient','request', 'message'));
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
        //todo QUITAR SIGUIENTES LINEAS ANTES DE GIT PUSH
//        $contact = Patient::find($request->get('contact_id'));
        $contactPatientIndex = ContactPatient::find($contactPatientId);
//        $response = $this->setContactPatientWs($contact);
//        $this->setQuestionnairePatientWs($contactPatientIndex);
//        dd($response);

//        if($response['code'] == 1){
//            session()->flash('info', 'EPIVIGILA: ' . $response['message'] . '. ' . $response['detalle_mensaje']);
//        }
//        else{
//            session()->flash('warning', 'EPIVIGILA: ' . $response['mensaje'] . '. ' . $response['detalle_mensaje']);
//        }

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
     * @param Patient $patient
     * @throws GuzzleException
     */
    public function setContactPatientWs(Patient $patient){

        $run = $patient->run . '-' . $patient->dv;
        $family = $patient->fathers_family;
        $given_name = $patient->name;
        $mothers_family = $patient->mothers_family;
        $mobile_phone = (int)$patient->demographic->telephone;
        $home_phone = ($patient->demographic->telephone2) ? (int)$patient->demographic->telephone2 : null;
        $email = ($patient->demographic->email) ? $patient->demographic->email : null;
        $gender = $patient->gender;
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

        //todo obtener suspect case por parametro?, por ahora se obtiene el ultimo caso
        $suspectCase = SuspectCase::where('patient_id', $patient->id)
            ->orderBy('id', 'desc')
            ->first();

        /** Obtiene codigo deis del establecimiento del suspect case **/
        $establecimiento_code_deis = ($suspectCase->establishment->new_code_deis) ?
            (int)$suspectCase->establishment->new_code_deis : null;

        /** Obtiene paciente indice del contacto **/
        $indexContactPatient = ContactPatient::select('patient_id')
            ->where('contact_id', $patient->id)
            ->where('index', true)->first();

        //todo verificar si no tiene paciente indice

        dump('Paciente indice: ' . $indexContactPatient->patient_id);

        $indexPatient = Patient::select('run', 'dv')->where('id', $indexContactPatient->patient_id)->first();

        dump('Paciente indice: ' . $indexPatient->run . '-' . $indexPatient->dv);

        if ($indexContactPatient != null){
//            $response= $this->getFolioPatientWs('1', '17353836-7');

            if($indexPatient->run){
                $idType = '1';
                $idPatient = $indexPatient->run. '-' . $indexPatient->dv;
            }
            else{
                $idType = '5';
                $idPatient = $indexPatient->other_identification;
            }

//            $response = $this->getFolioPatientWs($idType, $idPatient);
            $response = EpivigilaApi::instance()->getFolioPatientWs($idType, $idPatient);

            if($response['code'] == 1){
                $folioIndice = (string)$response['data']['identifier'][0]['value'];
                dump('folio indice: ' . $folioIndice);
            }
            else
                dump( '====ERROR==== ' . $response['mensaje']);
        }
        else
            dump('====ERRORR==== ' . 'No existe paciente índice para el contacto');

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
//                        todo cambiar a una de las opciones:
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
//                    'type' => array(
//                        'coding' => array(
//                            'system' => 'apidocs.epivigila.minsal.cl/tipo-documento',
//                            //todo hacer dinamico segun tipo doc (run u otro (5))
//                            'code' => 1,
//                            'display' => 'run')
//                    ),
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
            'birthDate' => '1992-10-27', //todo agregar birthdate
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
            Storage::disk('public')->put('prueba.json', $patientJson);
            dump($patientJson);
            //todo obtener respuesta y verificar si envio fue valido
            return EpivigilaApi::instance()->requestApiEpivigila('POST', 'Patient', $patientArray);
//            $response = ['status' => 1, 'msg' => 'OK'];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            dump('====ERROR====: ' . $decode);
            return $decode;
//            $response = ['status' => 0, 'msg' => $decode->error];
        }
    }

    public function setQuestionnairePatientWs(ContactPatient $contactPatient){

        //Obtencion folio del indice
        $response =  EpivigilaApi::instance()->getFolioPatientWs('1', $contactPatient->self_patient->run . '-' . $contactPatient->self_patient->dv);
        if($response['code'] == 1){
            $folioIndice = (string)$response['data']['identifier'][0]['value'];
            dump('folio indice: ' . $folioIndice);
        }
        else
            dump('======ERROR=======: ' . $response['mensaje']);

        //Obtiene folio del contacto
        $response = EpivigilaApi::instance()->getFolioContactPatientWs('1', $contactPatient->patient->run . '-' . $contactPatient->patient->dv, $folioIndice);
        if($response['code'] == 1)
            $folioContact = (string)$response['data']['identifier'][0]['value'];
        else
            dump( 'respuesta getfoliocontactpatientws: ' . $response['mensaje']);

        dump('contacto: ' . $contactPatient->patient->run . '-' . $contactPatient->patient->dv . ' ' . $contactPatient->patient->name .  ' ' . $contactPatient->patient->fathers_family);

        //Obtencion de usuario
        $userRut = auth()->user()->run . '-' . auth()->user()->dv;
        $userName = auth()->user()->name;

        //Obtencion de establecimiento
//        $establishmentCode = auth()->user()->establishment->new_code_deis;
//        $establishmentName = auth()->user()->establishment->name;

        //Obtencion de relacion
        $relacion = $contactPatient->categoryEpivigila;
        $parentesco = $contactPatient->relationshipNameEpivigila;


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
                            'value' => 102010) //todo
                    ),
                    'name' => 'Actividades gestionadas por la Dirección del Servicio para apoyo de la Red (S.S de Iquique)' //todo
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
                    'item' => array(
                        array(
                            'linkId' => '1.1',
                            'text' => 'Parentesco',
                            'answer' => array(
                                array(
                                    'valueCoding' => $parentesco
                                )
                            )
                        )
                    )
                ),
                array(
                    'linkId' => '2',
                    'text' => 'Fecha de inicio de cuarentena',
                    'answer' => array(
                        array(
                            'valueDate' => '2020-09-24' //todo
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
            Storage::disk('public')->put('prueba.json', $bundleJson);
            dump($bundleJson);
            EpivigilaApi::instance()->requestApiEpivigila('POST', 'QuestionnaireResponse', $questionnaireArray);

//            $response = ['status' => 1, 'msg' => 'OK'];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            dump('=======ERROR=====: ' + $decode);

//            $response = ['status' => 0, 'msg' => $decode->error];
        }

    }


}
