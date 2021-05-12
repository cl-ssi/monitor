<?php

namespace App\Http\Controllers;

use App\Commune;
use App\Demographic;
use App\Establishment;
use App\Patient;
use App\SuspectCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebserviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fonasa(Request $request)
    {
        /* Si se le envió el run y el dv por GET */
        if ($request->has('run') and $request->has('dv')) {
            $rut = $request->input('run');
            $dv = $request->input('dv');

            $wsdl = asset('ws/fonasa/CertificadorPrevisionalSoap.wsdl');
            $client = new \SoapClient($wsdl, array('trace' => TRUE));
            $parameters = array(
                "query" => array(
                    "queryTO" => array(
                        "tipoEmisor" => 3,
                        "tipoUsuario" => 2
                    ),
                    "entidad"           => env('FONASA_ENTIDAD'),
                    "claveEntidad"      => env('FONASA_CLAVE'),
                    "rutBeneficiario"   => $rut,
                    "dgvBeneficiario"   => $dv,
                    "canal"             => 3
                )
            );
            $result = $client->getCertificadoPrevisional($parameters);

            if ($result === false) {
                /* No se conecta con el WS */
                $error = array("error" => "No se pudo conectar a FONASA");
            } else {
                /* Si se conectó al WS */
                if ($result->getCertificadoPrevisionalResult->replyTO->estado == 0) {
                    /* Si no hay error en los datos enviados */

                    $certificado            = $result->getCertificadoPrevisionalResult;
                    $beneficiario           = $certificado->beneficiarioTO;
                    $afiliado               = $certificado->afiliadoTO;

                    $user['run']            = $beneficiario->rutbenef;
                    $user['dv']             = $beneficiario->dgvbenef;
                    $user['name']           = $beneficiario->nombres;
                    $user['fathers_family'] = $beneficiario->apell1;
                    $user['mothers_family'] = $beneficiario->apell2;
                    $user['birthday']       = $beneficiario->fechaNacimiento;
                    $user['gender']         = $beneficiario->generoDes;
                    $user['desRegion']         = $beneficiario->desRegion;
                    $user['desComuna']         = $beneficiario->desComuna;
                    $user['direccion']      = $beneficiario->direccion;
                    $user['telefono']      = $beneficiario->telefono;

                    if ($afiliado->desEstado == 'ACTIVO') {
                        $user['tramo'] = $afiliado->tramo;
                    } else {
                        $user['tramo'] = null;
                    }
                    //$user['estado']       = $afiliado->desEstado;
                } else {
                    /* Error */
                    $error = array("error" => $result->getCertificadoPrevisionalResult->replyTO->errorM);
                }
            }

            // echo '<pre>';
            //print_r($result);
            //dd($result);

            return isset($user) ? json_encode($user) : json_encode($error);
        }
    }

    public function caseCreate(Request $request)
    {
        try {
            $dataArray = json_decode($request->getContent(), true);

            if (isset($dataArray['patient']['run']) && is_numeric($dataArray['patient']['run'])) {
                if (!isset($dataArray['patient']['dv']) || (isset($dataArray['patient']['dv']) && !in_array($dataArray['patient']['dv'], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'k', 'K']))) {
                    $responseArray = ['success' => false,
                        'message' => 'Debe ingresar un dv válido'];
                    return json_encode($responseArray);
                }
            } elseif (!$dataArray['patient']['other_identification']) {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar other_identification o run válido'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['patient']['name']) || $dataArray['patient']['name'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar name'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['patient']['fathers_family']) || $dataArray['patient']['fathers_family'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar fathers_family'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['patient']['fathers_family']) || $dataArray['patient']['fathers_family'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar fathers_family'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['patient']['gender']) || $dataArray['patient']['gender'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar gender'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['patient']['birthday']) || $dataArray['patient']['birthday'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar birthday'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['demographic']['street_type']) || $dataArray['demographic']['street_type'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar street_type'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['demographic']['address']) || $dataArray['demographic']['address'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar address'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['demographic']['commune_deis']) || $dataArray['demographic']['commune_deis'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar commune_deis'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['demographic']['nationality']) || $dataArray['demographic']['nationality'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar nationality'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['demographic']['telephone']) || $dataArray['demographic']['telephone'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar telephone'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['case']['laboratory_id']) || $dataArray['case']['laboratory_id'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar laboratory_id'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['case']['sample_type']) || $dataArray['case']['sample_type'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar sample_type'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['case']['establishment_deis']) || $dataArray['case']['establishment_deis'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar establishment_deis'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['case']['gestation']) || $dataArray['case']['gestation']) {
                $responsearray = ['success' => false,
                    'message' => 'Debe ingresar gestation'];
                return json_encode($responsearray);
            }

            if (!isset($dataArray['case']['epivigila']) || $dataArray['case']['epivigila'] === '') {
                $responsearray = ['success' => false,
                    'message' => 'Debe ingresar epivigila'];
                return json_encode($responsearray);
            }

            if (!isset($dataArray['case']['case_type']) || $dataArray['case']['case_type'] == '') {
                $responsearray = ['success' => false,
                    'message' => 'Debe ingresar case_type'];
                return json_encode($responsearray);
            }

            $patientsDbCount = Patient::query()
                ->when($dataArray['patient']['run'], function ($q) use ($dataArray) {
                    $q->where('run', $dataArray['patient']['run']);
                })
                ->when($dataArray['patient']['other_identification'], function ($q) use ($dataArray) {
                    $q->where('other_identification', $dataArray['patient']['other_identification']);
                })
                ->count();

            if ($patientsDbCount == 0) {
                $newPatient = new Patient();
                $newPatient->run = $dataArray['patient']['run'];
                $newPatient->dv = $dataArray['patient']['dv'];
                $newPatient->other_identification = $dataArray['patient']['other_identification'];
                $newPatient->name = $dataArray['patient']['name'];
                $newPatient->fathers_family = $dataArray['patient']['fathers_family'];
                $newPatient->mothers_family = $dataArray['patient']['mothers_family'];
                $newPatient->gender = $dataArray['patient']['gender'];
                $newPatient->birthday = $dataArray['patient']['birthday'];
                $newPatient->status = $dataArray['patient']['status'];
                $newPatient->save();
            }

            $patient = Patient::query()
                ->when($dataArray['patient']['run'], function ($q) use ($dataArray) {
                    $q->where('run', $dataArray['patient']['run']);
                })
                ->when($dataArray['patient']['other_identification'], function ($q) use ($dataArray) {
                    $q->where('other_identification', $dataArray['patient']['other_identification']);
                })
                ->get()
                ->first();

            if ($patient) {

                $commune_id = Commune::where('code_deis', $dataArray['demographic']['commune_deis'])->first()->id;
                //todo debería actualizar el demographic?
                if (!$patient->demographic) {
                    $newDemographic = new Demographic();
                    $newDemographic->street_type = $dataArray['demographic']['street_type'];
                    $newDemographic->address = $dataArray['demographic']['address'];
                    $newDemographic->number = $dataArray['demographic']['number'];
                    $newDemographic->department = $dataArray['demographic']['department'];
                    $newDemographic->city = $dataArray['demographic']['city'];
                    $newDemographic->suburb = $dataArray['demographic']['suburb'];
                    $newDemographic->commune_id = $commune_id;
                    $newDemographic->nationality = $dataArray['demographic']['nationality'];
                    $newDemographic->telephone = $dataArray['demographic']['telephone'];
                    $newDemographic->email = $dataArray['demographic']['email'];
                    $newDemographic->patient_id = $patient->id;
                    $newDemographic->save();
                }
            }

            if ($patient) {
                $establishment_id = Establishment::where('new_code_deis', $dataArray['case']['establishment_deis'])->first()->id;
                $newSuspectCase = new SuspectCase();
                $newSuspectCase->laboratory_id = $dataArray['case']['laboratory_id'];
                $newSuspectCase->sample_type = $dataArray['case']['sample_type'];
                //todo validar sample_at no dos el msimo dia
                //todo validar que no se repita minsal_ws_id
                $newSuspectCase->sample_at = $dataArray['case']['sample_at'];
                $newSuspectCase->pcr_sars_cov_2 = 'pending';
                $newSuspectCase->establishment_id = $establishment_id;
                $newSuspectCase->origin = $dataArray['case']['origin'];
                $newSuspectCase->run_medic = $dataArray['case']['run_medic'];
                $newSuspectCase->symptoms = $dataArray['case']['symptoms'];
                $newSuspectCase->gestation = $dataArray['case']['gestation'];
                $newSuspectCase->close_contact = $dataArray['case']['close_contact'];
                $newSuspectCase->functionary = $dataArray['case']['functionary'];
                $newSuspectCase->observation = $dataArray['case']['observation'];
                $newSuspectCase->epivigila = $dataArray['case']['epivigila'];
                $newSuspectCase->patient_id = $patient->id;
                $newSuspectCase->user_id = Auth::user()->id;
                $newSuspectCase->gender = $patient->gender;
                $newSuspectCase->age = $patient->age;
                $newSuspectCase->minsal_ws_id = $dataArray['case']['minsal_ws_id'];
                $newSuspectCase->epidemiological_week = Carbon::createFromDate($newSuspectCase->sample_at->format('Y-m-d'))
                    ->add(1, 'days')->weekOfYear;
                $newSuspectCase->save();
            }

            //Respuesta
            $responseArray = ['success' => true,
                'case_id' => $newSuspectCase->id];
            return json_encode($responseArray);

        } catch (\Exception $e) {
            $responseArray = ['success' => false,
                'message' => $e->getMessage()];
            return json_encode($responseArray);
        }
    }

    public function caseReception(Request $request)
    {
        try {
            $dataArray = json_decode($request->getContent(), true);

            if (!isset($dataArray['reception']['case_id']) || $dataArray['reception']['case_id'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar case_id'];
                return json_encode($responseArray);
            }

            if (!isset($dataArray['reception']['reception_at']) || $dataArray['reception']['reception_at'] == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar reception_at'];
                return json_encode($responseArray);
            }

            $suspectCase = SuspectCase::find($dataArray['reception']['case_id']);
            $suspectCase->reception_at = $dataArray['reception']['reception_at'];
            $suspectCase->receptor_id = Auth::user()->id;
            $suspectCase->save();

            $responseArray = ['success' => true, 'case_id' => $suspectCase->id];
            return json_encode($responseArray);
        } catch (\Exception $e) {

            $responseArray = ['success' => false,
                'message' => $e->getMessage()];
            return json_encode($responseArray);
        }
    }

    public function caseResult(Request $request)
    {
        try {

            if (!$request->has('case_id') || $request->get('case_id') == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar case_id'];
                return json_encode($responseArray);
            }

            if (!$request->has('pcr_sars_cov_2') || !in_array($request->get('pcr_sars_cov_2'), ['positive', 'negative', 'rejected', 'undetermined'])) {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar pcr_sars_cov_2 válido'];
                return json_encode($responseArray);
            }

            if (!$request->has('pcr_sars_cov_2_at') || $request->get('pcr_sars_cov_2_at') == '') {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar pcr_sars_cov_2_at'];
                return json_encode($responseArray);
            }

            if (!$request->hasFile('file')) {
                $responseArray = ['success' => false,
                    'message' => 'Debe ingresar file'];
                return json_encode($responseArray);
            }

            $suspectCase = SuspectCase::find($request->get('case_id'));
            $suspectCase->pcr_sars_cov_2 = $request->get('pcr_sars_cov_2');
            $suspectCase->pcr_sars_cov_2_at = $request->get('pcr_sars_cov_2_at');
            $suspectCase->pcr_result_added_at = Carbon::now();
            $suspectCase->validator_id = Auth::id();
            $suspectCase->epidemiological_week = Carbon::createFromDate(
                $suspectCase->sample_at->format('Y-m-d'))->add(1, 'days')->weekOfYear;
            $file = $request->file('file');
            $file->storeAs('suspect_cases', $request->get('case_id') . '.pdf');
            $suspectCase->file = true;
            $suspectCase->save();

            $responseArray = ['success' => true, 'case_id' => $suspectCase->id];
            return json_encode($responseArray, JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            $responseArray = ['success' => false,
                'message' => $e->getMessage()];
            return json_encode($responseArray);
        }

//        Log::channel('integracionEpivigila')->debug('caseresult: ' . $request->getContent());
    }

}
