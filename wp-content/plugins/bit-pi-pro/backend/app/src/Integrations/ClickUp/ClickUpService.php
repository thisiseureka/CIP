<?php

namespace BitApps\PiPro\src\Integrations\ClickUp;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\src\Authorization\AuthorizationFactory;
use BitApps\Pi\src\Authorization\AuthorizationType;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Client\HttpClient;

final class ClickUpService
{
    private $baseUrl;

    private $connectionId;

    /**
     * ClickUpService constructor.
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
     * @param mixed $taskData
     * @param mixed $listId
     *
     * @return collection
     */
    public function createTask($taskData, $listId)
    {
        $tokenAuthorization = AuthorizationFactory::getAuthorizationHandler(
            AuthorizationType::API_KEY,
            $this->connectionId
        );
        $staticFieldsKeys = ['name', 'description', 'status', 'priority', 'due_date', 'due_date_time', 'time_estimate', 'start_date', 'start_date_time', 'points', 'notify_all'];

        foreach ($taskData as $data) {
            if (\in_array($data['column'], $staticFieldsKeys)) {
                if ($data['column'] === 'start_date' || $data['column'] === 'due_date') {
                    $requestParams[$data['column']] = strtotime($data['value']) * 1000;
                } else {
                    $requestParams[$data['column']] = $data['value'];
                }
            } else {
                $requestParams['custom_fields'][] = (object) [
                    'id'    => $data['column'],
                    'value' => $data['value'],
                ];
            }
        }

        $headers = [
            'Authorization' => $tokenAuthorization->getAccessToken(),
            'content-type'  => 'application/json'
        ];
        $http = new HttpClient();

        $response = $http->request(
            $this->baseUrl . "/list/{$listId}/task",
            'POST',
            wp_json_encode($requestParams),
            $headers
        );

        return ['response' => $response, 'payload' => $requestParams, 'status_code' => $http->getResponseCode()];
    }
}
