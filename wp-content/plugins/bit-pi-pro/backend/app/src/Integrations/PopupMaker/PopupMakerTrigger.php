<?php

namespace BitApps\PiPro\src\Integrations\PopupMaker;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class PopupMakerTrigger
{
    public static function handleSubmit(...$record)
    {
        $formData = PopupMakerHelper::setFields($record[0]);

        $flows = FlowService::exists('popupMaker', 'popupFormSubmit');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
