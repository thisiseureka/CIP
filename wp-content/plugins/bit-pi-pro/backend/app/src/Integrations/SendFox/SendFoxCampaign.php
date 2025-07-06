<?php

namespace BitApps\PiPro\src\Integrations\SendFox;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


final class SendFoxCampaign
{
    private $http;

    private $baseUrl;

    /**
     * SendFoxCampaignService constructor.
     *
     * @param $httpClient
     * @param $baseUrl
     */
    public function __construct($httpClient, $baseUrl)
    {
        $this->http = $httpClient;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Get all campaigns
     *
     * @return array
     */
    public function all()
    {
        return $this->http->request($this->baseUrl . 'campaigns', 'GET', []);
    }

    /**
     * Get campaign by id
     *
     * @param string $id
     *
     * @return array
     */
    public function getCampaignById($id)
    {
        return $this->http->request($this->baseUrl . $id, 'POST', []);
    }
}
