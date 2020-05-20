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
                "entidad" => env('FONASA_ENTIDAD'),
                "claveEntidad" => env('FONASA_CLAVE'),
                "rutBeneficiario" => $rut,
                "dgvBeneficiario" => $dv,
                "canal" =>3
            )
        );

        //$client->soap_defencoding = 'UTF-8';
        $result = $client->getCertificadoPrevisional($parameters);

	$obj_user = $result->getCertificadoPrevisionalResult->beneficiarioTO;
	$user['run'] = $obj_user->rutbenef;
	$user['dv'] = $obj_user->dgvbenef;
	$user['name'] = $obj_user->nombres;
	$user['fathers_family'] = $obj_user->apell1;
	$user['mothers_family'] = $obj_user->apell2;
	$user['birthday'] = $obj_user->fechaNacimiento;
	$user['gender'] = $obj_user->generoDes;

        echo '<pre>';
        print_r(json_encode($user,JSON_UNESCAPED_UNICODE));

        //var_dump($result); //cuando trae false no conecta :c, del cotrario si c:
        // if ($result === false){
        //     $result = array("status" => "noContetar");
        // }
    }
}
