<?php

namespace BitApps\PiPro\src\Integrations\Coblocks;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class CoblocksHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmit' => [
                'hook'          => 'coblocks_form_submit',
                'callback'      => [CoblocksTrigger::class, 'handleSubmit'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
