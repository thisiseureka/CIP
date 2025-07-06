<?php

namespace BitApps\PiPro\src\Integrations\Omnisend;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\src\API\BaseAPI;
use BitApps\Pi\src\Authorization\ApiKey\ApiKeyAuthorization;
use BitApps\PiPro\Deps\BitApps\WPKit\Helpers\JSON;

class OmnisendHelper extends BaseAPI
{
    private const OMNISEND_API_VERSION = 'v5';

    private const BASE_URL = 'https://api.omnisend.com/';

    public function __construct(ApiKeyAuthorization $authorization, $apiKey)
    {
        parent::__construct($authorization, self::BASE_URL . self::OMNISEND_API_VERSION . '/', 'application/json');
        $this->http->setHeader('X-API-KEY', $apiKey);
    }

    public function createNewContact($data, $splitChannels, $status)
    {
        $email = $data['email'];
        $phone = $data['phone_number'];

        foreach ($data as $key => $value) {
            if ($key !== 'email' && $key !== 'phone_number') {
                $requestParams[$key] = $value;
            }
        }

        $identifiers = [];
        if (\count($splitChannels) > 0) {
            foreach ($splitChannels as $channel) {
                $type = $channel === 'email' ? 'email' : 'phone';
                $id = $channel === 'email' ? $email : $phone;
                $identifiers[] = (object) [
                    'channels' => [
                        $channel => [
                            'status' => $status
                        ]
                    ],
                    'type'     => $type,
                    'id'       => $id

                ];
            }
        }

        $requestParams['identifiers'] = $identifiers;

        $this->setPayload($requestParams);
        $response = (array) $this->post('contacts');

        return [
            'response'    => $response,
            'payload'     => $requestParams,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getContactsList($afterContactId, $limitInfo)
    {
        $this->setQueryParam('after', $afterContactId);
        $this->setQueryParam('limit', $limitInfo);
        $response = (array) $this->get('contacts');

        return [
            'response'    => $response,
            'payload'     => [],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getProductList($offsetInfo, $limitInfo, $sortStatus)
    {
        $this->setQueryParam('offset', $offsetInfo);
        $this->setQueryParam('limit', $limitInfo);
        $this->setQueryParam('sort', $sortStatus);
        $response = (array) $this->get('products');

        return [
            'response'    => $response,
            'payload'     => [],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getProduct($productId)
    {
        $response = (array) $this->get("products/{$productId}");

        return [
            'response'    => $response,
            'payload'     => [],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function deleteProduct($productId)
    {
        $response = (array) $this->delete("products/{$productId}");

        return [
            'response'    => $response,
            'payload'     => [],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function updateContacts($contactId, $data)
    {
        $this->setPayload($data);
        $response = (array) $this->patch("contacts/{$contactId}");

        return [
            'response'    => $response,
            'payload'     => $data,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getContact($contactId)
    {
        $response = (array) $this->get("contacts/{$contactId}");

        return [
            'response'    => $response,
            'payload'     => [],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function sendCustomerEvent($data)
    {
        $requestParams = [];
        $contact = [];
        foreach ($data as $key => $value) {
            if ($key === 'email' || $key === 'phone') {
                $contact[$key] = $value;

                continue;
            }

            $requestParams[$key] = $value;
        }

        $requestParams['contact'] = $contact;
        $this->setPayload($requestParams);
        $response = (array) $this->post('events');

        return [
            'response'    => $response,
            'payload'     => $data,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function createProduct($data)
    {
        $data['variants'] = JSON::decode($data['variants']);
        $this->setPayload($data);
        $response = (array) $this->post('products');

        return [
            'response'    => $response,
            'payload'     => $data,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    private function setQueryParam($key, $value)
    {
        if (!empty($value)) {
            $this->http->setQueryParam($key, $value);
        }
    }
}
