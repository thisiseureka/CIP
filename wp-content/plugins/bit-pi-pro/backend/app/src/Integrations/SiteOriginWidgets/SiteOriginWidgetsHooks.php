<?php

namespace BitApps\PiPro\src\Integrations\SiteOriginWidgets;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class SiteOriginWidgetsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'contactFormSubmitted' => [
                'hook'     => 'siteorigin_widgets_contact_sent',
                'callback' => [SiteOriginWidgetsTrigger::class, 'handleSiteOriginWidgetsSubmit'],
                'priority' => 10,
                'args'     => 1,
            ],
        ];
    }
}
