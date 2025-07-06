<?php

namespace BitApps\PiPro\src\Integrations\Kadence;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class KadenceTrigger
{
    public static function handleSubmit($formArgs, $fields, $formId, $postId = null)
    {
        $recordData = KadenceHelper::extractRecordData($formId, $postId, $formArgs['fields'], $fields);

        $formData = KadenceHelper::setFields($recordData);

        $flows = FlowService::exists('kadence', 'formSubmission');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
