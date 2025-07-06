<?php

namespace BitApps\PiPro\src\Integrations\SiteOriginWidgets;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class SiteOriginWidgetsTrigger
{
    public static function handleSiteOriginWidgetsSubmit(...$record)
    {
        $formData = SiteOriginWidgetsHelper::setFields($record[0]['fields'], $record[0]['_sow_form_id'], $record[1]);

        $flows = FlowService::exists('siteOriginWidgets', 'contactFormSubmitted');

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
