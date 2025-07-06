<?php

namespace BitApps\PiPro\src\Integrations\ClickUp;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Interfaces\ActionInterface;

class ClickUpAction implements ActionInterface
{
    public const BASE_URL = 'https://api.clickup.com/api/v2';

    private $nodeInfoProvider;

    private $clickUpService;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    public function execute(): array
    {
        $executedNodeAction = $this->executeClickUpAction();

        return Utility::formatResponseData(
            $executedNodeAction['status_code'],
            $executedNodeAction['payload'],
            $executedNodeAction['response']
        );
    }

    private function executeClickUpAction()
    {
        $machineSlug = $this->nodeInfoProvider->getMachineSlug();

        $connectionId = $this->nodeInfoProvider->getFieldMapConfigs('connection-id.value');

        $listId = $this->nodeInfoProvider->getFieldMapConfigs('list-id.value');

        $repeaters = $this->nodeInfoProvider->getFieldMapRepeaters('task-data.value', false, false);

        $this->clickUpService = new ClickUpService(static::BASE_URL, $connectionId);

        if ($machineSlug === 'createTask') {
            return $this->clickUpService->createTask($repeaters, $listId);
        }
    }

    // FUTURE WORK LIST
    /*
    Form
        List Form Question
        Add Question to Form

    Submission
        Get Form Submission
        Get Form Submission by ID
    */
}
