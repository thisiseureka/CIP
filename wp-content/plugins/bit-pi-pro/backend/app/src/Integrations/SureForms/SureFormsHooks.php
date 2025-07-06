<?php

namespace BitApps\PiPro\src\Integrations\SureForms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class SureFormsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'sureFormsSubmission' => [
                'hook'          => 'srfm_form_submit',
                'callback'      => [SureFormsTrigger::class, 'handleSureFormsSubmit'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
