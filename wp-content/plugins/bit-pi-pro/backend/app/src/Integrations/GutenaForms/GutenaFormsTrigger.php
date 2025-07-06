<?php

namespace BitApps\PiPro\src\Integrations\GutenaForms;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class GutenaFormsTrigger
{
    public static function handleGutenaFormsSubmit(...$record)
    {
        $formData = GutenaFormsHelper::setFields($record[0], $record[1]);

        $flows = FlowService::exists('gutenaForms', 'formSubmit');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
