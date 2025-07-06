<?php

namespace BitApps\PiPro\src\Integrations\Paymattic;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class PaymatticHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'paymentFormSubmission' => [
                'hook'          => 'wppayform/after_form_submission_complete',
                'callback'      => [PaymatticTrigger::class, 'paymentFormSubmission'],
                'priority'      => 20,
                'accepted_args' => 1,
            ],
            'paymentStatusChanged' => [
                'hook'          => 'wppayform/after_payment_status_change',
                'callback'      => [PaymatticTrigger::class, 'paymentStatusChanged'],
                'priority'      => 20,
                'accepted_args' => 2,
            ],
            'paymentSuccess' => [
                'hook'          => 'wppayform/form_payment_success',
                'callback'      => [PaymatticTrigger::class, 'paymentSuccess'],
                'priority'      => 20,
                'accepted_args' => 4,
            ],
            'paymentFailed' => [
                'hook'          => 'wppayform/form_payment_failed',
                'callback'      => [PaymatticTrigger::class, 'paymentFailed'],
                'priority'      => 20,
                'accepted_args' => 4,
            ],
            'noteCreatedByUser' => [
                'hook'          => 'wppayform/after_create_note_by_user',
                'callback'      => [PaymatticTrigger::class, 'noteCreatedByUser'],
                'priority'      => 20,
                'accepted_args' => 1,
            ],
        ];
    }
}
