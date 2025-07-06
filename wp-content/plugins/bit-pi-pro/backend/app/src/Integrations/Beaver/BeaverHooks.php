<?php

namespace BitApps\PiPro\src\Integrations\Beaver;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class BeaverHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'beaverContactFormSubmission' => [
                'hook'          => 'fl_module_contact_form_after_send',
                'callback'      => [BeaverTrigger::class, 'beaverContactFormSubmitted'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'beaverLoginFormSubmission' => [
                'hook'          => 'fl_builder_login_form_submission_complete',
                'callback'      => [BeaverTrigger::class, 'beaverLoginFormSubmitted'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'beaverSubscribeFormSubmission' => [
                'hook'          => 'fl_builder_subscribe_form_submission_complete',
                'callback'      => [BeaverTrigger::class, 'beaverSubscribeFormSubmitted'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
        ];
    }
}
