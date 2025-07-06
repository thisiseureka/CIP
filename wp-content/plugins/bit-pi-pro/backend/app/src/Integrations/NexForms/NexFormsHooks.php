<?php

namespace BitApps\PiPro\src\Integrations\NexForms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class NexFormsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmission' => [
                'hook'          => 'NEXForms_submit_form_data',
                'callback'      => [NexFormsTrigger::class, 'handleFormSubmitted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
