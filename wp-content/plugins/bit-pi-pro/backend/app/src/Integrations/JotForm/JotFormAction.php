<?php

namespace BitApps\PiPro\src\Integrations\JotForm;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Interfaces\ActionInterface;

class JotFormAction implements ActionInterface
{
    public const BASE_URL = 'https://api.jotform.com/';

    private $nodeInfoProvider;

    private $jotFormService;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    public function execute(): array
    {
        $executedNodeAction = $this->executeJotFormAction();

        return Utility::formatResponseData(
            $executedNodeAction['status_code'],
            $executedNodeAction['payload'],
            $executedNodeAction['response']
        );
    }

    private function executeJotFormAction()
    {
        $machineSlug = $this->nodeInfoProvider->getMachineSlug();

        $data = $this->nodeInfoProvider->getFieldMapData();

        $connectionId = $this->nodeInfoProvider->getFieldMapConfigs('connection-id.value');

        $questions = $this->nodeInfoProvider->getFieldMapRepeaters('questions.value', false, false);

        $this->jotFormService = new JotFormService(static::BASE_URL, $connectionId);

        if ($machineSlug === 'createForm') {
            return $this->jotFormService->createNewForm($data, $questions);
        }
    }
}
