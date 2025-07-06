<?php

namespace BitApps\PiPro\src\Integrations\SureMail;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class SureMailHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'emailSentSuccessfully' => [
                'hook'          => 'wp_mail_succeeded',
                'callback'      => [SureMailTrigger::class, 'handleEmailSentSuccessfully'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'emailSentFailed' => [
                'hook'          => 'wp_mail_failed',
                'callback'      => [SureMailTrigger::class, 'handleEmailSentFailed'],
                'priority'      => 10,
                'accepted_args' => 1,
            ]
        ];
    }
}
