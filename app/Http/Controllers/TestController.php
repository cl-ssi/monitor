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
        $rut = 15287582;
        $dv  = 7;
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

        echo '<pre>';
        print_r($parameters);

        //var_dump($result); //cuando trae false no conecta :c, del cotrario si c:
        // if ($result === false){
        //     $result = array("status" => "noContetar");
        // }
    }
}
