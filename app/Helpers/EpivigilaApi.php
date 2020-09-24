<?php
namespace App\Helpers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class EpivigilaApi{

    /**
     * En desarrollo. Obtiene folio-indice de paciente indice.
     * @param String $type_id Tipo de identificacion. 1-run, 2-pasaporte, 3-comprobante de parto, 4-identificacion local
     * @param String $id Identificacion de paciente
     * @return mixed
     * @throws GuzzleException
     */
    public function getFolioPatientWs(String $type_id, String $id)
    {
        try {
            $method = 'GET';
            $uri = 'Patient/' . $type_id . '/' . $id;
            /** test_parameters: type_id=1, $id=17353836-7 **/

            return $this->requestApiEpivigila($method, $uri);

//            $response = ['status' => 1, 'msg' => 'OK'];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            dd($decode);
//            $response = ['status' => 0, 'msg' => $decode->error];
        }
    }

    /**
     * Obtiene folio-contacto de paciente contacto estrecho
     * @param String $type_id Tipo de identificacion. 1-run, 2-pasaporte, 3-comprobante de parto, 4-identificacion local
     * @param String $id Identificacion de paciente contacto estrecho
     * @param String $folio_indice folio del paciente indice del contacto estrecho
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getFolioContactPatientWs(String $type_id, String $id, String $folio_indice){
        try {
            $method = 'GET';
            $uri = 'Patient/' . $type_id . '/' . $id . '/' . $folio_indice;
            return $this->requestApiEpivigila($method, $uri);

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $decode = json_decode($responseBodyAsString);
            dd($decode);
        }
    }

    /**
     * Obtiene token de acceso a API epivigila
     * @return mixed
     */
    public function getTokenApiEpivigila()
    {
        $guzzle = new \GuzzleHttp\Client();
        $response = $guzzle->post(env('TOKEN_ENDPOINT'), [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
            ],
        ]);

        dump(json_decode((string)$response->getBody(), true));
        return json_decode((string)$response->getBody(), true)['access_token'];
    }

    /**
     * Genera request a API epivigila
     * @param string $method Tipo request (POST, GET)
     * @param string $uri uri que viene despues de BASE_ENDPOINT
     * @param array|null $json array de datos a enviar
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function requestApiEpivigila(string $method, string $uri, array $json = null)
    {
        try {
            $accessToken = $this->getTokenApiEpivigila();
            $client = new \GuzzleHttp\Client(['base_uri' => env('BASE_ENDPOINT')]);
            $headers = [
                'Authorization' => 'Bearer ' . $accessToken,
                'x-api-key' => env('X_API_KEY'),
            ];
            $options = [
                'headers' => $headers,
            ];

            if ($json != null){
                $options['json'] = $json;
            }

            dump($options);

            $response = $client->request($method, $uri, $options);
            dump(json_decode((string)$response->getBody(), true));
            return json_decode((string)$response->getBody(), true);
        } catch (GuzzleException $e) {
            dd($e->getMessage());
        }

    }

    public static function instance(){
        return new EpivigilaApi();
    }

}
