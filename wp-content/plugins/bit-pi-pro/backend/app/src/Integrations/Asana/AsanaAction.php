<?php

namespace BitApps\PiPro\src\Integrations\Asana;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Interfaces\ActionInterface;

class AsanaAction implements ActionInterface
{
    public const BASE_URL = 'https://app.asana.com/api/1.0';

    private $nodeInfoProvider;

    private $asanaService;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    public function execute(): array
    {
        $executedNodeAction = $this->executeAsanaAction();

        return Utility::formatResponseData(
            $executedNodeAction['status_code'],
            $executedNodeAction['payload'],
            $executedNodeAction['response']
        );
    }

    private function executeAsanaAction()
    {
        $machineSlug = $this->nodeInfoProvider->getMachineSlug();

        $connectionId = $this->nodeInfoProvider->getFieldMapConfigs('connection-id.value');

        $projectId = $this->nodeInfoProvider->getFieldMapConfigs('project-id.value');
        $sectionId = $this->nodeInfoProvider->getFieldMapConfigs('section-id.value');
        $taskId = $this->nodeInfoProvider->getFieldMapConfigs('task-id.value');
        $tagId = $this->nodeInfoProvider->getFieldMapConfigs('tag-id.value');

        $repeaters = $this->nodeInfoProvider->getFieldMapRepeaters('task-data.value', false, false);

        $this->asanaService = new AsanaService(static::BASE_URL, $connectionId);

        if ($machineSlug === 'createTask') {
            return $this->asanaService->createTask($repeaters, $projectId, $sectionId, $taskId, $tagId);
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
