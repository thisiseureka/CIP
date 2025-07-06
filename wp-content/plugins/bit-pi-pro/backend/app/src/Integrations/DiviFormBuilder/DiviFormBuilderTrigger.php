<?php

namespace BitApps\PiPro\src\Integrations\DiviFormBuilder;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class DiviFormBuilderTrigger
{
    public static function handleDiviFormBuilderSubmit(...$record)
    {
        $formData = DiviFormBuilderHelper::setFields($record[0], $record[1]);

        $flows = FlowService::exists('diviFormBuilder', 'formBuilderSubmit');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
