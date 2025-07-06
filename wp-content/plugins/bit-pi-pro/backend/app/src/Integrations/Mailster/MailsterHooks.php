<?php

namespace BitApps\PiPro\src\Integrations\Mailster;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class MailsterHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'addSubscriber' => [
                'hook'          => 'mailster_add_subscriber',
                'callback'      => [MailsterTrigger::class, 'handleSubmit'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
