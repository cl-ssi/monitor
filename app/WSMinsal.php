<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
// use GuzzleHttp\Exception\ClientErrorResponseException;

// use Illuminate\Support\Facades\Storage;
// use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Input;

use App\SuspectCase;
use App\Commune;
use App\Country;

class WSMinsal extends Model
{
    public static function crea_muestra(Request $request) {

        $response = [];
        $client = new \GuzzleHttp\Client();
        //obtiene proximo id suspect case
        $NextsuspectCase = SuspectCase::max('id');
        $NextsuspectCase += 1;
        // $NextsuspectCase = 27;

        // webservices MINSAL
          if($request->gender == "female"){
            $genero = "F";
          }else{$genero = "M";}

          $comuna = Commune::where('id',$request->commune_id)->get();
          $commune_code_deis = $comuna->first()->code_deis;

          $paciente_ext_paisorigen = '';
          if ($request->run == "") {
            $paciente_tipodoc = "PASAPORTE";
            $country = Country::where('name',$request->nationality)->get();
            $paciente_ext_paisorigen = $country->first()->id_minsal;
          }else{$paciente_tipodoc = "RUN";}

          $array = array(
            'raw' => array('codigo_muestra_cliente' => $NextsuspectCase,
                           'rut_responsable' => '15980951-K', //Claudia Caronna //Auth::user()->run . "-" . Auth::user()->dv, //se va a enviar rut de enfermo del servicio
                           'cod_deis' => '02-100', //$request->establishment_id
                           'rut_medico' => '16350555-K', //Pedro Valjalo
                           'paciente_run' => $request->run,
                           'paciente_dv' => $request->dv,
                           'paciente_nombres' => $request->name,
                           'paciente_ap_mat' => $request->fathers_family,
                           'paciente_ap_pat' => $request->mothers_family,
                           'paciente_fecha_nac' => $request->birthday,
                           'paciente_comuna' => $commune_code_deis,
                           'paciente_direccion' => $request->address . " " . $request->number,
                           'paciente_telefono' => $request->telephone,
                           'paciente_tipodoc' => $paciente_tipodoc,
                           'paciente_ext_paisorigen' => $paciente_ext_paisorigen,
                           'paciente_pasaporte' => $request->other_identification,
                           'paciente_sexo' => $genero,
                           'paciente_prevision' => 'FONASA', //fijo por el momento
                           'fecha_muestra' => $request->sample_at,
                           'tecnica_muestra' => 'RT-PCR', //fijo
                           'tipo_muestra' => $request->sample_type
                         )
          );


        try {
            $response = $client->request('POST', 'https://tomademuestras.api.openagora.org/328302d8-0ba3-5611-24fa-a7a2f146241f', [
                  'json' => $array,
                  'headers'  => [ 'ACCESSKEY' => env('TOKEN_WS_MINSAL')]
            ]);

            $array = json_decode($response->getBody()->getContents(), true);
            $response = ['status' => 1, 'msg' => $array[0]['id_muestra']];

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            $response = ['status' => 0, 'msg' => $decode->error];
        }

        return $response;
    }



    public static function recepciona_muestra($minsal_ws_id) {
        $response = [];
        $client = new \GuzzleHttp\Client();
        $array = array('raw' => array('id_muestra' => $minsal_ws_id));

        try {
            $response = $client->request('POST', 'https://tomademuestras.api.openagora.org/27f9298d-ead4-1746-8356-cc054f245118', [
                  'json' => $array,
                  'headers'  => [ 'ACCESSKEY' => env('TOKEN_WS_MINSAL')]
            ]);
             $response = ['status' => 1, 'msg' => 'OK'];

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            $response = ['status' => 0, 'msg' => $decode->error];
        }

        return $response;
    }



    public static function resultado_muestra(Request $request, $minsal_ws_id) {
        // $response = [];
        // $client = new \GuzzleHttp\Client();
        // $array = array('raw' => array('id_muestra' => $minsal_ws_id));
        //
        // try {
        //     $response = $client->request('POST', 'https://tomademuestras.api.openagora.org/27f9298d-ead4-1746-8356-cc054f245118', [
        //           'json' => $array,
        //           'headers'  => [ 'ACCESSKEY' => env('TOKEN_WS_MINSAL')]
        //     ]);
        //      $response = ['status' => 1, 'msg' => 'OK'];
        //
        // } catch (RequestException $e) {
        //     $response = $e->getResponse();
        //     $responseBodyAsString = $response->getBody()->getContents();
        //     $decode = json_decode($responseBodyAsString);
        //     $response = ['status' => 0, 'msg' => $decode->error];
        // }

        $result = "";
        if($request->pscr_sars_cov_2 == "positive"){$result = "Positivo";}
        if($request->pscr_sars_cov_2 == "negative"){$result = "Negativo";}
        if($request->pscr_sars_cov_2 == "rejected"){$result = "Rechazado";}
        if($request->pscr_sars_cov_2 == "undetermined"){$result = "Indeterminado";}

        //guarda información
        $client = new \GuzzleHttp\Client();

        // dd($request);
        try {
            $response = $client->request('POST', 'https://tomademuestras.openagora.org/ws/a3772090-34dd-d3e3-658e-c75b6ebd211a', [
                'multipart' => [
                    [
                        'name'     => 'upfile',
                        // 'contents' => fopen('C:\Users\sick_\Desktop\pdf.pdf', 'r')
                        'contents' => fopen('' . $request->file('forfile')[0] . '', 'r'),
                        'filename' => $request->forfile[0]->getClientOriginalName()
                    ],
                    [
                        'name'     => 'parametros',
                        'contents' => '{"id_muestra":"' . $minsal_ws_id .'","resultado":"' . $result .'"}'
                    ]
                ],
                'headers'  => [ 'ACCESSKEY' => env('TOKEN_WS_MINSAL')]
            ]);
            $response = ['status' => 1, 'msg' => 'OK'];

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            $response = ['status' => 0, 'msg' => $decode->error];
        }


        // if(var_export($response->getStatusCode(), true) == 201){
        //   $array = json_decode($response->getBody()->getContents(), true);
        //   if(array_key_exists('errorXXXXX', $array)){
        //     session()->flash('warning', 'No se registró resultado - Error webservice minsal: <h3>' . $array['errorXXXXX'] . '</h3>');
        //     return redirect()->back();
        //   }else{
        //     $server_response = $array[0]['mensaje'];
        //     // dd($server_response);
        //   }
        // }

        return $response;
    }
}
