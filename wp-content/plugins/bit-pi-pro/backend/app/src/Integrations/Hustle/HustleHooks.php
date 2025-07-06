<?php

namespace BitApps\PiPro\src\Integrations\Hustle;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class HustleHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmit' => [
                'hook'          => 'hustle_form_submit_before_set_fields',
                'callback'      => [HustleTrigger::class, 'handleHustleSubmit'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
