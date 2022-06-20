<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Country;
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

        $documentType = $suspectCase->other_identification === null ? 1 : 9;

        if (strlen($suspectCase->patient->demographic->commune->code_deis) > 4) {
            $communeCodeDeis = $suspectCase->patient->demographic->commune->code_deis;
        }else{
            $communeCodeDeis = '0' . $suspectCase->patient->demographic->commune->code_deis;
        }

        $nationalityCountry = Country::where('name', $suspectCase->patient->demographic->nationality)->first();

        $suspectCaseData = array(
            'id_esmeralda' => $suspectCase->id,
            'rut' => "{$suspectCase->patient->run}",
            'dv' => $suspectCase->patient->dv,
            'tipo_documento' => $documentType,
            'numero_documento' => $suspectCase->patient->other_identification,
            'nombres' => $suspectCase->patient->name,
            'primer_apellido' => $suspectCase->patient->fathers_family,
            'segundo_apellido' => $suspectCase->patient->mothers_family,
            'nombre_social' => '',
            'fecha_nacimiento' => Carbon::parse($suspectCase->patient->birthday)->format('d/m/Y'),
            'codigo_sexo' => $suspectCase->patient->sexCode,
            'email' => $suspectCase->patient->demographic->email ?? null,
            'codigo_comuna' => $communeCodeDeis,
            'codigo_pais' => $nationalityCountry->code_deis,
            'tipo_via' => $suspectCase->patient->demographic->streetTypeCode,
            'nombre_via' => $suspectCase->patient->demographic->address,
            'numero_via' => $suspectCase->patient->demographic->number,
            'telefono_fijo' => $suspectCase->patient->demographic->telephone2,
            'telefono_movil' => $suspectCase->patient->demographic->telephone,
            'codigo_establecimiento' => $suspectCase->establishment->new_code_deis,
            'fecha_muestra' => $suspectCase->sample_at->format('d/m/Y H:i'),
            'tipo_solicitud' => 'pcr esmeralda',
        );

//        dd(json_encode($suspectCaseData, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])
            ->post(env('HETG_WS_URL_REQUEST'), $suspectCaseData, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8']);

//        dd($response->json());

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
