<?php

namespace App\Service;

use GuzzleHttp\Client;

class GuzzleService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.ejemplo.com',  // URL base de la API
            'timeout'  => 2.0,  // Timeout de la conexión
        ]);
    }

    public function getRequest(string $endpoint)
    {
        $response = $this->client->request('GET', $endpoint);
        $statusCode = $response->getStatusCode();
        $body = $response->getBody();
        
        return json_decode($body, true);  // Devuelve la respuesta en formato JSON
    }

    public function postRequest(string $endpoint, array $data)
    {
        $response = $this->client->request('POST', $endpoint, [
            'json' => $data,
        ]);
        $statusCode = $response->getStatusCode();
        $body = $response->getBody();

        return json_decode($body, true);
    }
}
