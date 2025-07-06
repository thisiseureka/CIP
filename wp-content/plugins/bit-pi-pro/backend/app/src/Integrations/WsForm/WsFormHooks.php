<?php

namespace BitApps\PiPro\src\Integrations\WsForm;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class WsFormHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'wsFormSubmit' => [
                'hook'          => 'bit_pi_do_action',
                'callback'      => [WsFormTrigger::class, 'handleSubmit'],
                'priority'      => 9999,
                'accepted_args' => 4,
            ],
        ];
    }
}
