<?php

namespace BitApps\PiPro\src\Integrations\Formidable;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class FormidableHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmit' => [
                'hook'          => 'frm_success_action',
                'callback'      => [FormidableTrigger::class, 'handleFormidableSubmit'],
                'priority'      => 10,
                'accepted_args' => 5,
            ],
        ];
    }
}
