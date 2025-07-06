<?php

namespace BitApps\PiPro\src\Integrations\AvadaForms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class AvadaFormsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmission' => [
                'hook'          => 'fusion_form_submission_data',
                'callback'      => [AvadaFormsTrigger::class, 'formSubmission'],
                'priority'      => 10,
                'accepted_args' => 2,
            ]
        ];
    }
}
