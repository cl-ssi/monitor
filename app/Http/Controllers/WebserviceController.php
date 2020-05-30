<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        if($request->has('run') AND $request->has('dv')) {
            $rut = $request->input('run');
            $dv  = $request->input('dv');

            $wsdl = asset('ws/fonasa/CertificadorPrevisionalSoap.wsdl');
            $client = new \SoapClient($wsdl,array('trace'=>TRUE));
            $parameters = array(
                "query" => array(
                    "queryTO" => array(
                        "tipoEmisor"  => 3,
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
            }
            else {
                /* Si se conectó al WS */
                if($result->getCertificadoPrevisionalResult->replyTO->estado == 0) {
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

                    if($afiliado->desEstado == 'ACTIVO') {
                        $user['tramo'] = $afiliado->tramo;
                    }
                    else {
                        $user['tramo'] = null;
                    }
                    //$user['estado']       = $afiliado->desEstado;
                }
                else {
                    /* Error */
                    $error = array("error" => $result->getCertificadoPrevisionalResult->replyTO->errorM);
                }
            }

            // echo '<pre>';
             //print_r($result);
             dd($result);

            //return isset($user) ? json_encode($user) : json_encode($error);
        }
    }
}
