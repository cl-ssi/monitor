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

    public function addCase(Request $request)
    {
        $dataArray = json_decode($request->getContent(), true);

        //TODO validar otros campos
        if (is_numeric($dataArray[0]['run'])) {
            if (!$dataArray[0]['dv']) {
                $responseArray = ['error' => 'Debe ingresar un dv válido'];
                return json_encode($responseArray);
            }
        } elseif (!$dataArray[0]['other_identification']) {
            //TODO string "0" es false
            $responseArray = ['error' => 'Debe ingresar other_identification o run válido'];
            return json_encode($responseArray);
        }

        $patientsDbCount = Patient::query()
            ->when($dataArray[0]['run'], function ($q) use ($dataArray) {
                $q->where('run', $dataArray[0]['run']);
            })
            ->when($dataArray[0]['other_identification'], function ($q) use ($dataArray) {
                $q->where('other_identification', $dataArray[0]['other_identification']);
            })
            ->count();

        if ($patientsDbCount == 0) {
            $newPatient = new Patient();
            $newPatient->run = $dataArray[0]['run'];
            $newPatient->dv = $dataArray[0]['dv'];
            $newPatient->other_identification = $dataArray[0]['other_identification'];
            $newPatient->name = $dataArray[0]['name'];
            $newPatient->fathers_family = $dataArray[0]['fathers_family'];
            $newPatient->mothers_family = $dataArray[0]['mothers_family'];
            $newPatient->gender = $dataArray[0]['gender'];
            $newPatient->birthday = $dataArray[0]['birthday'];
            $newPatient->status = $dataArray[0]['status'];
            $newPatient->save();
        }

        $patient = Patient::query()
            ->when($dataArray[0]['run'], function ($q) use ($dataArray) {
                $q->where('run', $dataArray[0]['run']);
            })
            ->when($dataArray[0]['other_identification'], function ($q) use ($dataArray) {
                $q->where('other_identification', $dataArray[0]['other_identification']);
            })
            ->get()
            ->first();

        if ($patient) {

            $commune_id = Commune::where('code_deis', $dataArray[1]['commune_deis'])->first()->id;
            //todo debería actualizar el demographic?
            if (!$patient->demographic) {
                $newDemographic = new Demographic();
                $newDemographic->street_type = $dataArray[1]['street_type'];
                $newDemographic->address = $dataArray[1]['address'];
                $newDemographic->number = $dataArray[1]['number'];
                $newDemographic->department = $dataArray[1]['department'];
                $newDemographic->city = $dataArray[1]['city'];
                $newDemographic->suburb = $dataArray[1]['suburb'];
                $newDemographic->commune_id = $commune_id;
                $newDemographic->nationality = $dataArray[1]['nationality'];
                $newDemographic->telephone = $dataArray[1]['telephone'];
                $newDemographic->email = $dataArray[1]['email'];
                $newDemographic->patient_id = $patient->id;
                $newDemographic->save();
            }
        }

        if ($patient) {

            $establishment_id = Establishment::where('new_code_deis', $dataArray[2]['establishment_deis'])->first()->id;
            $newSuspectCase = new SuspectCase();
            $newSuspectCase->laboratory_id = $dataArray[2]['laboratory_id'];
            $newSuspectCase->sample_type = $dataArray[2]['sample_type'];
            $newSuspectCase->sample_at = $dataArray[2]['sample_at'];
//            $newSuspectCase->reception_at = $dataArray['reception_at'];
//            $newSuspectCase->pcr_sars_cov_2_at = $dataArray['pcr_sars_cov_2_at'];
            $newSuspectCase->pcr_sars_cov_2 = 'pending';
            $newSuspectCase->establishment_id = $establishment_id;
            $newSuspectCase->origin = $dataArray[2]['origin'];
            $newSuspectCase->run_medic = $dataArray[2]['run_medic'];
            $newSuspectCase->symptoms = $dataArray[2]['symptoms'];
            $newSuspectCase->gestation = $dataArray[2]['gestation'];
            $newSuspectCase->close_contact = $dataArray[2]['close_contact'];
            $newSuspectCase->functionary = $dataArray[2]['functionary'];
            $newSuspectCase->observation = $dataArray[2]['observation'];
            $newSuspectCase->epivigila = $dataArray[2]['epivigila'];
            $newSuspectCase->patient_id = $patient->id;
            $newSuspectCase->user_id = Auth::user()->id;
            $newSuspectCase->gender = $patient->gender;
            $newSuspectCase->age = $patient->age;
            $newSuspectCase->minsal_ws_id = $dataArray[2]['minsal_ws_id'];
            $newSuspectCase->epidemiological_week = Carbon::createFromDate($newSuspectCase->sample_at->format('Y-m-d'))
                                                    ->add(1, 'days')->weekOfYear;
            $newSuspectCase->save();
        }

        //Respuesta
        $responseArray = ['patient_id' => $patient->id];
        return json_encode($responseArray);


//        error_log('probando');
//        info('con info');
//        Log::channel('integracionEpivigila')->debug($array);
//        Log::info('con loginfo');
    }

}
