<?php

namespace BitApps\PiPro\src\Integrations\Asana;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\src\Authorization\AuthorizationFactory;
use BitApps\Pi\src\Authorization\AuthorizationType;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Client\HttpClient;

final class AsanaService
{
    private $baseUrl;

    private $connectionId;

    /**
     * AsanaService constructor.
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
     * @param mixed $projectId
     * @param mixed $sectionId
     * @param mixed $taskId
     * @param mixed $tagId
     *
     * @return collection
     */
    public function createTask($taskData, $projectId, $sectionId, $taskId, $tagId)
    {
        $tokenAuthorization = AuthorizationFactory::getAuthorizationHandler(
            AuthorizationType::BEARER_TOKEN,
            $this->connectionId
        );
        $staticFieldsKeys = [
            'name',
            'notes',
            'approval_status',
            'start_at',
            'start_on',
            'due_at',
            'due_on',
            'completed',
        ];

        foreach ($taskData as $data) {
            if (\in_array($data['column'], $staticFieldsKeys)) {
                $requestParams[$data['column']] = $data['value'];
            } else {
                $requestParams['custom_fields'] = [
                    $data['column'] => $data['value']
                ];
            }
        }

        if (!empty($projectId)) {
            $requestParams['projects'][] = ($projectId);
        }

        if (!empty($sectionId)) {
            $requestParams['assignee_section'] = ($sectionId);
        }

        if (!empty($taskId)) {
            $requestParams['parent'] = ($taskId);
        }

        if (!empty($tagId)) {
            $requestParams['tags'][] = ($tagId);
        }

        $headers = [
            'Authorization' => $tokenAuthorization->getAccessToken(),
            'content-type'  => 'application/json'
        ];
        $http = new HttpClient();

        $response = $http->request(
            $this->baseUrl . '/tasks',
            'POST',
            wp_json_encode(['data' => $requestParams]),
            $headers
        );

        return ['response' => $response, 'payload' => $requestParams, 'status_code' => $http->getResponseCode()];
    }
}
