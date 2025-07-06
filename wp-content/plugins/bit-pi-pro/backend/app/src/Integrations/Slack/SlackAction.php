<?php

namespace BitApps\PiPro\src\Integrations\Slack;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\src\Authorization\AuthorizationFactory;
use BitApps\Pi\src\Authorization\AuthorizationType;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Interfaces\ActionInterface;

class SlackAction implements ActionInterface
{
    private NodeInfoProvider $nodeInfoProvider;

    private SlackService $slackService;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    public function execute(): array
    {
        $executedNodeAction = $this->executeSlackAction();

        $response = $executedNodeAction['response'];

        if (isset($response->ok) && $response->ok === true) {
            return [
                'status'  => 'success',
                'message' => $response->message,
                'output'  => $response ?? [],
                'input'   => $requestBody ?? [],
            ];
        }

        return [
            'status' => 'error',
            'output' => $response,
            'input'  => $executedNodeAction['payload'] ?? [],
        ];
    }

    private function setNodeInfoProperties()
    {
        $machineSlug = $this->nodeInfoProvider->getMachineSlug();
        $configs = $this->nodeInfoProvider->getFieldMapConfigs();
        $fieldMapData = $this->nodeInfoProvider->getFieldMapData();

        if (!empty($fieldMapData['image_url'])) {
            $fieldMapData = array_merge(
                $fieldMapData,
                [
                    'blocks' => [
                        [
                            'type'      => 'image',
                            'image_url' => $fieldMapData['image_url'],
                            'alt_text'  => 'Description of image',
                        ],
                    ],
                ]
            );
        }
        unset($fieldMapData['image_url']);

        $tokenAuthorization = AuthorizationFactory::getAuthorizationHandler(
            AuthorizationType::BEARER_TOKEN,
            $configs['connection-id']
        );

        $token = $tokenAuthorization->getAccessToken();

        $header = [
            'Content-Type'  => 'application/json; charset=utf-8',
            'Authorization' => $token
        ];

        return [
            'machineSlug'  => $machineSlug,
            'configs'      => $configs,
            'fieldMapData' => $fieldMapData,
            'header'       => $header,

        ];
    }

    private function executeAction(
        array $slackData
    ) {
        switch ($slackData['machineSlug']) {
            case 'createChannel':
                return $this->slackService->createChannel($slackData['fieldMapData']);

            case 'sendMessageToChannel':
                return $this->slackService->sendMessageToChannel($slackData['fieldMapData']);

            case 'sendDirectMessage':
                $userId = ['users' => $slackData['fieldMapData']['user']];

                return $this->slackService->sendDirectMessage($slackData['fieldMapData'], $userId);

            case 'joinChannel':
                return $this->slackService->joinChannel($slackData['fieldMapData']);

            case 'findUserByEmail':
                return $this->slackService->findUserByEmail($slackData['fieldMapData']);
        }
    }

    private function executeSlackAction(): array
    {
        $slackData = $this->setNodeInfoProperties();
        $this->slackService = new SlackService($slackData['header']);

        return $this->executeAction(
            $slackData
        );
    }
}
