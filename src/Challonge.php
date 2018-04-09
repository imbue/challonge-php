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
            'http_errors' => false,
        ]);

        switch ($http_verb) {
            case 'get':
                $response = $client->get($method);
                break;
            case 'post':
                $response = $client->post($method, $args);
                break;
            case 'put':
                $response = $client->put($method, $args);
                break;
            case 'delete':
                $response = $client->delete($method, $args);
                break;
        }

        $this->response = $response;

        return $this->parseResponse($response);
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

    /**
     * Make an HTTP PUT request.
     *
     * @param $method
     * @param array $args
     * @param int $timeout
     * @return mixed
     */
    protected function put($method, $args = [], $timeout = self::TIMEOUT)
    {
        return $this->makeRequest('put', $method, $args, $timeout);
    }

    /**
     * Make an HTTP DELETE request.
     *
     * @param $method
     * @param array $args
     * @param int $timeout
     * @return mixed
     */
    protected function delete($method, $args = [], $timeout = self::TIMEOUT)
    {
        return $this->makeRequest('delete', $method, $args, $timeout);
    }

    /*
     * Retrieve a set of tournaments.
     */
    public function getTournaments()
    {
        return $this->get("tournaments");
    }

    /**
     * Retrieve a single tournament.
     *
     * @param $tournament
     * @return array
     */
    public function getTournament($tournament)
    {
        return $this->get("tournaments/{$tournament}");
    }

    /**
     * Create a new tournament.
     *
     * @param array $params
     * @return mixed
     */
    public function createTournament(array $params)
    {
        return $this->post("tournaments", ['form_params' => $params]);
    }

    /**
     * Update an existing tournament.
     *
     * @param $tournament
     * @param array $params
     * @return mixed
     */
    public function updateTournament($tournament, array $params)
    {
        return $this->put("tournaments/{$tournament}", ['form_params' => $params]);
    }

    /**
     * Delete a tournament.
     *
     * @param $tournament
     * @return mixed
     */
    public function deleteTournament($tournament)
    {
        return $this->delete("tournaments/{$tournament}");
    }

    /**
     * Retrieve a tournament's participant list.
     *
     * @param $tournament
     * @return array
     */
    public function getParticipants($tournament)
    {
        return $this->get("tournaments/{$tournament}/participants");
    }

    /*
    * Retrieve a single participant record for a tournament.
    */
    /**
     * @param $tournament
     * @param $participant
     * @return array
     */
    public function getParticipant($tournament, $participant)
    {
        return $this->get("tournaments/{$tournament}/participants/{$participant}");
    }

    /**
     * Add a participant to a tournament.
     *
     * @param $tournament
     * @param array $params
     * @return mixed
     */
    public function createParticipant($tournament, array $params)
    {
        return $this->post("tournaments/{$tournament}/participants", ['form_params' => $params]);
    }

    /**
     * Update an existing tournament.
     *
     * @param $tournament
     * @param $participant
     * @param array $params
     * @return mixed
     */
    public function updateParticipant($tournament, $participant, array $params)
    {
        return $this->put("tournaments/{$tournament}/participants/{$participant}", ['form_params' => $params]);
    }

    /**
     * If the tournament has not started, delete a participant, automatically filling in the abandoned seed number. If tournament is underway, mark a participant inactive, automatically forfeiting his/her remaining matches.
     *
     * @param $tournament
     * @param $participant
     * @return mixed
     */
    public function deleteParticipant($tournament, $participant)
    {
        return $this->delete("tournaments/{$tournament}/participants/{$participant}");
    }

    /**
     * Randomize seeds among participants. Only applicable before a tournament has started.
     *
     * @param $tournament
     * @return mixed
     */
    public function randomizeParticipants($tournament)
    {
        return $this->post("tournaments/{$tournament}/participants/randomize");
    }

    /*
     * Retrieve a set of matches.
     */
    public function getMatches($tournament)
    {
        return $this->get("tournaments/{$tournament}/matches");
    }

    /*
    * Retrieve a single match.
    */
    public function getMatch($tournament, $match)
    {
        return $this->get("tournaments/{$tournament}/matches/{$match}");
    }

    /**
     * Update an existing tournament.
     *
     * @param $tournament
     * @param $match
     * @param array $params
     * @return mixed
     */
    public function updateMatch($tournament, $match, array $params)
    {
        return $this->put("tournaments/{$tournament}/matches/{$match}", ['form_params' => $params]);
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
