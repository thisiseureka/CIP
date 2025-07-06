<?php

namespace BitApps\PiPro\src\Integrations\Bricksforge;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class BricksforgeTrigger
{
    public static function handleBricksforgeSubmit(...$record)
    {
        $formData = BricksforgeHelper::setFields($record[0]);

        $flows = FlowService::exists('bricksforge', 'bricksforgeFormSubmit');

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
