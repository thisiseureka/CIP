<?php

namespace BitApps\PiPro\src\Integrations\StudioCart;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class StudioCartHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'orderCompletion' => [
                'hook'          => 'sc_order_complete',
                'callback'      => [StudioCartTrigger::class, 'newOrderCreated'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
