<?php

namespace BitApps\PiPro\src\Integrations\MailPoet;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class MailPoetHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'subscriptionSubmit' => [
                'hook'          => 'mailpoet_subscription_before_subscribe',
                'callback'      => [MailPoetTrigger::class, 'handleMailPoetSubmit'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
