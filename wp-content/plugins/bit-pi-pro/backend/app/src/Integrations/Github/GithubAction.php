<?php

namespace BitApps\PiPro\src\Integrations\Github;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\src\Authorization\AuthorizationFactory;
use BitApps\Pi\src\Authorization\AuthorizationType;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Interfaces\ActionInterface;

class GithubAction implements ActionInterface
{
    private NodeInfoProvider $nodeInfoProvider;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    public function execute(): array
    {
        $executedNodeAction = $this->executeGithubAction();

        return Utility::formatResponseData(
            $executedNodeAction['status_code'],
            $executedNodeAction['payload'],
            $executedNodeAction['response']
        );
    }

    private function executeMachine($machineSlug, $data, $apiKey)
    {
        $header = [
            'accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
            'content-type'  => 'application/json'
        ];
        $githubHelperObj = new GithubHelper($header);

        switch ($machineSlug) {
            case 'createGist':
                return $githubHelperObj->createGist($data);

                break;

            case 'createIssue':
                return $githubHelperObj->createIssue($data);

                break;

            case 'createIssueComment':
                return $githubHelperObj->createIssueComment($data);

                break;

            case 'getUser':
                return $githubHelperObj->getUser($data);

                break;

            case 'getRepository':
                return $githubHelperObj->getRepository($data);

                break;
        }
    }

    private function executeGithubAction()
    {
        $machineSlug = $this->nodeInfoProvider->getMachineSlug();
        $connectionId = $this->nodeInfoProvider->getFieldMapConfigs('connection-id.value');
        $data = $this->nodeInfoProvider->getFieldMapData();
        $tokenAuthorization = AuthorizationFactory::getAuthorizationHandler(
            AuthorizationType::API_KEY,
            $connectionId
        );
        $apiKey = $tokenAuthorization->getAccessToken();

        return $this->executeMachine($machineSlug, $data, $apiKey);
    }
}
