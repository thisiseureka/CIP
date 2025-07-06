<?php

namespace BitApps\PiPro\src\Integrations\JotForm;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\src\Authorization\AuthorizationFactory;
use BitApps\Pi\src\Authorization\AuthorizationType;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Client\HttpClient;

final class JotFormService
{
    private $baseUrl;

    private $connectionId;

    /**
     * JotFormService constructor.
     *
     * @param $httpClient
     * @param $baseUrl
     * @param mixed $connectionId
     */
    public function __construct($baseUrl, $connectionId)
    {
        $this->baseUrl = $baseUrl;
        $this->connectionId = $connectionId;
    }

    /**
     * Create New Form
     *
     * @param array $data
     * @param mixed $configs
     * @param mixed $questions
     *
     * @return collection
     */
    public function createNewForm($data, $questions)
    {
        $tokenAuthorization = AuthorizationFactory::getAuthorizationHandler(
            AuthorizationType::API_KEY,
            $this->connectionId
        );

        $bodyParams = $this->formatBodyParams(
            [
                'questions'  => $questions,
                'properties' => $data,
            ]
        );

        $http = new HttpClient();

        $response = $http->request(
            add_query_arg('apiKey', $tokenAuthorization->getAccessToken(), $this->baseUrl . 'user/forms'),
            'POST',
            $bodyParams
        );

        return ['response' => $response, 'payload' => $bodyParams, 'status_code' => $http->getResponseCode()];
    }

    private function formatBodyParams($formProperties, $prefix = '')
    {
        $result = [];

        foreach ($formProperties as $key => $value) {
            $currentKey = $prefix ? "{$prefix}[{$key}]" : $key;
            if (\is_array($value)) {
                $result += $this->formatBodyParams($value, $currentKey);
            } else {
                $result[$currentKey] = $value;
            }
        }

        return $result;
    }
}
