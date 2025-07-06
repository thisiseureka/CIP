<?php

namespace BitApps\PiPro\src\Integrations\EssentialBlocks;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

class EssentialBlocksTrigger
{
    public static function essentialBlocksHandler(...$args)
    {
        $flows = FlowService::exists('essentialBlocks', 'formSubmit');

        IntegrationHelper::handleFlowForForm($flows, $args);
    }
}
