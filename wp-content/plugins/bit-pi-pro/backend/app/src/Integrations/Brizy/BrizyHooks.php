<?php

namespace BitApps\PiPro\src\Integrations\Brizy;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class BrizyHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmit' => [
                'hook'          => 'brizy_form_submit_data',
                'callback'      => [BrizyTrigger::class, 'handleSubmit'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
