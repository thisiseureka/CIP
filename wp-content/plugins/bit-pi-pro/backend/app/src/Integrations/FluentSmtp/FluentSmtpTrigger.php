<?php

namespace BitApps\PiPro\src\Integrations\FluentSmtp;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class FluentSmtpTrigger
{
    public static function handleEmailSentSuccessfully($mail)
    {
        if (empty($mail) || !self::isPluginInstalled()) {
            return;
        }

        return self::execute($mail, 'emailSentSuccessfully');
    }

    public static function handleEmailSentFailed($logId, $handler, $data)
    {
        if (empty($data)) {
            return;
        }

        $mailLogData = ['log_id' => $logId, 'handler' => $handler, 'data' => $data];

        return self::execute($mailLogData, 'emailSentFailed');
    }

    private static function execute($data, $machineSlug)
    {
        $flows = FlowService::exists('FluentSmtp', $machineSlug);

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return \function_exists('fluentSmtpInit');
    }
}
