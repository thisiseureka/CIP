<?php

namespace BitApps\PiPro\src\Integrations\OpenAi;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Helpers\MixInputHandler;
use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\src\Authorization\AuthorizationFactory;
use BitApps\Pi\src\Authorization\AuthorizationType;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Interfaces\ActionInterface;
use BitApps\PiPro\src\Integrations\OpenAi\helpers\OpenAiActionHandler;

class OpenAiAction implements ActionInterface
{
    private const BASE_URL = 'https://api.openai.com/v1';

    private NodeInfoProvider $nodeInfoProvider;

    private OpenAiService $openAiService;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    public function execute(): array
    {
        $executedNodeAction = $this->executeOpenAiAction();

        return Utility::formatResponseData(
            $executedNodeAction['status_code'],
            $executedNodeAction['payload'],
            $executedNodeAction['response']
        );
    }

    private function executeOpenAiAction()
    {
        $machineSlug = $this->nodeInfoProvider->getMachineSlug();
        $connectionId = $this->nodeInfoProvider->getFieldMapConfigs('connection-id.value');
        $batchId = $this->nodeInfoProvider->getFieldMapConfigs('batch-id.value');
        $inputText = $this->nodeInfoProvider->getFieldMapRepeaters('input-list.value', false, false);
        $stopSequence = $this->nodeInfoProvider->getFieldMapRepeaters('stop-sequences-list.value', false, false);
        $optionalFields = $this->nodeInfoProvider->getFieldMapRepeaters('optional-field-list.value', false, false);
        $messageList = $this->nodeInfoProvider->getFieldMapRepeaters('messages-list.value', false, false);
        $inputFormat = $this->nodeInfoProvider->getFieldMapConfigs('input-format.value');
        $fieldMapData = $this->nodeInfoProvider->getFieldMapData();
        $batchLimit = $this->nodeInfoProvider->getFieldMapConfigs('batch-limit.value');
        $batchLimit = MixInputHandler::replaceMixTagValue($batchLimit);


        $fieldMapData = OpenAiActionHandler::handleConditions($fieldMapData, $stopSequence, $messageList, $inputFormat, $inputText, $optionalFields);

        if (!empty($optionalFields)) {
            $fieldMapData = OpenAiActionHandler::castFieldsIfExist($fieldMapData);
        }

        $batchLimit = (int) $batchLimit;

        $tokenAuthorization = AuthorizationFactory::getAuthorizationHandler(
            AuthorizationType::API_KEY,
            $connectionId
        );

        $apiKey = $tokenAuthorization->getAccessToken();

        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey
        ];

        $this->openAiService = new OpenAiService(self::BASE_URL, $header);

        return OpenAiActionHandler::executeAction(
            $machineSlug,
            $this->openAiService,
            $batchLimit,
            $batchId,
            $fieldMapData,
        );
    }
}
