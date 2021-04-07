<?php

namespace ForwardForce\Estated;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Client as GuzzleHttpClient;

class Estated
{
    const API_VERSION = 'v4';
    const BASE_URL = 'https://apis.estated.com/' . self::API_VERSION;

    protected $client;
    protected $token;

    public function __construct(string $accessToken)
    {
        $this->client = new GuzzleHttpClient();
        $this->token = $accessToken;
    }

    /**
     * Property API Overview
     * @param string $address Field Street Address (REQUIRED)
     * @param string $city  Field City (REQUIRED)
     * @param string $state Field State (REQUIRED)
     * @param string $zipCode Field Zip Code (OPTIONAL)
     *
     * @return array
     */
    public function generalData(string $address, string $city, string $state, string $zipCode): array
    {
        try {
            $options = [
                'token' => $this->token,
                'street_address' => $address,
                'city' => $city,
                'state' => $state,
                'zip_code' => $zipCode
            ];
            $response = $this->client->get(self::BASE_URL . '/property?' . http_build_query($options));
        } catch (RequestException $e) {
            $error['request'] = Message::toString($e->getRequest());

            if ($e->hasResponse()) {
                $error['response'] = Message::toString($e->getResponse());
            }

            return $error;
        }

        return json_decode($response->getBody()->getContents(), true) ?? [];
    }
}