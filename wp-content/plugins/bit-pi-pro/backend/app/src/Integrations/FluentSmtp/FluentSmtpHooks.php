<?php

namespace BitApps\PiPro\src\Integrations\FluentSmtp;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class FluentSmtpHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'emailSentSuccessfully' => [
                'hook'          => 'wp_mail_succeeded',
                'callback'      => [FluentSmtpTrigger::class, 'handleEmailSentSuccessfully'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'emailSentFailed' => [
                'hook'          => ['fluentmail_email_sending_failed', 'fluentmail_email_sending_failed_no_fallback'],
                'callback'      => [FluentSmtpTrigger::class, 'handleEmailSentFailed'],
                'priority'      => 10,
                'accepted_args' => 3,
            ]
        ];
    }
}
