<?php

namespace BitApps\PiPro\src\Integrations\Demio;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Deps\BitApps\WPKit\Helpers\JSON;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Client\HttpClient;

class DemioEvent
{
    private const BASE_URL = 'https://my.demio.com/api/v1/';

    private $http;

    private $headers;

    /**
     * DemioService constructor.
     *
     * @param $BASE_URL
     * @param mixed $headers
     */
    public function __construct($headers)
    {
        $this->http = new HttpClient();
        $this->headers = $headers;
    }

    /**
     * Create New Contact
     *
     * @param mixed $data
     *
     * @return array
     */
    public function createNewContact($data)
    {
        $url = self::BASE_URL . 'event/register';

        $response = $this->http->request($url, 'POST', JSON::encode($data), $this->headers);

        return [
            'response'    => $response,
            'payload'     => $data,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getEventList()
    {
        $url = self::BASE_URL . 'events';

        $response = $this->http->request($url, 'GET', null, $this->headers);

        return [
            'response'    => $response,
            'payload'     => [],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getSessionList($eventId)
    {
        $url = self::BASE_URL . "event/{$eventId}";

        $response = $this->http->request($url, 'GET', null, $this->headers);
        if (!empty($response->dates)) {
            $response = $response->dates;
        }

        return [
            'response'    => $response,
            'payload'     => [],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getEventInfo($eventId, $activeStatus)
    {
        $url = self::BASE_URL . "event/{$eventId}?active={$activeStatus}";

        $response = $this->http->request($url, 'GET', null, $this->headers);

        return [
            'response'    => $response,
            'payload'     => [],
            'status_code' => $this->http->getResponseCode()
        ];
    }
}
