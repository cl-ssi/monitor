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
            'corpac' => $suspectCase->id,
            'rut' => "{$suspectCase->patient->run}-{$suspectCase->patient->dv}",
            'nombres' => $suspectCase->patient->name,
            'apellido_paterno' => $suspectCase->patient->fathers_family,
            'apellido_materno' => $suspectCase->patient->mothers_family,
            'fecha_nacimiento' => Carbon::parse($suspectCase->patient->birthday)->format('d/m/Y'),
            'telefono_fijo' => $suspectCase->patient->demographic->telephone,
            'sexo' => 'M', //TODO homologar con $suspectCase->patient->gender,
            'pais' => 'cl', //TODO homologar con $suspectCase->patient->demographic->nationality
            'nombre_social' => $suspectCase->patient->name,
            'tipo_documento' => 'P', //TODO homologar
            'numero_documento' => $suspectCase->patient->run, //$suspectCase->patient->other_identification, //TODO si no tiene que se envía?
            'tipo_via' => '01', //TODO homologar con $suspectCase->demographic->street_type
            'nombre_via' => $suspectCase->patient->demographic->address,
            'numero_via' => $suspectCase->patient->demographic->number,
            'email' => 'prueba@prueba.com', //$suspectCase->patient->demographic->email ?? "", //TODO si no tiene que se envía?
            'tipo_solicitud' => 'solicitud pcr esmeralda',
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
