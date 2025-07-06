<?php

namespace BitApps\PiPro\src\Integrations\Brizy;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class BrizyTrigger
{
    public static function handleSubmit($fields, $form)
    {
        if (!method_exists($form, 'getId')) {
            return $fields;
        }

        $recordData = BrizyHelper::extractRecordData($fields, $form->getId());

        $formData = BrizyHelper::setFields($recordData);

        $flows = FlowService::exists('brizy', 'formSubmit');

        if (!$flows) {
            return;
        }

        $formData = array_column($formData, 'value', 'name');

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
