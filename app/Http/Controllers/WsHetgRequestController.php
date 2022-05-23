<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\SuspectCase;
use App\WsHetgRequest;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WsHetgRequestController extends Controller
{
    /**
     * @return mixed
     */
    private function getToken()
    {
        $lastValidToken = self::getLastValidToken();
        if ($lastValidToken != '') {
            return $lastValidToken;
        }

        $loginData = array(
            'usuario' => env('HETG_WS_USER'),
            'clave' => env('HETG_WS_PASSWORD'),
            'aplicacion' => env('HETG_WS_APP')
        );

        $response = Http::post(env('HETG_WS_URL_LOGIN'), $loginData);

        $wsHetgRequest = new WsHetgRequest();
        $wsHetgRequest->type = WsHetgRequest::TYPE_LOGIN;
        $wsHetgRequest->sent_data = json_encode($loginData);
        $wsHetgRequest->response_data = $response->body();
        $wsHetgRequest->status = $response->status();
        $wsHetgRequest->token = $response->json()['token'];
        $wsHetgRequest->save();

        $response->throw();

        return $response->json()['token'];
    }

    /**
     * @return mixed|string
     */
    private function getLastValidToken()
    {
        $lastToken = WsHetgRequest::query()
            ->where('type', WsHetgRequest::TYPE_LOGIN)
            ->latest()
            ->first();

        if ($lastToken === null) {
            return '';
        }

        if (now()->diffInMinutes($lastToken->created_at) > 59) {
            return '';
        }

        return $lastToken->token;
    }

    /**
     * @param SuspectCase $suspectCase
     * @return void
     */
    public function sendToYani(SuspectCase $suspectCase)
    {
        $token = self::getToken();

        $suspectCaseData = array(
            'id_esmeralda' => $suspectCase->id,
            'rut' => "{$suspectCase->patient->run}",
            'dv' => $suspectCase->patient->dv,
            'tipo_documento' => 1, //TODO dinámico
            'numero_documento' => $suspectCase->patient->run, //$suspectCase->patient->other_identification, //TODO enviar el que corresponda
            'nombres' => $suspectCase->patient->name,
            'primer_apellido' => $suspectCase->patient->fathers_family,
            'segundo_apellido' => $suspectCase->patient->mothers_family,
            'nombre_social' => '',
            'fecha_nacimiento' => Carbon::parse($suspectCase->patient->birthday)->format('d/m/Y'),
            'codigo_sexo' => '01', //TODO homologar con $suspectCase->patient->gender,
            'email' => 'prueba@prueba.com', //$suspectCase->patient->demographic->email ?? "", //TODO si no tiene que se envía?
            'codigo_comuna' => '01000', //$suspectCase->patient->demographic->email ?? "", //TODO si no tiene que se envía?
            'codigo_pais' => 'cl', //TODO homologar con $suspectCase->patient->demographic->nationality
            'tipo_via' => '01', //TODO homologar con $suspectCase->demographic->street_type
            'nombre_via' => $suspectCase->patient->demographic->address,
            'numero_via' => $suspectCase->patient->demographic->number,
            'telefono_fijo' => $suspectCase->patient->demographic->telephone,
            'telefono_movil' => $suspectCase->patient->demographic->telephone, //TODO si no tiene que se envía?
            'codigo_establecimiento' => 111111,
            'fecha_muestra' => '18/05/2022 05:35',
            'tipo_solicitud' => 'pcr esmeralda',
        );

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])
            ->post(env('HETG_WS_URL_REQUEST'), $suspectCaseData);

        $wsHetgRequest = new WsHetgRequest();
        $wsHetgRequest->type = WsHetgRequest::TYPE_REQUEST;
        $wsHetgRequest->sent_data = json_encode($suspectCaseData);
        $wsHetgRequest->response_data = $response->body();
        $wsHetgRequest->status = $response->status();
        $wsHetgRequest->token = $token;
        $wsHetgRequest->suspect_case_id = $suspectCase->id;
        $wsHetgRequest->save();

        $response->throw();

        session()->flash('success', "Se ha enviado la muestra $suspectCase->id a YANI.");
        return redirect()->back();
    }
}
