<?php

namespace BitApps\PiPro\src\Integrations\Divi;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class DiviHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'contactFormSubmit' => [
                'hook'          => 'et_pb_contact_form_submit',
                'callback'      => [DiviTrigger::class, 'handleDiviSubmit'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
