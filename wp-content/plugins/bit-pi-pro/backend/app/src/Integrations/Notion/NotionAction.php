<?php

namespace BitApps\PiPro\src\Integrations\Notion;

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

class NotionAction implements ActionInterface
{
    private NodeInfoProvider $nodeInfoProvider;

    private NotionHelper $notionApi;

    private $machineSlug;

    private $connectionId;

    private $dataArr;

    private $databaseId;

    private $pageId;

    private $userId;

    private $titleId;

    private $startCursor;

    private $pageSize;

    private $tokenAuthorization;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    public function execute(): array
    {
        $executedNodeAction = $this->executeNotionAction();

        return Utility::formatResponseData(
            $executedNodeAction['status_code'],
            $executedNodeAction['payload'],
            $executedNodeAction['response']
        );
    }

    private function executeMachine(): array
    {
        switch ($this->machineSlug) {
            case 'getDatabase':
                return $this->notionApi->getDatabase($this->databaseId);

            case 'getPage':
                return $this->notionApi->getPage($this->pageId);

            case 'listUsers':
                return $this->notionApi->allUsersList($this->startCursor, $this->pageSize);

            case 'getUser':
                return $this->notionApi->getUser($this->userId);

            case 'retrieveUser':
                return $this->notionApi->retrieveUser();

            case 'createPage':
                return $this->notionApi->createPage($this->pageId, $this->dataArr);

            case 'createDatabase':
                return $this->notionApi->createDatabase($this->dataArr, $this->pageId, $this->titleId);

            case 'createDatabaseItem':
                return $this->notionApi->createDatabaseItem($this->dataArr, $this->databaseId);
        }

        return [];
    }

    private function getMixInputValue($name)
    {
        $value = $this->nodeInfoProvider->getFieldMapConfigs($name . '.value');

        if (!empty($value)) {
            return MixInputHandler::replaceMixTagValue($value);
        }

        return $value;
    }

    private function setNodeInfoProperties()
    {
        $this->machineSlug = $this->nodeInfoProvider->getMachineSlug();

        $this->connectionId = $this->nodeInfoProvider->getFieldMapConfigs('connection-id.value');

        $this->dataArr = $this->nodeInfoProvider->getFieldMapRepeaters('field-properties.value', false, true, 'notionField', 'value');

        $this->databaseId = $this->nodeInfoProvider->getFieldMapConfigs('database-id.value');
        $this->pageId = $this->nodeInfoProvider->getFieldMapConfigs('page-id.value');
        $this->userId = $this->getMixInputValue('user-id');
        $this->titleId = $this->getMixInputValue('title-id');
        $this->startCursor = $this->getMixInputValue('start-cursor');
        $this->pageSize = $this->getMixInputValue('page-size');

        $this->tokenAuthorization = AuthorizationFactory::getAuthorizationHandler(
            AuthorizationType::OAUTH2,
            $this->connectionId
        );
    }

    private function executeNotionAction(): array
    {
        $this->setNodeInfoProperties();
        $this->notionApi = new NotionHelper($this->tokenAuthorization);

        return $this->executeMachine();
    }
}
