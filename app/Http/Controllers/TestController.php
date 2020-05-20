<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fonasa()
    {
        $rut = 21097570;
        $dv  = 5;
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
            $error = "No se pudo conectar a FONASA";
        }
        else {
            /* Si se conectó al WS */
            if($result->getCertificadoPrevisionalResult->replyTO->estado == 0) {
                /* Si no hay error en los datos enviados */
                $obj_user = $result->getCertificadoPrevisionalResult->beneficiarioTO;

                $user['run']            = $obj_user->rutbenef;
                $user['dv']             = $obj_user->dgvbenef;
                $user['name']           = $obj_user->nombres;
                $user['fathers_family'] = $obj_user->apell1;
                $user['mothers_family'] = $obj_user->apell2;
                $user['birthday']       = $obj_user->fechaNacimiento;
                $user['gender']         = $obj_user->generoDes;
            }
            else {
                /* Error */
                $error = $result->getCertificadoPrevisionalResult->replyTO->errorM
            }
        }

        echo '<pre>';
        print_r($result);
        print_r(json_encode($user,JSON_UNESCAPED_UNICODE));
    }
}
