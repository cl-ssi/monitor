<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\SuspectCase;
use App\Commune;
use App\Country;

use App\File;
use Illuminate\Support\Facades\Storage;

class WSMinsal extends Model
{

    public static function crea_muestra(SuspectCase $SuspectCase) {

        $response = [];
        $client = new \GuzzleHttp\Client();
        //obtiene proximo id suspect case
        // $NextsuspectCase = SuspectCase::max('id');
        // $NextsuspectCase += 1;
        // $NextsuspectCase = 27;

        // webservices MINSAL
          if($SuspectCase->gender == "female"){
            $genero = "F";
          }else{$genero = "M";}

          $comuna = Commune::where('id',$SuspectCase->patient->demographic->commune_id)->get();
          $commune_code_deis = $comuna->first()->code_deis;

          $paciente_ext_paisorigen = '';
          if ($SuspectCase->patient->run == "") {
            $paciente_tipodoc = "PASAPORTE";
            $country = Country::where('name',$SuspectCase->patient->demographic->nationality)->get();
            $paciente_ext_paisorigen = $country->first()->id_minsal;
          }else{$paciente_tipodoc = "RUN";}

          $array = array(
            'raw' => array('codigo_muestra_cliente' => $SuspectCase->id,
                           'rut_responsable' => '15980951-K', //Claudia Caronna //Auth::user()->run . "-" . Auth::user()->dv, //se va a enviar rut de enfermo del servicio
                           'cod_deis' => '02-100', //$request->establishment_id
                           'rut_medico' => '16350555-K', //Pedro Valjalo
                           'paciente_run' => $SuspectCase->patient->run,
                           'paciente_dv' => $SuspectCase->patient->dv,
                           'paciente_nombres' => $SuspectCase->patient->name,
                           'paciente_ap_mat' => $SuspectCase->patient->fathers_family,
                           'paciente_ap_pat' => $SuspectCase->patient->mothers_family,
                           'paciente_fecha_nac' => $SuspectCase->patient->birthday,
                           'paciente_comuna' => $commune_code_deis,
                           'paciente_direccion' => $SuspectCase->patient->demographic->address . " " . $SuspectCase->patient->demographic->number,
                           'paciente_telefono' => $SuspectCase->patient->demographic->telephone,
                           'paciente_tipodoc' => $paciente_tipodoc,
                           'paciente_ext_paisorigen' => $paciente_ext_paisorigen,
                           'paciente_pasaporte' => $SuspectCase->patient->other_identification,
                           'paciente_sexo' => $genero,
                           'paciente_prevision' => 'FONASA', //fijo por el momento
                           'fecha_muestra' => $SuspectCase->sample_at,
                           'tecnica_muestra' => 'RT-PCR', //fijo
                           'tipo_muestra' => $SuspectCase->sample_type
                         )
          );


        try {
            $response = $client->request('POST', 'https://tomademuestras.api.openagora.org/328302d8-0ba3-5611-24fa-a7a2f146241f', [
                  'json' => $array,
                  'headers'  => [ 'ACCESSKEY' => $SuspectCase->laboratory->token_ws]
            ]);

            $array = json_decode($response->getBody()->getContents(), true);
            $SuspectCase->minsal_ws_id = $array[0]['id_muestra'];
            $SuspectCase->save();
            $response = ['status' => 1, 'msg' => $array[0]['id_muestra']];

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            // dd($decode);
            $response = ['status' => 0, 'msg' => $decode->error];
        }

        return $response;
    }



    public static function recepciona_muestra(SuspectCase $SuspectCase) {

        $minsal_ws_id = $SuspectCase->minsal_ws_id;
        $response = [];
        $client = new \GuzzleHttp\Client();
        $array = array('raw' => array('id_muestra' => $minsal_ws_id));

        try {
            $response = $client->request('POST', 'https://tomademuestras.api.openagora.org/27f9298d-ead4-1746-8356-cc054f245118', [
                  'json' => $array,
                  'headers'  => [ 'ACCESSKEY' => $SuspectCase->laboratory->token_ws]
            ]);
             $response = ['status' => 1, 'msg' => 'OK'];

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            // dd("2".$decode);
            $response = ['status' => 0, 'msg' => $decode->error];
        }

        return $response;
    }



    public static function resultado_muestra(SuspectCase $SuspectCase) {

        $pdf = NULL;
        if ($SuspectCase->laboratory) {
            if ($SuspectCase->laboratory->pdf_generate) {
                $case = $SuspectCase;
                $pdf = \PDF::loadView('lab.results.result', compact('case'));
            }
        }

        $resultado = NULL;
        if ($SuspectCase->pscr_sars_cov_2 == "positive") {
            $resultado = "Positivo";
        }
        if ($SuspectCase->pscr_sars_cov_2 == "negative") {
            $resultado = "Negativo";
        }
        if ($SuspectCase->pscr_sars_cov_2 == "rejected") {
            $resultado = "Muestra no apta";
        }
        if ($SuspectCase->pscr_sars_cov_2 == "undetermined") {
            $resultado = "Indeterminado";
        }

        //guarda información
        $client = new \GuzzleHttp\Client();

        try {
            if ($pdf == NULL) {
                $response = $client->request('POST', 'https://tomademuestras.openagora.org/ws/a3772090-34dd-d3e3-658e-c75b6ebd211a', [
                    'multipart' => [
                        [
                            'name'     => 'upfile',
                            'contents' => Storage::get($SuspectCase->files->first()->file),
                            'filename' => $SuspectCase->files->first()->name
                        ],
                        [
                            'name'     => 'parametros',
                            'contents' => '{"id_muestra":"' . $SuspectCase->minsal_ws_id .'","resultado":"' . $resultado .'"}'
                        ]
                    ],
                    'headers'  => [ 'ACCESSKEY' => env('TOKEN_WS_MINSAL')]
                ]);
            }else{
                $response = $client->request('POST', 'https://tomademuestras.openagora.org/ws/a3772090-34dd-d3e3-658e-c75b6ebd211a', [
                    'multipart' => [
                        [
                            'name'     => 'upfile',
                            'contents' => $pdf->output(),
                            'filename' => 'Resultado.pdf'
                        ],
                        [
                            'name'     => 'parametros',
                            'contents' => '{"id_muestra":"' . $SuspectCase->minsal_ws_id .'","resultado":"' . $resultado .'"}'
                        ]
                    ],
                    'headers'  => [ 'ACCESSKEY' => $SuspectCase->laboratory->token_ws]
                ]);
            }
            $response = ['status' => 1, 'msg' => 'OK'];

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            // dd("3".$decode);
            $response = ['status' => 0, 'msg' => $decode->error];
        }

        return $response;
    }
}
