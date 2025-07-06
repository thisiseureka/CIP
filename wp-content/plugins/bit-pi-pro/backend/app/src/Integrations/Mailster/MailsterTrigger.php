<?php

namespace BitApps\PiPro\src\Integrations\Mailster;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use MailsterSubscribers;

final class MailsterTrigger
{
    public static function handleSubmit($userId)
    {
        if (empty($userId)) {
            return;
        }

        if (!class_exists('MailsterSubscribers')) {
            return;
        }

        $mailsterSubscribers = new MailsterSubscribers();

        $getSubscribers = (array) $mailsterSubscribers->get($userId, true);

        if ($getSubscribers !== [] && $flows = FlowService::exists('mailster', 'addSubscriber')) {
            IntegrationHelper::handleFlowForForm($flows, $getSubscribers);
        }
    }
}
