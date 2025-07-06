<?php

namespace BitApps\PiPro\src\Integrations\Spectra;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use WP_Error;

class SpectraTrigger
{
    public static function spectraHandler(...$args)
    {
        $flows = FlowService::exists('spectra', 'spectraFormSubmit');

        IntegrationHelper::handleFlowForForm($flows, $args);
    }
}
