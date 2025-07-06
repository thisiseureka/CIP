<?php

namespace BitApps\PiPro\src\Integrations\SureMail;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use SureMails\Loader;

final class SureMailTrigger
{
    public static function handleEmailSentSuccessfully($mailData)
    {
        return self::execute($mailData, 'emailSentSuccessfully');
    }

    public static function handleEmailSentFailed($mailData)
    {
        return self::execute($mailData, 'emailSentFailed');
    }

    private static function execute($mailData, $machineSlug)
    {
        if (empty($mailData) || !self::isPluginInstalled()) {
            return;
        }

        $flows = FlowService::exists('SureMail', $machineSlug);

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $mailData);
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return class_exists(Loader::class);
    }
}
