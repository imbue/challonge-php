<?php

namespace Imbue\Challonge;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

Class Challonge
{
    protected $endpoint = 'https://api.challonge.com/v1/';

    protected $api_version = 'v1';

    protected $api_key;

    protected $format = 'application/json';

    protected $response;

    protected $success = false;

    protected $results;

    const TIMEOUT = 10;

    /**
     * Challonge constructor.
     *
     * @param string|null $api_key
     * @throws \Exception
     */
    public function __construct(string $api_key = null)
    {
        if (empty($api_key)) {
            throw new \Exception("Invalid api key supplied.");
        }

        $this->api_key = $api_key;
    }

    /**
     * Check if the response was successful.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->success;
    }

    /**
     * Return the latest response.
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Return the latest results.
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param $http_verb
     * @param $method
     * @param array $args
     * @param int $timeout
     * @return mixed
     */
    protected function makeRequest($http_verb, $method, $args = [], $timeout = self::TIMEOUT)
    {
        $client = new Client([
            'timeout' => $timeout,
            'version' => $this->api_version,
            'base_uri' => $this->endpoint,
            'headers' => [
                'Accept' => $this->format,
                'Content-Type' => $this->format,
                'User-Agent' => "Challonge PHP wrapper {$this->api_version} (https://github.com/imbue/challonge-php)"
            ],
            'query' => [
                'api_key' => $this->api_key
            ],
        ]);

        switch ($http_verb) {
            case 'get':
                $response = $client->get($method);
                break;
            case 'post':
                $response = $client->post($method, $args);
                break;
        }

        $this->response = $response;

        return $this->parseResponse($this->response);
    }

    /**
     * Make an HTTP GET request.
     *
     * @param $method
     * @param array $args
     * @param int $timeout
     * @return array
     */
    protected function get($method, $args = [], $timeout = self::TIMEOUT)
    {
        return $this->makeRequest('get', $method, $args, $timeout);
    }

    /**
     * Make an HTTP POST request.
     *
     * @param $method
     * @param array $args
     * @param int $timeout
     * @return mixed
     */
    protected function post($method, $args = [], $timeout = self::TIMEOUT)
    {
        return $this->makeRequest('post', $method, $args, $timeout);
    }

    /*
     * Retrieve a set of tournaments created with your account.
     */
    public function getTournaments()
    {
        return $this->get('tournaments');
    }

    /**
     * Retrieve a single tournament.
     *
     * @param $tournament_id
     * @return array
     */
    public function getTournament($tournament_id)
    {
        return $this->get("tournaments/{$tournament_id}");
    }

    /**
     * Create a new tournament.
     *
     * @param $params
     * @return mixed
     */
    public function createTournament($params)
    {
        return $this->post('tournaments', ['form_params' => $params]);
    }

    /**
     * Parse the response.
     *
     * @param Response $response
     * @return mixed
     */
    protected function parseResponse(Response $response)
    {
        $response_content = $response->getBody()->getContents();

        if ($this->format == 'application/xml') {
            $results = simplexml_load_string($response_content);
        } else {
            $results = json_decode($response_content);
        }

        $this->results = $results;

        if ($response->getReasonPhrase() == 'OK') {
            $this->success = true;
        }

        return $this->results;
    }
}
