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
	//$client->SomeFunction(
        $parametros = array("query" =>
                                array("queryTO" => array("tipoEmisor"  => 3,"tipoUsuario" => 2),
                                "entidad" => env('FONASA_ENTIDAD'),
                                "claveEntidad" => env('FONASA_CLAVE'),
                                "rutBeneficiario" => $rut,
                                "dgvBeneficiario" => $dv,
                                "canal" =>3)
                            );
	$functions = $client->__getFunctions();
	echo '<pre>';
	var_dump($functions);
	

        $client->soap_defencoding = 'UTF-8';
	$result = $client->getCertificadoPrevisional($parametros);
	//$result = $client->__soapCall("getCertificadoPrevisional", $parametros);
       	//"http://ws.fonasa.cl:8080/Certificados/Previsional");
        var_dump($result); //cuando trae false no conecta :c, del cotrario si c:
        // if ($result === false){
        //     $result = array("status" => "noContetar");
        // }

        die('pum');
        // {{ asset('ws/fonasa/CertificadorPrevisionalSoap.wsdl') }}
        /*
		$vlsDig='';
		$vlsCanal=3;
		$vlsUsuario=2;
		$vlsTipo=3;
        */
    }
}
