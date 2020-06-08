<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\SuspectCase;
use App\Commune;
use App\Country;
use App\File;


class WSMinsal extends Model
{

    public static function crea_muestra(SuspectCase $suspectCase) {

        $response = [];
        $client = new \GuzzleHttp\Client();

        $genero = strtoupper($suspectCase->gender[0]);
        // if($suspectCase->gender == "female"){
        //     $genero = "F";
        // }else{$genero = "M";}

        $commune_code_deis = Commune::find($suspectCase->patient->demographic->commune_id)->code_deis;

        $paciente_ext_paisorigen = '';

        if($suspectCase->patient->run == "") {
            $paciente_tipodoc = "PASAPORTE";
            $country = Country::where('name',$suspectCase->patient->demographic->nationality)->get();
            $paciente_ext_paisorigen = $country->first()->id_minsal;
        }
        else {
            $paciente_tipodoc = "RUN";
        }

        $array = array(
            'raw' => array(
                'codigo_muestra_cliente' => $suspectCase->id,
                'rut_responsable' => '15980951-K', //Claudia Caronna //Auth::user()->run . "-" . Auth::user()->dv, //se va a enviar rut de enfermo del servicio
                'cod_deis' => '102100', //$request->establishment_id
                'rut_medico' => '16350555-K', //Pedro Valjalo
                'paciente_run' => $suspectCase->patient->run,
                'paciente_dv' => $suspectCase->patient->dv,
                'paciente_nombres' => $suspectCase->patient->name,
                'paciente_ap_mat' => $suspectCase->patient->fathers_family,
                'paciente_ap_pat' => $suspectCase->patient->mothers_family,
                'paciente_fecha_nac' => $suspectCase->patient->birthday,
                'paciente_comuna' => $commune_code_deis,
                'paciente_direccion' => $suspectCase->patient->demographic->address . " " . $suspectCase->patient->demographic->number,
                'paciente_telefono' => $suspectCase->patient->demographic->telephone,
                'paciente_tipodoc' => $paciente_tipodoc,
                'paciente_ext_paisorigen' => $paciente_ext_paisorigen,
                'paciente_pasaporte' => $suspectCase->patient->other_identification,
                'paciente_sexo' => $genero,
                'paciente_prevision' => 'FONASA', //fijo por el momento
                'fecha_muestra' => $suspectCase->sample_at,
                'tecnica_muestra' => 'RT-PCR', //fijo
                'tipo_muestra' => $suspectCase->sample_type
            )
        );


        try {
            $response = $client->request('POST', env('WS_CREAR_MUESTRA'), [
                'json' => $array,
                'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
            ]);

            $array = json_decode($response->getBody()->getContents(), true);
            $suspectCase->minsal_ws_id = $array[0]['id_muestra'];
            $suspectCase->save();
            $response = ['status' => 1, 'msg' => $array[0]['id_muestra']];

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            $response = ['status' => 0, 'msg' => $decode->error];
        }

        return $response;
    }



    public static function recepciona_muestra(SuspectCase $suspectCase) {

        $minsal_ws_id = $suspectCase->minsal_ws_id;
        $response = [];
        $client = new \GuzzleHttp\Client();
        $array = array('raw' => array('id_muestra' => $minsal_ws_id));

        try {
            $response = $client->request('POST', env('WS_RECEPCIONA_MUESTRA'), [
                  'json' => $array,
                  'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
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



    public static function resultado_muestra(SuspectCase $suspectCase) {
        $pdf = NULL;
        if ($suspectCase->laboratory) {
            if ($suspectCase->laboratory->pdf_generate) {
                $case = $suspectCase;
                $pdf = \PDF::loadView('lab.results.result', compact('case'));
            }
        }

        $resultado = $suspectCase->covid19;

        $client = new \GuzzleHttp\Client();

        try {
            if ($pdf == NULL) {
                $response = $client->request('POST', env('WS_RESULTADO_MUESTRA'), [
                    'multipart' => [
                        [
                            'name'     => 'upfile',
                            'contents' => Storage::get($suspectCase->files->first()->file),
                            'filename' => $suspectCase->files->first()->name
                        ],
                        [
                            'name'     => 'parametros',
                            'contents' => '{"id_muestra":"' . $suspectCase->minsal_ws_id .'","resultado":"' . $resultado .'"}'
                        ]
                    ],
                    'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
                ]);
            }else{
                $response = $client->request('POST', env('WS_RESULTADO_MUESTRA'), [
                    'multipart' => [
                        [
                            'name'     => 'upfile',
                            'contents' => $pdf->output(),
                            'filename' => 'Resultado.pdf'
                        ],
                        [
                            'name'     => 'parametros',
                            'contents' => '{"id_muestra":"' . $suspectCase->minsal_ws_id .'","resultado":"' . $resultado .'"}'
                        ]
                    ],
                    'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
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
