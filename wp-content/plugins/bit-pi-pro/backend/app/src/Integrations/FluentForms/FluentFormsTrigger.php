<?php

namespace BitApps\PiPro\src\Integrations\FluentForms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Node;
use BitApps\Pi\Services\FlowService;
use BitApps\Pi\src\Flow\FlowExecutor;
use BitApps\Pi\src\Flow\NodeInfoProvider;

class FluentFormsTrigger
{
    public static function handleSubmit($entryId, $formData, $form)
    {
        $flows = FlowService::exists('fluentForms', 'submissionInserted');

        if (!$flows) {
            return;
        }

        $data = ['entry_id' => $entryId, 'form_data' => $formData, 'form' => json_decode(wp_json_encode($form), true)];

        foreach ($flows as $flow) {
            $triggerNode = Node::getNodeInfoById($flow->id . '-1', $flow->nodes);

            if (!$triggerNode) {
                continue;
            }

            // matching submitted form
            $nodeHelper = new NodeInfoProvider($triggerNode);
            $configFormId = $nodeHelper->getFieldMapConfigs('form-id.value');

            if ($configFormId !== 'any' && (int) $configFormId !== $form->id) {
                continue;
            }

            FlowExecutor::execute($flow, $data);
        }
    }
}
